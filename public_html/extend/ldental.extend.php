<?php
if (!defined('_GNUBOARD_')) exit;

$ld_clinic_file = G5_DATA_PATH . '/ldental.clinic.php';
if (is_file($ld_clinic_file)) {
    include_once $ld_clinic_file;
}

include_once G5_LIB_PATH . '/ldental.lib.php';
include_once G5_LIB_PATH . '/ldental.seed.php';

ldental_seed_once();
if (function_exists('ldental_seed_phase2')) {
    ldental_seed_phase2();
}
if (function_exists('ldental_seed_phase3')) {
    ldental_seed_phase3();
}
if (function_exists('ldental_seed_phase4')) {
    ldental_seed_phase4();
}

if (defined('G5_THEME_URL') && !defined('G5_IS_ADMIN')) {
    add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_URL . '/rb.layout/basic/page.css?ver=' . G5_SERVER_TIME . '">', 1);
}

if (!defined('G5_IS_ADMIN') && !defined('G5_ADMIN')) {
    $ld_bo = isset($_GET['bo_table']) ? preg_replace('/[^a-z0-9_]/i', '', (string) $_GET['bo_table']) : '';
    if ($ld_bo === 'consult') {
        global $is_admin, $member;
        $level = isset($member['mb_level']) ? (int) $member['mb_level'] : 0;
        $script = basename($_SERVER['SCRIPT_NAME'] ?? '');
        if (!$is_admin && $level < 10 && in_array($script, array('board.php', 'write.php', 'view.php'), true)) {
            goto_url(ld_page_url('consult'));
        }
    }
}
