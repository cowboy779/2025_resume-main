"use strict";
const myAge = 30;
const myFriends = ['Alice', 'Bob', 'Charlie'];
const myLocation = [37.7749, -122.4194];
const user = {
    id: '1',
    name: 'John Doe',
    age: 28
};
const greet = (name) => `익명의 1인 : ${name}!`;
// 애니메이션 효과 함수 (7번 기능)
function animateElement(elementOrId, animationClass) {
    let el = null;
    if (typeof elementOrId === 'string') {
        el = document.getElementById(elementOrId);
    }
    else if (elementOrId instanceof HTMLElement) {
        el = elementOrId;
    }
    if (!el)
        return;
    el.classList.remove(animationClass);
    void el.offsetWidth; // reflow
    el.classList.add(animationClass);
}
// window에 안전하게 등록
if (typeof window !== 'undefined') {
    window.animateElement = animateElement;
}
console.log(greet('TEST'));
console.log(greet('World')); // Hello, World!
console.log(myAge); // 30
console.log(myFriends); // ['Alice', 'Bob', 'Charlie']  
console.log(myLocation); // [37.7749, -122.4194]
