<?php

function prefer_name($name, $surname, $nickname)
{
    if ($nickname) {
        return $nickname;
    }
    {
        return $name . ' ' . $surname;
    }
}

function print_address($print_order)
{
    $address = json_decode($print_order['address']);
//    dd($address);
    $country = $print_order->address_country ?? null ? $print_order->address_country . ',' : null;
    if ($address->type == 'foreign') {
        $address = $address->unrestricted_value;
    } else {
        $address = "$country $address->unrestricted_value";
    }
    $full_string = "{$address}, {$print_order['send_to_name']}, {$print_order['send_to_tel']}";
    return $full_string;
}

function tracking_link($print_order)
{
    if ($print_order['logistic'] == 'cdek') {
        $link = "https://www.cdek.ru/ru/tracking/?order_id={$print_order['track_number']}";
    } else {
        $link = "https://www.pochta.ru/tracking#{$print_order['track_number']}";
    }
    return $link;
}
