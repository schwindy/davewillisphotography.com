<?php

function convert_to($from, $volume, $to)
{
    if (empty($volume)) {
        return 0;
    }
    if ($from == $to || $volume == 0) {
        return (float)$volume;
    }

    $db = Database::getInstance();

    if ($from === "USDT") {
        $from = "USD";
    }

    if ($to === "USDT") {
        $to = "USD";
    }

    // Look for an XMarket w/ this Market ID
    $xmarket = $db->get_row("SELECT 
          last, 
          base_currency, 
          quote_currency 
        FROM xmarkets
        WHERE 
          last > 0 AND
          ((base_currency='$from' AND quote_currency='$to') OR
          (base_currency='$to' AND quote_currency='$from'))
        ORDER BY volume DESC
        LIMIT 1");

    // Look for an Market w/ this Market ID
    if (empty($xmarket)) {
        $xmarket = $db->get_row("SELECT
              last,
              base_currency,
              quote_currency
            FROM markets
            WHERE
              last > 0 AND
              ((base_currency='$from' AND quote_currency='$to') OR
              (base_currency='$to' AND quote_currency='$from'))
            ORDER BY volume_btc DESC
            LIMIT 1");
    }

    // Look for an XMarket w/ Market ID containing $from and try to convert into $to using a "link" 3rd XMarket
    if (empty($xmarket)) {
        $xmarkets = $db->get_rows("SELECT 
              last, 
              base_currency, 
              quote_currency 
            FROM xmarkets
            WHERE 
              last > 0 AND 
              (base_currency='$from' AND quote_currency='BTC') OR 
              (base_currency='BTC' AND quote_currency='$from')
            ORDER BY volume DESC");

        foreach ($xmarkets as $row) {
            $xmarket = $db->get_row("SELECT 
                  last, 
                  base_currency, 
                  quote_currency 
                FROM xmarkets
                WHERE 
                  last > 0 AND 
                  (base_currency='$to' AND quote_currency='BTC') OR 
                  (base_currency='BTC' AND quote_currency='$to')
                ORDER BY volume DESC");

            if (empty($xmarket)) {
                continue;
            }
            $row['last'] = (float)$row['last'];
            $row['volume'] = (float)$row['volume'];

            if ($row['base_currency'] === "BTC") {
                $volume = $volume * (1 / $row['last']);
            } else {
                $volume = $volume * $row['last'];
            }
            if (!empty($xmarket)) {
                break;
            }
        }

        if (empty($xmarket)) {
            return false;
        }
    }

    if (empty($xmarket)) {
        return -1;
    }
    $rate = (float)$xmarket['last'];
    $volume = (float)$volume;
    if (empty($rate)) {
        return -2;
    }
    if (empty($volume)) {
        return -3;
    }

    if ($xmarket['base_currency'] === $to) {
        $calc = $volume * (1 / $rate);
    } else {
        if ($xmarket['quote_currency'] !== $to) {
            return -4;
        }

        $calc = $volume * $rate;
    }

    return $calc;
}

function convert_to_raw($from, $volume, $to, $xmarkets = [])
{
    if (empty($volume)) {
        return 0;
    }
    if ($from == $to || $volume == 0) {
        return (float)$volume;
    }
    if (empty($xmarkets)) {
        return 0;
    }
    if ($from === "USDT") {
        $from = "USD";
    }
    if ($to === "USDT") {
        $to = "USD";
    }
    $xmarket = false;

    // Look for an XMarket w/ this Market ID
    foreach ($xmarkets as $x) {
        $x = (array)$x;
        if ((float)$x['last'] <= 0) {
            continue;
        }

        if ($x['market_id'] === $from . "_" . $to || $x['market_id'] === $to . "_" . $from) {
            $xmarket = $x;
            break;
        }
    }

    // Look for an XMarket w/ Market ID containing $from and try to convert into $to using a "link" 3rd XMarket
    if (empty($xmarket)) {
        $routes = [];
        foreach ($xmarkets as $x) {
            $x = (array)$x;
            if ((float)$x['last'] <= 0) {
                continue;
            }

            if ($x['base_currency'] === $from && $x['quote_currency'] === "BTC") {
                $routes[] = $x;
            } else {
                if ($x['base_currency'] === "BTC" && $x['quote_currency'] === $from) {
                    $routes[] = $x;
                }
            }
        }

        foreach ($routes as $row) {
            foreach ($xmarkets as $x) {
                $x = (array)$x;
                if ((float)$x['last'] <= 0) {
                    continue;
                }

                if ($x['base_currency'] === $to && $x['quote_currency'] === "BTC") {
                    $xmarket = $x;
                    break;
                } else {
                    if ($x['base_currency'] === "BTC" && $x['quote_currency'] === $to) {
                        $xmarket = $x;
                        break;
                    }
                }
            }

            if (empty($xmarket)) {
                continue;
            }
            $row['last'] = (float)$row['last'];
            $row['volume'] = (float)$row['volume'];

            if ($row['base_currency'] === "BTC") {
                $volume = $volume * (1 / $row['last']);
            } else {
                $volume = $volume * $row['last'];
            }

            if (!empty($xmarket)) {
                break;
            }
        }

        if (empty($xmarket)) {
            return false;
        }
    }

    if (empty($xmarket)) {
        return -1;
    }
    $rate = (float)$xmarket['last'];
    $volume = (float)$volume;
    if (empty($rate)) {
        return -2;
    }
    if (empty($volume)) {
        return -3;
    }

    if ($xmarket['base_currency'] === $to) {
        $calc = $volume * (1 / $rate);
    } else {
        if ($xmarket['quote_currency'] === $to) {
            $calc = $volume * $rate;
        } else {
            return -4;
        }
    }

    return $calc;
}

function get_base_currency($currency_link)
{
    return substr($currency_link, 0, strpos($currency_link, '_'));
}

function get_quote_currency($currency_link)
{
    return substr($currency_link, strpos($currency_link, '_') + 1);
}

function get_reverse_currency_link($currency_link)
{
    return get_quote_currency($currency_link) . '_' . get_base_currency($currency_link);
}