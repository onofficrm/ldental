<?php
include_once __DIR__ . '/_init.php';
$g5['title'] = '원장 소개';
include_once G5_PATH . '/_head.php';

$bio_lines = preg_split('/\r\n|\r|\n/', ld_clinic('doctor_bio'));
?>

<div class="ld-page">
    <div class="ld-wrap">
        <p class="ld-kicker">DOCTOR</p>
        <h1><?php echo ld_escape(ld_clinic('doctor')); ?> <?php echo ld_escape(ld_clinic('doctor_title', '대표원장')); ?></h1>
        <p class="ld-page__lead"><?php echo ld_escape(ld_clinic('name')); ?> <?php echo ld_escape(ld_clinic('doctor_title', '대표원장')); ?> <?php echo ld_escape(ld_clinic('doctor')); ?>입니다. 환자분께 필요한 치료만 정확히 설명하고, 편안한 진료 환경을 만들기 위해 노력합니다.</p>

        <div class="ld-doctor">
            <img class="ld-page__photo ld-doctor__photo" src="<?php echo G5_THEME_URL; ?>/rb.img/clinic/consult.jpg" alt="<?php echo ld_escape(ld_clinic('doctor')); ?> 원장">
            <div>
                <h2>약력 및 진료 분야</h2>
                <ul class="ld-doctor__list">
                    <?php foreach ($bio_lines as $line) {
                        $line = trim($line);
                        if ($line === '') {
                            continue;
                        }
                    ?>
                    <li><?php echo ld_escape($line); ?></li>
                    <?php } ?>
                </ul>
                <p class="ld-doctor__note">위 약력은 병원 정보 설정에서 수정할 수 있습니다. 실제 경력·자격에 맞게 반영해 주세요.</p>
            </div>
        </div>

        <div class="ld-page__cta">
            <a class="ld-btn ld-btn--primary" href="<?php echo ld_page_url('consult'); ?>">상담 예약하기</a>
            <a class="ld-btn ld-btn--ghost" href="<?php echo ld_page_url('intro'); ?>">병원 소개</a>
        </div>
    </div>
</div>

<?php
include_once G5_PATH . '/_tail.php';
