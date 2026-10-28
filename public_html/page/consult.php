<?php
include_once __DIR__ . '/_init.php';
$g5['title'] = '상담예약';
include_once G5_PATH . '/_head.php';

$care = isset($_GET['care']) ? clean_xss_tags($_GET['care'], 1, 1) : '';
$done = isset($_GET['done']) && $_GET['done'] === '1';
$token = bin2hex(random_bytes(16));
set_session('ld_consult_token', $token);
$a = rand(1, 8);
$b = rand(1, 8);
set_session('ld_consult_captcha', (string) ($a + $b));

$cares = array('임플란트', '치아교정', '심미보철', '충치·신경치료', '스케일링·예방', '사랑니·발치', '기타');
$clinic_name = ld_clinic('name', '노경환치과');
$img = G5_THEME_URL . '/rb.img/clinic';
?>

<div class="ld-page ld-page--consult">
    <div class="ld-consult-hero">
        <img src="<?php echo $img; ?>/consult.jpg" alt="">
        <div class="ld-consult-hero__shade"></div>
        <div class="ld-wrap">
            <p class="ld-kicker">CONSULT</p>
            <h1>편하게 남겨 주세요.<br>확인 후 연락드립니다.</h1>
            <p>이름과 연락처만 있으면 됩니다. 개인정보는 상담 확인 외 용도로 쓰지 않습니다.</p>
        </div>
    </div>

    <div class="ld-wrap ld-consult-grid">
        <aside class="ld-consult-aside">
            <div class="ld-consult-aside__photo">
                <img src="<?php echo $img; ?>/care.jpg" alt="<?php echo ld_escape($clinic_name); ?> 진료실">
            </div>
            <ol class="ld-consult-steps">
                <li>
                    <strong>01</strong>
                    <div>
                        <h3>신청</h3>
                        <p>관심 진료와 연락처를 남겨 주세요.</p>
                    </div>
                </li>
                <li>
                    <strong>02</strong>
                    <div>
                        <h3>확인</h3>
                        <p>병원에서 일정과 내용을 확인합니다.</p>
                    </div>
                </li>
                <li>
                    <strong>03</strong>
                    <div>
                        <h3>안내</h3>
                        <p>전화 또는 카카오톡으로 회신드립니다.</p>
                    </div>
                </li>
            </ol>
            <dl class="ld-consult-hours">
                <div><dt>평일</dt><dd><?php echo ld_escape(ld_clinic('hours_weekday')); ?></dd></div>
                <div><dt>토요일</dt><dd><?php echo ld_escape(ld_clinic('hours_saturday')); ?></dd></div>
                <div><dt>점심</dt><dd><?php echo ld_escape(ld_clinic('hours_lunch')); ?></dd></div>
                <div><dt>일요일·공휴일</dt><dd><?php echo ld_escape(ld_clinic('hours_off')); ?></dd></div>
            </dl>
        </aside>

        <div class="ld-consult-card">
            <?php if ($done) { ?>
            <p class="ld-form__done" role="status">상담 신청이 접수되었습니다. 확인 후 연락드리겠습니다.</p>
            <?php } ?>

            <form name="ldconsult" method="post" action="<?php echo G5_URL; ?>/page/consult.update.php" class="ld-form" onsubmit="return ld_consult_submit(this);">
                <input type="hidden" name="ld_token" value="<?php echo $token; ?>">
                <input type="hidden" name="wr_2" id="ld_care" value="<?php echo ld_escape($care); ?>">
                <div class="ld-hp" aria-hidden="true">
                    <label>홈페이지 <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="ld-form__row">
                    <label>
                        <span>이름</span>
                        <input type="text" name="wr_name" required maxlength="20" class="frm_input" placeholder="홍길동" autocomplete="name">
                    </label>
                    <label>
                        <span>연락처</span>
                        <input type="tel" name="wr_1" required maxlength="20" class="frm_input" placeholder="010-0000-0000" autocomplete="tel">
                    </label>
                </div>

                <fieldset class="ld-form__chips">
                    <legend>관심 진료</legend>
                    <div>
                        <?php foreach ($cares as $item) { ?>
                        <button type="button" class="ld-chip<?php echo ($care === $item) ? ' is-on' : ''; ?>" data-care="<?php echo ld_escape($item); ?>">
                            <?php echo ld_escape($item); ?>
                        </button>
                        <?php } ?>
                    </div>
                </fieldset>

                <label>
                    <span>희망 방문일 <em>선택</em></span>
                    <input type="date" name="wr_3" class="frm_input">
                </label>

                <label>
                    <span>상담 내용</span>
                    <textarea name="wr_content" required rows="5" class="frm_input" placeholder="증상, 궁금한 점, 이전에 받은 치료를 적어 주세요."></textarea>
                </label>

                <label class="ld-form__captcha">
                    <span>자동등록방지 <b><?php echo $a; ?> + <?php echo $b; ?></b></span>
                    <input type="text" name="ld_captcha" required inputmode="numeric" class="frm_input" maxlength="3" placeholder="정답을 입력해 주세요">
                </label>

                <label class="ld-form__agree">
                    <input type="checkbox" name="agree" value="1" required>
                    <span><a href="<?php echo get_pretty_url('content', 'privacy'); ?>" target="_blank">개인정보 처리방침</a>에 동의합니다.</span>
                </label>

                <button type="submit" class="ld-btn ld-btn--primary ld-form__submit">상담 신청하기</button>
            </form>
        </div>
    </div>
</div>

<script>
function ld_consult_submit(f) {
    if (!f.wr_name.value.trim()) {
        alert('이름을 입력해 주세요.');
        f.wr_name.focus();
        return false;
    }
    if (!f.wr_1.value.trim()) {
        alert('연락처를 입력해 주세요.');
        f.wr_1.focus();
        return false;
    }
    if (!f.wr_content.value.trim()) {
        alert('상담 내용을 입력해 주세요.');
        f.wr_content.focus();
        return false;
    }
    if (!f.agree.checked) {
        alert('개인정보 처리방침에 동의해 주세요.');
        return false;
    }
    return true;
}
document.addEventListener('DOMContentLoaded', function () {
    var hidden = document.getElementById('ld_care');
    var chips = document.querySelectorAll('.ld-chip');
    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            chips.forEach(function (item) { item.classList.remove('is-on'); });
            chip.classList.add('is-on');
            if (hidden) hidden.value = chip.getAttribute('data-care') || '';
        });
    });
});
</script>

<?php
include_once G5_PATH . '/_tail.php';
