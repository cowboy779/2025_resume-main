<?php define('_WEMADEMAX_', true); ?>

<!DOCTYPE html>
<?php 
// session_start(); 
// session_destroy();
?>

<html>
    <head>
        <meta charset="utf-8" />
        <title>web main</title>
    </head>
    <body>
        <h1>메인 작업 리스트</h1>

        <h3>중요 </h3>
        <?php
             echo "<a href=\"1_Key-points/view_md.php\">[실행전 중요사항]</a>";
             ?>
        <hr />

        <h4>UserAgent </h4>
        <?php
             echo "<a href=\"useragent/index.html\">[UserAgent TEST]</a>";
             ?>
        <hr />

        <h4>RESUME </h4>
        <?php
             echo "<a href=\"resume/view_md.php\">[RESUME]</a>";
             ?>
        <hr />

        <h4>GITEA </h4>
        <?php
             echo "<a href=\"https://alpha.lightcon.net:8015/gitea/\">[Gitea TEST]</a>";
             ?>
        <hr />
        
        <h4>타입 스크립트  </h4>
        <?php
             echo "<a href=\"typescript/src/ex1.html\">[TYPESCRIPT]</a>";
             ?>
        <hr />

        <h4>SVN Test  </h4>
        <?php
             echo "<a href=\"svn/svnadmin/login.php\">[SVN Login]</a>";
             ?>
        <hr />

        <h4>Google Login  </h4>
        <?php
             echo "<a href=\"google/glogin.php\">[Google Login]</a>";
             ?>
        <hr />
        
        <h4>PHP noti TEST  </h4>
        <?php
             echo "<a href=\"telegrame-smtp-autoload/index.php\">[php noti_telegram]</a>";
             ?>
        <hr />
        
        <h4>구글 reCaptcha  </h4>
        <?php
             echo "<a href=\"recaptcha/recaptcha.php\">[*구글 리캡챠]</a>";
             ?>
        <hr />

        <h4>그누보드5 바일로 습작 </h4>
        <?php
             echo "<a href=\"..\gnu_bylo/\">[*바일로 퍼블리싱]</a>";
        ?>

        <br></br>
        <hr />
        <h4>파일 업로드 </h4>
        <?php
             echo "<a href=\"ldap_login/file_test.php\">[*파일업로드1]</a>";
        ?>

        <br></br>
        <hr />
        <h4>LDAP </h4>
        <?php
             echo "<a href=\"ldap_login/ldap_php_test.php\">[LDAP PHP 인증 예제]</a>";
        ?>

        <br></br>
        <hr />
        <h4>CSS </h4>
        <?php
             echo "<a href=\"flex/media.html\">1. media 태그 활용 및 테스트예제</a>";
             echo "<br/>";
             echo "<a href=\"flex/00-blank.html\">2. flex 관련 배치정렬</a>";
             echo "<br/>";
             echo "<a href=\"flex/ui-page-1.html\">3. flex 반응형</a>";
             echo "<br/>";
             echo "<a href=\"flex/grid.html\">4. CSS Grid WemadeMax 레이아웃</a>";
             echo "<br/>";
             echo "<a href=\"flex/hambuger-menu.html\">┖4-2. Hambuger-menu SET TEST</a>";
        ?>

        <br></br>
        <hr />
        <h4>SNS 공유방법 기능</h4>
        <?php
             echo "<a href=\"sns/opengraph.html\">[텍스트/링크/썸네일 3가지 방법 sns 공유]<br/> └ 유튜브 HTML 추가작업</a>";
        ?>

        <br></br>
        <hr />
        <h4>원스크롤 페이지 기능</h4>
        <?php
             echo "<a href=\"one/scroll2.html\">[원스크롤 기본 예제]</a>";
             echo "<br/>";
             echo "<a href=\"one/scroll.html\">[원스크롤 작업]</a>";
             echo "<br/>";
             echo "<a href=\"one/swiper_one.html\">[스와이퍼 js 원스크롤 작업]</a>";
        ?>

        <br></br>
        <hr />
        <h4>다국어 처리관련</h4>
        <?php
             echo "<a href=\"i18n/ex.html\">[라이브러리로 i18n 작업메뉴로]</a>";
             echo "<br/>";
             echo "<a href=\"i18n/ex2.php\">[php 다국어처리 및 브라우저별 언어확인 테스트]</a>";
        ?>


       <br></br>
        <hr />
        <h4>게시판 관련</h4>
        <?php
             echo "<a href=\"page\boardindex.php\">[게시판 작업메뉴로]</a>";
        ?>

        <br></br>
        <hr />
        <h4>로그인 관련</h4>
        <?php
             echo "<a href=\"index.php\">[php 정보]</a>";
             echo "<br/>";
             echo "<a href=\"login\loginindex.php\">[로그인 작업메뉴로]</a>";
        ?>

    </body>