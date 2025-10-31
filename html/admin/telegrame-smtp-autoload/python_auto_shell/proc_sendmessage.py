import os, sys

real_path = os.path.realpath(os.path.dirname(__file__))  
move_path = os.path.join(real_path, "..")  
run_path = os.path.abspath(move_path)  
sys.path.append(run_path)  

# print(f"Proc_send Current Run Path: {run_path}")

import json
from lib import *
from admin.admin_func import *
from admin.proc.telegram import *
from config import config

def write_send_msg_result(result, data, ipaddr):
    query = "INSERT INTO log(date, result, log, ipaddr) VALUES(NOW(), %s, %s, %s);"
    rdb.QueryWithCommit(query, [result, data, ipaddr])

def send_message(rowdata):
    msg_id = rowdata['id']

    deleted = rdb.QueryWithCommit("DELETE FROM noti_queue WHERE id = %s;", [msg_id])

    result = {"id": msg_id}
    log_data = {
        "deleted": deleted,
        "id": rowdata.get("id"),
        "date": rowdata.get("date"),
    }

    ipaddr = rowdata.get("ipaddr")

    try:
        data_set = json.loads(rowdata.get("datas", "{}"))  # JSON 문자열을 딕셔너리로 변환
    except json.JSONDecodeError:
        data_set = {}

    project = data_set.get("project", {})
    media = project.get("media")
    
    log_data["data"] = {key: data_set.get(key) for key in ["project", "to", "subject", "attachment"]}
    if media == "all":
        log_data["data"]["message"] = data_set.get("message")

    # 첨부 파일 처리
    attachment = data_set.get("attachment")
    attachment_filedata = None
    if attachment:
        attachment_filedata = {
            "name": attachment.get("name"),
            "tmp_name": f"{config.UPLOAD_DIR}{attachment.get('file')}"
        }

    result["project"] = project.get("name")

    # 이메일 및 텔레그램 메시지 전송
    to = data_set.get("to")
    subject = data_set.get("subject")
    message = data_set.get("message")

    user = get_user_data_by_email(to)
    if user:
        if media in ["all", "telegram"]:
            telegram = Telegram.send_message(user.get("telegram"), subject, message, attachment)

            try:
                log_data["telegram"] = json.loads(telegram)
                result["telegram"] = log_data["telegram"].get("ok")
            except json.JSONDecodeError:
                log_data["telegram"] = telegram

        if media in ["all", "email"]:
            result["email"] = smtp.send_mail(project.get("name"), to, user.get("name", ""), subject, message, attachment_filedata)
    else:
        result["email"] = smtp.send_mail(project.get("name"), to, "", subject, message, attachment_filedata)

    write_send_msg_result(
        json.dumps(result, ensure_ascii=False),
        json.dumps(log_data, ensure_ascii=False, default=str),  # datetime 변환
        ipaddr
    )

def main(nid):
    if nid > 0:
        result = rdb.QueryWithFetchAll("SELECT * FROM noti_queue WHERE id = %s;", [nid])
        if result: 
            data = result[0]
            send_message(data)

if __name__ == '__main__':
    if len(sys.argv) != 2:
        sys.exit()

    nid = int(sys.argv[1])
    main(nid)
