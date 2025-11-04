<?php

$country = strtolower(filter_input(INPUT_GET, 'country'));
$ipaddr = ip2long(filter_input(INPUT_GET, 'ipaddr'));

//if (empty($ipaddr)) {
if (empty($ipaddr) || empty($country)) {
    print("err_ip");
    exit;
}

ini_set('memory_limit', '512M');

//if (empty($country) || $country == "all") {
if ($country == "find" || $country == "all") {
    print(GetCountryCodeByIP($ipaddr));
}
else {
    $ccList = array_map('trim', explode(",", $country));
    foreach (array_unique($ccList) as $cc)
    {
        if (empty($cc)) {
            continue;
        }
        if (file_exists("./datas/ip_{$cc}.php") == false) {
            print("err_cc:{$cc}");
            exit;
        }
        if (IsCountryIP($ipaddr, $cc)) {
            print($cc);
            exit;
        }
    }
    print("false");
}
exit;

//==============================================================================
function GetCountryCodeByIP($ip)
{
    require("./datas/ip_all.php");
    return find_ip_cc($ip, 0, count($list), $list);
}
function find_ip_cc($val, $s, $e, &$list)
{
    $idx = ((int)(($e - $s)/2)) + $s;
    if ($val < $list[$idx][0]) {
        return find_ip_cc($val, $s, $idx, $list);
    }
    else if ($list[$idx+1][0] <= $val) {
        return find_ip_cc($val, $idx+1, $e, $list);
    }
    if ($list[$idx][0] <= $val && $val <= $list[$idx][1]) {
        return $list[$idx][2];
    }
    return null;
}


//==============================================================================
function IsCountryIP($ip, $cc)
{
    require("./datas/ip_{$cc}.php");
    return find_ip($ip, 0, count($list), $list);
}
function find_ip($val, $s, $e, &$list)
{
    $idx = ((int)(($e - $s)/2)) + $s;
    if ($list[$idx][0] <= $val && $val <= $list[$idx][1]) {
        return true;
    }
    //==========================================================================
    if ($idx > 0 && $val < $list[$idx][0]) {
        return find_ip($val, $s, $idx, $list);
    }
    if (isset($list[$idx+1]) && $list[$idx+1][0] <= $val) {
        return find_ip($val, $idx+1, $e, $list);
    }
    return false;
}
