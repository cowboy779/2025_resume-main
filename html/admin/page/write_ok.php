<?php include $_SERVER['DOCUMENT_ROOT']."/admin/page/db.php";

//각 변수에 write.php에서 input name값들을 저장한다
$username = $_POST['name'];
$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
$title = $_POST['title'];
$content = $_POST['content'];
$date = date('Y-m-d');

$noti = $_POST['noti'];
error_log(var_export($noti,true));
if(isset($noti)){
    $noti ="Y";
}else{
    $noti ="N";
}

if($username && $userpw && $title && $content){
    $sql = mq("insert into board(name,pw,title,content,date,noti) values('".$username."','".$userpw."','".$title."','".$content."','".$date."','".$noti."')");
    echo "<script> alert('글쓰기 완료되었습니다.');
    location.href='/admin/page/boardindex.php';</script>";
}else{
    echo "<script>
    alert('글쓰기에 실패했습니다.');
    history.back();</script>";
}
?>