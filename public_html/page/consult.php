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
?>

<div class="ld-page">
    <div class="ld-wrap ld-consult">
        <p class="ld-kicker">CONSULT</p>
        <h1>상담 예약</h1>
        <p class="ld-page__lead">이름과 연락처를 남겨 주시면 확인 후 연락드립니다. 개인정보는 상담 목적 외로 사용하지 않습니다.</p>

        <?php if ($done) { ?>
        <p class="ld-form__done" role="status">상담 신청이 접수되었습니다. 확인 후 연락드리겠습니다.</p>
        <?php } ?>

        <form name="ldconsult" method="post" action="<?php echo G5_URL; ?>/page/consult.update.php" class="ld-form" onsubmit="return ld_consult_submit(this);">
            <input type="hidden" name="ld_token" value="<?php echo $token; ?>">
            <div class="ld-hp" aria-hidden="true">
                <label>홈페이지 <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <label>
                <span>이름</span>
                <input type="text" name="wr_name" required maxlength="20" class="frm_input">
            </label>
            <label>
                <span>연락처</span>
                <input type="tel" name="wr_1" required maxlength="20" class="frm_input" placeholder="010-0000-0000">
            </label>
            <label>
                <span>관심 진료</span>
                <select name="wr_2" class="frm_input">
                    <option value="">선택해 주세요</option>
                    <?php foreach ($cares as $item) { ?>
                    <option value="<?php echo ld_escape($item); ?>"<?php echo get_selected($care, $item); ?>><?php echo ld_escape($item); ?></option>
                    <?php } ?>
                </select>
            </label>
            <label>
                <span>희망 방문일</span>
                <input type="date" name="wr_3" class="frm_input">
            </label>
            <label>
                <span>상담 내용</span>
                <textarea name="wr_content" required rows="6" class="frm_input" placeholder="증상이나 궁금한 점을 적어 주세요."></textarea>
            </label>
            <label>
                <span>자동등록방지 <?php echo $a; ?> + <?php echo $b; ?> = ?</span>
                <input type="text" name="ld_captcha" required inputmode="numeric" class="frm_input" maxlength="3">
            </label>
            <label class="ld-form__agree">
                <input type="checkbox" name="agree" value="1" required>
                <span><a href="<?php echo get_pretty_url('content', 'privacy'); ?>" target="_blank">개인정보 처리방침</a>에 동의합니다.</span>
            </label>
            <button type="submit" class="ld-btn ld-btn--primary">상담 신청하기</button>
        </form>
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
</script>

<?php
include_once G5_PATH . '/_tail.php';
