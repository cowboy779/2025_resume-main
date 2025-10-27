import os, sys
run_path = os.path.abspath(os.path.join(os.path.realpath(os.path.dirname(__file__)), '..'))
sys.path.append(run_path)  

os.chdir(run_path)     
print(f"Current Run Path: {run_path}")

from admin.admin_func import *
from lib import *
from config import config

# 데이터 캐시 변수
ALL_USER_DATAS = None
ALL_DEPT_DATAS = None
ALL_MEMBER_DATAS = None


# 
def main():
    ldaprootconn = ConnectToLdapRoot()

    gw_accounts = GetWemadeMaxAccountData()
    ldap_accounts = GetWemadeMaxLdapAccountData(ldaprootconn)

    if not gw_accounts:
        result_text = "[그룹웨어계정 리스트 획득 오류]\n"
        
        for email in config.ADMIN_LIST:
            result = SendMail(email, 'Wemade Max 개발계정 자동 작업 처리 결과', result_text)
        exit()

    # 이메일 리스트 추출
    gw_email = list(gw_accounts.keys())
    ldap_email = list(ldap_accounts.keys())

    # 신규 입사자, 퇴사자, 팀명 변경 사용자 리스트 계산
    # GW 그룹웨어 기준으로 처리됨
    new_user_list = list(set(gw_email) - set(ldap_email))  # 신규입사자 > 그룹웨어에만 존재하고 LDAP에는 없음(이메일)
    del_user_list = list(set(ldap_email) - set(gw_email))  # 퇴사자 > LDAP에만 존재하는 그룹웨어에는 없음(이메일)
    upd_dept_list = GetChangedDeptList(gw_accounts, ldap_accounts)  # 팀명이 바뀐 사용자들
    
    # 퇴사자가 너무 많을 경우
    if len(del_user_list) > 30:
        result_text = "[Wemade Max 개발계정 LDAP계정 삭제 리스트 오류(너무 많은 인원)]\n"
        result_text += json.dumps(del_user_list, ensure_ascii=False, indent=4)
        
        for email in config.ADMIN_LIST:
            result = SendMail(email, 'Wemade Max 개발계정 자동 작업 처리 결과', result_text)
        exit()


    #======================================================================================================================

    # 새로운 사용자 추가
    new_user_result = []
    for email in new_user_list:
        data = gw_accounts.get(email, {})
        new_user_result.append(AddNewUser(ldaprootconn, True, email, data.get('name'), data.get('code'), data.get('dept')))

    # 퇴사한 사용자 삭제
    del_user_result = []
    for email in del_user_list:
        del_user_result.append(DeleteUser(ldaprootconn, email))

    # 부서 변경된 사용자 업데이트
    upd_dept_result = []
    for email, dept in upd_dept_list.items():
        old_dept = ldap_accounts.get(email, {}).get('dept')
        upd_dept_result.append(UpdateUserDept(ldaprootconn, email, dept, old_dept))
    

    #======================================================================================================================

    # 현재 GW 그룹데이터 및 멤버, LDAP 그룹데이터 및 멤버 비교
    # return {'기술지원팀_기술지원테스트': ['test1', 'test2', 'test2']}
    gw_groups = GetWemadeMaxGroupData()  
    ldap_groups = GetWemadeMaxLdapGroupData(ldaprootconn)
    
    group_result = []

    # GW 그룹을 기준으로 LDAP와 비교
    if gw_groups:
        for deptName, gwMemberUids in gw_groups.items():
            ldapMemberUids = ldap_groups.get(deptName, [])

            # GW에 팀이 추가된 경우 (LDAP에 없음)
            if deptName not in ldap_groups:
                group_result.append(AddNewGroup(ldaprootconn, deptName))

            # GW에는 멤버로 있는데 LDAP에 없는 경우 → 추가
            for uid in gwMemberUids:
                if uid not in ldapMemberUids:

                    result = "true" if AddGroupUser(ldaprootconn, deptName, uid) else "false"
                    # 받은 메일안의 개발계정 그룹 변경
                    group_result.append(f"addmember[{deptName}] uid[{uid}] : {result}")

        # LDAP 그룹을 기준으로 GW와 비교
        for deptName, ldapMemberUids in ldap_groups.items():
            gwMemberUids = gw_groups.get(deptName, [])

            # GW에서 삭제된 팀 (LDAP에는 있는데 GW에는 없음) → 삭제
            if deptName not in gw_groups:
                group_result.append(DeleteGroup(ldaprootconn, deptName))
            else:
                # LDAP에는 있는데 GW에는 없는 멤버 → 삭제
                for uid in ldapMemberUids:
                    if uid not in gwMemberUids:

                        group_dn = f"cn={deptName},cn=groups,{config.LDAP_BASE_DN}"
                        result = "true" if DeleteGroupUser(ldaprootconn, group_dn, uid) else "false"
                        
                        # 받은 메일안의 개발계정 그룹 변경
                        group_result.append(f"deletemember[{deptName}] uid[{uid}] : {result}")

    # LDAP 연결 종료
    ldaprootconn.unbind()

    # 모든 결과가 비어 있으면 종료
    if not (new_user_result or del_user_result or upd_dept_result or group_result):
        exit()

    result_text = "[Wemade Max 개발계정 등록]\n"
    result_text += json.dumps(new_user_result, indent=4, ensure_ascii=False)
    result_text += "\n\n\n[Wemade Max 개발계정 삭제]\n"
    result_text += json.dumps(del_user_result, indent=4, ensure_ascii=False)
    result_text += "\n\n\n[Wemade Max 개발계정 팀 변경]\n"
    result_text += json.dumps(upd_dept_result, indent=4, ensure_ascii=False)
    result_text += "\n\n\n[Wemade Max 개발계정 그룹 변경]\n"
    result_text += json.dumps(group_result, indent=4, ensure_ascii=False)

    # 이메일 발송 및 해당 관리자 리스트만 Send
    for email in config.ADMIN_LIST:  
        SendMail(email, "Wemade Max 개발계정 자동 작업 처리 결과", result_text)

    exit()

