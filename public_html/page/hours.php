<?php
include_once __DIR__ . '/_init.php';
$g5['title'] = '진료시간';
include_once G5_PATH . '/_head.php';
?>

<div class="ld-page">
    <div class="ld-wrap">
        <p class="ld-kicker">HOURS</p>
        <h1>진료시간</h1>
        <p class="ld-page__lead">예약 진료를 권장합니다. 당일 방문은 대기 시간이 있을 수 있습니다.</p>

        <ul class="ld-page__hours">
            <li><em>평일</em> <?php echo ld_escape(ld_clinic('hours_weekday')); ?></li>
            <li><em>토요일</em> <?php echo ld_escape(ld_clinic('hours_saturday')); ?></li>
            <li><em>점심시간</em> <?php echo ld_escape(ld_clinic('hours_lunch')); ?></li>
            <li><em>휴진</em> <?php echo ld_escape(ld_clinic('hours_off')); ?></li>
        </ul>

        <div class="ld-page__cta">
            <a class="ld-btn ld-btn--primary" href="<?php echo ld_page_url('consult'); ?>">상담 예약하기</a>
            <?php if (ld_clinic_phone_ready()) { ?>
            <a class="ld-btn ld-btn--ghost" href="<?php echo ld_escape(ld_clinic('phone_href')); ?>">전화하기</a>
            <?php } ?>
        </div>
    </div>
</div>

<?php
include_once G5_PATH . '/_tail.php';
