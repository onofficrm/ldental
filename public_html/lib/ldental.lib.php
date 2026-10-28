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

function ldental_clinic_bo_table($key)
{
    global $g5;

    static $map = null;
    if ($map !== null) {
        return isset($map[$key]) ? $map[$key] : $key;
    }

    $map = array(
        'notice' => 'notice',
        'faq' => 'faq',
        'consult' => 'consult',
        'review' => 'review',
    );

    if (!isset($g5['board_table'])) {
        return $map[$key];
    }

    foreach (array('review', 'pat_review') as $candidate) {
        $row = sql_fetch(" select bo_table from {$g5['board_table']} where bo_table = '{$candidate}' ", false);
        if (!empty($row['bo_table'])) {
            $map['review'] = $candidate;
            break;
        }
    }

    return isset($map[$key]) ? $map[$key] : $key;
}

function ldental_repair_clinic_boards()
{
    global $g5;

    if (!isset($g5['board_table']) || !function_exists('ldental_ensure_board')) {
        return false;
    }

    $gr = sql_fetch(" select gr_id from {$g5['group_table']} where gr_id = 'clinic' ", false);
    $gr_id = !empty($gr['gr_id']) ? 'clinic' : '';
    if (!$gr_id) {
        $any = sql_fetch(" select gr_id from {$g5['group_table']} order by gr_id limit 1 ", false);
        $gr_id = isset($any['gr_id']) ? $any['gr_id'] : 'clinic';
    }

    $defs = array(
        'notice' => array('공지사항', 10, 1, 0),
        'review' => array('치료후기', 10, 1, 0),
        'pat_review' => array('치료후기', 10, 1, 0),
        'faq' => array('자주 묻는 질문', 10, 1, 0),
        'consult' => array('상담예약', 1, 10, 1),
    );

    foreach ($defs as $bo_table => $opt) {
        ldental_ensure_board($gr_id, $bo_table, $opt[0], $opt[1], $opt[2], $opt[3]);
    }

    $review_board = sql_fetch(" select bo_table from {$g5['board_table']} where bo_table = 'review' ", false);
    if (empty($review_board['bo_table'])) {
        $write_review = $g5['write_prefix'] . 'review';
        $has_review_writes = sql_fetch(" show tables like '" . sql_real_escape_string($write_review) . "' ", false);
        if ($has_review_writes) {
            ldental_ensure_board($gr_id, 'pat_review', '치료후기', 10, 1, 0);
            ldental_migrate_review_writes('review', 'pat_review');
        }
    }

    if (function_exists('g5_delete_cache_by_prefix')) {
        g5_delete_cache_by_prefix('latest-');
    }

    return true;
}

function ldental_migrate_review_writes($from, $to)
{
    global $g5;

    $from = preg_replace('/[^a-z0-9_]/i', '', $from);
    $to = preg_replace('/[^a-z0-9_]/i', '', $to);
    if ($from === $to) {
        return;
    }

    ldental_ensure_write_table($to);
    $from_table = $g5['write_prefix'] . $from;
    $to_table = $g5['write_prefix'] . $to;
    $exists_from = sql_fetch(" show tables like '" . sql_real_escape_string($from_table) . "' ", false);
    if (!$exists_from) {
        return;
    }

    $cnt = sql_fetch(" select count(*) as cnt from {$to_table} where wr_is_comment = 0 ", false);
    if (!empty($cnt['cnt']) && (int) $cnt['cnt'] > 0) {
        return;
    }

    $result = sql_query(" select * from {$from_table} where wr_is_comment = 0 order by wr_id ", false);
    if (!$result) {
        return;
    }

    while ($row = sql_fetch_array($result)) {
        $subject = sql_real_escape_string($row['wr_subject']);
        $dup = sql_fetch(" select wr_id from {$to_table} where wr_subject = '{$subject}' and wr_is_comment = 0 limit 1 ", false);
        if (!empty($dup['wr_id'])) {
            continue;
        }

        $fields = array();
        foreach ($row as $col => $val) {
            if ($col === 'wr_id') {
                continue;
            }
            if (!preg_match('/^[a-z0-9_]+$/i', $col)) {
                continue;
            }
            $fields[] = "`{$col}` = '" . sql_real_escape_string((string) $val) . "'";
        }
        if (!$fields) {
            continue;
        }
        sql_query(' insert into ' . $to_table . ' set ' . implode(', ', $fields), false);
        $new_id = sql_insert_id();
        if ($new_id) {
            sql_query(" update {$to_table} set wr_parent = '{$new_id}' where wr_id = '{$new_id}' ", false);
        }
    }

    $count = sql_fetch(" select count(*) as cnt from {$to_table} where wr_is_comment = 0 ", false);
    if (!empty($count['cnt'])) {
        sql_query(" update {$g5['board_table']} set bo_count_write = '" . (int) $count['cnt'] . "' where bo_table = '{$to}' ", false);
    }
}

function ld_board_url($bo_table)
{
    $key = preg_replace('/[^a-z0-9_]/i', '', (string) $bo_table);
    if ($key === 'review' || $key === 'pat_review') {
        $key = 'review';
    }
    $bo_table = ldental_clinic_bo_table($key);
    return G5_BBS_URL . '/board.php?bo_table=' . $bo_table;
}

function ld_write_url($bo_table, $wr_id)
{
    $bo_table = ldental_clinic_bo_table($bo_table === 'review' || $bo_table === 'pat_review' ? 'review' : $bo_table);
    return G5_BBS_URL . '/board.php?bo_table=' . urlencode($bo_table) . '&wr_id=' . (int) $wr_id;
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
