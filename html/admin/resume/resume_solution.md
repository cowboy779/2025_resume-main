<style>
/* * 1. [폰트] Pretendard (부드럽고 가독성 높은 폰트)
 * CDN을 통해 웹 폰트를 불러옵니다.
 */
@import url('https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css');

/* * 1-1. [A4 페이지 설정 - Microsoft Word 기본 여백] 
 */
@page {
  size: A4 portrait; /* A4 세로 */
  margin-top: 20mm;
  margin-right: 25.4mm;
  margin-bottom: 25.4mm;
  margin-left: 15.4mm;
}

/* * 2. [전체] 폰트 및 컬러 이모지 설정
 */
section {
  font-family: 'Pretendard', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif,
               "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
  font-weight: 400;
  line-height: 1.6;
  color: #343a40;
  background-color: #fff;
  
  width: 210mm;
  min-height: 297mm;
  
  /* 이중 여백 방지를 위해 padding 제거 */
  
  margin: 0 auto;
  font-size: 10pt;
  box-sizing: border-box;
  justify-content: flex-start; /* 내용을 위에서부터 정렬 */
}

/* * 3. 제목 (H1): 이름 */
h1 {
  font-size: 22pt;
  font-weight: 800;
  color: #111;
  margin-top: 0;
  margin-bottom: 6pt;
  padding-bottom: 0;
  border-bottom: none;
  page-break-after: avoid;
}

/* * 4. 연락처/소개 (H1 바로 다음 문단) */
h1 + p {
  font-size: 10pt;
  font-weight: 300;
  color: #495057;
  margin-top: 0;
  margin-bottom: 12pt;
  display: flex;
  flex-wrap: wrap;
  gap: 0 10pt;
  line-height: 1.5;
}
h1 + p a { 
  color: #495057; 
  text-decoration: none; 
}

/* * 5. 섹션 제목 (H2): 소개, 경력 요약 등 */
h2 {
  font-size: 14pt;
  font-weight: 700;
  color: #212529;
  border-bottom: 2px solid #f1f3f5;
  padding-bottom: 3pt;
  margin-top: 20pt;
  margin-bottom: 10pt;
  page-break-after: avoid;
}

/* * 6. 하위 섹션 제목 (H3): 회사명, 프로젝트명 등 */
h3 {
  font-size: 11.5pt;
  font-weight: 700;
  color: #212529;
  margin-top: 12pt;
  margin-bottom: 6pt;
  page-break-after: avoid;
}

/* * 7. [!!! 수정된 부분 !!!] 
 * "레터박스" 또는 "액센트 바" 스타일
 * 기존의 두꺼운 테두리, 그림자, 흰색 배경 대신
 * 왼쪽에만 깔끔한 '바(Bar)'
 */
blockquote {
  background-color: #f8f9fa;  /* 아주 연한 회색 배경 */
  border-radius: 5px;         /* 살짝 둥근 모서리 */
  padding: 14pt 16pt;         /* 안쪽 여백 */
  margin: 12pt 0;             /* 바깥 여백 */
  
  /* [!!! 핵심 수정 !!!] */
  /* 왼쪽 '액센트 바' (레터박스) 스타일 */
  border-left: 5px solid #007bff; /* 파란색으로 포인트 */

  /* 나머지 테두리는 모두 제거 */
  border-top: none;
  border-right: none;
  border-bottom: none;
  box-shadow: none; /* 그림자 제거 */

  font-style: normal;
  font-size: 10pt;
  page-break-inside: avoid;
}
blockquote h3:first-child { margin-top: 0; }
blockquote > :last-child { margin-bottom: 0; }


/* * 8. 테이블 (table): 경력 요약, 기술 스택 */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 8pt;
  margin-bottom: 12pt;
  border: 1px solid #e9ecef;
  font-size: 9.5pt;
  border-radius: 8px;
  overflow: hidden;
  page-break-inside: avoid;
}
th, td {
  border: none;
  border-bottom: 1px solid #e9ecef;
  padding: 8pt 10pt;
  text-align: left;
  vertical-align: top;
}
th {
  background-color: #f8f9fa;
  font-weight: 600;
}
tr:last-child td { border-bottom: none; }
table th:first-child,
table td:first-child { 
  width: 25%; 
  font-weight: 600; 
}

/* * 9. 목록 (ul) */
ul {
  padding-left: 12pt;
  list-style-type: none;
  margin-top: 6pt;
  margin-bottom: 10pt;
}
ul li {
  margin-bottom: 4pt;
  position: relative;
  padding-left: 0;
  page-break-inside: avoid;
}
ul li::before {
  content: '•';
  position: absolute;
  left: -12pt;
  top: 0;
  color: #868e96;
  font-size: 11pt;
}

