---
marp: true
paginate: true
class: lead
title: "오정훈 - Portfolio"
style: |
  @import url('custom-theme-v4.css');
# theme: default
---

<!-- _class: title -->
# 😄🙂 안녕하세요, 지원자 오정훈입니다  
<!-- ### @CreatiCoding (Creative + Coding)   -->
### <https://github.com/cowboy779>  

---

## introduce [0]  
- ##### WEMADE MAX (LIGHTCON Co., Ltd 플랫폼 웹개발 기술지원팀)  
- ##### **웹기능 개발 6년차**  

라이트컨 경력으로 입사하여 현재는 웹개발자로 기술지원 파트를 담당하고 있습니다.  
팀 내 플랫폼 지원을 웹개발자를 신규로 자리편성하여, 
기술지원 및 안정화가 되도록 하는걸 목표로 하고 있습니다.  
현재는 기술지원팀 특성상 개발유지보수 인프라 구성이나 간단한 SVN/GIT 권한관리 및  
개발 VM 서버관리를 지원하고 있습니다.

> 모바일게임의 빠른 생존주기에 맞게 웹으로 전환되면서,
> 서버개발자 및 운영 사이에서 원활한 소통 협업과 가치창출을 위해 노력하고 있습니다.

---

## introduce [1]
저는 아래를 생각하고 고민합니다.

- 최소의 리소스로 최대의 효과  
- 성능 좋은 코드 < **읽기 좋은 코드**  
- 주석달린 코드 < **직관적인 코드**  
- 플랫폼 특성에 맞는 견고성 및 유지보수성 확보

---

## introduce [2]
저는 이것을 추구하고 열망합니다.  

- 최소의 장애방지를 위한 안정화된 개발 프로그램
- 사용자 편의 및 안정성을 위한 **DevOps(자동배포 및 스크립트화)**  
- **트러블 슈팅** (상태 추적, 로그분석, 문제해결)
- **성능 최적화** (규격화된 보일러플레이트, 협업툴 활용)

---

<!-- _backgroundImage: url('./images/bg_intro.jpg') -->
## Project [0]  
#### 프로모션 쿠폰 발급 시스템 구축
###### - **배경:** 자사내 다른게임에서도 연동해서 쓸수 있게 서버요청시 쿠폰 발급 기능
###### - **기술 스택:** PHP, MySQL
```php
$pendingMissionCodeList = array_diff($missionCodeList, array_keys($coupons));
if (count($pendingMissionCodeList) > 0) {
    $couponKey = LTFunction::getString('COUPONKEY', $couponData[0]);
    $result = createCrossPromoCoupons($couponId, $publisherId, $pendingMissionCodeList, $couponKey);
    if (is_string($result)) {
        return makeResponse(CouponResult::CROSSPROMO_ERROR_DB_INSERT, "Failed publish coupon (coupon_id : {$couponId}, mission_code : {$result}, publisher_id : {$publisherId})");
    }
    else {
        $coupons = array_merge($coupons, $result);
    }
}
return makeResponse(CouponResult::OK, $coupons);
```   
- **[cross-promotion](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/crosspromo/crosspromo.php)** : 쿠폰은 Admin에서 다량 생성해서 서버에서 처리하였지만,
  `이벤트 쿠폰은 서버에서 요청시 한장씩 동적 생성 후 등록`


---

