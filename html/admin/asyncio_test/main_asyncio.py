import asyncio
import os

async def worker(name):
    process = await asyncio.create_subprocess_exec(
        "python", "subtask.py", name,
        stdout=asyncio.subprocess.PIPE,
        stderr=asyncio.subprocess.PIPE,
    )
    stdout, stderr = await process.communicate()
    print(stdout.decode().strip())

async def main():
    tasks = []
    for i in range(4):
        tasks.append(asyncio.create_task(worker(f"job-{i+1}")))
    await asyncio.gather(*tasks)

if __name__ == "__main__":
    asyncio.run(main())