---
marp: true
paginate: true
class: lead
title: "오정훈 - Portfolio"
theme: default
style: |
  @import url('custom-theme-v4.css');
---

> ## CS 플랫폼 구축 경험과 인프라 운영 역량을 갖춘
> ## **솔루션 엔지니어링** 전문가

# 💻 지원자 오정훈입니다
### <https://github.com/cowboy779>

---

## 🙋‍♂️ Introduction
- **CS 플랫폼 & 웹 개발 7년차** (Full-stack & DevOps)
- 現 **WEMADE MAX** (플랫폼 개발팀 / 기술지원)

**"사용자와 시스템을 잇는 솔루션을 만듭니다."**
입사 후 사내 운영툴과 `고객센터(FAQ) 시스템을 직접 기획/개발`하고,
노후화된 레거시 시스템을 현대적인 아키텍처(Python/Flask)로 전환했습니다.
단순 개발을 넘어 `인프라(Linux, Nginx)` 와 `보안(OAuth2, ISO)` 까지 책임지며,
안정적인 서비스 운영을 최우선 가치로 삼고 있습니다.

> **Key Strength:**
> CS 솔루션 자체 구축 경험 | API 연동 및 자동화 | 트러블 슈팅

---

## 💡 Engineering Philosophy
저는 아래를 생각하고 고민합니다.

- **첫째:** 기술을 위한 기술이 아닌, `비즈니스 문제를 해결하는 기술`
- **둘째:** 나만 아는 코드 < `동료가 즉시 수정 가능한 직관적인 코드`
- **셋째:** 반복되는 수동 업무 < `스크립트를 통한 자동화(Automation)`
- **넷째:** 기능 구현 < `장애를 예방하는 견고한 아키텍처`

---

## 🚀 Core Competencies


1. **CS 솔루션 구축 및 커스터마이징**
   - 고객센터 FAQ 시스템 및 관리자(Admin) 페이지 자체 구축 경험 (Python/Flask)
   - 개인정보 보호 정책 준수 및 글로벌 서비스 대응(반응형 웹)

2. **System Integration (API & Auth)**
   - `Google OAuth2 / LDAP` 인증 통합 및 SSO 환경 구축
   - Telegram, SMTP, 결제 모듈 등 다양한 `3rd Party API 연동`

3. **DevOps & Troubleshooting**
   - Linux(RHEL/CentOS) 서버 운영 및 `Nginx 리버스 프록시` 설계
   - `트러블 슈팅` (패킷 분석, 로그 추적, 병목 구간 튜닝)
  


---

