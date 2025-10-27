<?php include $_SERVER['DOCUMENT_ROOT']."/admin/page/db.php";

$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
$hit = mysqli_fetch_array(mq("select * from board where idx ='".$bno."'"));
$hit = $hit['hit'] +1;

mq("update board set hit = '".$hit."' where idx = '".$bno."'");

//echo "<script> location.replace='/admin/page/read.php?idx=<?php echo $bno ?>'; </script>;";

?>
<meta http-equiv="refresh" content="0 url=/admin/page/read.php?idx=<?php echo $bno; ?>">


