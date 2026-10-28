<?php
if (!defined('_GNUBOARD_')) exit;

add_stylesheet('<link rel="stylesheet" href="' . $latest_skin_url . '/style.css">', 0);
$list_count = (is_array($list) && $list) ? count($list) : 0;
?>

<div class="ld-latest ld-latest--review">
    <div class="ld-latest__head">
        <h2><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo get_text($bo_subject); ?></a></h2>
        <a class="ld-latest__more" href="<?php echo get_pretty_url($bo_table); ?>">더보기</a>
    </div>
    <div class="ld-latest__grid">
    <?php for ($i = 0; $i < $list_count; $i++) {
        $thumb = '';
        if (!empty($list[$i]['first_file_thumb']['bf_file'])) {
            $thumb = G5_DATA_URL . '/file/' . $bo_table . '/' . $list[$i]['first_file_thumb']['bf_file'];
        } else {
            $thumb = G5_THEME_URL . '/rb.img/clinic/smile.jpg';
        }
        $excerpt = isset($list[$i]['content']) ? strip_tags($list[$i]['content']) : '';
        if (function_exists('cut_str')) {
            $excerpt = cut_str($excerpt, 80);
        } elseif (function_exists('mb_strlen') && mb_strlen($excerpt, 'UTF-8') > 80) {
            $excerpt = mb_substr($excerpt, 0, 80, 'UTF-8') . '…';
        }
    ?>
        <article>
            <a href="<?php echo get_pretty_url($bo_table, $list[$i]['wr_id']); ?>">
                <div class="ld-latest__thumb"><img src="<?php echo $thumb; ?>" alt=""></div>
                <h3><?php echo $list[$i]['subject']; ?></h3>
                <p><?php echo $excerpt; ?></p>
            </a>
        </article>
    <?php } ?>
    <?php if ($list_count === 0) { ?>
        <p class="ld-latest__empty">등록된 후기가 없습니다.</p>
    <?php } ?>
    </div>
</div>
