<?php

function generate_order_cart($cart = [], $acl = 'user')
{
    $db = Database::getInstance();

    $elements = [];
    foreach ($cart as $id => $item) {
        $row = $db->get_row("SELECT * FROM elements WHERE id='$id' AND type='shop_item'");

        $e = new Element($row);
        $e->amount = $item['amount'];
        $e->price = $acl === 'user' ? $e->properties['price'] : $e->properties["price_$acl"];
        $e->price = empty($e->price) ? $e->properties['price'] : $e->price;

        if ($acl === 'dealers') {
            $user = get_dealer();
            $level_discount = __config("SHOP_DISCOUNT_$acl" . "_$user[account_level]");
            if (!empty($level_discount)) {
                $e->price *= (float)$level_discount;
            }
        }

        if (empty($item['options'])) {
            $elements[] = $e;
            continue;
        }

        foreach ($item['options'] as $type => $options) {
            $type = ucwords($type);

            foreach ($options as $option => $amount) {
                if (empty($amount)) {
                    continue;
                }

                $e = new Element($row);
                $e->display_name = "$row[display_name] | $type: $option";
                $e->amount = $amount;
                $e->price = $acl === 'user' ? $e->properties['price'] : $e->properties["price_$acl"];
                $e->price = empty($e->price) ? $e->properties['price'] : $e->price;

                if ($acl === 'dealers') {
                    $user = get_dealer();
                    $level_discount = __config("SHOP_DISCOUNT_$acl" . "_$user[account_level]");
                    if (!empty($level_discount)) {
                        $e->price *= (float)$level_discount;
                    }
                }

                $elements[] = $e;
            }
        }
    }

    $html = __html('table', [
        'title'    => 'Cart',
        'elements' => $elements,
        'headers'  => [
            'Item Name' => 'button',
            'Price ($)' => 'button',
            'Quantity'  => 'button',
        ],
        'fields'   => [
            'display_name' => ['class' => 'display_name'],
            'price'        => ['class' => 'price'],
            'amount'       => ['class' => 'amount'],
        ],
    ]);

    return $html;
}