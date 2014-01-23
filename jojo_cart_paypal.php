<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Harvey Kane <code@ragepank.com>
 * Copyright 2008 Michael Holt <code@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

class jojo_plugin_jojo_cart_paypal extends JOJO_Plugin
{
    static function getPaymentOptions()
    {
        global $smarty;
        $options = array();

        /* PayPal can't be used until a PayPal address is entered in options */
        if (Jojo::getOption('jojo_cart_paypal_email', false)) {
          /* get available card types (specified in options) */
          $cardtypes = explode(',', Jojo::getOption('jojo_cart_paypal_card_types', 'visa,mastercard,amex,discover,paypal'));

          /* uppercase first letter of each card type */
          foreach ($cardtypes as $k => $v) {
              $cardtypes[$k] = trim(ucwords($v));
              if ($cardtypes[$k] == 'Visa') {
                  $cardimages[$k] = '<img class="icon-image" src="images/creditcardvisa.gif" alt="Visa" title="Visa" />';
              } elseif ($cardtypes[$k] == 'Mastercard') {
                  $cardimages[$k] = '<img class="icon-image" src="images/creditcardmastercard.gif" alt="Mastercard" title="Mastercard" />';
              } elseif ($cardtypes[$k] == 'Amex') {
                  $cardimages[$k] = '<img class="icon-image" src="images/creditcardamex.gif" alt="American Express" title="American Express" />';
              } elseif ($cardtypes[$k] == 'Discover') {
                  $cardimages[$k] = '<img class="icon-image" src="images/creditcarddiscover.gif" alt="Discover" title="Discover" />';
              } elseif ($cardtypes[$k] == 'Paypal') {
                  $cardimages[$k] = '<img class="icon-image" src="images/creditcardpaypal.gif" alt="PayPal" title="PayPal" />';
              }
          }
        $smarty->assign('cardtypes', $cardtypes);

        $testmode=call_user_func(array(Jojo_Cart_Class, 'isTestMode'));

        if($testmode && Jojo::getOption('jojo_cart_paypal_testemail', false)) {
          $smarty->assign('paypalform', "https://www.sandbox.paypal.com/nz/cgi-bin/webscr");
          $smarty->assign('paypalemail', Jojo::getOption('jojo_cart_paypal_testemail', false));
        } else {
          $smarty->assign('paypalform', "https://www.paypal.com/nz/cgi-bin/webscr");
          $smarty->assign('paypalemail', Jojo::getOption('jojo_cart_paypal_email', false));
        }

            $options[] = array('id' => 'paypal', 'label' => 'PayPal or credit card '.implode(' ', $cardimages), 'html' => $smarty->fetch('jojo_cart_paypal_checkout.tpl'));
        }
        return $options;
    }

    /*
    * Determines whether this payment plugin is active for the current payment.
    */
    static function isActive()
    {
        /* PayPal will post a 'custom' var which should match the token */
        if (Jojo::getGet('token', true) == Jojo::getPost('custom', false)) return true;

        /* if the handler is specified as PayPal, then obey this */
        return (Jojo::getFormData('handler', false) == 'paypal') ? true : false;
    }

    static function process()
    {
        $result = self::pingPaypal();
        //$receipt = array('Info' => '');
        $receipt = array();//TODO: build a proper receipt
        $errors = array();

        $message = ($result) ? 'Thank you for your payment via Paypal.': '';

         $return = array(
                 'success' => $result,
                 'receipt' => $receipt,
                 'errors'  => $errors,
                 'message' => $message
                 );
         return $return;
    }


    private static function pingPaypal()
    {
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        /*
        $header = '';
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        */
        /* updated Paypal code for 7 Oct 2013 */
        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n";
        $header .= "Host: www.paypal.com\r\n";
        $header .= "Connection: close\r\n\r\n";

        $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);

        if (!$fp) {
            /* returning a 404 tells PayPal to try again later */
            $toname      = _FROMNAME;
            $toaddress   = _WEBMASTERADDRESS;
            $subject     = 'Paypal transaction '.Jojo::getOption('sitetitle');
            $message     = 'Paypal hit a 404 '.Jojo::emailFooter();
            $fromname    = _FROMNAME;
            $fromaddress = _WEBMASTERADDRESS;
            Jojo::simpleMail($toname, $toaddress, $subject, $message, $fromname, $fromaddress);

            header("HTTP/1.0 404 Not Found");
            exit;
            return false;
        } else {
            fputs($fp, $header . $req);
            while (!feof($fp)) {
                $res = trim(fgets($fp, 1024), "\r\n");
                if (strcmp($res, "VERIFIED") === 0) {
                    // check the payment_status is Completed
                    // check that txn_id has not been previously processed
                    // check that receiver_email is your Primary PayPal email
                    // check that payment_amount/payment_currency are correct
                    // process payment
                    return true;
                } else if (strcmp($res, "INVALID") === 0) {
                    Jojo::simpleMail(_FROMNAME, _WEBMASTERADDRESS, 'Paypal invalid response from '.Jojo::getIP().' - please contact webmaster.', $req);
                    return false;
                }
            }
            fclose($fp);
            /* returning a 404 tells PayPal to try again later */
            $toname      = _FROMNAME;
            $toaddress   = _WEBMASTERADDRESS;
            $subject     = 'Paypal transaction '.Jojo::getOption('sitetitle');
            $message     = 'Paypal response had no valid data.'.Jojo::emailFooter();
            $fromname    = _FROMNAME;
            $fromaddress = _WEBMASTERADDRESS;
            Jojo::simpleMail($toname, $toaddress, $subject, $message, $fromname, $fromaddress);

            header("HTTP/1.0 404 Not Found");
            exit;
            return false;
        }
        return false;
    }

}