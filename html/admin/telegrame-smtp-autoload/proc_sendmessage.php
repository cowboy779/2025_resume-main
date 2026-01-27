<?php

require 'init.php';

if ($argc != 2) {
    exit;
}

$nid = GetIntVal(1, $argv);
if ($nid > 0) {
    $data = Helper::GetNotification($nid);
    if ($data) {
        SendMessage($data);
    }
}

function SendMessage($rowdata)
{
    $id = GetIntVal('id', $rowdata);
    $args = [];
    $args[] = $id;
    $deleteResult = DB()->QuerySafe('delete from noti_queue where id=?;', $args);
    $deleted = DB()->GetRowCount($deleteResult);
    
    $result = [];
    $logData = [];

    $logData['deleted'] = $deleted;
    foreach (['id', 'date'] as $key) {
        $logData[$key] = GetObjVal($key, $rowdata);
    }

    $ipaddr = GetStrVal('ipaddr', $rowdata);
    $result['id'] = GetIntVal('id', $rowdata);

    //noti_queue 테이블의 datas 컬럼에 > $datas 내용
    $datas = json_decode(GetStrVal('datas', $rowdata), true);
    $project = GetObjVal('project', $datas);
    $media = GetStrVal('media', $project);
    
    //if ($media != 'email') $media = 'all';

    if ($media == 'all' || $media == 'email' || $media == 'telegram') {
        $to = GetStrVal('to', $datas);
        $subject = GetStrVal('subject', $datas);
        $message = GetStrVal('message', $datas);

        $attachment = GetObjVal('attachment', $datas);
        $logData['data'] = [];
        //foreach (['project', 'to', 'subject', 'message', 'attachment'] as $key) {

        foreach (['project', 'to', 'subject', 'attachment'] as $key) {
            $logData['data'][$key] = GetObjVal($key, $datas);
        }
        if ($media == 'all') {
            $logData['data']['message'] = GetObjVal('message', $datas);
        }

        //파일첨부
        $attachment_filedata = null;
        if ($attachment) {
            $uploaddir = Config::$UPLOAD_DIR;
            $attachment_filedata['name'] = $attachment['name'];
            $attachment_filedata['tmp_name'] = $uploaddir . $attachment['file'];
        }

        $result['project'] = GetStrVal('name', $project);
        $user = Helper::GetUserDataByEmail($to);
        
        if ($user) {
            //$logData['user'] = $user;
            if ($media == 'all' || $media == 'telegram') {
                $telegram = Telegram::sendMessage(GetStrVal('telegram', $user), $subject, $message, $attachment);

                $logData['telegram'] = $telegram;
                $result['telegram'] = GetObjVal('ok', json_decode($telegram, true));
            }
            if ($media == 'all' || $media == 'email') {
                $result['email'] = Helper::sendMail(GetStrVal('name', $project), $to, GetStrVal('name', $user), $subject, $message, $attachment_filedata);

            }
            //$result['facebook'] = Facebook::sendMessage(GetStrVal('facebook', $user), $subject, $message);
        } else {
            $result['email'] = Helper::sendMail(GetStrVal('name', $project), $to, '', $subject, $message, $attachment_filedata);
        }
    }
    Helper::WriteSendMsgResult(json_encode($result, JSON_UNESCAPED_UNICODE), json_encode($logData, JSON_UNESCAPED_UNICODE), $ipaddr);
}
