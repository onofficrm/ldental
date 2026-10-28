<?php
if (!defined('_GNUBOARD_')) exit;

function ld_clinic($key, $default = '')
{
    global $ld_clinic;
    if (!isset($ld_clinic[$key])) {
        return $default;
    }
    $value = trim((string) $ld_clinic[$key]);
    return $value !== '' ? $value : $default;
}

function ld_clinic_phone_ready()
{
    $phone = preg_replace('/[^0-9]/', '', ld_clinic('phone'));
    return $phone !== '' && $phone !== '0000000000';
}

function ld_clinic_kakao_ready()
{
    $kakao = ld_clinic('kakao', 'https://pf.kakao.com/');
    return $kakao !== '' && $kakao !== 'https://pf.kakao.com/';
}

function ld_page_url($page)
{
    $page = preg_replace('/[^a-z0-9_]/i', '', $page);
    return G5_URL . '/page/' . $page . '.php';
}

function ld_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
