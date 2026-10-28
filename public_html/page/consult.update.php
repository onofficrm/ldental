<?php
include_once dirname(__DIR__) . '/_common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    goto_url(ld_page_url('consult'));
}

$honeypot = isset($_POST['website']) ? trim((string) $_POST['website']) : '';
if ($honeypot !== '') {
    goto_url(ld_page_url('consult'));
}

$token = isset($_POST['ld_token']) ? (string) $_POST['ld_token'] : '';
$saved = get_session('ld_consult_token');
if (!$token || !$saved || !hash_equals((string) $saved, $token)) {
    alert('잘못된 접근입니다. 다시 시도해 주세요.', ld_page_url('consult'));
}

$captcha = isset($_POST['ld_captcha']) ? preg_replace('/[^0-9]/', '', (string) $_POST['ld_captcha']) : '';
$answer = (string) get_session('ld_consult_captcha');
if ($captcha === '' || $answer === '' || $captcha !== $answer) {
    alert('자동등록방지 숫자가 올바르지 않습니다.', ld_page_url('consult'));
}

$last = (int) get_session('ld_consult_last');
if ($last && (G5_SERVER_TIME - $last) < 20) {
    alert('잠시 후 다시 신청해 주세요.', ld_page_url('consult'));
}

$agree = isset($_POST['agree']) ? (int) $_POST['agree'] : 0;
if (!$agree) {
    alert('개인정보 처리방침에 동의해 주세요.', ld_page_url('consult'));
}

$wr_name = isset($_POST['wr_name']) ? clean_xss_tags($_POST['wr_name'], 1, 1) : '';
$wr_1 = isset($_POST['wr_1']) ? clean_xss_tags($_POST['wr_1'], 1, 1) : '';
$wr_2 = isset($_POST['wr_2']) ? clean_xss_tags($_POST['wr_2'], 1, 1) : '';
$wr_3 = isset($_POST['wr_3']) ? clean_xss_tags($_POST['wr_3'], 1, 1) : '';
$wr_content = isset($_POST['wr_content']) ? clean_xss_tags($_POST['wr_content'], 1, 1) : '';

if (mb_strlen($wr_name, 'UTF-8') < 2 || mb_strlen($wr_name, 'UTF-8') > 20) {
    alert('이름을 확인해 주세요.', ld_page_url('consult'));
}
if (mb_strlen($wr_1, 'UTF-8') < 8) {
    alert('연락처를 확인해 주세요.', ld_page_url('consult'));
}
if (mb_strlen($wr_content, 'UTF-8') < 5) {
    alert('상담 내용을 조금 더 자세히 적어 주세요.', ld_page_url('consult'));
}

ldental_seed_once();

$bo_table = 'consult';
$write_table = $g5['write_prefix'] . $bo_table;
$board = sql_fetch(" select bo_table from {$g5['board_table']} where bo_table = '{$bo_table}' ", false);
if (empty($board['bo_table'])) {
    alert('상담 접수 준비가 아직 끝나지 않았습니다. 전화로 문의해 주세요.', ld_page_url('consult'));
}

ldental_ensure_write_table($bo_table);

$subject = '[상담] ' . $wr_name;
if ($wr_2) {
    $subject .= ' / ' . $wr_2;
}
$content = "이름: {$wr_name}\n연락처: {$wr_1}\n관심 진료: {$wr_2}\n희망 방문일: {$wr_3}\n\n{$wr_content}";

$wr_name_sql = sql_real_escape_string($wr_name);
$subject_sql = sql_real_escape_string($subject);
$content_sql = sql_real_escape_string($content);
$wr_1_sql = sql_real_escape_string($wr_1);
$wr_2_sql = sql_real_escape_string($wr_2);
$wr_3_sql = sql_real_escape_string($wr_3);
$wr_password = get_encrypt_string(uniqid('pw', true));
$wr_num = get_next_num($write_table);
$ip = sql_real_escape_string($_SERVER['REMOTE_ADDR']);

$sql = " insert into {$write_table}
            set wr_num = '{$wr_num}',
                wr_reply = '',
                wr_comment = 0,
                wr_parent = 0,
                wr_is_comment = 0,
                ca_name = '',
                wr_option = 'secret',
                wr_subject = '{$subject_sql}',
                wr_content = '{$content_sql}',
                wr_seo_title = '',
                wr_link1 = '',
                wr_link2 = '',
                wr_link1_hit = 0,
                wr_link2_hit = 0,
                wr_hit = 0,
                wr_good = 0,
                wr_nogood = 0,
                mb_id = '',
                wr_password = '{$wr_password}',
                wr_name = '{$wr_name_sql}',
                wr_email = '',
                wr_homepage = '',
                wr_datetime = '" . G5_TIME_YMDHIS . "',
                wr_last = '" . G5_TIME_YMDHIS . "',
                wr_ip = '{$ip}',
                wr_1 = '{$wr_1_sql}',
                wr_2 = '{$wr_2_sql}',
                wr_3 = '{$wr_3_sql}',
                wr_4 = '', wr_5 = '', wr_6 = '', wr_7 = '', wr_8 = '', wr_9 = '', wr_10 = '' ";

if (sql_query($sql, false) === false) {
    alert('신청 중 오류가 났습니다. 잠시 후 다시 시도해 주세요.', ld_page_url('consult'));
}

$wr_id = sql_insert_id();
if ($wr_id) {
    sql_query(" update {$write_table} set wr_parent = '{$wr_id}' where wr_id = '{$wr_id}' ", false);
    sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}' ", false);
    if (isset($g5['board_new_table'])) {
        sql_query(" insert into {$g5['board_new_table']}
                        set bo_table = '{$bo_table}',
                            wr_id = '{$wr_id}',
                            wr_parent = '{$wr_id}',
                            bn_datetime = '" . G5_TIME_YMDHIS . "',
                            mb_id = '' ", false);
    }
}

set_session('ld_consult_token', '');
set_session('ld_consult_captcha', '');
set_session('ld_consult_last', G5_SERVER_TIME);

alert('상담 신청이 접수되었습니다. 확인 후 연락드리겠습니다.', G5_URL);
