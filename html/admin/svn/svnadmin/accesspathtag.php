<?php
//250723 태그 인터페이스 추가
include("include/config.inc.php");

$update = check_request_var('update');

if( $update )
{
  $appEngine->handleAction('update_access_tag');

  header("Location: index.php");
  exit(); 
}

?>
