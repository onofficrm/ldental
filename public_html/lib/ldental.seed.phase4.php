<?php
if (!defined('_GNUBOARD_')) exit;

function ldental_seed_phase4()
{
    global $g5;

    $lock = G5_DATA_PATH . '/ldental.phase4';
    if (is_file($lock) || !isset($g5['board_table'])) {
        return;
    }

    $skin = 'theme/ld.clinic';
    $skin_sql = sql_real_escape_string($skin);
    $review_bo = function_exists('ldental_clinic_bo_table') ? ldental_clinic_bo_table('review') : 'review';
    $tables = array('notice', 'faq', $review_bo);
    $ok = true;

    foreach (array_unique($tables) as $bo_table) {
        $bo_table = preg_replace('/[^a-z0-9_]/i', '', $bo_table);
        $sql = " update {$g5['board_table']}
                    set bo_skin = '{$skin_sql}',
                        bo_mobile_skin = '{$skin_sql}',
                        bo_use_list_content = '1',
                        bo_use_list_file = '1'
                  where bo_table = '{$bo_table}' ";
        if (sql_query($sql, false) === false) {
            $ok = false;
        }
    }

    if ($ok) {
        @file_put_contents($lock, date('c') . PHP_EOL);
        if (function_exists('g5_delete_cache_by_prefix')) {
            g5_delete_cache_by_prefix('latest-');
        }
    }
}
