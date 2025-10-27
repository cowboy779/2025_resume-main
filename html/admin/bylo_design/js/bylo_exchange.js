
$(function () {
    let num = 0;

    $('#cell_exchange').click(function () {
        if (num == 0) {
            $('#cell_left_img').css({
                "background-image": "url(./png/img_top_chip.png)"
                , "background-repeat": "no-repeat"
                , "background-size": "35px 35px"
                , "background-position": "top right"
            });
            $('#cell_right_img').css({
                "background-image": "url(./png/img_top_bylo.png)"
                , "background-repeat": "no-repeat"
                , "background-size": "35px 35px"
                , "background-position": "top right"
            });

            //BYLO <> CHIP 텍스트 반대로
            var leftText = $("#cell_left_text").text();
            var rightText = $('#cell_right_text').text();
            $("#cell_left_text").text(rightText);
            $("#cell_right_text").text(leftText);

            //BYLO <> CHIP 금액 반대로
            var leftCoin = $("#cell_left_coin").val();
            var rightCoin = $("#cell_right_coin").val();
            $("#cell_left_coin").val(rightCoin);
            $("#cell_right_coin").val(leftCoin);

            //하단 노티스 문구 교체
            $(".rate-bylo-text").css('display', 'none');
            $(".rate-chip-text").css('display', 'block');

            //상단 교환소 가격 문구 교체
            $(".ex-rate-bc-text").css('display', 'none');
            $(".ex-rate-cb-text").css('display', 'block');

            num = 1;
        } else {
            $('#cell_left_img').css({
                "background-image": "url(./png/img_top_bylo.png)"
                , "background-repeat": "no-repeat"
                , "background-size": "35px 35px"
                , "background-position": "top right"
            });
            $('#cell_right_img').css({
                "background-image": "url(./png/img_top_chip.png)"
                , "background-repeat": "no-repeat"
                , "background-size": "35px 35px"
                , "background-position": "top right"
            });

            //BYLO <> CHIP 텍스트 반대로
            var leftText = $("#cell_left_text").text();
            var rightText = $('#cell_right_text').text();
            $("#cell_left_text").text(rightText);
            $("#cell_right_text").text(leftText);

            //BYLO <> CHIP 금액 반대로
            var leftCoin = $("#cell_left_coin").val();
            var rightCoin = $("#cell_right_coin").val();
            $("#cell_left_coin").val(rightCoin);
            $("#cell_right_coin").val(leftCoin);

            //하단 노티스 문구 교체
            $(".rate-bylo-text").css('display', 'block');
            $(".rate-chip-text").css('display', 'none');

            //상단 교환소 가격 문구 교체
            $(".ex-rate-bc-text").css('display', 'block');
            $(".ex-rate-cb-text").css('display', 'none');

            num = 0;
        }
    });

    $('#cell_exchange').hover(
        function () {
            $(this).addClass('hovered');
        },
        function () {
            $(this).removeClass('hovered');
        }
    );

    $('#icon_refresh').css('animation-play-state', 'paused');

    let num2 = 0;
    $('.form-group2').click(function () {
        if(num2 == 0){
            $(".alert-tot-custom").css('display', 'none');
            $(".alert-tot-custom2").css('display', 'block');

            num2 = 1;
        }else{
            $(".alert-tot-custom").css('display', 'block');
            $(".alert-tot-custom2").css('display', 'none');

            num2 = 0;
        }
        
    });
});

$('#icon_refresh').click(function () {
    $('#icon_refresh').css('animation', 'none'); // 애니메이션 초기화
    
    // console.log($('#icon_refresh').width()); 
    void $('#icon_refresh').width(); // 리플로우 트리거
    
    $('#icon_refresh').css('animation', 'icon_refresh 0.8s linear'); // 애니메이션 재설정

    // setTimeout(() => $('#icon_refresh').css('animation', 'none'), 1000);
});

