<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>PHP Session Login Test</title>
    </head>
    <body>
        <h1>로그인 테스트</h1>
        <?php
            var_export( $_SESSION['user_id']);
            
            if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
                echo "<p>로그인을 해 주세요. <a href=\"login.php\">[로그인]</a></p>";
            } else {
                $user_id = $_SESSION['user_id'];
                $user_name = $_SESSION['user_name'];
                echo "<p><strong>$user_name</strong>($user_id)님 환영합니다.";
                echo "<a href=\"logout.php\">[로그아웃]</a></p>";
            }
        ?>
        <hr />
        <p>-</p>
    </body>
</html>