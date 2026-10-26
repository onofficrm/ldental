$(document).ready(function () {
    // flex_box 클래스를 가진 모든 요소를 선택
    var flexBoxes = $('.flex_box');

    // flex_box 요소와 data-layout 매핑
    flexBoxes.each(function (index) {
        $(this).attr('data-layout', index + 1);
    });

    // AJAX 요청 배열 생성
    var ajaxRequests = flexBoxes.map(function (index, element) {
        var layoutIndex = index + 1; // 1부터 시작하도록 설정
        var $element = $(element); // jQuery 캐싱

        return $.ajax({
            url: g5_url + '/rb/rb.config/ajax.layout_set.shop.php',
            method: 'POST',
            dataType: 'html',
            data: {
                layout: layoutIndex,
            }
        }).done(function (data) {
            // 성공적으로 데이터를 가져온 경우
            $element.html(data);

            // 필요한 경우 추가 초기화 실행
            if (typeof initializeCalendar === "function") {
                initializeCalendar();
            }
        }).fail(function () {
            // 요청 실패
            console.error('레이아웃 ' + layoutIndex + ' 로드 중 오류가 발생했습니다.');
        });
    }).get();

    // 모든 AJAX 요청 완료 후 실행
    $.when.apply($, ajaxRequests).then(function () {
        $('.rb_swiper').each(function () {
            initializeSlider($(this));
        });

        function initializeSlider($rb_slider) {
            var realInx = 0;
            var winWChk = '';
            var swiper;

            $(window).on('load resize', function () {
                var pcRows = parseInt($rb_slider.data('pc-h'), 10) || 1;
                var moRows = parseInt($rb_slider.data('mo-h'), 10) || 1;
                var pcCols = parseInt($rb_slider.data('pc-w'), 10) || 1;
                var moCols = parseInt($rb_slider.data('mo-w'), 10) || 1;
                var winW = window.innerWidth;
                var pcGap = parseInt($rb_slider.data('pc-gap'), 10) || 0;
                var moGap = parseInt($rb_slider.data('mo-gap'), 10) || 0;
                var pcSwap = $rb_slider.data('pc-swap') == 1 ? 1 : 0;
                var moSwap = $rb_slider.data('mo-swap') == 1 ? 1 : 0;

                if (winW <= 1024 && winWChk != 'mo') {
                    var view = moRows * moCols;
                    winWChk = 'mo';
                    setListWidth($rb_slider, moCols, moGap);
                    reorganizeSlides($rb_slider, view, moGap, moCols);
                    startSlider($rb_slider, view, moSwap, moGap);
                } else if (winW > 1024 && winWChk != 'pc') {
                    var view = pcRows * pcCols;
                    winWChk = 'pc';
                    setListWidth($rb_slider, pcCols, pcGap);
                    reorganizeSlides($rb_slider, view, pcGap, pcCols);
                    startSlider($rb_slider, view, pcSwap, pcGap);
                }
            });

            function setListWidth($rb_slider, cols, gap) {
                var widthPercentage = `calc(${100 / cols}% - ${(gap * (cols - 1)) / cols}px)`;
                $rb_slider.find('.rb_swiper_list').css('width', widthPercentage);
            }

            function reorganizeSlides($rb_slider, view, gap, cols) {
                if ($rb_slider.find('.rb_swiper_list').parent().hasClass('rb-swiper-slide')) {
                    $rb_slider.find('.swiper-slide-duplicate').remove();
                    $rb_slider.find('.rb_swiper_list').unwrap('.rb-swiper-slide');
                }

                var num = 0;
                $rb_slider.find('.rb_swiper_list').each(function (i) {
                    $(this).addClass('rb_swiper_list' + (Math.floor((i + view) / view)));
                    num = Math.floor((i + view) / view);
                }).promise().done(function () {
                    for (var i = 1; i <= num; i++) {
                        $rb_slider.find('.rb_swiper_list' + i).wrapAll('<div class="rb-swiper-slide swiper-slide"></div>');
                        $rb_slider.find('.rb_swiper_list' + i).removeClass('rb_swiper_list' + i);
                    }
                });

                removeRightGap($rb_slider, gap, cols); // 마지막 요소 오른쪽 간격 제거
                setSlideGap($rb_slider, gap); // 슬라이드 간격 설정
            }

            function removeRightGap($rb_slider, gap, cols) {
                $rb_slider.find('.rb_swiper_list').each(function (index) {
                    if ((index + 1) % cols === 0) {
                        $(this).css('margin-right', '0');
                    }
                });
            }

            function setSlideGap($rb_slider, gap) {
                $rb_slider.find('.rb-swiper-slide').css({
                    'gap': gap + 'px', // 동적으로 gap 적용
                });
            }

            function startSlider($rb_slider, view, swap, gap) {
                var autoplayEnabled = $rb_slider.data('autoplay') == 1;
                var autoplayDelay = parseInt($rb_slider.data('autoplay-time'), 10) || 3000;

                if (swiper) {
                    swiper.destroy(true, true); // 기존 인스턴스 제거
                }

                swiper = new Swiper($rb_slider.find('.rb_swiper_inner')[0], {
                    slidesPerView: 1,
                    initialSlide: Math.floor(realInx / view),
                    resistanceRatio: 0,
                    spaceBetween: gap,
                    touchRatio: swap,
                    autoplay: autoplayEnabled ? {
                        delay: autoplayDelay,
                        disableOnInteraction: false
                    } : false,
                    navigation: {
                        nextEl: $rb_slider.find('.rb-swiper-next')[0],
                        prevEl: $rb_slider.find('.rb-swiper-prev')[0],
                    },
                    on: {
                        slideChange: function () {
                            realInx = this.realIndex * view;
                        }
                    },
                });
            }
        }
        console.log('모든 레이아웃 데이터 로드 완료');
    });
});
