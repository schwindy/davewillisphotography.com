<?php

function checkout_final()
{
    if (empty($_REQUEST['cart'])) {
        new APIResponse(0, "Cart is empty!");
    }

    $db = Database::getInstance();

    $cart_total = get_cart_total();
    $order_id = generate_mysql_id();

    $result = braintree_sale_create([
        "amount"             => (string)round($cart_total, 2),
        "paymentMethodNonce" => $_REQUEST["payment_method_nonce"],
        "customer_name"      => $_REQUEST['customer_name'],
        "customer_email"     => $_REQUEST['customer_email'],
        "customer_phone"     => $_REQUEST['customer_phone'],
        "order_id"           => $order_id
    ]);

    if (!$result->success) {
        $message = empty($result->transaction->status) ? 'SESSION Timed Out' : $result->transaction->status;
        new APIResponse(0, "Error: $message");
    }

    $user = get_user();
    $match =
        $db->get_row("SELECT * FROM customers WHERE name='$_REQUEST[customer_name]' AND email='$_REQUEST[customer_email]'");
    if (empty($match)) {
        $customer_id = generate_mysql_id();
        $db->insert('customers', [
            'id'      => $customer_id,
            'user_id' => empty($user) ? '' : $user['id'],
            'name'    => $_REQUEST['customer_name'],
            'email'   => $_REQUEST['customer_email'],
            'phone'   => $_REQUEST['customer_phone'],
            'address' => $_REQUEST['address'],
            'city'    => $_REQUEST['city'],
            'state'   => $_REQUEST['state'],
            'zip'     => $_REQUEST['zip'],
            'created' => get_date(),
        ]);
    }

    $customer_id = empty($match) ? $customer_id : $match['id'];

    $db->insert('orders', [
        'id'          => $order_id,
        'customer_id' => $customer_id,
        'type'        => 'shop',
        'cart'        => json_encode($_REQUEST['cart']),
        'cart_total'  => $cart_total,
        'created'     => get_date(),
    ]);

    $cart_html = "";
    foreach ($_REQUEST['cart'] as $id => $item) {
        $row = $db->get_row("SELECT * FROM elements WHERE id='$id' AND type='shop_item'");
        $e = new Element($row);
        $e->price = $e->properties['price'];
        $e->amount = $item['amount'];

        if (empty($item['options'])) {
            $cart_html .= "<p style='color:#000;font-size:12pt;'>" . $e->amount . " x " . $e->display_name . " ($" . $e->price . " ea.)</p>";
            continue;
        }

        foreach ($item['options'] as $type => $options) {
            $type = ucwords($type);

            foreach ($options as $option => $amount) {
                $e = new Element($row);
                $e->display_name = "$row[display_name]<br> $type: $option";
                $e->amount = $amount;
                $e->price = $e->properties['price'];
                $cart_html .= "<p style='color:#000;font-size:12pt;'>" . $e->amount . " x " . $e->display_name . " ($" . $e->price . " ea.)</p>";
            }
        }
    }

    $cart_html .= "<h3 style='color:#000;'>Total: $" . "$cart_total</h3>";

    $message = "<html>
					<head>
						<title>" . SITE_NAME . " | New Shop Order ($order_id)</title>
					</head>
					<body>
						<h1 style='color:#000;'>New Shop Order!</h1>
						<p style='color:#000;font-size:12pt;'>A Customer has placed a new order using your website.</p>
						<br>
						<h2 style='color:#000;'>Customer Information:</h2>
						<p style='color:#000;font-size:12pt;'>Name: $_REQUEST[customer_name]</p>
						<p style='color:#000;font-size:12pt;'>Email: $_REQUEST[customer_email]</p>
						<p style='color:#000;font-size:12pt;'>Phone: $_REQUEST[customer_phone]</p>
						<p style='color:#000;font-size:12pt;'>Address: $_REQUEST[address] $_REQUEST[city], $_REQUEST[state] $_REQUEST[zip]</p>
						<br>
						<h2 style='color:#000;'>Shopping Cart:</h2>
						$cart_html
					</body>
				</html>";

    send_email(SALES_EMAIL, SITE_NAME . " | New Shop Order ($order_id)", $message);

    $message = "<html>
					<head>
						<title>" . SITE_NAME . " | New Order</title>
					</head>
					<body>
						<h1 style='color:#000;'>New Order!</h1>
						<p style='color:#000;font-size:12pt;'>Thank you for placing an order in the " . SITE_NAME . " Shop!</p>
						<br>
						<h2 style='color:#000;'>Your Information:</h2>
						<p style='color:#000;font-size:12pt;'>Name: $_REQUEST[customer_name]</p>
						<p style='color:#000;font-size:12pt;'>Email: $_REQUEST[customer_email]</p>
						<p style='color:#000;font-size:12pt;'>Phone: $_REQUEST[customer_phone]</p>
						<p style='color:#000;font-size:12pt;'>Address: $_REQUEST[address] $_REQUEST[city], $_REQUEST[state] $_REQUEST[zip]</p>
						<br>
						<h2 style='color:#000;'>Your Cart:</h2>
						$cart_html
					</body>
				</html>";

    send_email($_REQUEST['customer_email'], SITE_NAME . " | New Order", $message);

    new APIResponse(1, "Order placed successfully!", $order_id);
}