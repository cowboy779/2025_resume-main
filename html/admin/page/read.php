<?php include $_SERVER['DOCUMENT_ROOT']."/admin/page/db.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$suer_id = $_SESSION['user_id'];
$suer_role = $_SESSION['user_role'];

?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/admin/page/css/style.css" />
</head>
<body>
	<?php
		$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
		$hit = mysqli_fetch_array(mq("select * from board where idx ='".$bno."'"));
		$hit = $hit['hit'];
		$fet = mq("update board set hit = '".$hit."' where idx = '".$bno."'");
		$sql = mq("select * from board where idx='".$bno."'");
		$board = $sql->fetch_array();
	?>
<!-- 글 불러오기 -->
<div id="board_read">
	<h2><?php echo $board['title']; ?></h2>
		<div id="user_info">
			<?php echo $board['name']; ?> <?php echo $board['date']; ?> 조회 :<?php echo $board['hit']; ?> 추천 :<?php echo $board['thumbup']; ?>
				<div id="bo_line"></div>
			</div>
			<div id="bo_content">
				<?php echo nl2br("$board[content]"); ?>
			</div>
	<!-- 목록, 수정, 삭제 -->
	<div id="bo_ser">
		<ul>
			<li><a href="/admin/page/boardindex.php">[목록으로]</a></li>
            <li><a href="thumbup.php?idx=<?php echo $board['idx']; ?>">[추천]</a></li>

			<?php if($suer_role =="ad"|| isset($suer_id) && $board['name'] == $suer_id){ ?>
				<li><a href="modify.php?idx=<?php echo $board['idx']; ?>">[수정]</a></li>
				<li><a href="delete.php?idx=<?php echo $board['idx']; ?>">[삭제]</a></li>
			<?php } ?>
		</ul>
	</div>
</div>
</body>
</html>