## Project [1]  
### 라이트컨 웹 고객센터 FAQ 생성  
###### - **배경:** 구글 스토어 정책을 준수하며, 고객 개인정보 최소수집을 위한 이메일주소로만 페이지 구현  
###### - **기술 스택:** Python, flask, jinja2, MySQL  
- **[WEB FAQ](https://wemademax.com/faq)** : 고객센터 FAQ 페이지를 위한 데이터베이스 및 API 구축/ 관리툴 개발  
- 모바일 및 PC 웹에서 모두 접근 가능하도록 반응형 웹 구현/ 글로벌 서비스 대응
- 관리자 페이지에서 FAQ 콘텐츠 관리 기능 개발  

---

## Project [2]  
![Account](../ldap_login/images/account_list.png)  
### NAS LDAP 연동 및 전환 프로젝트
###### - **배경:** 내부 사내 NAS 시스템의 사용자 인증 및 권한 관리를 위해 LDAP 연동 
###### - **기술 스택:** NAS LDAP, Python, Php, MySQL

---

## Project [2]-2  
- **[LDAP LIST](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/ldap_login/internal.py)** : NAS LDAP 사용자 계정 리스트 조회 및 관리툴 개발
- 공통 그룹웨어가 아닌 내부에서 사용하기 위해, 사원 입퇴사 관리 조직 그룹망을 구현하기 위해 자동 batch > [batch script 보기](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/ldap_login/python_auto_shell/job_autoproc.py)
```
##### run_account.sh crontab 설정 예시
# 매주 월~금 09:30 ~ 19:30 매 시간 30분마다 실행
0,30 9-19 * * 1-5 /ltcon/account_site/run_account.sh

##### run_account.sh
#!/bin/bash
# 가상 환경 활성화
source /ltcon/venv/account_system/bin/activate
# Python 스케쥴링 스크립트 실행
python /ltcon/account_system/script/job_autoproc.py

```

---

## Project [3]
### 개발 테스트 서버 상황 및 내부그룹웨어 상황 알림시스템 필요
###### - **배경:** zabbix 모니터링 시스템과 내부 그룹웨어 상황 및 서버상태 알림을 위한 텔레그램 연동
###### - **기술 스택:** zabbix, Python, Php, Telegram API, SMTP, Linux
- **[Telegram-notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/telegram.py)**  
  Telegram API Bot 기반 및 SMTP 프로토콜을 사용한 메일 알림 라이브러리  
- 관리자 알림툴에서 `email, telegram, all` 선택적으로 알림 전송 가능




---

## improvement [0]
### 텔렘그램 및 SMTP 중복 방지 및 많은 양의 알림 전송 최적화
- 비동기 처리 및 재시도 메커니즘 구현으로 안정성 향상
- **[PHP : notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/job_notify.php)**  =>  **[Python : notifier](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/telegrame-smtp-autoload/python_auto_shell/job_notiqueue.py)**  
  기존 php 에서는 child process fork 및 프로세스 병렬처리로 제어 구현하였지만, 
  대량의 알림이 발생할 경우, 중복 전송 및 지연 문제 해결을 위해 Lock 과 서브프로세스 Task로 비동기로 개선

---
<!-- _class: img-small-right -->
## improvement [1]
### 바일로 칩 교환소 팝업 디자인 생성
![bylo](../bylo_design/bylo_exchange.png)

- **[BYLO EXCHANGE](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/bylo_design/index.html)**  
  스테이블 코인 기반의 게임 내 재화 교환소  
  팝업 UI 디자인 및 구현  
- **[WEMIX](https://wemadetree.gitbook.io/wemix-play-tech-int-guide/about-wemix3.0/account)** 플랫폼 참고
- HTML5, CSS3, JavaScript(jQuery) 기반의 반응형 웹 디자인

---

<!-- _class: section -->
## Project [0]  
### Next.js pm2 무중단 배포

- **http server의 close 함수**를 활용  
- **pm2 reload**를 통해 graceful stop 구현  

```js
process.on("SIGINT", () => {
  server.close(err => {
    if (err) throw err;
    process.exit(0);
  });
});
```

---

<!-- _class: section -->
<!-- _backgroundImage: url('./images/bg_intro.jpg') -->
## introduce [0]
**안녕하세요!**  
저는 **<span class="mark-blue">효율과 가독성</span>** 을 추구하는  
Front-end 개발자 **<span class="en">OMaLang</span>** 입니다.

---

<!-- _class: section -->
## 주요 기술
- Vue.js / React
- **<span class="mark-green">DevOps</span>** 경험: CI/CD, AWS
- 협업: GitHub Actions, Slack, Jira  

![|width=60%](./images/devops_chart.png)

---

<!-- _class: section -->
<!-- _backgroundImage: url('./images/bg_thanks.jpg') -->
# Thanks for Reading 🙌

| 구분 | 링크 |
|---|---|
| **GitHub** | `https://github.com/cowboy779` |
| **Email** | `cowboy779@naver.com` |
| **Mobile** | `010-8809-3586` |