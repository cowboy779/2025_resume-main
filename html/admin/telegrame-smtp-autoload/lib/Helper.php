<?php
include_once __DIR__ . '/../config.php';

class Helper
{
    static function GetProjectData($prjcode) {
        return GetObjVal(0, DB()->GetAllData(DB()->QuerySafe('select * from project where prjcode=? and active=1;', [$prjcode])));
    }

    /*
    static function GetUserData($userkey) {
        return GetObjVal(0, DB()->GetAllData(DB()->QuerySafe('select * from user where userkey=?;', [$userkey])));
    }
    */
    static function GetUserDataByEmail($email) {
        return GetObjVal(0, DB()->GetAllData(DB()->QuerySafe('select * from user where email=?;', [$email])));
    }
    static function GetUserDataById($type, $id) {
        return GetObjVal(0, DB()->GetAllData(DB()->QuerySafe("select * from user where {$type}=?;", [$id])));
    }

    static function WriteLog($data) {
        return DB()->QuerySafe('insert into log(date, ipaddr, log) values(NOW(), ?, ?);', [GetClientIP(), $data]);
    }

    static function InsertNotification($datas) {
        $result = DB()->QuerySafe('insert into noti_queue(date, ipaddr, datas) values(NOW(), ?, ?);', [GetClientIP(), $datas]);
        return DB()->GetRowCount($result);
    }
    
    static function GetNotification($id) {
        return GetObjVal(0, DB()->GetAllData(DB()->QuerySafe('select * from noti_queue where id=?;', [$id])));
    }
    static function GetNotiQueueDatas() {
        return DB()->GetAllData(DB()->QuerySafe('select * from noti_queue;'));
    }
    static function WriteSendMsgResult($result, $data, $ipaddr) {
        return DB()->QuerySafe('insert into log(date, result, log, ipaddr) values(NOW(), ?, ?, ?);', [$result, $data, $ipaddr]);
    }
    
