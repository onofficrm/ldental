<!--
경로 : /rb/rb.widget/rb.quick_btns/
사용자코드를 입력하세요.
-->
<div class="q_btns pc">
    <button type="button" class="arr_bg" onclick="location.href='https://www.onoffedu.net/content/company';">
        <i><img src="<?php echo G5_THEME_URL ?>/rb.img/icon/icon_btn1.svg"></i>
        <span>온오프유학 회사소개</span>
    </button>

    <button type="button" class="arr_bg" onclick="location.href='https://onoffedu.net/bbs/faq.php?fm_id=1';">
        <i><img src="<?php echo G5_THEME_URL ?>/rb.img/icon/icon_btn2.svg"></i>
        <span>자주묻는 질문</span>
    </button>

    <button type="button" class="arr_bg" onclick="location.href='https://www.youtube.com/@onoffstudy';">
        <i><img src="<?php echo G5_THEME_URL ?>/rb.img/icon/icon_btn3.svg"></i>
        <span>유투브채널 바로가기</span>
    </button>
</div>


<script>
function openNewWindow(url) {
    var width = Math.min(window.screen.width, 1380);
    var height = Math.min(window.screen.height, 768);
    var left = (window.screen.width - width) / 2;
    var top = (window.screen.height - height) / 2;
    
    window.open(url, '_blank', `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`);
}
</script>