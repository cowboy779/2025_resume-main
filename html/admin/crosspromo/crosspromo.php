<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once 'common/init.php';


function makeResponse($code, $data = null) {
    return array('code' => $code, 'data' => $data);
}
function validateInput($input) {
    if (empty($input)) { // 빈 문자열 체크
        return false;
    }
    return preg_match('/^[a-zA-Z0-9-_.,]+$/', $input);
}



function createCrossPromoCoupons($couponId, $publisherId, $completedMissions, $couponKey) {
    SQLDBWrapper::GetSQLDB()->run("LOCK TABLES coupon_data_{$couponId} WRITE");

    $result = [];
    foreach ($completedMissions as $missionCode) {
        // 쿠폰 번호 생성
        $randomKey = mt_rand(1, mt_getrandmax()) . "_" . microtime(true) . "_" . $publisherId;
        $couponSeed = hexdec(substr(md5("{$couponKey}_{$randomKey}"), 0, 14)); // PHP 64bit 시스템
        $couponNumber = CreateCouponNumber($couponKey, $couponSeed);

        $insertData = array();
        $insertData['COUPON_NUMBER'] = $couponNumber;
        $insertData['PUBLISHER_ID'] = $publisherId;
        $insertData['MISSION_CODE'] = $missionCode;
        $insertData['PUBLISH_DATE'] = date("Y-m-d H:i:s", LTFunction::getCurrentTime());

        $insertResult = SQLDBWrapper::GetSQLDB()->insert("coupon_data_{$couponId}", $insertData);
        if ($insertResult == false) {
            $result = $missionCode;
            break;
        }
        else {
            // 발행된 쿠폰번호 로그
            GMGameLog::SendCrossPromoCouponPublishLog($publisherId, $couponId, $missionCode, $couponNumber);
            LTLog::printLog("[publish] publisher_id : {$publisherId}, coupon_id : {$couponId}, mission_code : {$missionCode}, coupon_number : {$couponNumber}", 'crosspromo');

            $result[$missionCode] = $couponNumber;
        }
    }
    SQLDBWrapper::GetSQLDB()->run("UNLOCK TABLES");
    return $result;
}