#======================================================================================================================

def GetWemadeMaxGroupData():
    users = GetAllUserData()
    members = GetAllMemberData()

    def GetMembers(dept_id, members):
        return [member["user_id"] for member in members if member["dept_id"] == dept_id]

    result = rdb.QueryWithFetchAll("SELECT * FROM depts;")
    
    groups = {data["id"]: data for data in result}

    # 부모-자식 관계 설정
    # 그룹관리 처럼 트리구조로 변형
    for data in groups.values():
        data["parent"] = None
        data["children"] = []

    for data in groups.values():
        if data["parent_id"]:
            data["parent"] = groups[data["parent_id"]]
            groups[data["parent_id"]]["children"].append(data)

    all_data = []
    
    for dept in groups.values():
        if len(dept["children"]) > 0 or dept["parent"] is None:
            continue

        # detp 실_팀 으로 데이터 가공 > 기술전략실_기술지원팀
        emailList = []
        deptName = f"{dept['parent']['name']}_{dept['name']}" if dept["parent"]["parent"] else dept["name"]

        # 해당실의 리더
        if dept["parent"]["parent"] and dept["parent"].get("leader_id"):
            emailList.append(users.get(dept["parent"]["leader_id"], {}).get("email"))

        # 해당팀의 리더
        if dept.get("leader_id"):
            emailList.append(users.get(dept["leader_id"], {}).get("email"))

        # 팀에 해당하는 멤버
        for user_id in GetMembers(dept["id"], members):
            emailList.append(users.get(user_id, {}).get("email"))

        emailList = list(filter(None, set(emailList)))  # 중복 제거 및 None 값 제거
        all_data.append({"name": deptName, "emails": emailList})
    
    gw_groupdatas = {}
    for group in all_data:
        # name:기술지원실_기술지원팀
        deptName = group["name"]
        # 다수의 @wemade.com 제거
        uids = [email.split("@")[0].lower() for email in group["emails"]]

        # {'기술지원팀_기술지원테스트': ['test1', 'test2']}
        if deptName and uids:
            gw_groupdatas[deptName] = uids

    return gw_groupdatas

def GetWemadeMaxLdapGroupData(ldaprootconn):
    search_base = f"cn=groups,{config.LDAP_BASE_DN}"
    search_filter = "(description={})".format(config.AUTO_GROUP_DESC)  
    
    ldaprootconn.search(search_base, search_filter, attributes=['cn', 'memberuid'])

    ldap_groupdatas = {}
    
    if ldaprootconn.entries:
        for entry in ldaprootconn.entries:
            deptName = entry.cn.value
            members = entry.memberuid
            uids = []

            # memberuid가 존재하면 유효한 UID 목록을 추가
            if members:
                uids = [member for member in members]
            # {'기술지원팀_기술지원테스트': ['test1', 'test2', 'test3']}
            if deptName and uids:
                ldap_groupdatas[deptName] = uids

    return ldap_groupdatas

