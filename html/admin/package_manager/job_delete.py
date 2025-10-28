import os, sys
run_path = os.path.abspath(os.path.join(os.path.realpath(os.path.dirname(__file__)), '..', '..'))
sys.path.append(run_path)
os.chdir(run_path)


import time
import datetime
from pkgmanager.config import Config
import pkgmanager.dbhelper as db


# # 설정 및 유틸 함수
# EXCEPT_LIST = ['real']  # "real"은 삭제하지 않음
# EXCEPT_MIN_STORE_CNT = 20  # "real"은 최소 20개 보관
# EXCEPT_MIN_STORE_TIME = time.time() - (86400 * 30)  # 30일 이상 지난 항목 대상

# 30일간은 무조건 보관, 20개는 무조건 보관
MIN_STORE_CNT = 20  # 최소 20개 보관
MIN_STORE_TIME = time.time() - (86400 * 60)  # 30일 이상 지난 항목 대상

# 180일이 안지난 경우, 50개 까지만 보관
EXP_STORE_TIME = time.time() - (86400 * 180)  # 180일 이상 지난 항목 대상
MAX_STORE_CNT = 50  # 180일 이내는 최대 50개 보관



# 메인 로직
def remove_old_packages():
    total_size = 0
    total_count = 0

    prj_data_list = db.get_projects()

    for prj_data in prj_data_list:
        project_id = prj_data['project_id']
        pkg_data_list = db.get_packages(project_id)

        list_by_type = {}
        for pkg_data in pkg_data_list:
            code = f"{pkg_data['store_type']}_{pkg_data['build_type']}"
            list_by_type.setdefault(code, []).append(pkg_data)

        for grp_code, grp_data_list in list_by_type.items():
            # grp_info = grp_code.split('_')
            # if grp_info[1] in EXCEPT_LIST:
            #     continue
            #
            min_cnt = MIN_STORE_CNT
            # if grp_info[1] in EXCEPT_LIST:
            #     min_cnt = EXCEPT_MIN_STORE_CNT
            if len(grp_data_list) <= min_cnt:
                continue

            max_cnt = len(grp_data_list)
            for i in range(min_cnt, max_cnt):
                pkg_data = grp_data_list[i]
                pkg_index = pkg_data['idx']
                pkg_date_str = pkg_data['date'].strftime("%Y-%m-%d")

                try:
                    pkg_date = time.mktime(datetime.datetime.strptime(pkg_date_str, "%Y-%m-%d").timetuple())
                except ValueError:
                    continue

                if pkg_date > MIN_STORE_TIME:
                    continue

                if pkg_date > EXP_STORE_TIME and i < MAX_STORE_CNT: # 180일이 안지난 경우, 50개 까지만 보관
                    continue

                file_name = pkg_data['filename']
                file_path = os.path.join(Config.PKG_FILE, project_id, grp_code, file_name)

                symbol_path = None
                symbol_name = pkg_data['symbol']
                if symbol_name:
                    symbol_path = os.path.join(Config.PKG_FILE, project_id, grp_code, symbol_name)

                log_msg = f"Delete Package({pkg_index}) [{file_name} / {pkg_date_str}] ({project_id}, {grp_code})"
                if symbol_path:
                    log_msg += f" [Symbol;{symbol_name}]"
                total_size += os.path.exists(file_path) and os.path.getsize(file_path) or 0
                total_count += 1

                # DB 삭제 및 로그 추가
                if db.delete_package(pkg_index):
                    db.add_log('script', 'system', log_msg)
                    try:
                        os.remove(file_path)
                    except OSError:
                        db.add_log('script', '[*system]', f"FAILED: Delete PackageFile({file_path})")

                    if symbol_path and os.path.isfile(symbol_path):
                        try:
                            os.remove(symbol_path)
                        except OSError:
                            db.add_log('script', '[*system]', f"FAILED: Delete SymbolFile({symbol_path})")

    # print(f"Total deleted size: {total_size} bytes")
    # print(f"Total deleted count: {total_count}")


def remove_old_sessions():
    exptm = int(datetime.datetime.now().timestamp()) - 86400
    db.delete_expired_sessions(exptm)


if __name__ == "__main__":
    remove_old_packages()
    remove_old_sessions()
