<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>간단한 다국어 지원 페이지 예제</title>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
</head>
<body> 
<?php
  if(isset($_GET['lang'])){
    $lang = $_GET['lang'];
  }else{
    //PHP Accept Language 브라우저 언어
    $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    //$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  }
   
  if ($lang == "ko") {    // 한국어
    $strings = array ("예", "아니오", "취소", "로그인", "이 메시지는 테스트 메시지입니다.", "이 페이지의 언어는 한국어입니다.");
  }elseif ($lang == "en"){// 영어
    $strings = array ("Yes", "No", "Cancel", "Sign in", "This message is a test message.", "The language of this page is English.");
  }elseif ($lang == "fr"){// 프랑스어
    $strings = array ("Oui", "Non", "Annuler", "Connexion", "Ce message est un message test", "La langue de cette page est française.");
  }elseif($lang == "ja"){ //일본어
    $strings = array ("はい", "いいえ", "キャンセル", "ログイン", "このメッセージはテストメッセージです", "このページの言語は日本語です.");
  }elseif($lang == "cn"){ //중국어(간체)
    $strings = array ("是的", "不", "取消", "登录", "此消息是测试消息。", "本页面语言为简体中文。");
  }elseif($lang == "tw"){ //중국어(번체)
    $strings = array ("是的", "不", "取消", "登錄", "此消息是測試消息。", "本頁面語言為繁體中文。");
  }elseif($lang == "es"){ //스페인어
    $strings = array ("Sí", "no", "cancelar", "iniciar sesión", "Este mensaje es un mensaje de prueba.", "El idioma de esta página es el español.");
  }elseif($lang == "pt"){ //포르투갈어
    $strings = array ("Sim", "não", "cancelar", "Conecte-se", "Esta mensagem é uma mensagem de teste.", "O idioma desta página é o português.");
  }elseif($lang == "ru"){ //러시아어
    $strings = array ("Да", "нет", "Отмена", "авторизоваться", "Это сообщение является тестовым.", "Язык этой страницы русский.");
  }elseif($lang == "th"){ //태국어
    $strings = array ("ใช่", "ไม่", "ยกเลิก", "เข้าสู่ระบบ", "ข้อความนี้เป็นข้อความทดสอบ", "ภาษาของหน้านี้เป็นภาษาไทย");
  }
  
  else {// 일치하는 언어 없으면 영어로 표시
    $strings = array ("Yes", "No", "Cancel", "Sign in", "This message is a test message.", "The language of this page is English.");
  }
  
  echo
    "
    <p>
        <strong>예</strong> : $strings[0] <br>
        <strong>아니오</strong> : $strings[1] <br>
        <strong>취소</strong> : $strings[2] <br>
        <strong>로그인</strong> : $strings[3] <br>
        <strong>이 메시지는 테스트 메시지입니다.</strong> : $strings[4] <br>
        <strong>이 페이지의 언어는 ~~어입니다.</strong> : $strings[5] <br>
    </p>
    ";
    
    echo "<script> var phpLang = '$lang';  
                  console.log('php 랭 :'+phpLang); 
          </script>";
  
?>

<select id="lang-select" onchange="location.href='ex2.php?lang='+this.value">
      <option value="en">English</option>
      <option value="ko">한국어</option>
      <option value="fr">Français</option>
      <option value="ja">日本語</option>
      <option value="cn">简体 中文</option>
      <option value="tw">繁體 中文</option>
      <option value="es">español</option>
      <option value="pt">Português</option>
      <option value="ru">Русский</option>
      <option value="th">ไทย</option>
</select>
<br/><br/>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='en'; ?>"><button>영어</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='fr'; ?>"><button>프랑스어</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='ja'; ?>"><button>일본어</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='cn'; ?>"><button>중국어(간체)</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='tw'; ?>"><button>중국어(번체)</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='es'; ?>"><button>스페인어</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='pt'; ?>"><button>포르투갈어</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='ru'; ?>"><button>러시아어</button></a>
<a href="/admin/i18n/ex2.php?lang=<?php echo $lang='th'; ?>"><button>태국어</button></a>
<br/><br/><br/>
<div id ="web_langPHP">ing</div>
<br/>
<div id ="web_langAjax">ing</div>
<br/>
<div id ="web_langNavi">ing</div>
<script>
    console.log("스크립트 랭 :"+phpLang);
    $("#lang-select").val(phpLang);
    
    
    //브라우저 언어 확인 방법 IE 방법 비동기방식
    function getLangAjax() {
        var userLang = 
        $.ajax({
                url: "https://ajaxhttpheaders.appspot.com",
                dataType: 'jsonp',
                success: function(headers) {
                  console.log(headers);
                  
                  language = headers['Accept-Language'];
                  alert(language); 
                },
                error: function(error){
                  console.log(error);
                }
              });
         ;
        return userLang;
    };

    //비 IE이거나 IE일대 네비게이터로 확인하는 방식
    function getLangNavi(){
        var userLang = navigator.language || navigator.userLanguage;
        
        return userLang;
    }
    
    $("#web_langPHP").html("<h2>1. 초기 PHP 브라우저 언어 Accept Language 로 확인 : <font color='red'>"+phpLang+"</color></h2>");
    //현재 url 작동안하는거 같음.
    $("#web_langAjax").html("<h2>2. 초기 현재접속 브라우저 언어 IE 비동기식 Accept Language 로 확인 : <font color='red'>"+getLangAjax()+"</color></h2>");

    $("#web_langNavi").html("<h2>3. 초기 현재접속 네비게이터 IE / 비 IE 확인 : <font color='red'>"+getLangNavi()+"</color></h2>");
    

    // $('#btn_a').on("click",()=>{
    //     window.location.href = "?lang=ko";
    // });
    
</script>   
<?php
    // $string = ";aaa;;bbb;";

    // $parts = [];
    // $tok = strtok($string, ";");

    // echo $tok;

    // while ($tok !== false) {
    //     $parts[] = $tok;
    //     $tok = strtok(";");
        
    // }

    // echo json_encode($parts),"\n";

    // $parts = explode(";", $string);
    // echo json_encode($parts),"\n";


    $string = "This is\tan example\nstring";
    /* Use tab and newline as tokenizing characters as well  */
    $tok = strtok($string, " \n\t");

    echo $tok."<br/>";

    while ($tok !== false) {
        echo "Word=$tok<br />";
        $tok = strtok(" \n\t");
        echo "chang=$tok<br />";
    }

    ?>
  <br/><br/>
  <a href="/admin/main.php"><button>메인으로</button></a>
</body>
</html>