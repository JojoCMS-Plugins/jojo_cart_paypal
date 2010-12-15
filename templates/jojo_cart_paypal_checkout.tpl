<form action="{$paypalform}" method="post">
  <div class="box contact-form" id="paypal-details">
    <h2>Pay using Paypal</h2>
    <p>PayPal is a secure 3rd party payment processor. PayPal accepts payments using Visa, Mastercard, American Express and Discover credit cards, or funds in your existing PayPal account.</p>
    <p>Press the 'Buy now' button to be redirected to the PayPal payment page where you can enter your credit card or PayPal account details. Once the transaction is complete, you will be sent a confirmation email and returned to this website.</p>
    <div style="text-align: center">
      <input type="image" class="paypal_buy_now" src="https://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal" />
    </div>
    <div class="clear"></div>

    <h3>Paying by credit card?</h3>
    <p>PayPal does accept credit card payments. When you arrive at the PayPal login screen, use the link indicated in the following screenshot.</p>
    <a href="images/paypal-example-large.jpg" target="_BLANK"><img src="images/paypal-example.jpg" alt="How to pay by credit card" /></a><br />
    <em>When you arrive at PayPal, click the link indicated to enter credit card details.</em>
    <div class="clear"></div>

    <input type="hidden" name="token" id="token" value="{$token}" />
    <input type="hidden" name="upload" value="1" />
    <input type="hidden" name="cmd" value="_cart" />
    <input type="hidden" name="redirect_cmd" value="_xclick" />
    <input type="hidden" name="business" value="{$paypalemail}" />
    <input type="hidden" name="item_name" value="{$sitetitle} Order" />
    <input type="hidden" name="currency_code" value="{$order.currency|default:'USD'}" />
    <input type="hidden" name="amount" value="{if $order.currency=='JPY'}{$order.amount|string_format:"%01.0f"}{else}{$order.amount|string_format:"%01.2f"}{/if}" />

    <input type="hidden" name="return" value="{$SECUREURL}/cart/complete/{$token}/" />
    <input type="hidden" name="rm" value="2" />{* POST the user back to the return URL *}
    <input type="hidden" name="custom" value="{$token}" />
    <input type="hidden" name="cancel_return" value="{$SECUREURL}/cart/cancel/" />
    {* <input type="hidden" name="image_url" value="{$SECUREURL}/images/paypal-logo.jpg" /> *}
    {if $SITEURL != $SECUREURL}<input type="hidden" name="cpp_header_image" value="{$SECUREURL}/images/paypal-header.jpg" />{/if}

    <input type="hidden" name="notify_url" value="{if $OPTIONS.jojo_cart_paypal_notify_base_url}{$OPTIONS.jojo_cart_paypal_notify_base_url}{else}{$SECUREURL}{/if}/cart/process/{$token}/" />

    <input type="hidden" name="undefined_quantity" value="1" />

    {foreach from=$items key=k item=i name=i}
    <!-- [Item details] -->
    {assign var=loopindex value=`$smarty.foreach.i.index+1`}
    <input type="hidden" name="item_name_{$loopindex}" value="{$i.name}" />
    <input type="hidden" name="item_number_{$loopindex}" value="{$i.id}" />
    <input type="hidden" name="amount_{$loopindex}" value="{if $order.currency=='JPY'}{$i.netprice|string_format:"%01.0f"}{else}{$i.netprice|string_format:"%01.2f"}{/if}" />
    {if $loopindex == 1}
      <input type="hidden" name="shipping_{$loopindex}" value="{if $order.currency=='JPY'}{$order.freight|string_format:"%01.0f"}{else}{$order.freight|string_format:"%01.2f"}{/if}" />
    {else}
      <input type="hidden" name="shipping_{$loopindex}" value="0" />
    {/if}
    <input type="hidden" name="quantity_{$loopindex}" value="{$i.quantity}" />
    {/foreach}

    <input type="hidden" name="first_name" value="{if $fields.billing_firstname}{$fields.billing_firstname}{else}{$fields.shipping_firstname}{/if}" />
    <input type="hidden" name="last_name" id="last_name" value="{if $fields.billing_lastname}{$fields.billing_lastname}{else}{$fields.shipping_lastname}{/if}" />
    <input type="hidden" name="email" value="{if $fields.billing_email}{$fields.billing_email}{else}{$fields.shipping_email}{/if}" />
    <input type="hidden" name="address1" value="{if $fields.billing_address1}{$fields.billing_address1}{else}{$fields.shipping_address1}{/if}" />
    <input type="hidden" name="address2" value="{if $fields.billing_address2}{$fields.billing_address2}{else}{$fields.shipping_address2}{/if}" />
    <input type="hidden" name="city" value="{if $fields.billing_city}{$fields.billing_city}{else}{$fields.shipping_city}{/if}" />
    <input type="hidden" name="state" value="{if $fields.billing_state}{$fields.billing_state}{elseif $fields.shipping_state}{$fields.shipping_state}{elseif $fields.billing_city}{$fields.billing_city}{else}{$fields.shipping_city}{/if}" />
    <input type="hidden" name="zip" value="{if $fields.billing_postcode}{$fields.billing_postcode}{else}{$fields.shipping_postcode}{/if}" />
    <input type="hidden" name="country" value="{if $fields.billing_country}{$fields.billing_country}{else}{$fields.shipping_country}{/if}" />

  </div>
<div style="text-align: center">
  <input type="image" class="paypal_buy_now" src="https://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" id="submit" alt="Make payments with PayPal" />
</div>
</form>
