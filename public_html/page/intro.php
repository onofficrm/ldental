<?php
include_once __DIR__ . '/_init.php';
$g5['title'] = '병원소개';
include_once G5_PATH . '/_head.php';
?>

<div class="ld-page">
    <div class="ld-wrap">
        <p class="ld-kicker">ABOUT</p>
        <h1>환자를 먼저 생각하는 진료</h1>
        <p class="ld-page__lead">과잉진료 없이, 필요한 치료만 정확하게 안내합니다. <?php echo ld_escape(ld_clinic('name')); ?>는 검사 결과와 치료 계획을 쉽게 풀어 설명하고, 환자가 선택할 수 있게 돕습니다.</p>
        <img class="ld-page__photo" src="<?php echo G5_THEME_URL; ?>/rb.img/clinic/consult.jpg" alt="진료 상담">

        <div class="ld-about">
            <div class="ld-about__card">
                <strong>01</strong>
                <h3>충분한 설명</h3>
                <p>치료 전 현재 상태와 선택지를 먼저 공유합니다. 급하게 결정하지 않도록 충분한 시간을 드립니다.</p>
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

        <div class="ld-page__cta">
            <a class="ld-btn ld-btn--primary" href="<?php echo ld_page_url('consult'); ?>">상담 예약하기</a>
            <a class="ld-btn ld-btn--ghost" href="<?php echo ld_page_url('treatment'); ?>">진료 안내 보기</a>
        </div>
    </div>
</div>

<?php
include_once G5_PATH . '/_tail.php';
