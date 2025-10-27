
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$action = GetReqVal('action');
if ($action == 'hiddenLoginOK') {
    require_once 'vendor/autoload.php';
    
    // Get $id_token via HTTPS POST.
    $id_token = $_POST['credential'];
    $CLIENT_ID = $_POST['client_id'];
    
    $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    $payload = $client->verifyIdToken($id_token);
    

    $nbf = date("Y-m-d H:i:s", $payload['nbf']);
    $sTime = date("Y-m-d H:i:s", $payload['iat']);
    $eTime = date("Y-m-d H:i:s", $payload['exp']);
    
    echo "토큰활성시간: " . $nbf ;
    echo "토근발급시간: " . $sTime;
    echo "토큰종료시간: " . $eTime;
    
    // if ($payload) {
    // $userid = $payload['sub'];
    //     // If request specified a G Suite domain:
    //     //$domain = $payload['hd'];
    // } else {
    //     // Invalid ID token
    // }
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

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>구글 채널 로그인</title>
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css" />
    <script src="/admin/js/jquery-1.11.1.min.js"></script>
    <script src="/admin/js/bootstrap.min.js"></script>
    <!-- <script src="https://apis.google.com/js/platform.js" async defer></script> -->
</head>
<body>
    <div class="container">
        
        <div class="col-md-6 col-md-offset-3 text-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Google LOGIN</h1>
                </div>
                <div class="panel-body">
                    <p class="text-danger"><strong>구글 계정</strong></p>
                    
                    <script src="https://accounts.google.com/gsi/client" async defer></script>
                    
                    <!-- 구글클라우드 클라이언트 ID 새로 발급 -->
                    <!-- 현재 테스트는 클라우드에서 삭제 -->
                    <div id="g_id_onload"
                        data-client_id="  "
                        data-ux_mode="popup"
                        data-auto_prompt="false"
                        data-callback="handleCredentialResponse"
                        >
                    </div>
                    <div class="g_id_signin" 
                        data-type="standard" 
                        data-theme="outline" 
                        data-shape="rectangular" 
                        data-width="400" 
                        data-logo_alignment="center">
                    </div>
                    
                    <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->
                    
                    <form method="post" id="form_loginOK">
                        <input type="hidden" id="credential" name="credential" value/>
                        <input type="hidden" id="client_id" name="client_id" value/>
                        <input type="hidden" name="action" value="hiddenLoginOK" />
                    </form>

                    <textarea id="txtarea1" rows="10" cols="50"></textarea>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleCredentialResponse(response) {
            $('#credential').val(response.credential);
            $('#client_id').val(response.client_id);
            // $('#form_loginOK').submit();

            // const responsePayload = decodeJwtResponse(response.credential);
            // console.log(responsePayload);
            // console.log("ID: " + responsePayload.sub);
            // console.log('Full Name: ' + responsePayload.name);
            // console.log('Given Name: ' + responsePayload.given_name);
            // console.log('Family Name: ' + responsePayload.family_name);
            // console.log("Image URL: " + responsePayload.picture);
            // console.log("Email: " + responsePayload.email);
            
            // location.href="result-composer.php";
        }
    </script>
    
</body>
</html>