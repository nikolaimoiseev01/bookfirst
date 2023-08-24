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

function print_address($id) {
    $print_order = \App\Models\Printorder::where('id', $id)->first();

    if($print_order['send_to_country']) {
        $address = $print_order['send_to_country'] . ', ' .
            $print_order['send_to_city'] . ', ' . $print_order['send_to_address'] . ', ' . $print_order['send_to_index'];
    } else {
        $address = $print_order['send_to_address'];
    }
    return $address .
        '. ' . $print_order['send_to_name'] .
        '. ' . $print_order['send_to_tel'];
}
