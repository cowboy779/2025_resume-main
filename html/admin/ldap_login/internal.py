# -*- coding: utf-8 -*-

from admin.admin_func import *
from admin.admin_conf import *
from config import config

def internal():
    user_data = InternalAccount()
    group_data = InternalMembers()
    group_detail = InternalMemberDetail(user_data, group_data)
    
    return {'user_data': user_data['data'], 'group_data': group_data['data'], 'group_detail': group_detail}
    
def InternalAccount():
    try:
        # LDAP 서버 연결
        server = Server(config.LDAP_SERVER, get_info=ALL)
        conn = Connection(server, auto_bind=True)
        
        # LDAP 검색 기준
        ldap_tree = f'cn=users,{config.LDAP_BASE_DN}'
        search_filter = f'(memberOf=cn=wemademax members,cn=groups,{config.LDAP_BASE_DN})'

        # LDAP 검색
        if not conn.search(ldap_tree, search_filter, attributes=['mail', 'uid', 'gecos', 'departmentNumber']):
            AlertMsg("LDAP 검색 결과가 없습니다.")
            return {'data': {}}
        
        # 검색 결과 가져오기
        ldap_accounts = {}
        
        for entry in conn.entries:
            if 'mail' in entry and entry.mail:
                userdata = {
                    'email': entry.mail.value,
                    'uid': entry.uid.value if 'uid' in entry else None,
                    'name': entry.gecos.value if 'gecos' in entry else None,
                    'dept': entry.departmentNumber.value if 'departmentNumber' in entry else None,
                }

                # 이름이 '#'로 시작하면 제외
                if userdata['name'] and userdata['name'].startswith('#'):
                    continue

                # 사용자 데이터를 딕셔너리에 추가
                ldap_accounts[userdata['email']] = {
                    'uid': userdata['uid'],
                    'name': userdata['name'],
                    'dept': userdata['dept'],
                }
                
        return {'data': ldap_accounts}

    except Exception as e:
        print(f"Error: {e}")
        return {'data': {}}

    finally:
        if 'conn' in locals() and conn:
            conn.unbind()

def InternalMembers():
    try:
        server = Server(config.LDAP_SERVER, get_info=ALL)
        conn = Connection(server, auto_bind=True)

        ldap_tree = f'cn=groups,{config.LDAP_BASE_DN}'
        search_filter = f'(description={config.AUTO_GROUP_DESC})'

        if not conn.search(ldap_tree, search_filter, attributes=['cn', 'memberUid']):
            return {'data': {}}

        ldap_groupdata = {}
        
        for entry in conn.entries:
            dept_name = entry.cn.value if 'cn' in entry else None
            members = entry.memberUid.values if 'memberUid' in entry else []
            
            if dept_name and members:
                ldap_groupdata[dept_name] = list(members)

        return {'data': ldap_groupdata}

    except Exception as e:
        print(f"Error: {e}")
        return {'data': {}}

    finally:
        if 'conn' in locals() and conn:
            conn.unbind()

def InternalMemberDetail(user_data, group_data):
    group_detail = {}
    
    for groupname, members in group_data['data'].items():
        member_list = []
        
        for uid in members:
            email = f"{uid}@wemade.com"
            account = GetObjVal(email, user_data['data'])
            name = GetObjVal("name", account)
            dept = GetObjVal("dept", account)
            member_list.append(f"<{email}> {name} [{dept}]")

        group_detail[groupname] = member_list
    
    return group_detail