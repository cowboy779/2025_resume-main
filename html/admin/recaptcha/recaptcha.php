<?php

include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function GetObjVal($key, $obj, $default = null) {
    if (isset($obj[$key])) {
        return $obj[$key];
    }
    return $default;
}
function GetReqVal($name) {
    if (isset($_REQUEST[$name])) {
        return trim($_REQUEST[$name]);
    }
    return null;
}
function GetClientIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }
    return '';
}



if (GetReqVal("action") == "hiddenLoginOK" && IsValidGoogleReCaptchaRequest()) {
    
    // POST 요청을 처리
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["user_id"]) && isset($_POST["user_pw"])) {
            $user_id = $_POST["user_id"];
            $user_pw = $_POST["user_pw"];
            error_log(var_export([$user_id,$user_pw], true));

            // 여기서 LDAP 로그인 함수 호출
            $login_result = GetDocListInfo($user_id, $user_pw);
            if ($login_result) {
                // 로그인 성공 처리
                echo "</br> 결과 : 로그인 성공";
                echo "</br>---------------------</br>";
                echo "<a href=\"recaptcha.php\">[로그아웃]</a>";
                print("<b>메인으로</b>");
            } else {
                // 로그인 실패 처리
                header("Content-Type: text/html; charset=UTF-8");
                echo "<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
                echo "window.location.replace('recaptcha.php');</script>";
                exit;
            }
        }
    }
    
}

function IsValidGoogleReCaptchaRequest() {
    $postData = [];
    $postData['secret'] = Config::$RECAPTCHA_SECRET;
    $postData['response'] = GetReqVal('g-recaptcha-response');
    $postData['remoteip'] = GetClientIP();

    $result = CallURL(Config::$RECAPTCHA_URL, $postData);
    
    $datas = json_decode($result, true);
    $final = GetObjVal('success', $datas);

    return $final;
}

function CallURL($url, $postData = null, $timeOut = 3) {
    $ch = curl_init();

    $opts[CURLOPT_URL] = $url;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    
    if ($timeOut > 0) {
        $opts[CURLOPT_TIMEOUT] = $timeOut;
    }
    if ($postData != null) {
        $opts[CURLOPT_POST] = true;

        if (is_string($postData) && strlen($postData) > 0) {
            $opts[CURLOPT_POSTFIELDS] = $postData;
        } else if (is_array($postData)) {
            $opts[CURLOPT_POSTFIELDS] = http_build_query($postData);
        }
    }

    curl_setopt_array($ch, $opts);
    
    $result = curl_exec($ch);
    
    curl_close($ch);
    return $result;
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

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Google reCaptcha TEST</title>
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css" />
    <script src="/admin/js/jquery-1.11.1.min.js"></script>
    <script src="/admin/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        
        <div class="col-md-6 col-md-offset-3 text-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Google reCaptcha (port X)</h1>
                    <h2>LDAP LOGIN</h2>
                </div>
                <div class="panel-body">
                    <p class="text-danger"><strong>위메이드맥스 계정</strong></p>
                    
                    <form method="post" id="form_loginOK" action="">
                        <p>아이디: <input type="text" name="user_id" /></p>
                        <p>비밀번호: <input type="password" name="user_pw" /></p>
                        <input type="hidden" name="action" value="hiddenLoginOK" />
                        <input type="submit" id="btn_loginOK" value="로그인" />
                        <input type="button" id="btn_main" value="메인으로" onclick="location.href='/admin/main.php';" />
                    </form>
                </div>
            </div>
        </div>

    </div>
    

    <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
        <script>
        // 한 화면에 reCaptcha버튼이 2개 이상일 경우
        var onloadCallback = function() {
          grecaptcha.render('btn_loginOK', {
            'sitekey' : '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
            'callback' : onSubmitOK
          });
        };
        function onSubmitOK() { 
            document.getElementById("form_loginOK").submit(); 
        }
        </script>
</body>
</html>