<?php
include_once __DIR__ . '/_init.php';
$g5['title'] = '진료안내';
include_once G5_PATH . '/_head.php';

$cares = array(
    'implant' => array('임플란트', '뼈 상태와 교합을 고려한 식립 계획으로 오래 쓰는 임플란트를 지향합니다. 필요한 경우 뼈 이식과 회복 일정도 함께 안내합니다.'),
    'ortho' => array('치아교정', '성장기 교정부터 성인 투명교정까지, 얼굴과 맞춘 교합을 설계합니다. 장치 종류와 예상 기간을 초진에서 설명합니다.'),
    'esthetic' => array('심미보철', '라미네이트, 크라운 등 자연스러운 색과 형태를 살린 보철 치료입니다. 치아를 과도하게 삭제하지 않는 방향을 우선합니다.'),
    'endo' => array('충치·신경치료', '삭제는 최소로, 통증은 줄이고 치아를 최대한 남기는 치료를 합니다.'),
    'prevent' => array('스케일링·예방', '정기 스케일링과 구강검진으로 큰 치료 전에 미리 관리합니다.'),
    'extract' => array('사랑니·발치', '매복 사랑니와 발치도 안전하게, 회복까지 꼼꼼히 안내합니다.'),
);
?>

<div class="ld-page">
    <div class="ld-wrap">
        <p class="ld-kicker">CARE</p>
        <h1>주요 진료 안내</h1>
        <p class="ld-page__lead">처음 오시는 분은 상담 예약 후 검진을 권합니다. 아래 항목을 눌러 해당 진료를 바로 확인할 수 있습니다.</p>

        <div class="ld-page__nav">
            <?php foreach ($cares as $id => $care) { ?>
            <a href="#<?php echo $id; ?>"><?php echo ld_escape($care[0]); ?></a>
            <?php } ?>
        </div>

        <div class="ld-page__list">
            <?php foreach ($cares as $id => $care) { ?>
            <article id="<?php echo $id; ?>">
                <h2><?php echo ld_escape($care[0]); ?></h2>
                <p><?php echo ld_escape($care[1]); ?></p>
                <a class="ld-btn ld-btn--primary" href="<?php echo ld_page_url('consult'); ?>?care=<?php echo urlencode($care[0]); ?>">이 진료 상담하기</a>
            </article>
            <?php } ?>
        </div>
    </div>
</div>

<?php
include_once G5_PATH . '/_tail.php';
