<?php
include 'config.php';

if (isset($_SERVER['SERVER_ADDR'])) {
    exit;
}
if ($_SERVER['USER'] != 'root') {
    print("'root' require.");
    exit;
}

ini_set('memory_limit', '512M');

chdir(dirname(__FILE__));

//$st = microtime(true);
//update_iplist_MAXMIND($ccList);
update_iplist_MAXMIND();
//print("\nTM:".(microtime(true) - $st)."\n");
exit;



function saveIpFile($cc, $iplist)
{
    ksort($iplist);
    
    $arrangedIpList = null;
    $curIp = null;
    foreach ($iplist as $ip) {
        if ($curIp == null) {
            $curIp = $ip;
        }
        else {
            //if ($curIp[1] + 1 == $ip[0]) {
            if ($curIp[1] + 1 == $ip[0]
                && ($cc != 'all' || $curIp[2] == $ip[2])) {
                $curIp[1] = $ip[1];
            }
            else {
                $arrangedIpList[] = $curIp;
                $curIp = $ip;
            }
        }
    }
    if ($curIp) {
        $arrangedIpList[] = $curIp;
    }

    ob_start();
    print("<?php\n");
    print('$list = ['."\n");
    foreach ($arrangedIpList as $ip) {
        if ($cc == 'all') {
            print(sprintf("[%s, %s, '%s'], // %s, %s\n", $ip[0], $ip[1], $ip[2], long2ip($ip[0]), long2ip($ip[1])));
        }
        else {
            print(sprintf("[%s, %s], // %s, %s\n", $ip[0], $ip[1], long2ip($ip[0]), long2ip($ip[1])));
        }
    }
    print("];\n");
    $filedata = ob_get_contents();
    ob_end_clean();

    if (!is_dir("./datas")) {
        mkdir("./datas");
    }
    file_put_contents("./datas/ip_{$cc}.php", $filedata);
}

function cidrToRange($cidr) {
    $range = [];
    $cidr = explode('/', $cidr);
    $range[0] = (ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1])));
    $range[1] = $range[0] + pow(2, (32 - (int)$cidr[1])) - 1;
    return $range;
}

//function update_iplist_MAXMIND($ccList)
function update_iplist_MAXMIND()
{
    $zipFileName = "/tmp/maxmind_".date('Ymd').".zip"; // Local Zip File Path
    if (file_exists($zipFileName) == false) {
        $zipFileHandle = fopen($zipFileName, "w");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::$IPCHECK_URL );
        curl_setopt($ch, CURLOPT_FILE, $zipFileHandle);
        //$result = curl_exec($ch);
        curl_exec($ch);
        //var_dump(curl_getinfo($ch));
        curl_close($ch);
        fclose($zipFileHandle);
    }
    
    $zip = new ZipArchive();
    $res = $zip->open($zipFileName);
    $countryData = null;
    $ipblockData = null;
    
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if (basename($name) == 'GeoIP2-Country-Locations-en.csv') {
            $countryData = $zip->getFromName($name);
        }
        if (basename($name) == 'GeoIP2-Country-Blocks-IPv4.csv') {
            $ipblockData = $zip->getFromName($name);
        }
    }
    $zip->close();
    
    //==========================================================================
    
    $datas = explode("\n", $countryData);

    $geonameList = [];
    foreach ($datas as $data) {
        $vals = str_getcsv($data);
        if ($vals[0] == "geoname_id") {
            continue;
        }
        if (isset($vals[4]) == false || strlen($vals[4]) != 2) {
            continue;
        }
        $geonameList[$vals[0]] = strtolower($vals[4]);
    }

    //==========================================================================

    $datas = explode("\n", $ipblockData);

    $list = [];
    $all = [];
    foreach ($datas as $data) {
        $vals = str_getcsv($data);
        if (count($vals) < 3 || $vals[0] == "network") {
            continue;
        }

        //$vals[1] => geoname_id
        //$vals[2] => registered_country_geoname_id
        if (array_key_exists($vals[1], $geonameList) == false) {
            $geonameList[$vals[1]] = "xx";
        }
        $cc = $geonameList[$vals[1]];

        $range = cidrToRange($vals[0]);
        $list[$cc][$range[0]] = $range;
        $all[$range[0]] = [$range[0], $range[1], $cc];
//        if (in_array($cc, $ccList) || in_array('all', $ccList)) {
//            $range = cidrToRange($vals[0]);
//            $list[$cc][$range[0]] = $range;
//        }
//        if (in_array('all', $ccList)) {
//            $range = cidrToRange($vals[0]);
//            $all[$range[0]] = [$range[0], $range[1], $cc];
//        }
    }
    
    foreach ($list as $cc => $iplist) {
        saveIpFile($cc, $iplist);
    }
//    if (in_array('all', $ccList)) {
//        saveIpFile('all', $all);
//    }
    saveIpFile('all', $all);
}