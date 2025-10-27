<?php include  $_SERVER['DOCUMENT_ROOT']."/admin/page/db.php";
    if ( !isset($_POST['user_id']) || !isset($_POST['user_pw']) ) {
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script>alert('아이디 또는 비밀번호가 빠졌거나 잘못된 접근입니다.');";
        echo "window.location.replace('/admin/page/pagelogin.php');</script>";
        exit;
    }

    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];

    //$members = array('1' => array('password' => '1', 'name' => '관리자') );

    $sql = mq("select * from boarduser where id= '$user_id' and pw = '$user_pw'");
    $row_num = mysqli_num_rows($sql); //로그인성공 레코드
    $rowData = mysqli_fetch_array($sql);

    if($row_num == 0){
        header("Content-Type: text/html; charset=UTF-8");
        echo "<script>alert('아이디 또는 비밀번호가 잘못되었습니다.');";
        echo "history.back(); </script>";
        exit;
    }
       
    /* If success */
    session_start();

    $user_role = $rowData['role'];
    
    if($user_role == "ad"){
        echo "<script>alert('관리자로 로그인되었습니다.'); </script>";
    }
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_role'] = $user_role;
    
    // if( !isset($members[$user_id]) || $members[$user_id]['password'] != $user_pw ) {
    //     header("Content-Type: text/html; charset=UTF-8");
    //     echo "<script>alert('아이디 또는 비밀번호가 잘못되었습니다.');";
    //     echo "history.back(); </script>";
    //     exit;
    // }
?>
<meta http-equiv="refresh" content="0;url=boardindex.php" />