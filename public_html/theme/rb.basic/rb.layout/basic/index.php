<?php
if (!defined('_GNUBOARD_')) exit;

add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/rb.layout/'.$rb_core['layout'].'/style.css?ver='.G5_SERVER_TIME.'">', 0);

$clinic_name = function_exists('ld_clinic') ? ld_clinic('name', '노경환치과') : '노경환치과';
$img = G5_THEME_URL.'/rb.img/clinic';
$banner_src = G5_DATA_URL.'/banners/thumb-4_835x152.png';
$banner_path = G5_DATA_PATH.'/banners/thumb-4_835x152.png';
$consult_url = function_exists('ld_page_url') ? ld_page_url('consult') : G5_URL.'/#consult';
$intro_url = function_exists('ld_page_url') ? ld_page_url('intro') : G5_URL.'/#clinic';
$kakao_url = function_exists('ld_clinic') ? ld_clinic('kakao', $consult_url) : $consult_url;

$cares = array(
    array('implant', '임플란트', '뼈 상태와 교합을 고려한 식립 계획으로 오래 쓰는 임플란트를 지향합니다.', 'care.jpg'),
    array('ortho', '치아교정', '성장기 교정부터 성인 투명교정까지, 얼굴과 맞춘 교합을 설계합니다.', 'smile.jpg'),
    array('esthetic', '심미보철', '라미네이트, 크라운 등 자연스러운 색과 형태를 살린 보철 치료입니다.', 'smile.jpg'),
    array('endo', '충치·신경치료', '삭제는 최소로, 통증은 줄이고 치아를 최대한 남기는 치료를 합니다.', 'care.jpg'),
    array('prevent', '스케일링·예방', '정기 스케일링과 구강검진으로 큰 치료 전에 미리 관리합니다.', 'consult.jpg'),
    array('extract', '사랑니·발치', '매복 사랑니와 발치도 안전하게, 회복까지 꼼꼼히 안내합니다.', 'care.jpg'),
);
?>

<div class="ld-home">
    <section class="ld-hero" id="intro">
        <img class="ld-hero__photo" src="<?php echo $img; ?>/hero.jpg" alt="노경환치과 진료 공간">
        <div class="ld-hero__shade"></div>
        <div class="ld-hero__inner">
            <p class="ld-kicker">L DENTAL · 노경환치과</p>
            <h1>건강한 미소,<br>오래가는 치아를 지킵니다.</h1>
            <p class="ld-hero__lead">임플란트 · 교정 · 심미 · 일반진료까지.<br>환자와 오래 가는 치과, <?php echo get_text($clinic_name); ?>입니다.</p>
            <div class="ld-hero__actions">
                <a class="ld-btn ld-btn--primary" href="<?php echo $consult_url; ?>">상담 예약</a>
                <a class="ld-btn ld-btn--ghost" href="<?php echo $intro_url; ?>">병원 소개</a>
            </div>
        </div>
    </section>

    <section class="ld-section" id="clinic">
        <div class="ld-wrap">
            <div class="ld-sec-head">
                <span>ABOUT</span>
                <h2>환자를 먼저 생각하는 진료</h2>
                <p>과잉진료 없이, 필요한 치료만 정확하게 안내합니다.</p>
            </div>
            <div class="ld-about-photo">
                <img src="<?php echo $img; ?>/consult.jpg" alt="진료 상담 모습">
                <div class="ld-about">
                    <div class="ld-about__card">
                        <strong>01</strong>
                        <h3>충분한 설명</h3>
                        <p>검사 결과와 치료 계획을 쉽게 풀어 설명하고, 환자가 선택할 수 있게 돕습니다.</p>
                    </div>
                    <div class="ld-about__card">
                        <strong>02</strong>
                        <h3>맞춤 치료</h3>
                        <p>임플란트, 교정, 심미보철을 환자 상태와 생활 패턴에 맞게 제안합니다.</p>
                    </div>
                    <div class="ld-about__card">
                        <strong>03</strong>
                        <h3>사후 관리</h3>
                        <p>치료 후에도 정기 검진과 스케일링으로 건강한 상태를 유지합니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ld-section" id="news">
        <div class="ld-wrap">
            <div class="ld-sec-head">
                <span>NEWS</span>
                <h2>공지와 치료 후기</h2>
                <p>병원 소식과 환자분들의 후기를 확인해 보세요.</p>
            </div>
            <div class="ld-board-home">
                <div class="ld-board-home__col">
                    <?php echo latest('theme/ld.latest.notice', 'notice', 3, 48); ?>
                </div>
                <div class="ld-board-home__col">
                    <?php echo latest('theme/ld.latest.review', 'review', 4, 60); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="ld-section ld-section--tint" id="service">
        <div class="ld-wrap">
            <div class="ld-sec-head">
                <span>CARE</span>
                <h2>주요 진료 안내</h2>
            </div>
            <div class="ld-grid">
                <?php foreach ($cares as $care) { ?>
                <article>
                    <div class="ld-card-photo">
                        <img src="<?php echo $img.'/'.$care[3]; ?>" alt="<?php echo $care[1]; ?>">
                    </div>
                    <h3><?php echo $care[1]; ?></h3>
                    <p><?php echo $care[2]; ?></p>
                    <a href="<?php echo function_exists('ld_page_url') ? ld_page_url('treatment').'#'.$care[0] : '#service'; ?>">자세히 보기</a>
                </article>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="ld-section" id="consult">
        <div class="ld-wrap">
            <?php if (is_file($banner_path)) { ?>
            <a class="ld-banner" href="<?php echo $kakao_url; ?>" target="_blank" rel="noopener">
                <img src="<?php echo $banner_src; ?>" alt="카카오톡 상담">
            </a>
            <?php } else { ?>
            <a class="ld-banner ld-banner--photo" href="<?php echo $consult_url; ?>">
                <img src="<?php echo $img; ?>/smile.jpg" alt="상담 예약">
                <span>상담이 필요하시면 편하게 문의해 주세요</span>
            </a>
            <?php } ?>
        </div>
    </section>

    <section class="ld-section ld-section--dark" id="hours">
        <div class="ld-wrap ld-hours">
            <div>
                <span>HOURS</span>
                <h2>진료시간</h2>
                <ul>
                    <li><em>평일</em> <?php echo function_exists('ld_clinic') ? ld_escape(ld_clinic('hours_weekday')) : '10:00 – 19:00'; ?></li>
                    <li><em>토요일</em> <?php echo function_exists('ld_clinic') ? ld_escape(ld_clinic('hours_saturday')) : '10:00 – 14:00'; ?></li>
                    <li><em>점심시간</em> <?php echo function_exists('ld_clinic') ? ld_escape(ld_clinic('hours_lunch')) : '13:00 – 14:00'; ?></li>
                    <li><em>일요일·공휴일</em> <?php echo function_exists('ld_clinic') ? ld_escape(ld_clinic('hours_off')) : '휴진'; ?></li>
                </ul>
                <p class="ld-hours__note">예약 진료를 권장합니다. 당일 방문은 대기 시간이 있을 수 있습니다.</p>
            </div>
            <div class="ld-hours__cta">
                <img src="<?php echo $img; ?>/care.jpg" alt="진료실">
                <p>상담이 필요하시면<br>편하게 문의해 주세요.</p>
                <a class="ld-btn ld-btn--primary" href="<?php echo $consult_url; ?>">상담 예약하기</a>
            </div>
        </div>
    </section>
</div>
