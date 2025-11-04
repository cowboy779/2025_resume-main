<?php

//======================================================================================================================
// LOCK이 걸리지 않았거나, 중복 실행이라고 판단되면 종료
if ((trim(file_get_contents("/proc/".posix_getppid()."/comm")) != 'flock') // flock으로 실행 되었는지 체크(flock 실행 권장)
        && (int)exec("pgrep -c -f '".basename(__FILE__)."'") > 1) // flock으로 실행되지 않았을 경우, 프로세스 갯수 체크
{
    exit;
}
//======================================================================================================================

chdir(dirname(__FILE__));

require 'init.php';

// 대략 1분 정도 실행(1초 단위로)
while (true) {
    SendQueueProc();
    sleep(1);
    DB()->Disconnect();
    if ((int)date('s') >= 58) {
        exit;
    }
}

// 다른 프로세스로 메시지를 전송한다.
function SendQueueProc() {
    $datas = Helper::GetNotiQueueDatas();
    if (count($datas) < 1) {
        return;
    }

    //$cpuCnt = substr_count(file_get_contents('/proc/cpuinfo'), 'processor'); // 프로세서 수 반환
    //$cpuCnt = 4;
    $cpuCnt = (int)(trim(shell_exec('nproc')) * 1.5); // I/O가 많거나 DB API 호출 대기 시간
    for ($i = 0; $i < $cpuCnt; $i++) { // CPU 수 만큼 프로세스 실행
        if (count($datas) < 1) break;
        $data = array_shift($datas);
        SendProc($data);
    }

    // child process가 죽을 때마다 체크해서 계속 job 할당
    while (pcntl_waitpid(0, $status) != -1) {
        //$status = pcntl_wexitstatus($status);
        if (count($datas) < 1) continue;
        $data = array_shift($datas);
        SendProc($data);
    }
}

// 처리할 job 데이터를 넘겨받아 child process fork해서 단위계산 수행
function SendProc($data)
{
    $pid = pcntl_fork();

    // error
    if ($pid == -1) {
        echo '[ERROR] could not fork'."\n";
        exit;
    }
    // child
    else if ($pid == 0) {
        $id = GetIntVal('id', $data);
        exec("php proc_sendmessage.php $id");
        exit;
    }
    // parent
    else {
    }
}
