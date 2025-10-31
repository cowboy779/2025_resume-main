# -*- coding: utf-8 -*-
import requests
from admin.admin_func import *
from admin.admin_conf import *

def telegram():
    action = GetReqVal('action')
    msg = ""

    if action == 'get_telegram_webhook':
        result = TelegramAPI.get_webhook_info()
        msg = format_response(action, result)
        
    if action == 'set_telegram_webhook':
        result = TelegramAPI.set_webhook()
        msg = format_response(action, result)
    
    if action == 'delete_telegram_webhook':
        result = TelegramAPI.delete_webhook()
        msg = format_response(action, result)

    users = rdb.QueryWithFetchAll('SELECT * FROM user order by email;')
    accountData = GetWemadeMaxAccountData()
    
    return locals()



def format_response(action, response):
    if isinstance(response, str):
        response = json.loads(response)

    response_str = json.dumps(response, indent=4, ensure_ascii=False)
    response_str = response_str.replace("\n", "<br>").replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;")

    return f"============================= [ {action} ] =============================<br>" + response_str


def call_url(url, headers=None, post_data=None, timeout=3):
    try:
        if post_data:
            response = requests.post(url, headers=headers, data=post_data, timeout=timeout)
        else:
            response = requests.get(url, headers=headers, timeout=timeout)
        
        return response.text  

    except requests.exceptions.RequestException as e:
        print(f"Error: {e}")
        return None

def get_user_data_by_id(user_type, user_id):
    query = "SELECT * FROM user WHERE {} = %s;".format(user_type)
    args = [user_id]
    result = rdb.QueryWithFetchAll(query, args)
    return result[0] if result else None

def get_user_data_by_email(email):
    query = "SELECT * FROM user WHERE email = %s;"
    args = [email]
    result = rdb.QueryWithFetchAll(query, args)
    return result[0] if result else None



class TelegramAPI:
    BASE_URL = f"https://api.telegram.org/bot{config.TELEGRAM_TOKEN}/"

    @staticmethod
    def get_webhook_info():
        return TelegramAPI._run("getWebhookInfo")

    @staticmethod
    def set_webhook():
        url = GetReqVal("url")
        return TelegramAPI._run("setWebhook", {"url": url})

    @staticmethod
    def delete_webhook():
        return TelegramAPI._run("deleteWebhook")

    @staticmethod
    def _run(cmd, data=None):
        api_url = TelegramAPI.BASE_URL + cmd
        return call_url(api_url, post_data=data)
    
class Telegram:
    @staticmethod
    def command(user_id, text):
        command_type = "telegram"
        result = Telegram.process_command(command_type, user_id, text)
        if result:
            Telegram.send_message(user_id, None, result)
    
    @staticmethod
    def send_message(chat_id, subject, message, attachment=None):
        if not chat_id or not str(chat_id).isdigit():
            return

        text = f"<b>[{subject}]</b>\n{message}" if subject else message

        if attachment:
            file_name = GetStrVal("name", attachment)
            file_size = GetIntVal("size", attachment)
            text += f"\n * 첨부파일 : {file_name} ({file_size} bytes)\n * 첨부파일은 이메일에서 확인 가능합니다."

        data = {
            "chat_id": chat_id,
            "text": text,
            "parse_mode": "HTML"
        }
        return TelegramAPI._run("sendMessage", data)
    
    @staticmethod
    def process_command(command_type, user_id, text):
        inputs = text.split()
        cmd = GetStrVal(0, inputs)

        if cmd == "/help":
            return "\n".join([
                f"/req [이메일] : {command_type} 등록코드 요청",
                f"/join [등록코드] : {command_type} 등록",
                f"/info : 정보 확인(등록 완료된 사용자만)"
            ])
        
        elif cmd == "/req":
            email = GetStrVal(1, inputs)
            user = get_user_data_by_id(command_type, user_id)
            
            if user:
                return f"already registered user({GetStrVal('email', user)}, {GetStrVal('name', user)})"
            
            name = get_wemade_max_account_name(email)

            if not name:
                user = get_user_data_by_email(email)

                if not user:
                    return "등록된 이메일이 아닙니다."
                
                name = GetStrVal("name", user)
            
            checkcode = make_check_code(command_type, user_id, email)
            message = f"{command_type} checkcode : {checkcode}\n[input {command_type} messenger] /join {checkcode}"
            
            if smtp.send_mail("NotiBot", email, name, f"[LightCON NotiBot] {command_type} checkcode", message):
                return f"{command_type} id join checkcode sent."

        elif cmd == "/join":
            checkcode = GetStrVal(1, inputs)
            email = get_email_by_check_code(checkcode, command_type, user_id)
            
            if email is None:
                return None
            
            user = get_user_data_by_email(email)

            if not user:
                name = get_wemade_max_account_name(email)

                insert_query = "INSERT INTO user (email, name) VALUES (%s, %s)"
                if not rdb.QueryWithCommit(insert_query, (email, name)):
                    return "error occurred."
            
            update_query = f"UPDATE user SET {command_type} = %s WHERE email = %s"
            if rdb.QueryWithCommit(update_query, (user_id, email)):
                return f"{command_type} id updated."
        
        elif cmd == "/info":
            user = get_user_data_by_id(command_type, user_id)

            if not user:
                return f"not {command_type} registered user."
            
            return "\n".join([
                f"name : {GetStrVal('name', user)}",
                f"email : {GetStrVal('email', user)}"
            ])
        
        return None