/* * 10. 수평선 (hr): --- */
hr {
  border: 0;
  height: 1px;
  background: #e9ecef;
  margin: 20pt 0;
  page-break-after: avoid;
}

/* * 11. 링크 (a) */
a {
  color: #007bff;
  text-decoration: none;
  font-weight: 500;
}
a:hover { 
  text-decoration: underline; 
}

/* * 12. 이미지 (img): 뱃지 스타일 */
img {
  max-width: 100%;
  vertical-align: text-bottom;
  margin-right: 3pt;
  border-radius: 3pt;
}

/* * 13. 코드 (code) */
code {
  font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
  font-size: 9pt;
  background-color: #f1f3f5;
  color: #d6336c;
  padding: 2pt 4pt;
  border-radius: 3pt;
}

/* * 14. 문단 간격 */
p {
  margin-top: 6pt;
  margin-bottom: 6pt;
}

</style>


# 오정훈 | Web Developer · DevOps  

>📍 경기 성남시 분당구
>🌐 cowboy779@naver.com
>💻 [https://github.com/cowboy779](https://github.com/cowboy779/2025_resume-main/tree/main/html/admin/)  
>📱 010-8809-3586  
 
---

## 🧑‍💻 소개
>>
>함께 성장하는 개발자 오정훈입니다.  
>현재 WEMADE MAX (LIGHTCON Corp)에서 **Platform Web Developer & DevOps**로 근무 중입니다.  
>>
>"CS 플랫폼 구축 경험과 인프라 운영 역량을 겸비한 솔루션 엔지니어"
>고객센터 FAQ 시스템을 직접 기획/개발하고, Google OAuth2 인증 통합 등 시스템 연동 경험을 보유하고 있습니다.
>단순 개발을 넘어 리눅스 서버 운영, Nginx 프록시 설정, 보안 정책 수립까지 가능한 'All-Rounder'입니다. ✨
>>


## 💼 경력 요약

| 회사명 | 직책 | 기간 | 주요 업무 |
| :----- | :--- | :--- | :------- |
| LIGHTCON Corp | Platform WEB Developer /<br> DevOps | 2022.12 ~ 재직 | 플랫폼 운영툴 유지보수, 개발계정 및 그룹관리, CI/CD 관리, Jenkins 배포 자동화, 인프라 기술지원|
| 전국지방의료원 엽합회 | Web Back-end Developer | 2019.05 ~ 2022.11 | 차세대 프로젝트, UI/UX 고도화 작업,<br> 수가코드 DB/SQL 기능개발 |

---


## 💼 경력 상세 기술서
> ### 라이트컨 LIGHTCON Corp  
![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD)
**Platform WEB Developer / DevOps**  
2022.12 ~ 재직 중  

- 플랫폼 및 개발운영툴 환경 개선
- 입사 후 내부 그룹웨어HR 유지보수중 위메이드 그룹웨어로 이관 
- 사내계정 NAS LDAP 개발 계정생성 및 부서그룹 유지보수
- 위메이드맥스 홈페이지 디자인 개선 및 글로벌 홈페이지 이벤트 및 티저사이트 생성
- 사내 인프라 기술지원업무 유지보수 및 개선

  그외 
- VM 트러블슈팅 장애대응 기술지원
- CSR CRT CA 'SSL' 인증서 발급 및 갱신
- Package Manager(앱 패키지파일 관리) 기반 유지보수 및 CDN 배치 구성
- 웹서버 관련 Nginx, Apache 세팅

<!-- - 중요 백업파일 MySQL DB 실시간 이중화 작업 -->
<!-- - AWS(보조) VPC(서브넷)피어링 - 라우팅테이블 - 인스턴스 등록 -->
<!-- - jenkins 전략에 따른 배포 자동화 (shell script) -->
<!-- - 장애 대응 자동화, 로그 관리 개선 (logrotate, Fluentd 수집 빅쿼리 모니터링)   -->

**주요 성과**  
- **노후화된 운영툴 전환작업** : 기존 보안에 취약한 구 PHP → Python Flask 전환  
- **Google OAuth2 인증작업** : 기존 아이디/패스워드 로그인 → Google OAuth2 인증
- **개발서버 생성 및 권한관리** : VM 생성 및 네트워크 연결 및 SSH 권한작업
- **자동화 작업** : 크론 작업 스크립트 및 오래된파일 및 계정 정리 스크립트 작성

---

> ### 전국지방의료원 연합회 Medios  
![medical](https://img.shields.io/badge/medical-16A34A?style=for-the-badge&labelColor=86EFAC)
**Back-end Web Developer**  
2019.05 ~ 2022.11  

- 차세대 프로젝트 Websquare5 UI/UX 신규개발
- 의료 신규 서비스 UI 개발 및 기존 MEDIOS3(JSP) 서비스 유지보수  
- Java기반 egove spring framework3.8/spring boot 구조로 웹서비스 재구축  
- Oracle RDB SQL 기반 데이터 유지보수 및 Pl/SQL 프로시저 유지보수


**대표 프로젝트**
- **차세대 프로젝트 전환작업** : 레거시 JSP 기반을 Java 통합 솔루션 SPA 웹 기반으로 신규전환  
- **covid-19(코로나) 대용량배치** : 종합병동에 코로나 환자로 들어오는 진료정보 병동입력 작업

---
<br>

## 🧩 주요 프로젝트

> ### 홈페이지 신규 모바일 FAQ 페이지 개발 프로젝트
**기간:** 2024.11 ~ 2025.01  
**기술:** Python, MySQL, HTML, CSS, JavaScript
**내용:** **[FAQ](https://wemademax.com/faq)**
- 기존 PC 중심 홈페이지 → 모바일 FAQ 페이지 신규개발
- 반응형 웹 디자인 적용 및 사용자 경험(UX) 최적화
- 관리자(운영자)페이지 편의를 위한 콘텐츠 CMS 기능 개발
- FAQ 데이터베이스 구축 및 조회 성능 개선 

---

> ### Google OAuth2 인증 및 로그인 프로젝트
**기간:** 2024.01 ~ 2024.05  
**기술:** Python, PHP, Google OAuth2, recaptcha  
**내용:**  
- **SSO(Single Sign-On) 구축:** 기존 ID/PW 방식을 Google OAuth2로 통합
- 사내 계정 통합 관리 및 보안 정책(Access Control) 수립
- 운영툴 접근 제어 및 비인가 접근 차단 로직 구현

---

> ### Legacy PHP → Python flask 전환 프로젝트
**기간:** 2025.01 ~ 2025.06   
**기술:** Python Flask, MySQL, Uwsgi, Nginx, jinja2, fluentd  
**내용:**  
- **시스템 현대화(Modernization):** 노후화된 PHP 레거시 시스템을 Python Flask 아키텍처로 전환
- Uwsgi + Nginx 기반 배포 환경 구성
- 로그 모니터링 체계 구축 및 운영 효율화
- 파이썬 기반 자동화 스크립트 작성으로 반복 업무 제거
- 보안 취약점 강화 로그인 및 세션 관리

---

> ### 모바일 쿠폰 발급 및 이벤트 연동 쿠폰 구축
**기간:** 2025.06 ~ 2025.08  
**기술:** Php, MySQL  
**내용:**  
- 게임 모바일 쿠폰 발급 API 설계 및 대용량 트래픽 처리 구현
- 외부 프로모션 시스템과의 API 연동
- 사용자 인증 및 쿠폰 발급/사용 이력의 정합성 보장

---

> ### 위메이드맥스 내부계정 관리 NAS LDAP 연동 및 전환 프로젝트
**기간:** 2025.01 ~ 2025.05  
**기술:** Python, PHP, NAS LDAP, mysql
**내용:**  
- 내부 그룹웨어 NAS LDAP 생성 및 Flask 연동
- 사내 인증 체계 통합 및 레거시 개발 계정 전환
- 비밀번호 보안 정책 강화 및 관리 포인트 일원화

---

> ### 공공의료 WebSquare 차세대 전환 프로젝트
**기간:** 2021.06 ~ 2022.06  
**기술:** Java, WebSquare5, Oracle DB, Spring Framework, Pl/SQL, Ajax, jQuery  
**내용:**  
- WebSquare5 기반 UI/UX 신규 개발 및 레거시 MEDIOS3(JSP) 시스템 전환 (**[이미지보기](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/medios/sugacode_img.png)**)
- egove spring framework3.8/spring boot 구조로 웹 서비스 재구축
- Oracle DB 기반 데이터 유지보수 및 Pl/SQL 프로시저 유지보수
- 의료정보시스템 보안 강화 및 접근 제어 정책 수립
- 의료 서비스 품질 향상을 위한 피드백 시스템 개발
- 의료 기관 간 데이터 공유 및 협업 플랫폼 구축


---

## 🌱 서브 프로젝트

- **[Telegram-notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/lib/Telegram.php)**  
  ** Telegram API Bot 및 SMTP 프로토콜을 활용한 **통합 알림 라이브러리** 개발 (Webhook 및 API 연동)

- **[SVNAdmin](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/svn)/Gitea**  
  기존 SVN Tortoise 환경을 Gitea(Git)로 이관 및 웹 기반 권한 관리 시스템 구축

---
<br>

## 💻 업무경험

![feature](https://img.shields.io/badge/Feature_Development-%23FF6B6B?style=flat-square&labelColor=%23FFE5E5)  
### 웹 기능 개발 및 시스템 구축
- **운영정책 관리 개선**: WYSIWYG 에디터(summernote) 도입 및 DB 기반 버전 관리 시스템 개발 [**[기업윤리 상담센터](https://www.wemademax.com/ethics)**]
- **플랫폼 홈페이지 고도화**: CSS Flex/Grid 를 활용한 반응형 UI 구현 및 디자인/팝업 연혁 개선 [**[GAME ICON](https://www.wemademax.com/games)**]
- **구글 애드몹 설정**: 광고매체 제공 및 앱 텍스트 사용자화면 개발
- **블록체인 WEMINX 연동**: WEMIX와 게임내 재화 토큰연동을 위한 SDK 이용 유지보수 **[서비스종료]**  
  
![devops](https://img.shields.io/badge/DevOps-%23ff66EB?style=flat-square&labelColor=%23ff66EB)  
### 인프라 및 배포 자동화
- **CI/CD 파이프라인**: Jenkins 기반 자동 배포 구성 및 Shell Script 작업
- **서버 인프라 운영**: Oracle VM 리소스 관리, SSH Key 기반 권한 제어
- **네트워크 설정**: Firewalld 방화벽 정책 수립 및 화이트리스트 관리
- **웹서버 최석화**: Nginx 리버스 프록시 및 WebSocket 연결 작업
- **백업 및 이중화**: MongoPSA 및 MySQL Master-Slave Replication 구성, 자동 백업 스크립트 작성
- **Log Pipeline 구축**: 중요로그 Fluentd 기반 로그 수집 및 로그 로테이션 작업
- **모니터링**: Zabbix 시스템 모니터링 이용한 상태 파악

![troubleshooting](https://img.shields.io/badge/Troubleshooting-%23F59E0B?style=flat-square&labelColor=%23FED7AA)

### 장애 대응 및 성능 최적화
- **대용량 트래픽 처리**: 알림 시스템 비동기(Async) 전환 (PHP → Python)으로 처리 속도 개선
- **동시성 제어(Concurrency)**: 프로세스 Lock 메커니즘 구현으로 중복 실행 및 데드락 방지
- **리소스 최적화**: DB 커넥션 풀링(Connection Pooling) 도입으로 리소스 누수 방지
- **트러블슈팅**: VM 용량 관리, Swap 메모리 관리, 디스크 파티션 확장 작업
- **접근 제어**: 허가된 외부 IP/Port 허용 관리, Juniper 방화벽 정책 설정 작업

![security](https://img.shields.io/badge/Security-%23FF6347?style=flat-square&labelColor=%23FFA07A)

### 보안 강화 및 취약점 대응 (ISO 심사 대응)
  - **웹 취약점 방어**: SQL Injection(Prepared Statement), XSS(Escape) 방어 로직 적용
  - **세션 보안**: HttpOnly/Secure 쿠키 설정 및 세션 하이재킹 방지
  - **서버 보안**: Nginx/PHP 버전 정보 은닉(Security through Obscurity)
  - **비정상 접근 차단**: reCAPTCHA 도입 및 2FA(OTP) 인증 프로세스 구현

![backend](https://img.shields.io/badge/Backend-%238B5CF6?style=flat-square&labelColor=%23DDD6FE)

### 백엔드 시스템 전환 및 개선
- **Migration**: 노후화된 PHP 레거시 시스템을 Python Flask로 리팩토링
- **API 개발**: Flask Blueprint를 활용한 RESTful API 구조 설계
- **세션 관리 개선**: 서버 사이드 세션(CacheLib) 적용, 세션 타임아웃 설정
- **유지보수성 향상**: 공통 모듈화 및 보일러플레이트(Boilerplate) 코드로 생산성 증대

---
<br>

## 🧠 사용 기술 스택

| 분야 | 기술 |
|------|------|
| Main | PHP, Python, Java, HTML, CSS(SCSS), JavaScript, jQuery |
| Backend / Infra | Apache, Nginx, REST API, Flask, GCP |
| DevOps | Gitea, Jenkins, SVN, NAS, Oracle VM, Linux (RHEL/CentOS) |
| Tooling | VS Code, PyCharm, Xshell, MySQL Workbench |
| Database | MySQL, Oracle |
| ETC | Telegram API, SMTP, Google OAuth2 |

---

<!-- ## 🌍 오픈소스 기여

- []()- README 개선  

--- -->

## 🎓 학력
**한국방송통신대학교**  
컴퓨터과학과 졸업예정
2024.09 ~ 2026.02~

**신안산대학교**  
멀티미디어컨텐츠학과 (3.8 / 4.5)  
2007.03 ~ 2012.02  

**충현고등학교**  
2004.03 ~ 2007.02  

---

## 🎓 수료

**솔데스크 IT 아카데미(자바 오픈소스기반 빅데이터 개발자 양성과정)**  
2017.12 ~ 2018.06  

---

## 📜 자격증

- **정보처리산업기사** (2018.05)  

---
<br>


_Last Updated: 2026-01-28_  
감사합니다.