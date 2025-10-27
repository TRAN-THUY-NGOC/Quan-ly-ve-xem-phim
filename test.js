import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
  vus: 180,          // 500 virtual users
  duration: '5m',    // chạy 2 phút (thay đổi tuỳ mục tiêu)
};

export default function () {
  const res = http.get('https://nguyenphat22.github.io/CodeMultipleChoiceGame2025/'); // đổi URL nếu cần
  check(res, { 'status 500': (r) => r.status === 500 });
  sleep(1); // hành vi user đơn giản: chờ 1s
}