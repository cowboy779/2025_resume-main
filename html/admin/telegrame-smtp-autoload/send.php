<?php

require 'init.php';

$prjcode = GetReqVal('prjcode');
$project = Helper::GetProjectData($prjcode);
// if (IsNullString($prjcode) || GetStrVal('prjcode', $project) != $prjcode) {
//     exit;
// }

$to_list = GetReqVal('to');
$subject = GetReqVal('subject');
$message = GetReqVal('message');

$attachment = null;
// if (array_key_exists('file', $_FILES)) {
//     $uploaddir = Config::$UPLOAD_DIR;
//     $attachment_filename = "af_" . time() . "_" . substr(md5(microtime(true)), 0, 8);
//     //$uploadfile = $uploaddir . basename($_FILES['file']['name']);
//     $uploadfile = $uploaddir . $attachment_filename;
//     if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
//         $attachment = [];
//         $attachment['name'] = $_FILES['file']['name'];
//         $attachment['size'] = $_FILES['file']['size'];
//         //$attachment['tmp_name'] = $_FILES['file']['tmp_name'];
//         $attachment['file'] = $attachment_filename;
//     }
// }

$fail = [];
$emailaddrs = explode(';', $to_list);
foreach ($emailaddrs as $email) {
    $to = trim($email);
    if (IsNullString($to)) {
        continue;
    }
    $datas = [];
    $datas['project'] = $project;
    $datas['to'] = $to;
    $datas['subject'] = $subject;
    $datas['message'] = $message;
    $datas['attachment'] = $attachment;
    
    $result = Helper::InsertNotification(json_encode($datas));
    
    if (is_array($result) && isset($result['data'])) {
        $datas['error_log'] = $result['data'];
    } else {
        $datas['error_log'] = $result;
    }

    if ($result <= 0) {
        $fail[] = $to;
    }
}

if (count($fail) > 0) {
    print(json_encode(['result' => 'fail', 'fail_list' => $fail]));
} else {
    print(json_encode(['result' => 'ok']));
}
exit;
