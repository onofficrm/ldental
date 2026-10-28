<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

define('KGINICIS_USE_CERT_SEED', isset($config['cf_cert_use_seed']) ? (int) $config['cf_cert_use_seed'] : 1);

// 유저 사이드뷰에서 아이콘 지정 안했을시 기본 no 프로필 이미지
define('G5_NO_PROFILE_IMG', '<span class="profile_img"><img src="'.G5_IMG_URL.'/no_profile.gif" alt="no_profile" width="'.$config['cf_member_icon_width'].'" height="'.$config['cf_member_icon_height'].'"></span>');

define('G5_USE_MEMBER_IMAGE_FILETIME', TRUE);

// 썸네일 처리 방식, 비율유지 하지 않고 썸네일을 생성하려면 주석을 풀고 값은 false 입력합니다. ( true 또는 주석으로 된 경우에는 비율 유지합니다. )
//define('G5_USE_THUMB_RATIO', false);

// 신규/초기 DB: cf_theme 미설정 시 레드빌더 기본 테마 (extend 로드 전 common.php에서 테마 상수 미정의 방지)
if (!defined('G5_THEME_URL')) {
    $default_theme = 'rb.basic';
    $default_theme_path = G5_PATH.'/'.G5_THEME_DIR.'/'.$default_theme;
    if (is_dir($default_theme_path)) {
        define('G5_THEME_PATH', $default_theme_path);
        define('G5_THEME_URL', G5_URL.'/'.G5_THEME_DIR.'/'.$default_theme);
        define('G5_THEME_MOBILE_PATH', $default_theme_path.'/'.G5_MOBILE_DIR);
        define('G5_THEME_LIB_PATH', $default_theme_path.'/'.G5_LIB_DIR);
        define('G5_THEME_CSS_URL', G5_THEME_URL.'/'.G5_CSS_DIR);
        define('G5_THEME_IMG_URL', G5_THEME_URL.'/'.G5_IMG_DIR);
        define('G5_THEME_JS_URL', G5_THEME_URL.'/'.G5_JS_DIR);
        if (empty($config['cf_theme'])) {
            $config['cf_theme'] = $default_theme;
        }
        if (is_file(G5_THEME_PATH.'/theme.config.php')) {
            include_once(G5_THEME_PATH.'/theme.config.php');
        }
        // shop.config.php는 extend보다 먼저 실행되어, 여기서 테마를 잡으면 쇼핑몰 테마 URL도 필요
        if (defined('G5_USE_SHOP') && G5_USE_SHOP && !defined('G5_THEME_SHOP_URL')) {
            define('G5_THEME_SHOP_PATH', G5_THEME_PATH.'/'.G5_SHOP_DIR);
            define('G5_THEME_SHOP_URL', G5_THEME_URL.'/'.G5_SHOP_DIR);
            define('G5_THEME_MSHOP_PATH', G5_THEME_PATH.'/'.G5_MOBILE_DIR.'/'.G5_SHOP_DIR);
            define('G5_THEME_MSHOP_URL', G5_THEME_URL.'/'.G5_MOBILE_DIR.'/'.G5_SHOP_DIR);
        }
    }
}

if (empty($config['cf_title']) || $config['cf_title'] === '그누보드5') {
    $config['cf_title'] = '노경환치과';
    if (empty($g5['title']) || $g5['title'] === '그누보드5') {
        unset($g5['title']);
    }
}