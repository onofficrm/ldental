<?php
if (!defined('_GNUBOARD_')) exit;
include_once G5_LIB_PATH . '/thumbnail.lib.php';
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css?ver=' . G5_SERVER_TIME . '">', 0);

$is_review = in_array($bo_table, array('review', 'pat_review'), true);
$kicker = $is_review ? 'REVIEW' : ($bo_table === 'faq' ? 'FAQ' : 'NOTICE');
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<article id="bo_v" class="ld-board ld-board--view">
    <div class="ld-board__head">
        <p class="ld-kicker"><?php echo $kicker; ?></p>
        <p class="ld-board__crumb">
            <a href="<?php echo $list_href; ?>"><?php echo get_text($board['bo_subject']); ?></a>
        </p>
        <h1 id="bo_v_title">
            <?php if ($category_name) { ?><em><?php echo $view['ca_name']; ?></em><?php } ?>
            <?php echo cut_str(get_text($view['wr_subject']), 80); ?>
        </h1>
        <p class="ld-board__meta">
            <b><?php echo $view['name']; ?></b>
            <time><?php echo date('Y.m.d H:i', strtotime($view['wr_datetime'])); ?></time>
            <i>조회 <?php echo number_format($view['wr_hit']); ?></i>
            <?php if ((int) $view['wr_comment'] > 0) { ?><i>댓글 <?php echo number_format($view['wr_comment']); ?></i><?php } ?>
        </p>
    </div>

    <div class="ld-board__actions">
        <a href="<?php echo $list_href; ?>" class="ld-btn ld-btn--ghost">목록</a>
        <?php if ($write_href) { ?><a href="<?php echo $write_href; ?>" class="ld-btn ld-btn--ghost">글쓰기</a><?php } ?>
        <?php if ($update_href) { ?><a href="<?php echo $update_href; ?>" class="ld-btn ld-btn--ghost">수정</a><?php } ?>
        <?php if ($delete_href) { ?><a href="<?php echo $delete_href; ?>" class="ld-btn ld-btn--ghost" onclick="del(this.href); return false;">삭제</a><?php } ?>
        <?php if ($reply_href) { ?><a href="<?php echo $reply_href; ?>" class="ld-btn ld-btn--ghost">답변</a><?php } ?>
        <?php if ($copy_href) { ?><a href="<?php echo $copy_href; ?>" class="ld-btn ld-btn--ghost" onclick="board_move(this.href); return false;">복사</a><?php } ?>
        <?php if ($move_href) { ?><a href="<?php echo $move_href; ?>" class="ld-btn ld-btn--ghost" onclick="board_move(this.href); return false;">이동</a><?php } ?>
    </div>

    <section id="bo_v_atc" class="ld-board__article">
        <h2 class="sound_only">본문</h2>
        <?php
        $v_img_count = count($view['file']);
        if ($v_img_count) {
            echo '<div id="bo_v_img" class="ld-board__images">';
            foreach ($view['file'] as $view_file) {
                echo get_file_thumbnail($view_file);
            }
            echo '</div>';
        }
        ?>
        <div id="bo_v_con" class="ld-board__content"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php if ($is_signature) { ?><p class="ld-board__sign"><?php echo $signature; ?></p><?php } ?>
    </section>

    <?php
    $cnt = 0;
    if ($view['file']['count']) {
        for ($i = 0; $i < count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
                $cnt++;
            }
        }
    }
    ?>
    <?php if ($cnt) { ?>
    <section class="ld-board__files">
        <h2>첨부파일</h2>
        <ul>
        <?php for ($i = 0; $i < count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) { ?>
            <li><a href="<?php echo $view['file'][$i]['href']; ?>" class="view_file_download"><?php echo $view['file'][$i]['source']; ?> (<?php echo $view['file'][$i]['size']; ?>)</a></li>
        <?php }
        } ?>
        </ul>
    </section>
    <?php } ?>

    <?php if ($prev_href || $next_href) { ?>
    <nav class="ld-board__nav">
        <?php if ($prev_href) { ?><a href="<?php echo $prev_href; ?>"><span>이전글</span><strong><?php echo $prev_wr_subject; ?></strong></a><?php } ?>
        <?php if ($next_href) { ?><a href="<?php echo $next_href; ?>"><span>다음글</span><strong><?php echo $next_wr_subject; ?></strong></a><?php } ?>
    </nav>
    <?php } ?>

    <?php include_once G5_BBS_PATH . '/view_comment.php'; ?>
</article>

<script>
function board_move(href) {
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });
    $("#bo_v_atc").viewimageresize();
});
</script>
