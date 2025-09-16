<?php

// Function to filter array by ID
function makeMoney($int, $decimals = 0, $rub_sign = false) {
    $money = number_format(intval($int), $decimals, ',', ' ') . ($rub_sign ? ' ₽' : '');
    return $money;
}

function getAvatarUrl($user) {
    $avatar = $user->getFirstMediaUrl('avatar');
    if($avatar == null || $avatar == '') {
        return '/fixed/default_avatar.svg';
    } else {
        return $avatar;
    }
}



