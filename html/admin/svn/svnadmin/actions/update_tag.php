<?php if (!defined('ACTION_HANDLING')) { die("HaHa!"); }

// Parameters.
$tag = get_request_var('tag');
$grouptagname = get_request_var('grouptagname');

// Validation
if ($tag == NULL)
{
  $appEngine->addException(new ValidationException(tr("You have to fill out all fields.")));
}
else
{
  // Create user object.
  $g = new \svnadmin\core\entities\Group;
  $g->id = $grouptagname;
  $g->name = $grouptagname;
  $g->tag = $tag;
  
  // error_log(var_export($g,true));
  // Create the user now.
  try
  {
    if ($appEngine->getGroupEditProvider()->updateGroupTag($g))
    {
      $appEngine->addMessage(tr("The group tag has been created successfully.", array($g->name)));
      $appEngine->getGroupEditProvider()->save();
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
