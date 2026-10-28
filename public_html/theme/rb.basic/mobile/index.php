<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>

<?php
include_once(G5_THEME_PATH.'/rb.layout/basic/index.php');
?>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');