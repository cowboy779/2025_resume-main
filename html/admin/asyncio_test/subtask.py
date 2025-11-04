import os
import time
import psutil
import sys

name = sys.argv[1]
pid = os.getpid()
cpu = psutil.Process(pid).cpu_num()

print(f"[{name}] PID={pid}, CPU={cpu} 시작")
start = time.time()

while time.time() - start < 10:
    _ = sum(i*i for i in range(10_000))

cpu_after = psutil.Process(pid).cpu_num()
print(f"[{name}] 완료. CPU={cpu_after}")