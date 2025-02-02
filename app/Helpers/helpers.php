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
        return "$address->unrestricted_value";
    } else {
        return "$country $address->unrestricted_value";
    }
}
