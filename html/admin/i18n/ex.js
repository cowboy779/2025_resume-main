//게임정보 화면 ------------------
//메뉴버튼들
$("#info_menu_0").on("click",()=>{
	SetInfoDescription(0);
    
	CheckMultiLanguageFontSizes();//다국에에 대한 글씨 크기 보정.
	
	//폰트 때문에 한번 호출해야함.
	UpdateWindowSize();
    
	UpdateScrollPos();
});

//게임정보창의 메뉴선택 함수
function SetInfoDescription(menuNum){
	infoMenuNum = menuNum;

	//버튼 선택 상황 갱신.
	for(i=0; i<6; i++){
		if(i==infoMenuNum){
			$("#info_menu_"+i).css('background-image', 'url(/image/main/tab_bg.png)');
		}else{
			$("#info_menu_"+i).css('background-image', '');
		}
	}
	//이미지 변경
	$("#info_img").attr("src","./images/3_growth_def/growth_def_img0"+(infoMenuNum+1)+".png")

	//텍스트 변경
	$("#info_title_text").html(infoTitles[infoMenuNum]);

	if(IsMobileLayout()){
		$("#info_desc_text").html(infoDescriptionsMobile[infoMenuNum]);
	}else{
		$("#info_desc_text").html(infoDescriptions[infoMenuNum]);
	}
}