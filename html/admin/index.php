<a href="google/glogin.php" onclick="signOut();"><b>[로그아웃]</b></a>
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
</script>

<?php 
 phpinfo();

?>