function getCrossPromoCouponData($couponId, $publisherId, $missionCodes) {
    if (strlen($publisherId) == 0) {
        return makeResponse(CouponResult::CROSSPROMO_WRONG_REQUEST, 'Empty publisher_id');
    }
    if (!validateInput($couponId)) {
        return makeResponse(CouponResult::CROSSPROMO_WRONG_REQUEST, "Invaild coupon_id($couponId)");
    }
    if (!validateInput($missionCodes)) {
        return makeResponse(CouponResult::CROSSPROMO_WRONG_REQUEST, 'Invalid mission_codes');
    }

    $missionCodeList = array_filter(array_map('trim', explode(",", $missionCodes)));
    if (count($missionCodeList) == 0) {
        return makeResponse(CouponResult::CROSSPROMO_WRONG_REQUEST, 'Invalid mission_codes');
    }

    $where = "COUPONID=:couponId";
    $bind = [":couponId" => $couponId];
    $couponData = SQLDBWrapper::GetSQLDB()->select(DBTable::COUPON_DATA, $where, $bind);

    if ($couponData == false) {
        return makeResponse(CouponResult::CROSSPROMO_INVALID_COUPON_ID, "Not found coupon_data(coupon_id : $couponId)");
    }

    // 크로스 프로모션 타입 인지 확인 필요.
    $_type = LTFunction::getNumber('TYPE', $couponData[0]);
    if ($_type != CouponType::CROSSPROMO_COUPON) { // 크로스 프로모션 타입이 아닌 경우
        return makeResponse(CouponResult::CROSSPROMO_INVALID_COUPON_ID, "Wrong cross promotion coupon type($_type)");
    }
    // 기간 체크 필요.
    $_start = LTFunction::getString('START', $couponData[0]);
    $_end = LTFunction::getString('END', $couponData[0]);

    if (strtotime($_start) > LTFunction::getCurrentTime() || strtotime($_end) <= LTFunction::getCurrentTime()) {
        return makeResponse(CouponResult::EXPIRE_COUPON, "start : $_start, end : $_end");
    }

    // 정지(차단)된 쿠폰인지 확인
    $block = LTFunction::getString('BLOCK', $couponData[0]);
    if ($block == 'Y') {
        return makeResponse(CouponResult::BLOCK_COUPON, "Blocked Coupon");
    }

    // 크로스 프로모션 쿠폰 발행
    $crossPromoData = json_decode(LTFunction::getString('MISSIONREWARD', $couponData[0]), true);
    $missionKeys = array_keys($crossPromoData);
    foreach ($missionCodeList as $missionCode) {
        if (in_array($missionCode, $missionKeys) == false) {
            return makeResponse(CouponResult::CROSSPROMO_INVALID_MISSION_CODE, "Invaild mission code($missionCode)");
        }
    }

    $where = "PUBLISHER_ID=:publisherId";
    $bind = [":publisherId" => $publisherId];
    $publishedCouponData = SQLDBWrapper::GetSQLDB()->select("coupon_data_{$couponId}", $where, $bind);

    $coupons = [];  // 기존에 발행된 쿠폰 리스트
    if ($publishedCouponData !== false && count($publishedCouponData) > 0) {
        for ($i = 0; $i < count($publishedCouponData); $i++) {
            $couponNumber = LTFunction::getString('COUPON_NUMBER', $publishedCouponData[$i]);
            $missionCode = LTFunction::getString('MISSION_CODE', $publishedCouponData[$i]);
            // 여기에서 미션코드 유효성 검사. (존재하는 미션 코드가 있어야 함)
            if (in_array($missionCode, $missionKeys) == false) {
                continue;
            }
            $coupons[$missionCode] = $couponNumber;
        }
    }

//    $coupons = array_intersect_key($coupons, array_flip($missionKeys));
    $pendingMissionCodeList = array_diff($missionCodeList, array_keys($coupons));
    if (count($pendingMissionCodeList) > 0) {
        $couponKey = LTFunction::getString('COUPONKEY', $couponData[0]);
        $result = createCrossPromoCoupons($couponId, $publisherId, $pendingMissionCodeList, $couponKey);
        if (is_string($result)) {
            return makeResponse(CouponResult::CROSSPROMO_ERROR_DB_INSERT, "Failed publish coupon (coupon_id : {$couponId}, mission_code : {$result}, publisher_id : {$publisherId})");
        }
        else {
            $coupons = array_merge($coupons, $result);
        }
    }

    if (count($coupons) == 0) {
        // 발행된 쿠폰이 없는 경우
        return makeResponse(CouponResult::CROSSPROMO_INVALID_COUPON_ID, "No coupons issued for this publisher_id($publisherId) and coupon_id($couponId)");
    }

    return makeResponse(CouponResult::OK, $coupons);
}



//======================================================================================================================
// 크로스 프로모션 쿠폰 데이터 조회
$publisherId = trim(LTFunction::getValueFromREQUEST('publisher_id'));
$couponId = trim(LTFunction::getValueFromREQUEST('coupon_id'));
$missionCodes = trim(LTFunction::getValueFromREQUEST('mission_codes'));

$response = getCrossPromoCouponData($couponId, $publisherId, $missionCodes);

//======================================================================================================================
// 응답 처리
$resultCode = LTFunction::getNumber('code', $response);
$resultData = LTFunction::getValue('data', $response);

$resp = [];
$resp['RESULT'] = $resultCode;
if ($resultCode != CouponResult::OK) {
    $resp['ERROR_DATA'] = $resultData;
    // 에러 로그
    GMErrorLog::SendCrossPromoCouponApiError($publisherId, $couponId, $missionCodes, $resultCode, $resultData);
}
else {
    $resp['COUPON_LIST'] = $resultData;
}

$responseBody = json_encode($resp);
print($responseBody);
LTLog::printLog("[response] {$responseBody}", 'crosspromo');
exit();
//======================================================================================================================
