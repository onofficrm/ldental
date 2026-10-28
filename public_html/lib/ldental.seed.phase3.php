<?php
if (!defined('_GNUBOARD_')) exit;

function ldental_seed_phase3()
{
    $lock = G5_DATA_PATH . '/ldental.phase3';
    if (is_file($lock)) {
        return;
    }

    if (!function_exists('ldental_repair_clinic_boards')) {
        return;
    }

    ldental_seed_once();
    ldental_repair_clinic_boards();

    global $g5;
    if (isset($g5['menu_table'])) {
        $review_bo = ldental_clinic_bo_table('review');
        $bbs = G5_BBS_URL;
        ldental_ensure_menu_item('70', '치료후기', $bbs . '/board.php?bo_table=' . $review_bo, 9);
    }

    if (function_exists('ldental_phase2_board_tuning')) {
        ldental_phase2_board_tuning();
    }

    @file_put_contents($lock, date('c') . PHP_EOL);
}
