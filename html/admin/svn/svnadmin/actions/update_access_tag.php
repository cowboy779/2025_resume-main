<?php if (!defined('ACTION_HANDLING')) { die("HaHa!"); }

// Parameters.
$tag = get_request_var('tag');
$accesspath_tag_name = get_request_var('accesspath_tagname');

// Validation
if ($tag === null) {
  $tag = '';
}
else
{
  // Create access-path object.
  $ap = new \svnadmin\core\entities\AccessPath;
  $ap->path = $accesspath_tag_name;
  $ap->tag = $tag;

  // error_log(var_export($ap,true));
  // Update the access-path tag now.
  try
  {
    if ($appEngine->getAccessPathEditProvider()->updateAccessPathTag($ap))
    {
      $appEngine->addMessage(tr("The access-path tag has been created successfully.", array($ap->path)));
      $appEngine->getAccessPathEditProvider()->save();
    }
    else
    {
      $appEngine->addException(new Exception(tr("An unknown error occured. Check your configuration, please.")));
    }
  }
  catch (Exception $ex)
  {
    $appEngine->addException($ex);
  }

}
?>
