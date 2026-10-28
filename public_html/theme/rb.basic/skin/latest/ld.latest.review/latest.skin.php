<?php
if (!defined('_GNUBOARD_')) exit;

add_stylesheet('<link rel="stylesheet" href="' . $latest_skin_url . '/style.css">', 0);
$list_count = (is_array($list) && $list) ? count($list) : 0;
$fallback_imgs = array('consult.jpg', 'care.jpg', 'smile.jpg', 'hero.jpg');
$img_base = G5_THEME_URL . '/rb.img/clinic/';
$review_bo = function_exists('ldental_clinic_bo_table') ? ldental_clinic_bo_table('review') : $bo_table;
$board_url = function_exists('ld_board_url') ? ld_board_url('review') : get_pretty_url($bo_table);
?>

<div class="ld-latest ld-latest--review">
    <div class="ld-latest__head">
        <h2><a href="<?php echo $board_url; ?>"><?php echo get_text($bo_subject); ?></a></h2>
        <a class="ld-latest__more" href="<?php echo $board_url; ?>">더보기</a>
    </div>
    <ul class="ld-latest__list">
    <?php for ($i = 0; $i < $list_count; $i++) {
        $href = function_exists('ld_write_url') ? ld_write_url('review', $list[$i]['wr_id']) : get_pretty_url($review_bo, $list[$i]['wr_id']);
        $excerpt = isset($list[$i]['content']) ? strip_tags($list[$i]['content']) : '';
        if ($excerpt === '' && !empty($list[$i]['subject'])) {
            $excerpt = $list[$i]['subject'];
        }
        if (function_exists('cut_str')) {
            $excerpt = cut_str($excerpt, 72);
        } elseif (function_exists('mb_strlen') && mb_strlen($excerpt, 'UTF-8') > 72) {
            $excerpt = mb_substr($excerpt, 0, 72, 'UTF-8') . '…';
        }
    ?>
        <li>
            <a href="<?php echo $href; ?>">
                <span class="ld-latest__subject"><?php echo $list[$i]['subject']; ?></span>
                <?php if ($excerpt !== '' && $excerpt !== $list[$i]['subject']) { ?>
                <span class="ld-latest__excerpt"><?php echo $excerpt; ?></span>
                <?php } ?>
            </a>
        </li>
    <?php } ?>
    <?php if ($list_count === 0) { ?>
        <li class="ld-latest__empty">등록된 후기가 없습니다.</li>
    <?php } ?>
    </ul>
</div>
