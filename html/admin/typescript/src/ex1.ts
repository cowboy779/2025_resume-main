type Age = number;
const myAge: Age = 30;

// Array
type Names = string[];
const myFriends: Names = ['Alice', 'Bob', 'Charlie'];
 
// Tuple
type Coordinates = [number, number];
const myLocation: Coordinates = [37.7749, -122.4194];
 
// 객체
type User = {
  id: string;
  name: string;
  age: number;
};
const user: User = { 
  id: '1', 
  name: 'John Doe', 
  age: 28 
};
 
// 함수
type GreetingFunction = (name: string) => string;
const greet: GreetingFunction = (name) => `익명의 1인 : ${name}!`;

// 애니메이션 효과 함수 (7번 기능)
function animateElement(elementOrId: string | HTMLElement, animationClass: string): void {
  let el: HTMLElement | null = null;
  if (typeof elementOrId === 'string') {
    el = document.getElementById(elementOrId);
  } else if (elementOrId instanceof HTMLElement) {
    el = elementOrId;
  }

  if (!el) return;
  el.classList.remove(animationClass);
  void el.offsetWidth; // reflow
  el.classList.add(animationClass);
}

// window에 안전하게 등록
if (typeof window !== 'undefined') {
  (window as any).animateElement = animateElement;
}

console.log(greet('TEST')); 
console.log(greet('World')); // Hello, World!
console.log(myAge); // 30
console.log(myFriends); // ['Alice', 'Bob', 'Charlie']  

console.log(myLocation); // [37.7749, -122.4194]
