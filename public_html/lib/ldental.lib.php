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

function ld_board_url($bo_table)
{
    $bo_table = preg_replace('/[^a-z0-9_]/i', '', (string) $bo_table);
    return G5_BBS_URL . '/board.php?bo_table=' . $bo_table;
}

function ldental_notify_consult($subject, $body)
{
    global $config;

    if (!function_exists('mailer')) {
        include_once G5_LIB_PATH . '/mailer.lib.php';
    }

    $to = ld_clinic('notify_email');
    if ($to === '' && !empty($config['cf_admin_email'])) {
        $to = trim((string) $config['cf_admin_email']);
    }
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $from_name = ld_clinic('name', '노경환치과');
    $from_mail = !empty($config['cf_admin_email']) ? $config['cf_admin_email'] : 'noreply@' . preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $content = nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));

    return mailer($from_name, $from_mail, $to, $subject, $content, 1);
}
