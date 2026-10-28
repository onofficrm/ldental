<?php
if (!defined('_GNUBOARD_')) exit;

function ldental_seed_phase2()
{
    global $g5;

    $lock = G5_DATA_PATH . '/ldental.phase2';
    if (is_file($lock)) {
        return;
    }

    if (!isset($g5['menu_table']) || !function_exists('sql_query')) {
        return;
    }

    ldental_seed_once();

    $ok = true;
    $ok = ldental_phase2_board_tuning() && $ok;
    $ok = ldental_phase2_menus() && $ok;
    $ok = ldental_phase2_samples() && $ok;
    $ok = ldental_phase2_privacy() && $ok;

    if ($ok) {
        @file_put_contents($lock, date('c') . PHP_EOL);
        if (function_exists('g5_delete_cache_by_prefix')) {
            g5_delete_cache_by_prefix('menu-');
            g5_delete_cache_by_prefix('latest-');
        }
    }
}

function ldental_phase2_board_tuning()
{
    global $g5;

    $review_bo = function_exists('ldental_clinic_bo_table') ? ldental_clinic_bo_table('review') : 'review';
    $review_bo = sql_real_escape_string($review_bo);
    $review = sql_query(" update {$g5['board_table']}
                                 set bo_skin = 'ld.clinic',
                                 bo_mobile_skin = 'ld.clinic',
                                 bo_gallery_cols = '3',
                                 bo_gallery_width = '400',
                                 bo_gallery_height = '300',
                                 bo_mobile_gallery_width = '280',
                                 bo_mobile_gallery_height = '210',
                                 bo_upload_count = '4',
                                 bo_use_list_content = '1',
                                 bo_use_list_file = '1'
                           where bo_table = '{$review_bo}' ", false);

    $faq = sql_query(" update {$g5['board_table']}
                          set bo_subject = '자주 묻는 질문',
                              bo_mobile_subject = '자주 묻는 질문',
                              bo_skin = 'ld.clinic',
                              bo_mobile_skin = 'ld.clinic',
                              bo_use_list_content = '1'
                        where bo_table = 'faq' ", false);

    sql_query(" update {$g5['board_table']}
                   set bo_skin = 'ld.clinic',
                       bo_mobile_skin = 'ld.clinic'
                 where bo_table = 'notice' ", false);

    $consult = sql_query(" update {$g5['board_table']}
                              set bo_list_level = '10',
                                  bo_read_level = '10',
                                  bo_write_level = '10',
                                  bo_use_search = '0'
                            where bo_table = 'consult' ", false);

    return $review !== false && $faq !== false && $consult !== false;
}

function ldental_phase2_menus()
{
    global $g5;

    $bbs = G5_BBS_URL;
    $items = array(
        array('1010', '원장 소개', ld_page_url('doctor'), 1),
        array('60', '공지사항', $bbs . '/board.php?bo_table=notice', 8),
        array('70', '치료후기', ld_board_url('review'), 9),
        array('80', 'FAQ', $bbs . '/board.php?bo_table=faq', 10),
    );

    foreach ($items as $item) {
        ldental_ensure_menu_item($item[0], $item[1], $item[2], $item[3]);
    }

    return true;
}

function ldental_ensure_menu_item($code, $name, $link, $order)
{
    global $g5;

    $code = sql_real_escape_string($code);
    $exists = sql_fetch(" select me_code from {$g5['menu_table']} where me_code = '{$code}' ", false);
    if (!empty($exists['me_code'])) {
        return true;
    }

    $name_sql = sql_real_escape_string($name);
    $link_sql = sql_real_escape_string($link);
    $order = (int) $order;

    return sql_query(" insert into {$g5['menu_table']}
                        set me_code = '{$code}',
                            me_name = '{$name_sql}',
                            me_link = '{$link_sql}',
                            me_target = 'self',
                            me_order = '{$order}',
                            me_use = '1',
                            me_mobile_use = '1' ", false) !== false;
}

function ldental_phase2_samples()
{
    $samples = array(
        'notice' => array(
            array('홈페이지를 새롭게 오픈했습니다', '<p>노경환치과 홈페이지를 리뉴얼했습니다. 진료 안내와 상담 예약을 편하게 이용해 주세요.</p>', true),
            array('연말연시 진료 안내', '<p>연말·연시 휴진 일정은 공지사항과 전화 안내를 참고해 주세요. 급한 통증이 있으면 먼저 연락 주시면 안내해 드립니다.</p>', false),
            array('정기 검진·스케일링 권장 안내', '<p>6개월마다 정기 검진과 스케일링을 받으시면 충치와 잇몸 질환을 미리 예방하는 데 도움이 됩니다.</p>', false),
        ),
        'review' => array(
            array('임플란트 상담 후 치료 결정했어요', '<p>뼈 상태와 비용, 기간을 차근차근 설명해 주셔서 불안 없이 치료를 시작할 수 있었습니다. 직원분들도 친절했습니다.</p>', false),
            array('투명교정 진행 중입니다', '<p>교정 과정과 관리 방법을 자세히 알려 주셔서 생활 속에서도 잘 지킬 수 있었어요. 결과가 기대됩니다.</p>', false),
            array('스케일링 받고 시원해졌어요', '<p>시술 전후로 관리 방법을 알려 주셔서 집에서도 실천하기 좋았습니다. 정기적으로 방문하려고 합니다.</p>', false),
            array('충치 치료, 최대한 아프지 않게', '<p>마취부터 치료까지 세심하게 챙겨 주셔서 걱정했던 것보다 편하게 받았습니다.</p>', false),
        ),
        'faq' => array(
            array('처음 방문할 때 무엇을 준비하면 되나요?', '<p>신분증과 (해당 시) 이전 치료 기록, 복용 중인 약 목록을 알려 주시면 됩니다. 예약 시간에 맞춰 방문해 주세요.</p>', false),
            array('임플란트는 얼마나 걸리나요?', '<p>뼈 상태와 보철 종류에 따라 다릅니다. 정확한 기간은 검사 후 치료 계획 상담 시 안내해 드립니다.</p>', false),
            array('교정은 몇 살부터 가능한가요?', '<p>성장기·성인 모두 가능합니다. 얼굴 성장과 치열 상태에 맞는 방법을 제안합니다.</p>', false),
            array('주차는 가능한가요?', '<p>병원 주변 주차 가능 여부는 오시는 길 안내 또는 전화·카카오 상담으로 안내해 드립니다.</p>', false),
        ),
    );

    foreach ($samples as $bo_table => $rows) {
        $target = $bo_table;
        if ($bo_table === 'review' && function_exists('ldental_clinic_bo_table')) {
            ldental_repair_clinic_boards();
            $target = ldental_clinic_bo_table('review');
        }
        foreach ($rows as $row) {
            if (!ldental_insert_sample_write($target, $row[0], $row[1], $row[2])) {
                return false;
            }
        }
    }

    return true;
}

function ldental_insert_sample_write($bo_table, $subject, $content, $is_notice = false)
{
    global $g5;

    $bo_table = preg_replace('/[^a-z0-9_]/i', '', $bo_table);
    ldental_ensure_write_table($bo_table);

    $write_table = $g5['write_prefix'] . $bo_table;
    $subject_sql = sql_real_escape_string($subject);
    $exists = sql_fetch(" select wr_id from {$write_table} where wr_subject = '{$subject_sql}' and wr_is_comment = 0 limit 1 ", false);
    if (!empty($exists['wr_id'])) {
        return true;
    }

    $content_sql = sql_real_escape_string($content);
    $wr_num = get_next_num($write_table);
    $ip = sql_real_escape_string($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
    $sql = " insert into {$write_table}
                set wr_num = '{$wr_num}',
                    wr_reply = '',
                    wr_comment = 0,
                    wr_parent = 0,
                    wr_is_comment = 0,
                    ca_name = '',
                    wr_option = 'html1',
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
                    mb_id = 'admin',
                    wr_password = '',
                    wr_name = '노경환치과',
                    wr_email = '',
                    wr_homepage = '',
                    wr_datetime = '" . G5_TIME_YMDHIS . "',
                    wr_last = '" . G5_TIME_YMDHIS . "',
                    wr_ip = '{$ip}',
                    wr_1 = '', wr_2 = '', wr_3 = '', wr_4 = '', wr_5 = '',
                    wr_6 = '', wr_7 = '', wr_8 = '', wr_9 = '', wr_10 = '' ";

    if (sql_query($sql, false) === false) {
        return false;
    }

    $wr_id = sql_insert_id();
    if (!$wr_id) {
        return false;
    }

    sql_query(" update {$write_table} set wr_parent = '{$wr_id}' where wr_id = '{$wr_id}' ", false);
    sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}' ", false);

    if ($is_notice) {
        sql_query(" update {$g5['board_table']} set bo_notice = '{$wr_id}' where bo_table = '{$bo_table}' ", false);
    }

    if (isset($g5['board_new_table'])) {
        sql_query(" insert into {$g5['board_new_table']}
                        set bo_table = '{$bo_table}',
                            wr_id = '{$wr_id}',
                            wr_parent = '{$wr_id}',
                            bn_datetime = '" . G5_TIME_YMDHIS . "',
                            mb_id = 'admin' ", false);
    }

    if (function_exists('delete_cache_latest')) {
        delete_cache_latest($bo_table);
    }

    return true;
}

function ldental_phase2_privacy()
{
    global $g5;

    $name = ld_clinic('name', '노경환치과');
    $html = '<h2>개인정보 처리방침</h2>'
        . '<p>' . $name . ' 홈페이지(이하 “병원”)는 「개인정보 보호법」 등 관련 법령을 준수하며, 이용자의 개인정보를 보호합니다.</p>'
        . '<h3>1. 수집 항목</h3><ul>'
        . '<li>상담 예약: 이름, 연락처, 관심 진료, 희망 방문일, 상담 내용</li>'
        . '<li>자동 수집: 접속 IP, 쿠키, 접속 로그(그누보드 기본 기능)</li></ul>'
        . '<h3>2. 이용 목적</h3><ul>'
        . '<li>상담·예약 확인 및 회신</li>'
        . '<li>진료 연계 안내 및 고객 문의 대응</li>'
        . '<li>홈페이지 운영·보안·통계</li></ul>'
        . '<h3>3. 보유 기간</h3>'
        . '<p>상담 접수 정보는 목적 달성 후 지체 없이 파기합니다. 다만 관련 법령에 따라 보관이 필요한 경우 해당 기간 동안 보관할 수 있습니다.</p>'
        . '<h3>4. 제3자 제공</h3>'
        . '<p>병원은 원칙적으로 이용자의 개인정보를 외부에 제공하지 않습니다. 법령에 따른 경우는 예외로 합니다.</p>'
        . '<h3>5. 이용자 권리</h3>'
        . '<p>이용자는 개인정보 열람·정정·삭제·처리 정지를 요청할 수 있습니다. 홈페이지 상담 또는 병원 연락처로 문의해 주세요.</p>'
        . '<h3>6. 문의</h3>'
        . '<p>개인정보 보호 문의: ' . $name . ' (홈페이지 상담 예약 또는 대표 전화)</p>';

    $html_sql = sql_real_escape_string($html);
    return sql_query(" update {$g5['content_table']}
                          set co_content = '{$html_sql}',
                              co_mobile_content = '{$html_sql}'
                        where co_id = 'privacy' ", false) !== false;
}
