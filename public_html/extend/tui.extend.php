<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$current_file = basename($_SERVER['SCRIPT_NAME']);

if ($current_file == "board.php" && $board['bo_select_editor'] == "tui.editor") {
    $editor_url = G5_EDITOR_URL.'/'.$board['bo_select_editor'];

    add_stylesheet('<link rel="stylesheet" href="'.$editor_url.'/css/toastui-editor.min.css">', 0);
    add_javascript('<script src="'.$editor_url.'/js/content_wrap.js"></script>', 1);
}