    static function sendMail($from, $to, $name, $subject, $message, $attachment=null)
    {
        //require './mailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = Config::$SMTP_SERVER;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = Config::$SMTP_ACCOUNT;                 // SMTP username
        $mail->Password = Config::$SMTP_PASSWORD;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom(Config::$SMTP_FROM, "라이트컨 {$from}");
        $mail->addAddress($to, $name);     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        //$mail->isHTML(true);                                  // Set email format to HTML
        if ($attachment) {
            $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        $mail->Subject = $subject;
        $mail->Body    = $message;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//        $mail->SMTPOptions = Config::$SMTP_OPTIONS;
        if(!$mail->send()) {
            //echo 'Message could not be sent.';
            return '[Mailer Error]' . $mail->ErrorInfo;
        }
        return true;
    }
    
    /*
    static private function makeCheckCodeUniqueKey($type, $id, $email, $expTm) {
        //$expTm = time() + 300;
        //return base62_encode($expTm).'#'.substr(sha1("joymaxnotifierbot***{$type}***{$id}***{$email}***{$expTm}"), 0, 8);
        //return substr(sha1("joymaxnotifierbot***{$type}***{$id}***{$email}***".date('YmdH')), 0, 8);
        //return substr(sha1("joymaxnotifierbot***{$type}***{$id}***{$email}***{$expTm}"), 0, 8);
        
    }
    */
    static private function makeCheckCode($type, $id, $email) {
        $expTm = time() + 300;
        $data = implode('|', [$expTm, $type, $id, $email]);
        $checkcode = substr(str_shuffle(base62_encode(hex2bin(md5($data.mt_rand(0, 99999999))))), 0, 20);
        $filepath = Config::$UPLOAD_DIR."rc_{$checkcode}";
        if (file_put_contents($filepath, $data)) {
            return $checkcode;
        }
        return null;
        //return base62_encode($expTm).'#'.self::makeCheckCodeUniqueKey($type, $id, $email, $expTm);
        //return substr(sha1("joymaxnotifierbot***{$type}***{$id}***{$email}***".date('YmdH')), 0, 8);
    }
    /*
    static private function isValidCheckCode($checkcode, $type, $id, $email) {
        $datas = explode('#', $checkcode);
        $expTm = base62_decode(GetStrVal(0, $datas));
        if ($expTm < time()) return false;
        return (GetStrVal(1, $datas) == self::makeCheckCodeUniqueKey($type, $id, $email, $expTm));
    }
    */
    static private function getEmailByCheckCode($checkcode, $type, $id) {
        $filepath = Config::$UPLOAD_DIR."rc_{$checkcode}";
        $data = @file_get_contents($filepath);
        if ($data == false) return null;
        $datas = explode('|', $data);
        if (GetIntVal(0, $datas) < time()) return null;
        if (GetStrVal(1, $datas) != $type) return null;
        if (GetIntVal(2, $datas) != $id) return null;
        unlink($filepath);
        return GetStrVal(3, $datas);
        
        //$datas = explode('#', $checkcode);
        //$expTm = base62_decode(GetStrVal(0, $datas));
        //if ($expTm < time()) return false;
        //return (GetStrVal(1, $datas) == self::makeCheckCodeUniqueKey($type, $id, $email, $expTm));
    }

    static function processCommand($type, $id, $text) {
        $inputs = explode(' ', $text);

        $cmd = GetStrVal(0, $inputs);

        if ($cmd == '/help') {
            $list = null;
            $list[] = "/req [이메일] : {$type} 등록코드 요청";
            $list[] = "/join [등록코드] : {$type} 등록";
            $list[] = "/info : 정보 확인(등록 완료된 사용자만)";
            //$list[] = "/login : 로그인 비밀번호 요청(등록 완료된 사용자만)";
            return implode("\n", $list);
        }
        else if ($cmd == '/req') {
            $email = GetStrVal(1, $inputs);
            $user = self::GetUserDataById($type, $id);
            if ($user) {
                $data = implode(', ', [GetStrVal('email', $user), GetStrVal('name', $user)]);
                return "already registered user({$data})";
            }

            $name = self::getWemadeMaxAccountName($email);
            if (!$name) {
                $user = self::GetUserDataByEmail($email);
                if (!$user) {
                    return '등록된 이메일이 아닙니다.';
                }
                $name = GetStrVal('name', $user);
            }

            $checkcode = self::makeCheckCode($type, $id, $email);
            $message = "{$type} checkcode : {$checkcode}\n[input {$type} messenger] /join {$checkcode}";
            if (self::sendMail('NotiBot', $email, $name, "[LightCON NotiBot] {$type} checkcode", $message) === true) {
                return "{$type} id join checkcode sent.";
            }
        }
        else if ($cmd == '/join') {
            $checkcode = GetStrVal(1, $inputs);
            $email = self::getEmailByCheckCode($checkcode, $type, $id);
            if ($email == null) return null;
            
            $user = self::GetUserDataByEmail($email);
            if (!$user) {
                $name = self::getWemadeMaxAccountName($email);
                //if ($name == null) return null;

                if (DB()->GetRowCount(DB()->QuerySafe('insert into user(email, name) values(?, ?);', [$email, $name])) <= 0) {
                    return 'error occured.';
                }
            }
            
            $result = DB()->QuerySafe("update user set {$type}=? where email=?;", [$id, $email]);
            if (DB()->GetRowCount($result)) {
                return "{$type} id updated.";
            }
        }
        else if ($cmd == '/info') {
            $user = self::GetUserDataById($type, $id);
            if (!$user) return "not {$type} registered user.";
            $list = null;
            //$list[] = "userkey : ".GetStrVal('userkey', $user);
            $list[] = "name : ".GetStrVal('name', $user);
            $list[] = "email : ".GetStrVal('email', $user);
            return implode("\n", $list);
        }
        /*
        else if ($cmd == '/login') {
            $user = self::GetUserDataById($type, $id);
            if (GetIntVal('level', $user) < 2) return null;

            $email = GetStrVal('email', $user);
            $passwd = substr(sha1("".rand()), 0, 8);
            $result = DB()->QuerySafe("update user set passwd=? where email=? and {$type}=?;", [sha1($passwd), $email, $id]);
            if (DB()->GetRowCount($result)) {
                return "Password : {$passwd}";
            }
        }
        */
        return null;
    }
    
    static function getWemadeMaxAccountName($email) {
        //$login = false;
        $name = null;

        // LDAP Auth
        $ldapserver = Config::$LDAP_SERVER;
        $ldaptree = Config::$LDAP_TREE;
        $ldapconn = ldap_connect($ldapserver);
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $user_search = ldap_search($ldapconn, $ldaptree, "(mail=$email)");

        if ($user_search) {
            $user_entry = ldap_first_entry($ldapconn, $user_search);
            //$userId = GetStrVal(0, ldap_get_values($ldapconn, $user_entry, "uid"));
            $name = GetStrVal(0, ldap_get_values($ldapconn, $user_entry, "gecos"));
            //$user_dn = ldap_get_dn($ldapconn, $user_entry);
            //$login = ldap_bind($ldapconn, $user_dn, $passwd);
        }

        ldap_close($ldapconn);
        
        return $name;
        //if ($login && DB_GetUserDataByEmail($email) == null) { // UserDB에 새로운 사용자로 등록
        //    DB_InsertNewUser($userId, '', $email, $name);
        //}

        //return $login;
    }
    
    static function getWemadeMaxAccountDatas()
    {
        // LDAP 계정 리스트 가져오기
        $ldapserver = Config::$LDAP_SERVER;
        $ldaptree = Config::$LDAP_TREE;

        $ldap_accounts = [];
        $ldapconn = ldap_connect($ldapserver);
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldapdata = null;
        $ldapbind = @ldap_bind($ldapconn);

        if ($ldapbind) {
            //$result = ldap_search($ldapconn, $ldaptree, "(memberOf=cn=joymax members,cn=groups,dc=joymax,dc=com)");
            $result = ldap_search($ldapconn, $ldaptree, "(objectClass=*)");
            
            if ($result) {
                $ldapdata = ldap_get_entries($ldapconn, $result);
            }
        }
        if ($ldapdata) {
            for ($i = 0; $i < $ldapdata["count"]; $i++) {
                if (isset($ldapdata[$i]["mail"][0])) {
                    $ldap_accounts[$ldapdata[$i]["mail"][0]] = $ldapdata[$i]["gecos"][0];
                    //$name = GetStrVal(0, ldap_get_values($ldapconn, $user_entry, "gecos"));
                }
            }
        }
        ldap_close($ldapconn);
        return $ldap_accounts;
    }
}