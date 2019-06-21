<?php

function get_cart_total($acl = 'user')
{
    $db = Database::getInstance();

    $total = 0;
    foreach ($_REQUEST['cart'] as $id => $item) {
        $row = $db->get_row("SELECT * FROM elements WHERE id='$id' AND type='shop_item'");

        $e = new Element($row);
        $e->amount = $item['amount'];
        $e->price = empty($e->properties["price_$acl"]) ? $e->properties['price'] : $e->properties["price_$acl"];

        if ($acl === 'dealers') {
            $user = get_dealer();
            $level_discount = __config("SHOP_DISCOUNT_$acl" . "_$user[account_level]");
            if (!empty($level_discount)) {
                $e->price *= (float)$level_discount;
            }
        }

        if (empty($item['options'])) {
            $total = $total + ((float)$e->amount * (float)$e->price);
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
                $e->price =
                    empty($e->properties["price_$acl"]) ? $e->properties['price'] : $e->properties["price_$acl"];

                if ($acl === 'dealers') {
                    $user = get_dealer();
                    $level_discount = __config("SHOP_DISCOUNT_$acl" . "_$user[account_level]");
                    if (!empty($level_discount)) {
                        $e->price *= (float)$level_discount;
                    }
                }

                $total += ((float)$e->amount * (float)$e->price);
            }
        }
    }

    return round($total + (float)__config('SHOP_PROCESSING_FEE'), 2);
}