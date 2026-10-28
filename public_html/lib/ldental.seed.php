<?php
if (!defined('_GNUBOARD_')) exit;

function ldental_seed_once()
{
    global $g5, $config, $ld_clinic;

    $lock = G5_DATA_PATH . '/ldental.seeded';
    if (is_file($lock)) {
        return;
    }

    if (!isset($g5['menu_table']) || !function_exists('sql_query')) {
        return;
    }

    $ok = true;
    $ok = ldental_seed_config() && $ok;
    $ok = ldental_seed_builder() && $ok;
    $ok = ldental_seed_contents() && $ok;
    $ok = ldental_seed_boards() && $ok;
    $ok = ldental_seed_menus() && $ok;

    if ($ok) {
        @file_put_contents($lock, date('c') . PHP_EOL);
        if (function_exists('g5_delete_cache_by_prefix')) {
            g5_delete_cache_by_prefix('menu-');
            g5_delete_cache_by_prefix('content-');
        }
    }
}

function ldental_seed_config()
{
    global $g5, $config;

    $title = sql_real_escape_string(ld_clinic('name', '노경환치과'));
    $sql = " update {$g5['config_table']}
                set cf_title = '{$title}'
              where cf_title = '' or cf_title = '그누보드5' ";
    $r1 = sql_query($sql, false);

    $sql = " update {$g5['config_table']}
                set cf_theme = 'rb.basic'
              where cf_theme = '' or cf_theme is null ";
    $r2 = sql_query($sql, false);

    if (empty($config['cf_title']) || $config['cf_title'] === '그누보드5') {
        $config['cf_title'] = ld_clinic('name', '노경환치과');
    }
    if (empty($config['cf_theme'])) {
        $config['cf_theme'] = 'rb.basic';
    }

    return $r1 !== false && $r2 !== false;
}

