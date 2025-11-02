<?php

// Function to filter array by ID
use Carbon\Carbon;

function makeMoney($int, $decimals = 0, $rub_sign = false)
{
    $money = number_format(intval($int), $decimals, ',', ' ') . ($rub_sign ? ' ₽' : '');
    return $money;
}

function getUserAvatar($user)
{
    $avatar = $user->getFirstMediaUrl('avatar');
    if ($avatar == null || $avatar == '') {
        return '/fixed/default_avatar.svg';
    } else {
        return $avatar;
    }
}

function getUserName($user)
{
    return $user['nickname'] ?? $user['name'] . ' ' . $user['surname'];
}

function getWorkCover($work)
{
    if ($work->getFirstMediaUrl('cover') ?? null) {
        $cover = $work->getFirstMediaUrl('cover');
    } else {
        $rnd = Rand(1, 4);
        $cover = "/fixed/default_work_pic_{$rnd}.svg";
    }
    return $cover;
}

function formatDate($date, $format) {
    return Carbon::parse($date)->translatedFormat($format);
}

function getTelegramChatId() {
    if (ENV('APP_ENV') == 'local') {
        return ENV('TELEGRAM_CHAT_ID_TEST');
    } else {
        return ENV('TELEGRAM_CHAT_ID');
    }
}


