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
- **[cross-promo](https://github.com/cowboy779/2025_resume-main/blob/main/html/admin/crosspromo/crosspromo.php)**  
  ㅇpen source link:
`<https://github.com/cowboy779>`
 

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