def GetWemadeMaxLdapAccountData(ldaprootconn):
    search_base = f"cn=users,{config.LDAP_BASE_DN}"
    search_filter = f"(memberOf=cn=wemademax members, cn=groups,{config.LDAP_BASE_DN})"
    
    try:
        ldaprootconn.search(search_base, search_filter, attributes=["uid", "gecos", "departmentnumber", "mail"])
    except Exception as e:
        print(f"LDAP search error: {e}")
        return {}
    
    ldap_result = ldaprootconn.entries

    ldap_accounts = {}
    
    for entry in ldap_result:
        if not entry or "mail" not in entry:
            continue
        
        user_data = {}

        user_data["uid"] = entry.uid.value if entry.uid else ""

        if entry.gecos:
            user_data["name"] = entry.gecos.value
        else:
            user_data["name"] = ""

        if entry.departmentnumber:
            user_data["dept"] = entry.departmentnumber.value
        else:
            user_data["dept"] = ""

        # 특수계정 필터
        if user_data.get("name", "").startswith("#"):
            continue

        email = entry.mail.value if entry.mail else ""
        ldap_accounts[email] = user_data

    return ldap_accounts

def GetWemadeMaxAccountData():
    gw_accounts = {}
    result = GetAllUserData()
    
    if result:
        for user_id, user in result.items():
            # code에 '#'이 포함된 경우 건너뛰기
            if '#' in user['code']:
                continue
            
            gw_accounts[user['email'].lower()] = {
                'email': user['email'],
                'code': user['code'],
                'name': user['name'],
                'dept': GetDeptsNameList(user_id)
            }

    return gw_accounts

def GetAllUserData():
    global ALL_USER_DATAS
    if ALL_USER_DATAS is not None:
        return ALL_USER_DATAS

    results = rdb.QueryWithFetchAll("SELECT * FROM users ORDER BY name;")

    users = {user['id']: user for user in results}

    # 캐시에 저장
    ALL_USER_DATAS = users
    return ALL_USER_DATAS

def GetDeptsNameList(user_id):
    all_dept_data = GetAllDeptData()  # 모든 부서 데이터 가져오기
    all_member_data = GetAllMemberData()  # 모든 멤버 데이터 가져오기
    
    dept_names = []

    for dept in all_dept_data.values():
        if dept['leader_id'] == user_id:
            dept_names.append(dept['name'])

    for member in all_member_data:
        if member['user_id'] == user_id:
            dept = all_dept_data.get(member['dept_id'])  # 해당 부서 찾기
            if dept:
                dept_names.append(dept['name'])

    return ', '.join(dept_names)

def GetAllDeptData():
    global ALL_DEPT_DATAS
    
    if 'ALL_DEPT_DATAS' in globals() and ALL_DEPT_DATAS is not None:
        return ALL_DEPT_DATAS

    results = rdb.QueryWithFetchAll("SELECT * FROM depts;")
    
    if not results:
        return {}

    depts = {}
    for row in results:
        # parent_id = row['parent_id'] if row['parent_id'] not in (None, '') else ''

        dept = {
            'id': row['id'],  # 부서 ID (id 컬럼)
            'name': row['name'],  # 부서 이름 (name 컬럼)
            'leader_id': row['leader_id'],  # 부서장 ID (leader_id 컬럼)
        }
        depts[dept['id']] = dept

    ALL_DEPT_DATAS = depts
    return ALL_DEPT_DATAS

def GetAllMemberData():
    global ALL_MEMBER_DATAS

    if 'ALL_MEMBER_DATAS' in globals() and ALL_MEMBER_DATAS is not None:
        return ALL_MEMBER_DATAS

    results = rdb.QueryWithFetchAll("SELECT * FROM members;")

    if not results:
        ALL_MEMBER_DATAS = []
        return ALL_MEMBER_DATAS

    ALL_MEMBER_DATAS = results
    return ALL_MEMBER_DATAS

def GetChangedDeptList(gw_accounts, ldap_accounts):
    upd_dept_list = {}
    
    for email, gw_data in gw_accounts.items():
        if email not in ldap_accounts:
            continue
        
        dept = gw_data.get('dept', None)
        if dept is None:
            dept = "-"
        
        ldap_data = ldap_accounts.get(email, {})
        ldap_dept = ldap_data.get('dept', None)
        
        if dept == ldap_dept:
            continue
        
        upd_dept_list[email] = dept
    
    return upd_dept_list



# 재사용 및 단독 실행 proc
if __name__ == "__main__":
    main()