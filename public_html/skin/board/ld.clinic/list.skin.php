<?php
if (!defined('_GNUBOARD_')) exit;

include_once G5_LIB_PATH . '/thumbnail.lib.php';
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css?ver=' . G5_SERVER_TIME . '">', 0);

$is_review = in_array($bo_table, array('review', 'pat_review'), true);
$kicker = $is_review ? 'REVIEW' : ($bo_table === 'faq' ? 'FAQ' : 'NOTICE');
$lead = $is_review
    ? '치료 경험과 안심되는 후기를 모았습니다.'
    : ($bo_table === 'faq' ? '방문 전에 많이 묻는 질문을 모았습니다.' : '병원 소식과 진료 안내를 전해 드립니다.');
?>

<div id="bo_list" class="ld-board">
    <div class="ld-board__head">
        <p class="ld-kicker"><?php echo $kicker; ?></p>
        <h1><?php echo get_text($board['bo_subject']); ?></h1>
        <p class="ld-board__lead"><?php echo $lead; ?></p>
    </div>

    <?php if ($is_category) { ?>
    <nav id="bo_cate" class="ld-board__cate">
        <h2 class="sound_only"><?php echo $board['bo_subject']; ?> 카테고리</h2>
        <ul id="bo_cate_ul"><?php echo $category_option; ?></ul>
    </nav>
    <?php } ?>

    <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
        <input type="hidden" name="stx" value="<?php echo $stx; ?>">
        <input type="hidden" name="spt" value="<?php echo $spt; ?>">
        <input type="hidden" name="sca" value="<?php echo $sca; ?>">
        <input type="hidden" name="sst" value="<?php echo $sst; ?>">
        <input type="hidden" name="sod" value="<?php echo $sod; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <input type="hidden" name="sw" value="">

        <div class="ld-board__toolbar">
            <p><strong><?php echo number_format($total_count); ?></strong>건</p>
            <div class="ld-board__tools">
                <?php if ($admin_href) { ?><a href="<?php echo $admin_href; ?>" class="ld-board__icon" title="관리자">설정</a><?php } ?>
                <button type="button" class="ld-board__icon btn_bo_sch" title="검색">검색</button>
                <?php if ($write_href) { ?><a href="<?php echo $write_href; ?>" class="ld-btn ld-btn--primary">글쓰기</a><?php } ?>
                <?php if ($is_admin == 'super' || $is_auth) { ?>
                <button type="button" class="ld-board__icon btn_more_opt is_list_btn" title="관리">관리</button>
                <?php if ($is_checkbox) { ?>
                <ul class="more_opt is_list_btn">
                    <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value">선택삭제</button></li>
                    <li><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value">선택복사</button></li>
                    <li><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value">선택이동</button></li>
                </ul>
                <?php } ?>
                <?php } ?>
            </div>
        </div>

        <?php if ($is_checkbox) { ?>
        <div class="ld-board__checkall">
            <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            <label for="chkall">현재 페이지 전체선택</label>
        </div>
        <?php } ?>

        <div class="ld-board__list<?php echo $is_review ? ' is-review' : ''; ?>">
            <?php
            for ($i = 0; $i < count($list); $i++) {
                $thumb = array();
                if ($is_review) {
                    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], 320, 220, false, true);
                }
                $excerpt = '';
                if (!empty($list[$i]['wr_content'])) {
                    $excerpt = trim(preg_replace('/\s+/', ' ', strip_tags($list[$i]['wr_content'])));
                    if (function_exists('cut_str')) {
                        $excerpt = cut_str($excerpt, 90);
                    }
                }
            ?>
            <article class="ld-board__item<?php echo $list[$i]['is_notice'] ? ' is-notice' : ''; ?>">
                <?php if ($is_checkbox) { ?>
                <label class="ld-board__chk">
                    <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id']; ?>" id="chk_wr_id_<?php echo $i; ?>">
                    <span class="sound_only"><?php echo $list[$i]['subject']; ?></span>
                </label>
                <?php } ?>
                <?php if ($is_review) { ?>
                <a class="ld-board__thumb" href="<?php echo $list[$i]['href']; ?>">
                    <?php if (!empty($thumb['src'])) { ?>
                    <img src="<?php echo $thumb['src']; ?>" alt="">
                    <?php } else { ?>
                    <span><?php echo $list[$i]['is_notice'] ? '공지' : '후기'; ?></span>
                    <?php } ?>
                </a>
                <?php } ?>
                <div class="ld-board__body">
                    <a href="<?php echo $list[$i]['href']; ?>">
                        <?php if ($list[$i]['is_notice']) { ?><em>공지</em><?php } ?>
                        <?php if ($is_category && $list[$i]['ca_name']) { ?><em class="is-cat"><?php echo $list[$i]['ca_name']; ?></em><?php } ?>
                        <strong><?php echo $list[$i]['subject']; ?></strong>
                        <?php if ($excerpt !== '') { ?><span><?php echo $excerpt; ?></span><?php } ?>
                    </a>
                    <p>
                        <b><?php echo $list[$i]['name']; ?></b>
                        <time><?php echo $list[$i]['datetime2']; ?></time>
                        <i>조회 <?php echo number_format($list[$i]['wr_hit']); ?></i>
                        <?php if ($list[$i]['comment_cnt']) { ?><i>댓글 <?php echo $list[$i]['wr_comment']; ?></i><?php } ?>
                    </p>
                </div>
            </article>
            <?php } ?>
            <?php if (count($list) == 0) { ?>
            <p class="ld-board__empty">등록된 글이 없습니다.</p>
            <?php } ?>
        </div>

        <?php echo $write_pages; ?>
    </form>

    <div class="bo_sch_wrap">
        <fieldset class="bo_sch">
            <h3>검색</h3>
            <form name="fsearch" method="get">
                <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
                <input type="hidden" name="sca" value="<?php echo $sca; ?>">
                <input type="hidden" name="sop" value="and">
                <label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl"><?php echo get_board_sfl_select_options($sfl); ?></select>
                <label for="stx" class="sound_only">검색어</label>
                <div class="sch_bar">
                    <input type="text" name="stx" value="<?php echo stripslashes($stx); ?>" required id="stx" class="sch_input" size="25" maxlength="20" placeholder="검색어를 입력해 주세요">
                    <button type="submit" value="검색" class="sch_btn">검색</button>
                </div>
                <button type="button" class="bo_sch_cls">닫기</button>
            </form>
        </fieldset>
        <div class="bo_sch_bg"></div>
    </div>
</div>

<script>
jQuery(function($){
    $(".btn_bo_sch").on("click", function() { $(".bo_sch_wrap").toggle(); });
    $('.bo_sch_bg, .bo_sch_cls').click(function(){ $('.bo_sch_wrap').hide(); });
    $(".btn_more_opt.is_list_btn").on("click", function(e) {
        e.stopPropagation();
        $(".more_opt.is_list_btn").toggle();
    });
    $(document).on("click", function (e) {
        if(!$(e.target).closest('.is_list_btn').length) {
            $(".more_opt.is_list_btn").hide();
        }
    });
});
<?php if ($is_checkbox) { ?>
function all_checked(sw) {
    var f = document.fboardlist;
    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]") f.elements[i].checked = sw;
    }
}
function fboardlist_submit(f) {
    var chk_count = 0;
    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked) chk_count++;
    }
    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }
    if(document.pressed == "선택복사") { select_copy("copy"); return; }
    if(document.pressed == "선택이동") { select_copy("move"); return; }
    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?")) return false;
        f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
    }
    return true;
}
function select_copy(sw) {
    var f = document.fboardlist;
    window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");
    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url+"/move.php";
    f.submit();
}
<?php } ?>
</script>
