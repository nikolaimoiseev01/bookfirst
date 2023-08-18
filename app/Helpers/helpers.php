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

    return $print_order['send_to_country'] . ', ' .
        $print_order['send_to_city'] . ', ' . $print_order['send_to_address'] . ', ' . $print_order['send_to_index'] .
        '. ' . $print_order['send_to_name'] .
        '. ' . $print_order['send_to_tel'];
}
