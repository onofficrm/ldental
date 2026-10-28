<?php
if (!defined('_GNUBOARD_')) exit;

$ld_clinic_file = G5_DATA_PATH . '/ldental.clinic.php';
if (is_file($ld_clinic_file)) {
    include_once $ld_clinic_file;
}

include_once G5_LIB_PATH . '/ldental.lib.php';
include_once G5_LIB_PATH . '/ldental.seed.php';

ldental_seed_once();

if (defined('G5_THEME_URL') && !defined('G5_IS_ADMIN')) {
    add_stylesheet('<link rel="stylesheet" href="' . G5_THEME_URL . '/rb.layout/basic/page.css?ver=' . G5_SERVER_TIME . '">', 1);
}
