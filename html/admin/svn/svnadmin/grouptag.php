<?php

include("include/config.inc.php");

// Form request to create the user
$update = check_request_var('update');
//error_log(var_export($update,true));

if( $update )
{
  $appEngine->handleAction('update_tag');

  header("Location: index.php");
  exit(); 
}

?>
