<?php
if (!defined('_GNUBOARD_')) exit;

add_stylesheet('<link rel="stylesheet" href="' . $latest_skin_url . '/style.css">', 0);
$list_count = (is_array($list) && $list) ? count($list) : 0;
?>

<div class="ld-latest ld-latest--notice">
    <div class="ld-latest__head">
        <?php $board_url = function_exists('ld_board_url') ? ld_board_url($bo_table) : get_pretty_url($bo_table); ?>
        <h2><a href="<?php echo $board_url; ?>"><?php echo get_text($bo_subject); ?></a></h2>
        <a class="ld-latest__more" href="<?php echo $board_url; ?>">더보기</a>
    </div>
    <ul>
    <?php for ($i = 0; $i < $list_count; $i++) { ?>
        <li>
            <a href="<?php echo function_exists('ld_write_url') ? ld_write_url($bo_table, $list[$i]['wr_id']) : get_pretty_url($bo_table, $list[$i]['wr_id']); ?>">
                <?php if ($list[$i]['is_notice']) { ?><span class="ld-latest__badge">공지</span><?php } ?>
                <span class="ld-latest__subject"><?php echo $list[$i]['subject']; ?></span>
            </a>
            <time datetime="<?php echo $list[$i]['datetime']; ?>"><?php echo $list[$i]['datetime2']; ?></time>
        </li>
    <?php } ?>
    <?php if ($list_count === 0) { ?>
        <li class="ld-latest__empty">등록된 공지가 없습니다.</li>
    <?php } ?>
    </ul>
</div>
