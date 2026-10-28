<?php
include_once __DIR__ . '/_init.php';
$g5['title'] = '오시는 길';
include_once G5_PATH . '/_head.php';
$address = ld_clinic('address');
$map_q = $address ? urlencode($address) : '';
?>

<div class="ld-page">
    <div class="ld-wrap">
        <p class="ld-kicker">LOCATION</p>
        <h1>오시는 길</h1>
        <?php if ($address) { ?>
        <p class="ld-page__lead"><?php echo ld_escape($address); ?></p>
        <div class="ld-map">
            <iframe title="오시는 길" src="https://maps.google.com/maps?q=<?php echo $map_q; ?>&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <p><a class="ld-btn ld-btn--ghost" href="https://map.naver.com/p/search/<?php echo $map_q; ?>" target="_blank" rel="noopener">네이버 지도에서 보기</a></p>
        <?php } else { ?>
        <p class="ld-page__lead"><?php echo ld_escape(ld_clinic('address_note')); ?></p>
        <p>전화 또는 카카오톡으로 오시는 길을 안내해 드립니다.</p>
        <?php } ?>

        <div class="ld-page__cta">
            <a class="ld-btn ld-btn--primary" href="<?php echo ld_page_url('consult'); ?>">상담 예약하기</a>
            <?php if (ld_clinic_phone_ready()) { ?>
            <a class="ld-btn ld-btn--ghost" href="<?php echo ld_escape(ld_clinic('phone_href')); ?>"><?php echo ld_escape(ld_clinic('phone')); ?></a>
            <?php } ?>
        </div>
    </div>
</div>

<?php
include_once G5_PATH . '/_tail.php';
