<?php

function DB() {
    global $gDBObj;
    if ($gDBObj == null) {
        $gDBObj = new MySQLManager();
    }
    return $gDBObj;
}

function Now() {
    if (isset($GLOBALS['NOWTIME']) == false) {
        $GLOBALS['NOWTIME'] = time();
    }
    return $GLOBALS['NOWTIME'];
}

function IsNullString($val) {
    if (is_string($val) == false)
        return true;
    return (strlen(trim($val)) <= 0);
}

function GetReqVal($name) {
    if (isset($_REQUEST[$name])) {
        return trim($_REQUEST[$name]);
    }
    return null;
}

function GetObjVal($key, $obj, $default = null) {
    if (isset($obj[$key])) {
        return $obj[$key];
    }
    return $default;
}

function GetIntVal($key, $obj, $default = 0) {
    if (isset($obj[$key])) {
        return (int) $obj[$key];
    }
    return (int) $default;
}

function GetStrVal($key, $obj, $default = '') {
    if (isset($obj[$key])) {
        if ((string) $obj[$key] == "0") {
            return "";
        }
        return trim((string) $obj[$key]);
    }
    if ($default === null)
        return null;
    return (string) $default;
}

function generateProjectCode()
{
    $val = sha1(microtime().(time()*time()).rand());
    return substr(str_shuffle(base62_encode($val)), 0, 12);
}

function base62_encode($data) {
    $outstring = '';
    $len = strlen($data);
    for ($i = 0; $i < $len; $i += 8) {
        $chunk = substr($data, $i, 8);
        $outlen = ceil((strlen($chunk) * 8) / 6);
        $x = bin2hex($chunk);
        $number = ltrim($x, '0');
        if ($number === '')
            $number = '0';
        $w = gmp_strval(gmp_init($number, 16), 62);
        $pad = str_pad($w, $outlen, '0', STR_PAD_LEFT);
        $outstring .= $pad;
    }
    return $outstring;
}

function base62_decode($data) {
    $outstring = '';
    $len = strlen($data);
    for ($i = 0; $i < $len; $i += 11) {
        $chunk = substr($data, $i, 11);
        $outlen = floor((strlen($chunk) * 6) / 8);
        $number = ltrim($chunk, '0');
        if ($number === '')
            $number = '0';
        $y = gmp_strval(gmp_init($number, 62), 16);
        $pad = str_pad($y, $outlen * 2, '0', STR_PAD_LEFT);
        $outstring .= pack('H*', $pad);
    }
    return $outstring;
}



function GetClientIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

function CallURL($url, $headers = [], $postData = null, $timeOut = 3) {
    $ch = curl_init();
    $opts[CURLOPT_URL] = $url;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    if ($timeOut > 0) {
        $opts[CURLOPT_TIMEOUT] = $timeOut;
    }
    if ($postData != null && $postData != false) {
        $opts[CURLOPT_POST] = true;
        if (is_string($postData) && strlen($postData) > 0) {
            $opts[CURLOPT_POSTFIELDS] = $postData;
        }
    }
    if (is_array($headers) && count($headers) > 0) {
        $opts[CURLOPT_HTTPHEADER] = $headers;
    }
    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);
    //SetCallURLLastError(curl_getinfo($ch, CURLINFO_HTTP_CODE), curl_errno($ch), curl_error($ch));
    curl_close($ch);
    return $result;
}
