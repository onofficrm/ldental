<?php
if (!defined('_GNUBOARD_')) exit;
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css?ver=' . G5_SERVER_TIME . '">', 0);

$is_review = in_array($bo_table, array('review', 'pat_review'), true);
$kicker = $is_review ? 'REVIEW' : ($bo_table === 'faq' ? 'FAQ' : 'NOTICE');
$page_label = $w === 'u' ? '글 수정' : '글쓰기';
?>

<section id="bo_w" class="ld-board ld-board--write">
    <div class="ld-board__head">
        <p class="ld-kicker"><?php echo $kicker; ?></p>
        <h1><?php echo get_text($board['bo_subject']); ?> <?php echo $page_label; ?></h1>
        <p class="ld-board__lead">제목과 내용을 정확히 적어 주세요. 후기는 사진이 있으면 더 좋습니다.</p>
    </div>

    <form name="fwrite" id="fwrite" class="ld-board__form" action="<?php echo $action_url; ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
        <input type="hidden" name="w" value="<?php echo $w; ?>">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id; ?>">
        <input type="hidden" name="sca" value="<?php echo $sca; ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
        <input type="hidden" name="stx" value="<?php echo $stx; ?>">
        <input type="hidden" name="spt" value="<?php echo $spt; ?>">
        <input type="hidden" name="sst" value="<?php echo $sst; ?>">
        <input type="hidden" name="sod" value="<?php echo $sod; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">

        <?php
        $option = '';
        $option_hidden = '';
        if ($is_notice || $is_html || $is_secret || $is_mail) {
            if ($is_notice) {
                $option .= '<label class="ld-board__opt"><input type="checkbox" id="notice" name="notice" value="1" ' . $notice_checked . '> 공지</label>';
            }
            if ($is_html) {
                if ($is_dhtml_editor) {
                    $option_hidden .= '<input type="hidden" value="html1" name="html">';
                } else {
                    $option .= '<label class="ld-board__opt"><input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="' . $html_value . '" ' . $html_checked . '> HTML</label>';
                }
            }
            if ($is_secret) {
                if ($is_admin || $is_secret == 1) {
                    $option .= '<label class="ld-board__opt"><input type="checkbox" id="secret" name="secret" value="secret" ' . $secret_checked . '> 비밀글</label>';
                } else {
                    $option_hidden .= '<input type="hidden" name="secret" value="secret">';
                }
            }
            if ($is_mail) {
                $option .= '<label class="ld-board__opt"><input type="checkbox" id="mail" name="mail" value="mail" ' . $recv_email_checked . '> 답변메일받기</label>';
            }
        }
        echo $option_hidden;
        ?>

        <?php if ($is_category) { ?>
        <label class="ld-board__field">
            <span>분류</span>
            <select name="ca_name" id="ca_name" required class="frm_input">
                <option value="">분류를 선택하세요</option>
                <?php echo $category_option; ?>
            </select>
        </label>
        <?php } ?>

        <?php if ($is_name || $is_password || $is_email) { ?>
        <div class="ld-board__row">
            <?php if ($is_name) { ?>
            <label class="ld-board__field">
                <span>이름</span>
                <input type="text" name="wr_name" value="<?php echo $name; ?>" id="wr_name" required class="frm_input required" placeholder="이름">
            </label>
            <?php } ?>
            <?php if ($is_password) { ?>
            <label class="ld-board__field">
                <span>비밀번호</span>
                <input type="password" name="wr_password" id="wr_password" <?php echo $password_required; ?> class="frm_input <?php echo $password_required; ?>" placeholder="비밀번호">
            </label>
            <?php } ?>
            <?php if ($is_email) { ?>
            <label class="ld-board__field">
                <span>이메일</span>
                <input type="text" name="wr_email" value="<?php echo $email; ?>" id="wr_email" class="frm_input email" placeholder="이메일">
            </label>
            <?php } ?>
        </div>
        <?php } ?>

        <?php if ($option) { ?>
        <div class="ld-board__opts"><?php echo $option; ?></div>
        <?php } ?>

        <label class="ld-board__field">
            <span>제목</span>
            <div id="autosave_wrapper">
                <input type="text" name="wr_subject" value="<?php echo $subject; ?>" id="wr_subject" required class="frm_input required" maxlength="255" placeholder="제목을 입력해 주세요">
                <?php if ($is_member) { ?>
                <script src="<?php echo G5_JS_URL; ?>/autosave.js"></script>
                <?php if ($editor_content_js) echo $editor_content_js; ?>
                <button type="button" id="btn_autosave" class="btn_frmline">임시저장 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
                <div id="autosave_pop">
                    <strong>임시 저장된 글 목록</strong>
                    <ul></ul>
                    <div><button type="button" class="autosave_close">닫기</button></div>
                </div>
                <?php } ?>
            </div>
        </label>

        <div class="ld-board__field">
            <span>내용</span>
            <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                <?php if ($write_min || $write_max) { ?>
                <p id="char_count_desc">최소 <?php echo $write_min; ?>자, 최대 <?php echo $write_max; ?>자</p>
                <?php } ?>
                <?php echo $editor_html; ?>
                <?php if ($write_min || $write_max) { ?>
                <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                <?php } ?>
            </div>
        </div>

        <?php for ($i = 1; $is_link && $i <= G5_LINK_COUNT; $i++) { ?>
        <label class="ld-board__field">
            <span>링크 <?php echo $i; ?></span>
            <input type="text" name="wr_link<?php echo $i; ?>" value="<?php echo ($w == 'u') ? $write['wr_link' . $i] : ''; ?>" id="wr_link<?php echo $i; ?>" class="frm_input">
        </label>
        <?php } ?>

        <?php for ($i = 0; $is_file && $i < $file_count; $i++) { ?>
        <div class="ld-board__field">
            <span>첨부 <?php echo $i + 1; ?></span>
            <input type="file" name="bf_file[]" id="bf_file_<?php echo $i + 1; ?>" title="파일첨부 <?php echo $i + 1; ?> : 용량 <?php echo $upload_max_filesize; ?> 이하" class="frm_file">
            <?php if ($w == 'u' && !empty($file[$i]['file'])) { ?>
            <label class="ld-board__opt"><input type="checkbox" id="bf_file_del<?php echo $i; ?>" name="bf_file_del[<?php echo $i; ?>]" value="1"> <?php echo $file[$i]['source']; ?> 삭제</label>
            <?php } ?>
        </div>
        <?php } ?>

        <?php if ($is_use_captcha) { ?>
        <div class="ld-board__field"><?php echo $captcha_html; ?></div>
        <?php } ?>

        <div class="ld-board__submit">
            <a href="<?php echo get_pretty_url($bo_table); ?>" class="ld-btn ld-btn--ghost">취소</a>
            <button type="submit" id="btn_submit" accesskey="s" class="ld-btn ld-btn--primary">작성완료</button>
        </div>
    </form>
