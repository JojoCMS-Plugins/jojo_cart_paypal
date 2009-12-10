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

/* Define the class for the cart */
if (!defined('Jojo_Cart_Class')) {
    define('Jojo_Cart_Class', Jojo::getOption('jojo_cart_class', 'jojo_plugin_jojo_cart'));
}

if (class_exists(Jojo_Cart_Class)) {
    call_user_func(array(Jojo_Cart_Class, 'setPaymentHandler'), 'jojo_plugin_jojo_cart_paypal');
}

Jojo::registerURI("cart/ipn", 'jojo_plugin_Jojo_cart_paypal_ipn'); // "cart/process/paypal"

$_options[] = array(
    'id'          => 'jojo_cart_paypal_testemail',
    'category'    => 'Cart',
    'label'       => 'Paypal TEST email',
    'description' => 'The PayPal TEST account (email address) you would like payments to go to',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jojo_cart_paypal'
);

$_options[] = array(
    'id'          => 'jojo_cart_paypal_email',
    'category'    => 'Cart',
    'label'       => 'Paypal email',
    'description' => 'The PayPal account (email address) you would like payments to go to',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jojo_cart_paypal'
);

$_options[] = array(
    'id'          => 'jojo_cart_paypal_card_types',
    'category'    => 'Cart',
    'label'       => 'Paypal Card types',
    'description' => 'A comma separated list of card types that are accepted by Paypal - you may wish to hide some of these if they are offered by other payment providers (visa, mastercard, amex, discover, paypal)',
    'type'        => 'text',
    'default'     => 'visa,mastercard,amex,discover,paypal',
    'options'     => '',
    'plugin'      => 'jojo_cart_paypal'
);

$_options[] = array(
    'id'          => 'jojo_cart_paypal_notify_base_url',
    'category'    => 'Cart',
    'label'       => 'Paypal Alternative Notify URL',
    'description' => 'This option is only needed when moving a site from one server to another and you want to accept payments while the DNS is propagating. Set this value to a fixed URI (eg http://111.222.333.444/~domain) that Paypal can use while www.domain.com is propagating.',
    'type'        => 'text',
    'default'     => '',
    'options'     => '',
    'plugin'      => 'jojo_cart_paypal'
);