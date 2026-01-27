<?php
/**
 * iF.SVNAdmin
 * Copyright (c) 2010 by Manuel Freiholz
 * http://www.insanefactory.com/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2
 * of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.
 */
include_once("include/config.inc.php");

$appEngine->checkUserAuthentication();
$appTR->loadModule("index");
$appTR->loadModule("roles");

error_log(var_export('index입장',true));

// PHP version.
$phpVersion=phpversion();

// Roles of current user.
$roles=null;
if ($appEngine->isAuthenticationActive())
{
  $u=new \svnadmin\core\entities\User();
  $u->name=$appEngine->getSessionUsername();
  
  $roles=$appEngine->getAclManager()->getRolesOfUser($u);
  sort($roles);
}
SetValue("PHPVersion", $phpVersion);
SetValue("Roles", $roles);

//==============
// Parameters.
$tags = get_request_var('tag');
// error_log(var_export($tags,true));

//======================================================================================================================
$allusers = $appEngine->getUserViewProvider()->getUsers();
//usort($allusers, array('\svnadmin\core\entities\User',"compare"));
//$allusers = array_values($allusers);
//SetValue("AllUserList", $allusers);

$allUserList = [];
foreach ($allusers as $key => &$val) {
    if (is_object($val) && property_exists($val, "name")) {
        $allUserList[$val->name] = $val;
    }
}

if($appEngine->getAclManager()->userHasRole($appEngine->getSessionUsername(), 'Administrator')) {
    // Get all groups and sort them by name.
    $groupDatas = [];
    $groups = $appEngine->getGroupViewProvider()->getGroups();
    usort( $groups, array('\svnadmin\core\entities\Group',"compare") );

    foreach ($groups as $oGroup) {
        //$oGroup = new \svnadmin\core\entities\Group;
        //$oGroup->id = $groupname;
        //$oGroup->name = $groupname;
        $users = $appEngine->getGroupViewProvider()->getUsersOfGroup( $oGroup );
        usort( $users, array('\svnadmin\core\entities\User',"compare") );

        $paths = $appEngine->getAccessPathViewProvider()->getPathsOfGroup( $oGroup );
        usort( $paths, array('\svnadmin\core\entities\AccessPath',"compare") );

        //$oGroup->id;
        $gd = ['info'=>$oGroup, 'path'=>[], 'member'=>[], 'unlink'=>[], 'tag'=>$oGroup->tag];
        
        foreach ($paths as $oPath) {
            $gd['path'][] = $oPath;
        }
        foreach ($users as $oUser) {
            if (array_key_exists($oUser->name, $allUserList)) {
                $gd['member'][] = $allUserList[$oUser->name];
            }
            else {
                $gd['unlink'][] = $oUser->name;
            }
        }
        $groupDatas[] = $gd;
    }
    SetValue("GroupDatas", $groupDatas);

    // Access Path
    $pathDatas = [];
    $paths = $appEngine->getAccessPathViewProvider()->getPaths();
    
    usort($paths, array('\svnadmin\core\entities\AccessPath', "compare"));
    

    foreach ($paths as $oPath) {
        //$o = new \svnadmin\core\entities\AccessPath;
        //$o->path = $accesspath;
        $users = $appEngine->getAccessPathViewProvider()->getUsersOfPath($oPath);
        usort( $users, array('\svnadmin\core\entities\User',"compare") );

        $groups = $appEngine->getAccessPathViewProvider()->getGroupsOfPath($oPath);
        usort($groups, array('\svnadmin\core\entities\Group',"compare"));

        $pd = ['info'=>$oPath, 'group'=>[], 'member'=>[], 'unlink'=>[], 'tag'=>$oPath->tag];
        foreach ($groups as $oGroup) {
            $pd['group'][] = $oGroup;
        }
        foreach ($users as $oUser) {
            if (array_key_exists($oUser->name, $allUserList)) {
                $pd['member'][] = $allUserList[$oUser->name];
            }
            else {
                $pd['unlink'][] = $oUser->name;
            }
        }
        $pathDatas[] = $pd;
    }
    //
    //error_log('원래 있떤');
    //error_log(var_export($pathDatas,true));
    SetValue("AccessPathDatas", $pathDatas);
}
else {
    $groups = $appEngine->getAccessPathViewProvider()->getGroupsOfUser($u);
    usort( $groups, array('\svnadmin\core\entities\Group',"compare") );
    
    $groupDatas = [];
    $groups = $appEngine->getAccessPathViewProvider()->getGroupsOfUser($u);
    usort( $groups, array('\svnadmin\core\entities\Group',"compare") );
    foreach ($groups as $oGroup) {
        $users = $appEngine->getGroupViewProvider()->getUsersOfGroup( $oGroup );
        usort( $users, array('\svnadmin\core\entities\User',"compare") );

        $gd = ['info'=>$oGroup, 'path'=>[], 'member'=>[]];
        foreach ($users as $oUser) {
            if (!array_key_exists($oUser->name, $allUserList)) continue;
            $gd['member'][] = $allUserList[$oUser->name];
        }
        $groupDatas[] = $gd;
    }
    SetValue("GroupDatas", $groupDatas);
    
    $paths = $appEngine->getAccessPathViewProvider()->getPathsOfUser($u);
    usort($paths, array('\svnadmin\core\entities\AccessPath', "compare"));
    SetValue("AccessPathDatas", $paths);
}
//======================================================================================================================





ProcessTemplate("index.html.php");
?>