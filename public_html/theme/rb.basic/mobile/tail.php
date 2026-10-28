<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
    return;
}

?>
    </div>
</div>


<?php echo poll('theme/basic'); // 설문조사 ?>
<?php echo visit('theme/basic'); // 방문자수 ?>


<div id="ft">
    <div id="ft_copy">
        <div id="ft_company">
            <a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a>
            <a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a>
            <a href="<?php echo get_pretty_url('content', 'provision'); ?>">서비스이용약관</a>
        </div>
        Copyright &copy; <b>소유하신 도메인.</b> All rights reserved.<br>
    </div>
    <div class="ft_cnt">
    	<h2>사이트 정보</h2>
        <p class="ft_info">
        	회사명 : 회사명 / 대표 : 대표자명<br>
			주소  : OO도 OO시 OO구 OO동 123-45<br>
			사업자 등록번호  : 123-45-67890<br>
			전화 :  02-123-4567  팩스  : 02-123-4568<br>
			통신판매업신고번호 :  제 OO구 - 123호<br>
			개인정보관리책임자 :  정보책임자명<br>
		</p>
    </div>
    <button type="button" id="top_btn"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
    <?php
    if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { ?>
    <a href="<?php echo get_device_change_url(); ?>" id="device_change">PC 버전으로 보기</a>
    <?php
    }

    if ($config['cf_analytics']) {
        echo $config['cf_analytics'];
    }
    ?>
</div>
<script>
jQuery(function($) {

    $( document ).ready( function() {

        // 폰트 리사이즈 쿠키있으면 실행
        font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
        
        //상단고정
        if( $(".top").length ){
            var jbOffset = $(".top").offset();
            $( window ).scroll( function() {
                if ( $( document ).scrollTop() > jbOffset.top ) {
                    $( '.top' ).addClass( 'fixed' );
                }
                else {
                    $( '.top' ).removeClass( 'fixed' );
                }
            });
        }

        //상단으로
        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });

    });
});
</script>

<nav class="ld-mcta" aria-label="모바일 바로가기">
    <?php if (function_exists('ld_clinic_phone_ready') && ld_clinic_phone_ready()) { ?>
    <a href="<?php echo ld_escape(ld_clinic('phone_href')); ?>">전화</a>
    <?php } else { ?>
    <a href="<?php echo function_exists('ld_page_url') ? ld_page_url('hours') : G5_URL; ?>">진료시간</a>
    <?php } ?>
    <?php if (function_exists('ld_clinic_kakao_ready') && ld_clinic_kakao_ready()) { ?>
    <a href="<?php echo ld_escape(ld_clinic('kakao')); ?>" target="_blank" rel="noopener">카카오톡</a>
    <?php } else { ?>
    <a href="<?php echo function_exists('ld_page_url') ? ld_page_url('location') : G5_URL; ?>">오시는 길</a>
    <?php } ?>
    <a class="is-primary" href="<?php echo function_exists('ld_page_url') ? ld_page_url('consult') : G5_URL; ?>">상담예약</a>
</nav>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");