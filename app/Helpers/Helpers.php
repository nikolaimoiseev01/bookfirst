<?php

// Function to filter array by ID
function makeMoney($int, $rub_sign = true) {
    $money = number_format($int, 2, ',', ' ') . $rub_sign ? ' ₽' : '';
    return $money;
}
