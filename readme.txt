This plugin adds PayPal allows you to use PayPal as a payment processor for the jojo_cart plugin.

Process overview:
-Customer adds one or more products to cart.
-Customer enters personal details (name, email etc) on cart confirmation page, then presses checkout.
-Customer selects PayPal as a payment option (they don't need to select if there is no other option)
-Customerconfirms details and is redirected to PayPal.
-On PayPal site, customer logs into their PayPal account, or enters their credit card details.
-Customer confirms payment on PayPal site.
-PayPal emails the PayPal account holder and the customer a payment notification.
-PayPal contacts the Jojo plugin (via IPN) with confirmation that the customer has paid.
-Jojo plugin sends the admin user and the customer an email with order details.
-Jojo runs a programming hook - use this to add custom behaviours to your payment process (eg email a file to the user, activate a user account, add them to a database etc)
-Shopping cart is emptied so the same transaction is not processed twice.
-PayPal redirects the customer to a 'thank you' page. Jojo has hooks on this page for customizing this message, or see the options page for simpler messages. Be sure to add any order tracking javascript here.

Before you start:
-Setup a PayPal account
-Verify your address with PayPal (recommended, but takes a while to do)
-Setup Instant Payment Notification (IPN) on your PayPal profile page - this plugin will not work without IPN.

Install:
-Ensure the jojo_cart plugin is installed.
-Install jojo_cart_paypal
-On admin/edit/options/ under the 'cart' category, enter your PayPal address (important)

Live vs Local:
-PayPal's IPN feature will not work on a local server. You must do your testing on a live website (PayPal needs to be able to access your Jojo site, which it can't do with local installs).
-If you wish to test your shopping cart locally, use the jojo_cart_test_processor to get things working.