</section>

<script>
<?php if ($write_min || $write_max) { ?>
var char_min = parseInt(<?php echo $write_min; ?>);
var char_max = parseInt(<?php echo $write_max; ?>);
check_byte("wr_content", "char_count");
$(function() {
    $("#wr_content").on("keyup", function() { check_byte("wr_content", "char_count"); });
});
<?php } ?>
function html_auto_br(obj) {
    if (obj.checked) {
        if (confirm("자동 줄바꿈을 하시겠습니까?")) obj.value = "html2";
        else obj.value = "html1";
    } else {
        obj.value = "";
    }
}
function fwrite_submit(f) {
    <?php echo $editor_js; ?>
    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: { "subject": f.wr_subject.value, "content": f.wr_content.value },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            subject = data.subject;
            content = data.content;
        }
    });
    if (subject) { alert("제목에 금지단어('"+subject+"')가 포함되어있습니다"); f.wr_subject.focus(); return false; }
    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined") ed_wr_content.returnFalse();
        else f.wr_content.focus();
        return false;
    }
    if (document.getElementById("char_count")) {
        var cnt = parseInt(check_byte("wr_content", "char_count"));
        if (char_min > 0 && char_min > cnt) { alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다."); return false; }
        if (char_max > 0 && char_max < cnt) { alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다."); return false; }
    }
    <?php echo $captcha_js; ?>
    document.getElementById("btn_submit").disabled = "disabled";
    return true;
}
</script>
