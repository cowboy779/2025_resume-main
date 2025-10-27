<?php

include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function GetDocListInfo($id, $pw) {
    echo "</br>---------------------";

    // LDAP 인증
    $login = false;
    $LDAP_SERVER = Config::$LDAP_SERVER;

    $ldapconn = ldap_connect($LDAP_SERVER);
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

    // ob_start();
    // var_dump($ldapconn);
    // $dumpedData = ob_get_clean();
    // error_log("ldapconn : ". $dumpedData);

    $userId = $id;
    $userDn = Config::$LDAP_USER_DN;

    $user_search = ldap_search($ldapconn, $userDn, "(mail=$userId)");
    $user_entry = ldap_first_entry($ldapconn, $user_search);
    $user_dn = ldap_get_dn($ldapconn, $user_entry);

    $passwd = $pw;
    $login = @ldap_bind($ldapconn, $user_dn, $passwd);

    // if ($user_search) {
    //     $user_entry = ldap_first_entry($ldapconn, $user_search);
    //     $user_dn = ldap_get_dn($ldapconn, $user_entry);
    //     $login = @ldap_bind($ldapconn, $user_dn, $passwd);
    // }

    ldap_close($ldapconn);

    return $login;
}


// POST 요청을 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["user_id"]) && isset($_POST["user_pw"])) {
        $user_id = $_POST["user_id"];
        $user_pw = $_POST["user_pw"];

        // 여기서 LDAP 로그인 함수 호출
        $login_result = GetDocListInfo($user_id, $user_pw);
        if ($login_result) {
            // 로그인 성공 처리
            echo "</br> 결과 : 로그인 성공";
            echo "</br>---------------------</br>";
            echo "<a href=\"ldap_php_test.php\">[로그아웃]</a>";
        } else {
            // 로그인 실패 처리
            header("Content-Type: text/html; charset=UTF-8");
            echo "<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
            echo "window.location.replace('ldap_php_test.php');</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>LDAP 로그인 테스트</title>
</head>
<body>
    <h1>LDAP 로그인 테스트!</h1>
    <hr />
    <h2>로그인</h2>
    <p>test ID</p>
    <form method="post" action="">
        <p>아이디: <input type="text" name="user_id" /></p>
        <p>비밀번호: <input type="password" name="user_pw" /></p>
        <p><input type="submit" value="로그인" /></p>
    </form>
</body>
</html>