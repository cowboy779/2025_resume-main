<?php
	include $_SERVER['DOCUMENT_ROOT']."/admin/page/db.php";
   
	$bno = $_GET['idx'];
    $thumbup = mysqli_fetch_array(mq("select thumbup from board where idx='$bno';"));
    $thumbup = $thumbup['thumbup'] + 1;
    mq("update board set thumbup=".$thumbup." where idx=".$bno.";");

    ?>
    <script type="text/javascript">alert("추천되었습니다.");</script>
    <meta http-equiv="refresh" content="0 url=/admin/page/read.php?idx=<?php echo $bno; ?>">