<?php session_start();  

$suser_id = $_SESSION['user_id'];
$suser_role = $_SESSION['user_role'];

?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/admin/page/css/style.css" />
</head>
<body>
    <div id="board_write">
        <h1><a href="/admin/page/boardindex.php">자유게시판</a></h1>
        <h4>글을 작성하는 공간입니다.</h4>
            <div id="write_area">
                <form action="write_ok.php" method="post">
                    <div id="in_title">
                        <textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_name">
                        <textarea name="name" id="uname" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required><?php echo $suser_id; ?></textarea>
                    </div>
                    <div class="wi_line"></div>
                    <div id="in_content">
                        <textarea name="content" id="ucontent" placeholder="내용" required></textarea>
                    </div>
                    <div id="in_pw">
                        <input type="password" name="pw" id="upw"  placeholder="비밀번호" required />  
                        <?php if($suser_role =="ad" ){ ?>
                            <input type="checkbox" name="noti" id="noti"  placeholder="공지사항 등록" />공지사항 등록
                        <?php }?>
                    </div>
                    <div class="bt_se">
                        <button type="submit">글 작성</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>