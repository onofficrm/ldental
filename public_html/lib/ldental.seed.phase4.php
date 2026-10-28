<?php
if (!defined('_GNUBOARD_')) exit;

function ldental_seed_phase4()
{
    ldental_apply_clinic_skins();
}

function ldental_apply_clinic_skins()
{
    global $g5;

    if (!isset($g5['board_table']) || !function_exists('sql_query')) {
        return;
    }

    $skin = is_dir(G5_PATH . '/skin/board/ld.clinic') ? 'ld.clinic' : 'theme/ld.clinic';
    $skin_sql = sql_real_escape_string($skin);
    $review_bo = function_exists('ldental_clinic_bo_table') ? ldental_clinic_bo_table('review') : 'review';
    $tables = array('notice', 'faq', $review_bo);

    foreach (array_unique($tables) as $bo_table) {
        $bo_table = preg_replace('/[^a-z0-9_]/i', '', $bo_table);
        sql_query(" update {$g5['board_table']}
                       set bo_skin = '{$skin_sql}',
                           bo_mobile_skin = '{$skin_sql}',
                           bo_use_list_content = '1',
                           bo_use_list_file = '1'
                     where bo_table = '{$bo_table}'
                       and (bo_skin <> '{$skin_sql}' or bo_mobile_skin <> '{$skin_sql}') ", false);
    }
}
