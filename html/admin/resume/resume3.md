

# 오정훈 | Web Developer · DevOps

📍 경기 성남시 분당구
📧 cowboy779@naver.com
<!-- 🌐 [https://creco.today](https://creco.today)   -->
<!-- 💻 [https://github.com/CreatiCoding](https://github.com/CreatiCoding)   -->
📱 010-8809-3586  

---

## 🧑‍💻 소개

함께 성장하는 개발자 오정훈입니다.  
현재 WEMADE MAX (LIGHTCON Corp)에서 **Platform Web Developer & DevOps**로 근무 중입니다.  

“번잡하지 않고, 최소한의 리소스로 최대의 효과”를 추구하는,  
**읽기 좋은 코드**, **개발 생산성 개선**, **자동화된 배포**,  에 관심이 많습니다.  
WEB 플랫폼 개발자도, DevOps를 이해하면 더 넓은 시야로 문제를 해결할 수 있다고 믿습니다.  

> #DevOps는 재밌어요 ✨  

---
<br>

## 💼 경력 요약

| 회사명 | 직책 | 기간 | 주요 업무 |
| :----- | :--- | :--- | :------- |
| LIGHTCON Corp | Platform WEB Developer /<br> DevOps | 2022.12 ~ 재직 | 플랫폼 운영툴 환경 개선, CI/CD 구축,<Br> Jenkins 배포 자동화 |
| 전국지방의료원 엽합회 | Web Back-end Developer | 2019.05 ~ 2022.11 | 차세대 프로젝트, UI/UX 고도화 작업,<br> 수가코드 DB/SQL 기능개발 |

---
<br>

## 💼 경력 상세 기술서
### 라이트컨 LIGHTCON Corp  
![game](https://img.shields.io/badge/game-2563EB?style=for-the-badge&labelColor=93C5FD)
**Platform WEB Developer / DevOps**  
2022.12 ~ 재직 중  

- 게임플랫폼 및 개발운영툴 환경 개선
- Google OAuth2 인증작업 및 NAS LDAP 생성 및 연결작업
- 위메이드맥스 홈페이지 디자인 개선 및 글로벌 홈페이지 이벤트 및 티저사이트 생성
- 운영툴, 홈페이지 디자인 및 보안 강화 및 전환작업
-  
- Gitea, SVNAdmin, 자체 package manager(앱 패키지파일 관리) 기반 자동 배포 파이프라인 구축/유지보수 및 CI/CD 안정화   
- Nginx, Apache 웹서버 관련 세팅
- jenkins 전략에 따른 배포 자동화 (shell script)
- LEMP 스택 개발서버 VM 생성 및 개발환경 네트워크 연결 및 권한작업(SSH)
- Zabbix 모니터링 툴
- 개발시스템 알림설정 텔레그램 API 연동작업 및 SMTP 메일작업
- 리눅스 자동화 크론작업 스크립트 백업 및 자동실행
- VM 트러블슈팅 장애대응(메모리,cpu,애플리케이션 systemd)

- 중요 백업파일 MySQL DB 실시간 이중화 작업
- ISO 관련 디렉토리 인덱싱 방어 및 SQL 인젝션 방어작업

  그외 
- CSR CRT CA 'SSL' 인증서 발급 및 갱신
- AWS(보조) VPC(서브넷)피어링 - 라우팅테이블 - 인스턴스 등록


<!-- - Hot Reload 속도 개선 및 초기 로딩 성능 최적화   -->
<!-- - SPA 버전 관리 및 캐싱 이슈 해결   -->



**주요 성과**
- **노후화된 운영툴 전환작업** : 기존 보안에 취약한 PHP 
<!-- - CI/CD 전환: CircleCI → GitHub Actions 이전   -->
<!-- - 무중단 배포 구성 (Next.js + PM2 + Nginx + ELB)   -->
- 장애 대응 자동화, 로그 관리 개선 (logrotate, Fluentd 수집 빅쿼리 모니터링)  

---

### 전국지방의료원 연합회 Medios  
![medical](https://img.shields.io/badge/medical-16A34A?style=for-the-badge&labelColor=86EFAC)
**Web Back-end Developer**  
2019.05 ~ 2022.11  

- 차세대 프로젝트 Websquare5 UI/UX 신규개발
- 의료 신규 서비스 UI 개발 및 기존 MEDIOS3(JSP) 서비스 유지보수  
- Java기반 egove spring framework3.8/spring boot 구조로 웹서비스 재구축  
- Oracle RDB SQL 기반 데이터 유지보수 및 Pl/SQL 프로시저 유지보수
<!-- - Elasticsearch 기반 검색 페이지 리뉴얼   -->
<!-- - HLS 기반 Video.js 플레이어로 “펫프티비” 개선   -->

**대표 프로젝트**
- **차세대 프로젝트 전환작업** : 레거시 JSP 기반을 Java 통합 솔루션 SPA 웹 기반으로 신규전환  
- **covid-19(코로나) 대용량배치** : 종합병동에 코로나 환자로 들어오는 진료정보 병동입력 작업
<!-- - **신 파트너사 사이트 (React + Next.js)**: SSR 기반 무중단 배포 구성   -->

---
<br>

## 🧩 주요 프로젝트

### Legacy PHP → Python flask 전환 프로젝트
**기간:** 2025.01 ~ 2025.06   
**기술:** Python Flask, MySQL, Uwsgi, Nginx, jinja2, fluentd  
**내용:**  
- 플랫폼 운영툴 Legacy PHP → Python Flask 전환  
- Uwsgi + Nginx 기반 배포 환경 구성
- 로그 관리 및 모니터링 체계 구축
- 운영툴 UI/UX 개선
- 파이썬 기반 자동화 스크립트 작성
- 보안 취약점 강화 로그인 및 세션 관리

---

### Google OAuth2 인증 및 로그인 프로젝트
**기간:** 2024.01 ~ 2024.05  
**기술:** Python, PHP, Google OAuth2  
**내용:**  
- 기존 로그인 아이디/패스워드 → Google OAuth2 인증 전환 구현
- 사내 로그인 계정 통합 관리 체계 구축
- 운영툴 접근 제어 정책 수립

---

### NAS LDAP 연동 및 전환 프로젝트
**기간:** 2024.03 ~ 2024.06  
**기술:** Python, PHP, NAS LDAP  
**내용:**  
- NAS LDAP 생성 및 Flask 연동 작업
- 사내 로그인 인증 및 내부망 그룹 개발계정 노후화 전환
- 비밀번호 보안 정책 강화


---

### 공공의료 WebSquare 차세대 전환 프로젝트
**기간:** 2021.06 ~ 2022.06  
**기술:** Java, WebSquare5, Oracle DB, Spring Framework, Pl/SQL, Ajax, jQuery  
**내용:**  
- WebSquare5 기반 UI/UX 신규개발
- 의료 신규 서비스 UI 개발 및 기존 MEDIOS3(JSP) 서비스 유지보수
- Java기반 egove spring framework3.8/spring boot 구조로 웹서비스 재구축
- Oracle RDB SQL 기반 데이터 유지보수 및 Pl/SQL 프로시저 유지보수
- 대용량 데이터 배치 작업 최적화 및 성능 개선
- 의료정보시스템 보안 강화 및 접근 제어 정책 수립
- 웹 접근성 준수 및 사용자 경험 향상 
- 의료 서비스 품질 향상을 위한 피드백 시스템 개발
- 의료 기관 간 데이터 공유 및 협업 플랫폼 구축


---
<br>

## 🧠 기술 스택

| 분야 | 기술 |
|------|------|
| Frontend | HTML, CSS(SCSS), JavaScript(ES6), Jquery |
| Backend / Infra | Apache, Nginx, RestAPI, FastAPI, GCP |
| DevOps | Gitea, Jenkins, SVN, NAS, Oracle VM, Linux |
| Test / Tooling | VScode, Pycharm, Xshell, Babel |
| Database | MySQL, Oracle, Redis |
| ETC | TypeScript(기초), Telegram, SMTP |

---

## 🌱 개인 프로젝트

- **[Telegram-notifier](https://github.com/cowboy779/2025_2_resume-main/tree/main/html/admin/autoload)**  
  Markdown 기반 웹 프레젠테이션 라이브러리 (marp-core + tiny-slider 통합)  

<!-- - **[action-scheduler](https://github.com/creaticoding/action-scheduler)**  
  GitHub Actions만으로 스케줄링, 크롤링, Telegram 알림 처리   -->

<!-- - **[lambda-image-uploader](https://github.com/CreatiCoding/lambda-image-uploader)**  
  AWS Lambda + S3 이미지 업로드 API 구성   -->


---

<!-- ## 🌍 오픈소스 기여

- [marp-team/marpit](https://github.com/marp-team/marpit/pull/281) - README 개선  
- [webpack-korea/webpack.js.org](https://github.com/webpack-korea/webpack.js.org) - Webpack 번역 참여  
- [javascript-tutorial/ko.javascript.info](https://github.com/javascript-tutorial/ko.javascript.info) - 번역 및 리뷰 참여  
- [vue-iamport](https://github.com/luiseok/vue-iamport/pull/1) - 오류 수정  
- [sequelize-auto](https://github.com/sequelize/sequelize-auto/pull/220) - 문서 기여  

--- -->
<br>

## 🎓 학력
**한국방송통신대학교**  
컴퓨터과학과 졸업예정
2024.09 ~ 2026.~

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



_Last Updated: 2025-10-22_