function ldental_seed_builder()
{
    $row = sql_fetch(" select * from rb_builder ", false);
    $name = sql_real_escape_string(ld_clinic('name', '노경환치과'));
    $doctor = sql_real_escape_string(ld_clinic('doctor', '노경환'));
    $phone = sql_real_escape_string(ld_clinic('phone'));
    $en = sql_real_escape_string(ld_clinic('en_name', 'L Dental'));
    $address = sql_real_escape_string(ld_clinic('address'));
    $copy = sql_real_escape_string('Copyright © ' . ld_clinic('name', '노경환치과') . '. All rights reserved.');
    $kakao = sql_real_escape_string(ld_clinic('kakao', 'https://pf.kakao.com/'));

    if (!$row) {
        return sql_query(" insert into rb_builder
            set bu_1 = '{$name}',
                bu_2 = '{$doctor}',
                bu_3 = '{$phone}',
                bu_8 = '{$en}',
                bu_10 = '{$address}',
                bu_12 = '{$copy}',
                bu_sns2 = '{$kakao}' ", false) !== false;
    }

    $sets = array();
    if (empty($row['bu_1'])) $sets[] = "bu_1 = '{$name}'";
    if (empty($row['bu_2'])) $sets[] = "bu_2 = '{$doctor}'";
    if (empty($row['bu_3']) && ld_clinic('phone')) $sets[] = "bu_3 = '{$phone}'";
    if (empty($row['bu_8'])) $sets[] = "bu_8 = '{$en}'";
    if (empty($row['bu_10']) && ld_clinic('address')) $sets[] = "bu_10 = '{$address}'";
    if (empty($row['bu_12'])) $sets[] = "bu_12 = '{$copy}'";
    if (empty($row['bu_sns2'])) $sets[] = "bu_sns2 = '{$kakao}'";

    if (!$sets) {
        return true;
    }

    return sql_query(" update rb_builder set " . implode(', ', $sets), false) !== false;
}

function ldental_seed_contents()
{
    global $g5;

    $pages = array(
        'intro' => array(
            '병원소개',
            '<h2>환자를 먼저 생각하는 진료</h2><p>과잉진료 없이, 필요한 치료만 정확하게 안내합니다. 검사 결과와 치료 계획을 쉽게 설명하고 환자가 선택할 수 있게 돕습니다.</p>',
        ),
        'hours' => array(
            '진료시간',
            '<h2>진료시간</h2><ul><li>평일 ' . ld_clinic('hours_weekday') . '</li><li>토요일 ' . ld_clinic('hours_saturday') . '</li><li>점심시간 ' . ld_clinic('hours_lunch') . '</li><li>' . ld_clinic('hours_off') . '</li></ul>',
        ),
        'location' => array(
            '오시는 길',
            '<h2>오시는 길</h2><p>' . (ld_clinic('address') ? ld_clinic('address') : ld_clinic('address_note')) . '</p>',
        ),
        'provision' => array(
            '서비스 이용약관',
            '<h2>서비스 이용약관</h2><p>' . ld_clinic('name', '노경환치과') . ' 홈페이지 이용과 상담 신청에 관한 기본 안내입니다. 상담 내용은 진료 목적으로만 사용됩니다.</p>',
        ),
        'privacy' => array(
            '개인정보 처리방침',
            '<h2>개인정보 처리방침</h2><p>상담 신청 시 수집하는 이름·연락처·상담내용은 예약 확인과 회신 외 목적으로 사용하지 않으며, 관련 법령에 따라 보관 후 파기합니다.</p>',
        ),
    );

    foreach ($pages as $co_id => $item) {
        $exists = sql_fetch(" select co_id from {$g5['content_table']} where co_id = '{$co_id}' ", false);
        if (isset($exists['co_id']) && $exists['co_id']) {
            continue;
        }

        $subject = sql_real_escape_string($item[0]);
        $html = sql_real_escape_string($item[1]);
        $seo = sql_real_escape_string($co_id);
        $sql = " insert into {$g5['content_table']}
                    set co_id = '{$co_id}',
                        co_html = '1',
                        co_subject = '{$subject}',
                        co_content = '{$html}',
                        co_mobile_content = '{$html}',
                        co_seo_title = '{$seo}',
                        co_skin = 'basic',
                        co_mobile_skin = 'basic',
                        co_tag_filter_use = '1',
                        co_include_head = '',
                        co_include_tail = '' ";
        if (sql_query($sql, false) === false) {
            return false;
        }
    }

    return true;
}

function ldental_seed_boards()
{
    global $g5;

    $group = sql_fetch(" select gr_id from {$g5['group_table']} where gr_id = 'clinic' ", false);
    if (empty($group['gr_id'])) {
        $sql = " insert into {$g5['group_table']}
                    set gr_id = 'clinic',
                        gr_subject = '치과',
                        gr_device = 'both',
                        gr_admin = '',
                        gr_use_access = '',
                        gr_1_subj = '', gr_2_subj = '', gr_3_subj = '', gr_4_subj = '', gr_5_subj = '',
                        gr_6_subj = '', gr_7_subj = '', gr_8_subj = '', gr_9_subj = '', gr_10_subj = '',
                        gr_1 = '', gr_2 = '', gr_3 = '', gr_4 = '', gr_5 = '',
                        gr_6 = '', gr_7 = '', gr_8 = '', gr_9 = '', gr_10 = '' ";
        if (sql_query($sql, false) === false) {
            $any = sql_fetch(" select gr_id from {$g5['group_table']} order by gr_id limit 1 ", false);
            $gr_id = isset($any['gr_id']) ? $any['gr_id'] : '';
            if (!$gr_id) {
                return false;
            }
        } else {
            $gr_id = 'clinic';
        }
    } else {
        $gr_id = 'clinic';
    }

    $boards = array(
        'notice' => array('공지사항', 10, 1, 0),
        'review' => array('치료후기', 10, 1, 0),
        'faq' => array('자주 묻는 질문', 10, 1, 0),
        'consult' => array('상담예약', 1, 10, 1),
    );

    foreach ($boards as $bo_table => $opt) {
        if (!ldental_ensure_board($gr_id, $bo_table, $opt[0], $opt[1], $opt[2], $opt[3])) {
            return false;
        }
    }

    return true;
}

function ldental_ensure_board($gr_id, $bo_table, $subject, $write_level, $list_level, $use_secret)
{
    global $g5;

    $bo_table = preg_replace('/[^a-z0-9_]/i', '', $bo_table);
    $exists = sql_fetch(" select bo_table from {$g5['board_table']} where bo_table = '{$bo_table}' ", false);
    if (!empty($exists['bo_table'])) {
        ldental_ensure_write_table($bo_table);
        return true;
    }

    $gr_id = sql_real_escape_string($gr_id);
    $subject_sql = sql_real_escape_string($subject);
    $write_level = (int) $write_level;
    $list_level = (int) $list_level;
    $use_secret = (int) $use_secret;

    $sql = " insert into {$g5['board_table']}
                set bo_table = '{$bo_table}',
                    gr_id = '{$gr_id}',
                    bo_subject = '{$subject_sql}',
                    bo_mobile_subject = '{$subject_sql}',
                    bo_device = 'both',
                    bo_admin = '',
                    bo_list_level = '{$list_level}',
                    bo_read_level = '{$list_level}',
                    bo_write_level = '{$write_level}',
                    bo_reply_level = '10',
                    bo_comment_level = '1',
                    bo_html_level = '1',
                    bo_link_level = '1',
                    bo_count_modify = '1',
                    bo_count_delete = '1',
                    bo_upload_level = '1',
                    bo_download_level = '1',
                    bo_read_point = '0',
                    bo_write_point = '0',
                    bo_comment_point = '0',
                    bo_download_point = '0',
                    bo_use_category = '0',
                    bo_category_list = '',
                    bo_use_sideview = '0',
                    bo_use_file_content = '0',
                    bo_use_secret = '{$use_secret}',
                    bo_use_dhtml_editor = '1',
                    bo_select_editor = 'smarteditor2',
                    bo_use_rss_view = '0',
                    bo_use_good = '0',
                    bo_use_nogood = '0',
                    bo_use_name = '0',
                    bo_use_signature = '0',
                    bo_use_ip_view = '0',
                    bo_use_list_view = '0',
                    bo_use_list_file = '0',
                    bo_use_list_content = '0',
                    bo_use_email = '0',
                    bo_use_cert = '',
                    bo_use_sns = '0',
                    bo_use_captcha = '1',
                    bo_table_width = '100',
                    bo_subject_len = '60',
                    bo_mobile_subject_len = '30',
                    bo_page_rows = '15',
                    bo_mobile_page_rows = '15',
                    bo_new = '24',
                    bo_hot = '100',
                    bo_image_width = '835',
                    bo_skin = 'basic',
                    bo_mobile_skin = 'basic',
                    bo_include_head = '_head.php',
                    bo_include_tail = '_tail.php',
                    bo_content_head = '',
                    bo_content_tail = '',
                    bo_mobile_content_head = '',
                    bo_mobile_content_tail = '',
                    bo_insert_content = '',
                    bo_gallery_cols = '4',
                    bo_gallery_width = '202',
                    bo_gallery_height = '150',
                    bo_mobile_gallery_width = '125',
                    bo_mobile_gallery_height = '100',
                    bo_upload_count = '2',
                    bo_upload_size = '1048576',
                    bo_reply_order = '1',
                    bo_use_search = '1',
                    bo_order = '0',
                    bo_count_write = '0',
                    bo_count_comment = '0',
                    bo_write_min = '0',
                    bo_write_max = '0',
                    bo_comment_min = '0',
                    bo_comment_max = '0',
                    bo_notice = '',
                    bo_upload_count = '2',
                    bo_sort_field = '',
                    bo_1_subj = '', bo_2_subj = '', bo_3_subj = '', bo_4_subj = '', bo_5_subj = '',
                    bo_6_subj = '', bo_7_subj = '', bo_8_subj = '', bo_9_subj = '', bo_10_subj = '',
                    bo_1 = '', bo_2 = '', bo_3 = '', bo_4 = '', bo_5 = '',
                    bo_6 = '', bo_7 = '', bo_8 = '', bo_9 = '', bo_10 = '' ";

    if (sql_query($sql, false) === false) {
        return false;
    }

    return ldental_ensure_write_table($bo_table);
}

function ldental_ensure_write_table($bo_table)
{
    global $g5;

    $create_table = $g5['write_prefix'] . $bo_table;
    $chk = sql_fetch(" show tables like '" . sql_real_escape_string($create_table) . "' ", false);
    if ($chk) {
        return true;
    }

    $file = @file(G5_ADMIN_PATH . '/sql_write.sql');
    if (!$file) {
        return false;
    }

    $file = get_db_create_replace($file);
    $sql = implode("\n", $file);
    $sql = preg_replace(array('/__TABLE_NAME__/', '/;/'), array($create_table, ''), $sql);
    if (sql_query($sql, false) === false) {
        return false;
    }

    $board_path = G5_DATA_PATH . '/file/' . $bo_table;
    @mkdir($board_path, G5_DIR_PERMISSION);
    @chmod($board_path, G5_DIR_PERMISSION);
    if ($f = @fopen($board_path . '/index.php', 'w')) {
        @fwrite($f, '');
        @fclose($f);
    }

    return true;
}

function ldental_seed_menus()
{
    global $g5;

    $cnt = sql_fetch(" select count(*) as cnt from {$g5['menu_table']} ", false);
    if (isset($cnt['cnt']) && (int) $cnt['cnt'] > 0) {
        return true;
    }

    $menus = array(
        array('10', '병원소개', ld_page_url('intro'), 0),
        array('20', '진료안내', ld_page_url('treatment'), 1),
        array('2010', '임플란트', ld_page_url('treatment') . '#implant', 2),
        array('2020', '치아교정', ld_page_url('treatment') . '#ortho', 3),
        array('2030', '심미보철', ld_page_url('treatment') . '#esthetic', 4),
        array('30', '진료시간', ld_page_url('hours'), 5),
        array('40', '오시는 길', ld_page_url('location'), 6),
        array('50', '상담예약', ld_page_url('consult'), 7),
    );

    foreach ($menus as $menu) {
        $code = sql_real_escape_string($menu[0]);
        $name = sql_real_escape_string($menu[1]);
        $link = sql_real_escape_string($menu[2]);
        $order = (int) $menu[3];
        $sql = " insert into {$g5['menu_table']}
                    set me_code = '{$code}',
                        me_name = '{$name}',
                        me_link = '{$link}',
                        me_target = 'self',
                        me_order = '{$order}',
                        me_use = '1',
                        me_mobile_use = '1' ";
        if (sql_query($sql, false) === false) {
            return false;
        }
    }

    return true;
}
