<?php

function generate_cart_element($cart = [], $acl = 'user')
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
                $e->display_name = "$row[display_name]<br> $type: $option";
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
        'elements' => $elements,
        'headers'  => [
            'Item Name'   => 'button width_5',
            'Price ($)'   => 'button width_5',
            'Quantity'    => 'button width_5',
            'Remove Item' => 'button width_5',
        ],
        'fields'   => [
            'display_name' => ['class' => 'display_name'],
            'price'        => ['class' => 'price'],
            'amount'       => ['class' => 'amount'],
            'button'       => ['class' => 'remove_from_cart button red_bg white',]
        ],
    ]);

    $html .= "<br>";

    return $html;
}