## Project [1]  ![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD) 
### 라이트컨 웹 고객센터 FAQ 생성  
###### - **배경:** 구글 스토어 정책을 준수하며, 고객 개인정보 최소수집을 위한 이메일주소로만 페이지 구현  
###### - **기술 스택:** Python, flask, jinja2, MySQL  
- **[WEB FAQ](https://wemademax.com/faq)** : 고객센터 FAQ 페이지를 위한 데이터베이스 및 API 구축/ 관리툴 개발  
- 모바일 및 PC 웹에서 모두 접근 가능하도록 반응형 웹 구현/ 글로벌 서비스 대응
- 관리자 페이지에서 FAQ 콘텐츠 관리 기능 개발을 하였습니다.

---

<!-- ## improvement [1]
### 텔렘그램 및 SMTP 중복 방지 및 많은 양의 알림 전송 최적화
- 비동기 처리 및 재시도 메커니즘 구현으로 안정성 향상
- **[PHP : notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/job_notiqueue.php)**  =>  **[Python : notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/python_auto_shell/job_notiqueue.py)**  
  기존 php 에서는 child process fork 및 프로세스 병렬처리로 제어 구현하였지만, 
  대량의 알림이 발생할 경우, 중복 전송 및 지연 문제 해결을 위해 Lock 과 서브프로세스 Task로 비동기로 개선해 보았습니다.
  
flock으로 동시실행 방지 및 뮤텍스
```php
if ((trim(file_get_contents("/proc/".posix_getppid()."/comm")) != 'flock')
        && (int)exec("pgrep -c -f '".basename(__FILE__)."'") > 1)
{
  exit;
}
``` -->

---

<!-- ## Project [2]  ![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD) 
### 개발 테스트 서버 상황 및 내부그룹웨어 상황 알림시스템 필요
###### - **배경:** zabbix 모니터링 시스템과 내부 그룹웨어 상황 및 서버상태 알림을 위한 텔레그램 연동
###### - **기술 스택:** zabbix, Python, Php, Telegram API, SMTP, Linux
- **[Telegram-notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/telegram.py)**  
  Telegram API Bot 기반 및 SMTP 프로토콜을 사용한 메일 알림 라이브러리  
- 관리자 알림툴에서 `email, telegram, all` 선택적으로 알림 전송 가능을 구현하였습니다. -->

---

## Project [2]  ![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD) 
### 대용량 알림 시스템 구축 및 비동기 처리 최적화
###### - **배경:** Zabbix 모니터링 및 사내 그룹웨어 알림을 위한 통합 시스템 필요 (기존 단일 발송의 한계 극복)
###### - **기술 스택:** Python, Zabbix, Telegram API, SMTP, Linux (flock)

- 시스템 구축 (Integration):
  - **[Telegram-notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/telegram.py)**: Telegram Bot API 및 SMTP를 활용한 **통합 알림 라이브러리** 개발
  - 관리자 도구에서 Email/Telegram/All 채널을 선택적으로 발송하는 기능 구현

---
## Project [2]-2  ![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD) 
  - 성능 최적화 (Optimization):
    - **[PHP](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/job_notiqueue.php) → [Python 전환](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/python_auto_shell/job_notiqueue.py):** 기존 Fork 방식의 한계를 **Python 비동기(Async) 아키텍처**로 재설계하여 대량 처리 속도 향상
    - **중복 방지 로직:** 리눅스 `flock`을 활용한 프로세스 잠금(Lock) 구현으로 **중복 발송 및 데드락 원천 차단**

```php
// 프로세스 중복 실행 방지 (Locking Mechanism)
if ((trim(file_get_contents("/proc/".posix_getppid()."/comm")) != 'flock')
        && (int)exec("pgrep -c -f '".basename(__FILE__)."'") > 1)
{ exit; }
```
---

## Project [3]  ![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD) 
![Account](../ldap_login/images/account_list.png)  
### 위메이드맥스 내부계정 관리 **NAS LDAP** 연동 및 전환 프로젝트
###### - **배경:** 사내 그룹웨어 `NAS 시스템`의 사용자 인증 및 권한 관리를 위해 LDAP 연동   
`위메이드 그룹웨어로 이관 > 위메이드맥스 직원 그룹관리 및 파일서버 개발계정에 이용` 
###### - **기술 스택:** NAS LDAP, Python, Php, MySQL

---
## Project [3]-2  ![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD) 

- **[LDAP LIST](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/ldap_login/internal.py)** : NAS LDAP 사용자 계정 리스트 조회 및 관리툴 개발
- 전사 그룹웨어가 아닌 자사 내부에서 사용하기 위해, 사원 입퇴사 관리 조직 그룹망을 구현하기 위해 자동 batch > [batch script 보기](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/ldap_login/python_auto_shell/job_autoproc.py)
```sh
##### run_account.sh crontab 설정 예시
# 매주 월~금 09:30 ~ 19:30 매 시간 30분마다 실행
0,30 9-19 * * 1-5 /company/account_site/run_account.sh

##### run_account.sh
#!/bin/bash
# 가상 환경 활성화
source /company/venv/account_system/bin/activate
# Python 스케쥴링 스크립트 실행
python /company/account_system/script/job_autoproc.py

```

---

## Project [4]  ![medical](https://img.shields.io/badge/medical-16A34A?style=for-the-badge&labelColor=86EFAC)
### 신규 디자인 필요, SQL 쿼리의 중앙집중화, 제이쿼리정리, 공통환자모듈 필요
###### - **배경:** 공공의료 종사자 편의성과 관리 및 환자 개인정보 권한 강화,데이터 통합솔루션으로 기획
- JSP 소스 Websquare5 UI로 디자인변경 및 oracle sql 동적 mybatis 추가하여 SQL 성능 향상 및 확장성으로 수정
- API 통한 생활(재택)치료센터 연계 데이터 배치작업 `private int semiInsertData`(**[소스보기](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/medios/LifeCenterService.java)**)
- JSP m2 소스 > 표준화된 Websquare에 맞게 전환 및 이식작업 (**[이미지보기](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/medios/sugacode_img.png)**)
- 전반적으로 안전하고 빠르게 소스를 신규포멧에 맞게 전환하는거에 초점
- 양이 많다보니 SQL은 추후에 튜닝이나 개발표준화을 기준으로 새롭게 만들었다.


---

## improvement [1]
### Google OAuth2 API 이용한 운영툴 로그인 통합
###### - **배경:** 정보통신망법 준수 및 ISO 보안심사 대응을 위한 통합 인증 체계 필요
###### - **기술 스택:** GCP, Google API, Python Flask
- **[Google Login](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/google/glogin.php)** : 개인 식별 정보를 활용한 안전한 접근 관리 구현
- **SSO(Single Sign-On) 환경 구축:** 기존 개별 ID/PW 방식을 Google 통합 로그인으로 교체
- **OAuth 2.0 적용:** 각 운영툴(Client)마다 액세스 권한을 분리하고, 키 관리를 중앙화하여 보안성과 관리 편의성 증대

---

## improvement [2]
### 홈페이지 운영약관 및 정책 업데이트 관리 개선
 - **[기업윤리 상담센터](https://www.wemademax.com/ethics)** : 신규 페이지 기획 및 개발
 - **CMS 기능 도입:** 기존 하드코딩 방식(JS document.write)을 **[Summernote](https://summernote.org/) (WYSIWYG 에디터)**로 교체하여 운영팀이 직접 정책을 수정/배포할 수 있도록 개선
 - **버전 관리 시스템 구축:** 정책 변경 이력을 DB로 관리하여, 수정 내역을 추적하고 필요시 롤백할 수 있는 안정성 확보

---
## Security Engineering [1]
### 엔터프라이즈급 보안 가이드라인 준수 및 취약점 방어
- **SQL Injection 방어:** `Prepared Statement` 적용으로 쿼리 조작 원천 차단
```php
$where = "COUPONID=:couponId";
$bind = [":couponId" => $couponID];
$couponData = SQLDBWrapper::GetSQLDB()->select(DBTable::COUPON_DATA, $where, $bind);
```
- **서버 정보 은닉** : `Nginx/PHP` 버전 정보 노출 차단

```nginx.conf
server_tokens off;
```
---

## Security Engineering [2]
### 계정 보안 강화 및 비정상 접근 차단
- **2FA (Two-Factor Authentication):** 자체 내부 비밀번호 검증 로직에 OTP 추가 적용
- **Bot 방지:** reCaptcha API를 연동하여 매크로 및 자동화 공격 방어
- **Session Security:**
  ```
  1. HttpOnly, Secure 쿠키 설정으로 XSS를 통한 세션 탈취 방지
  2. 서버 사이드 세션(CacheLib) 적용 및 세션 타임아웃 설정으로 관리자 권한 보호
  ```

---

## Infra & DevOps [1]
### 안정적인 서비스 운영을 위한 인프라 구축
- **Firewall & Network:**
  ```
  1. Linux firewalld를 활용한 Inbound/Outbound 정책 수립 및 포트 포워딩
  2. 특정 IP 대역(Office, VPN)만 허용하는 화이트리스트 정책 운영
  ```
- **Access Control:**
  ```
  1. 개발자별 SSH Key 기반 접속 권한 관리 (authorized_keys) 및 Sudo 권한 차등 부여
  2. Juniper 방화벽 정책 관리 (Trust/Untrust Zone 설정 및 DMZ 관리)
  ```
  
---

## Infra & DevOps [2]
### Jenkins & Shell Script를 활용한 배포 자동화
- **CI/CD Pipeline:**
  ```
  1. Jenkins Execute shell을 활용하여 원클릭 배포 시스템 구축
  2. Rsync를 이용한 변경 파일 동기화 및 배포 스크립트 작성
  ```
- **Automated Tasks:**
  ```
  1. Mysql Backup: 쉘 스크립트로 DB 덤프 및 이중화 백업 자동화 (Crontab 연동)
  2. Resource Monitoring: 디스크 용량, Swap 메모리 체크 및 임계치 도달 시 알림 발송
  ```
  
---

## Infra & DevOps [3]
### 실시간 모니터링 및 로그 파이프라인 구축
- **Nginx Reverse Proxy:**
  ```
  다수의 내부 VM을 단일 진입점으로 연결하고, Nginx 를 통한 프록시패스 WebSocket 프로토콜 지원 설정
  ```
- **Log Management (Fluentd):**
  ```
  1. 분산된 서버 로그를 Fluentd로 수집하여 중앙 로그 서버로 전송
  2. 에러 로그 발생 시 Telegram 알림 봇과 연동하여 실시간 장애 대응 체계 마련
  ```
  
---

## ETC & Future Plans
### 앞으로 어떻게 해야할지 지속적인 개선 및 기술적 고민
- **Architecture:** Flask Blueprint를 활용한 RESTful API 구조 리팩토링 및 Swagger 문서화 도입 검토
- **DB Connection Pool:** `DBHelper` 클래스를 구현하여 커넥션 생성/반납을 자동화하고, 리소스 누수(Leak) 방지 **> [소스보기](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/dbhelper/dbhelper.py).py**



---

<!-- _class: section -->
# Thanks for Reading 🙌

| 구분 | 링크 |
|---|---|
| **GitHub** | `https://github.com/cowboy779` |
| **Email** | `cowboy779@naver.com` |
| **Mobile** | `010-8809-3586` |
