import os, sys

real_path = os.path.realpath(os.path.dirname(__file__)) #현재 스크립트 절대경로
move_path = os.path.join(real_path, '..')  #상위 디렉토리로
run_path = os.path.abspath(move_path) #절대경로로 만듬
sys.path.append(run_path) #모듈 검색 경로에 상위 디렉토리로 추가

# print(f"Current Run Path: {run_path}")

import time
import asyncio
import fcntl
from lib import *
from admin.admin_func import *
from config import config

LOCK_FILE = "/tmp/job_notiqueue.lock"


def check_flock():
    global LOCK_FILE
    lockfile = open(LOCK_FILE, "w")
 
    try:
        fcntl.flock(lockfile, fcntl.LOCK_EX | fcntl.LOCK_NB)
    except BlockingIOError:
        sys.exit(0)

async def send_proc(msg_id):
    if msg_id is not None:
        # process = await asyncio.create_subprocess_exec("python", "proc_sendmessage.py", str(msg_id))
        # await process.wait()
        try:
            process = await asyncio.create_subprocess_exec("python", "proc_sendmessage.py", str(msg_id),
                                                       stdout=asyncio.subprocess.PIPE,
                                                       stderr=asyncio.subprocess.PIPE)
            stdout, stderr = await process.communicate()

            # stdout 성공코드, stderr 실패코드
            if process.returncode != 0:
                print(f"[ERROR] notiqueue_id={msg_id} 실패! stderr: {stderr.decode()}")

        except Exception as e:
            print(f"[EXCEPTION] notiqueue_id={msg_id} 실행 중 오류 발생: {e}")

async def send_queue_proc():
    noti_data = rdb.QueryWithFetchAll("SELECT * FROM noti_queue;")

    if not noti_data:
        return

    tasks = set()
    task_count = 6

    for data in noti_data:
        msg_id = data.get("id")

        if msg_id is not None:
            task = asyncio.create_task(send_proc(msg_id))
            tasks.add(task)

        if len(tasks) >= task_count:
            # done: 완료된 task, pending: 아직 실행 중인 task
            done, pending = await asyncio.wait(tasks, return_when=asyncio.FIRST_COMPLETED)
            tasks.difference_update(done)  # 완료된 task 제거

    if tasks:
        await asyncio.gather(*tasks)

async def main():
    check_flock() 
    start_time = time.time()

    while time.time() - start_time < 58:
        await send_queue_proc()
        await asyncio.sleep(1)

if __name__ == "__main__":
    asyncio.run(main())