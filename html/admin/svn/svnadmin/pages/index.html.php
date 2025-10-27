<?php GlobalHeader(); ?>

<h1>
  <?php Translate("Welcome"); ?>
  <?php if (IsUserLoggedIn()) { ?><?php SessionUsername(); ?><?php } ?>
</h1>

<table class="datatable">
  <colgroup>
    <col width="150">
    <col>
  </colgroup>
  <thead>
    <tr>
      <th colspan="2"><?php Translate("General information"); ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php Translate("Application version"); ?></td>
      <td><?php AppVersion(); ?> <small>(<a href="http://svnadmin.insanefactory.com/"><?php Translate("Check for updates"); ?></a>)</small></td>
    </tr>
    <tr>
      <td><?php Translate("PHP version"); ?></td>
      <td><?php PrintStringValue("PHPVersion"); ?></td>
    </tr>
    <?php if (IsUserLoggedIn()) { ?>
    <tr>
      <td><?php Translate("Logged in as"); ?></td>
      <td><?php SessionUsername(); ?></td>
    </tr>
    <?php } else { ?>
    <tr>
      <td><?php Translate("Logged in as"); ?></td>
      <td><?php Translate("Guest"); ?></td>
    </tr>
    <?php } ?>
    <?php if (IsProviderActive(PROVIDER_AUTHENTICATION)) { ?>
    <tr>
      <td><?php Translate("Roles of user"); ?></td>
      <td>
        <ul>
          <?php foreach (GetArrayValue("Roles") as $r) { ?>
          <li><?php Translate($r->name); ?> - <i><?php Translate($r->description); ?></i></li>
          <?php } ?>
        </ul>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<?php if(AppEngine()->getAclManager()->userHasRole(AppEngine()->getSessionUsername(), 'Administrator')) { ?>
<table class="datatable">
  <colgroup>
      <col style="width: 15%;">
      <col style="width: 25%;">
      <col style="width: 10%;">
      <col style="width: 30%;">
      <col style="width: 20%;">
  </colgroup>
  <thead>
  <tr>
    <th><?php Translate("Group"); ?></th>
    <th>Paths</th>
    <th>Members</th>
    <th>Unlink</th>
    <th>Tags</th>
  </tr>
  </thead>

<tbody>
<?php foreach (GetArrayValue("GroupDatas") as $g) { ?>
<tr>
  <td><a href="groupview.php?groupname=<?php print($g['info']->getEncodedName()); ?>"><?php print($g['info']->name); ?></a></td>
  <td>
      <?php foreach ($g['path'] as $ap): ?>
        <a href="accesspathview.php?accesspath=<?php echo $ap->getEncodedPath(); ?>"><?php echo $ap->getPath(); ?></a><br>
      <?php endforeach; ?>
  </td>
  <td>
    <?php foreach ($g['member'] as $u): ?>
      <a href="userview.php?username=<?php print($u->getEncodedName()); ?>"><?php print($u->getDisplayName()); ?></a><br>
      <?php endforeach; ?>
    </td>
    <td><?php print(implode('<br>', $g['unlink'])); ?></td>
    
    <!-- 240923 작성 -->
    <td>
      <form method="post" action="grouptag.php">
        <input type="hidden" name="grouptagname" value="<?php print($g['info']->getEncodedName()); ?>" >
        <input type="text" name="tag" id="tag" class="lineedit" value="<?php print($g['tag']); ?>" >
      
        <!-- 이미지는 좌표값으로 넘어감 -->
        <!-- <input type="image" src="templates/icons/accept.png" name="update" alt="<?php Translate("Update tag"); ?>" title="<?php Translate("Update tag"); ?>" class="uppbtn"> -->
        <input type="submit" name="update" value="<?php Translate('edit'); ?>" class="uppbtn">
      
      </form>
    </td>
</tr>

<?php } ?>
</tbody>
</table>



<!-- <table id="accesspathlist" class="datatable">
<thead>
<tr>
    <th><php Translate("Access-Path"); ?></th>
    <th>Members</th>
    <th>Unlink</th>
    <th>Tags</th>
</tr>
</thead>

<tbody>
<php foreach (GetArrayValue("AccessPathDatas") as $ap): ?>
  <tr>
    <td>
      <a href="accesspathview.php?accesspath=<php echo $ap['info']->getEncodedPath(); ?>">
        <php echo $ap['info']->getPath(); ?></a><br>
    </td>
    <td>
        <php foreach ($ap['member'] as $u): ?>
        <a href="userview.php?username=<?php print($u->getEncodedName()); ?>">
          <php print($u->getEncodedName()); ?></a><br>
          
        <php endforeach; ?>
    </td>
    <td>
        <php foreach ($ap['unlink'] as $u): ?>
        <php print($u->getEncodedName()); ?></a><br>
        <php endforeach; ?>
    </td>
    <td>
    </td>
  </tr>
<php endforeach; ?>
</tbody>
</table> -->



<table id="accesspathlist" class="datatable">
<thead>
    <colgroup>
       <col style="width: 25%;">
       <col style="width: 25%;">
       <col style="width: 8%;">
       <col style="width: 20%;">
    </colgroup>
<tr>
    <th><?php Translate("Access-Path"); ?></th>
    <th>Groups</th>
    <th>Members</th>
    <th>Unlink</th>
    <th>Tags</th>
</tr>
</thead>

<tbody>
<?php foreach (GetArrayValue("AccessPathDatas") as $ap) : ?>

<tr>
  <td>
    <a href="accesspathview.php?accesspath=<?php echo $ap['info']->getEncodedPath(); ?>"><?php echo $ap['info']->getPath(); ?></a><br>
  </td>
  <td>
      <?php foreach ($ap['group'] as $g): ?>
<!--      <?php print($g->name); ?><br>-->
      <a href="groupview.php?groupname=<?php print($g->getEncodedName()); ?>"><?php print($g->name); ?></a>
      <?php endforeach; ?>
  </td>
  <td>
      <?php foreach ($ap['member'] as $u): ?>
      <a href="userview.php?username=<?php print($u->getEncodedName()); ?>"><?php print($u->getEncodedName()); ?></a><br>
      <?php endforeach; ?>
  </td>
  <td>
    <?php print(implode('<br>', $ap['unlink'])); ?>
  </td>
  <!-- 250723 access-path 기준으로 tags 부분 -->
  <td>
    <form method="post" action="accesspathtag.php" style="display:flex; gap:6px; align-items:center; background:#f8fafc; padding:6px 10px; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
      <input type="hidden" name="accesspath_tagname" value="<?php print(urldecode($ap['info']->getEncodedPath())); ?>" >
      <input type="text" name="tag" id="tag_<?php print(urldecode($ap['info']->getEncodedPath())); ?>" class="lineedit" value="<?php print($ap['tag']); ?>" style="flex:1; padding:7px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:1em; background:#fff; transition:border 0.2s;">
      <input type="submit" name="update" value="<?php Translate('edit'); ?>" class="uppbtn graybtn">
    </form>
    <style>
      .lineedit:focus { border:1.5px solid #888 !important; outline:none; }
      .graybtn {
        background: #f3f4f6 !important;
        color: #222 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px;
        padding: 7px 18px;
        font-size: 1em;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, border 0.2s;
      }
      .graybtn:hover {
        background: #e5e7eb !important;
        color: #111 !important;
        border: 1.5px solid #bdbdbd !important;
      }
    </style>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>




<?php } else { ?>



<table class="datatable">
<thead>
<tr>
  <th>내가 포함된 SVN 그룹 리스트</th>
  <th>그룹 구성원</th>
</tr>
</thead>

<tbody>
<?php foreach (GetArrayValue("GroupDatas") as $g) { ?>
<tr>
    <td align="center"><b><?php print($g['info']->name); ?></b></td>
    <td style="padding: 1em;">
        <?php foreach ($g['member'] as $u): ?>
        <!-- <php print($u->getUserInfo()); ?><br> -->
        <?php print($u->getEncodedName()); ?><br>
        <?php endforeach; ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>

<table id="accesspathlist" class="datatable">
<thead>
<tr><th>내가 접근 가능한 SVN경로 리스트</th></tr>
</thead>
<tbody>
<?php foreach (GetArrayValue("AccessPathDatas") as $ap) : ?>
<tr>
    <td style="padding: 1em;"><b><?php print("https://alpha.lightcon.net:8015/svn/".str_replace(':/', '/', $ap->getPath())); ?></b></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>


<?php } ?>


<?php GlobalFooter(); ?>

