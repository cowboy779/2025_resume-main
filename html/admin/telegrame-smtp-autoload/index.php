<?php
  include_once './config.php';
  
  // Class를 new하면 호출되는 함수
  spl_autoload_register(function($class) {
    try {
      // Class이름에 .class.php를 붙힌다.
      $filename = $class . ".class.php";

      print('<br/>');
    
      // include한다.
      include_once $_SERVER ["DOCUMENT_ROOT"] ."/admin/telegrame-smtp-autoload/". $filename;

    } catch (Exception $e) {
      
    // 에러가 발생하면 에러 메시지를 표시하자.
      ob_clean();
      echo "broken";
      
      var_dump($e);
      die();
    }
  });

  // require나 include 없이 Class1과 Class2를 호출한다.
  $class1 = new Class1();
  // $class2 = new Class2();
  //마찬가지로 호출한다.
  $class2 = Class2::getData();

  $cpuCnt = substr_count(file_get_contents('/proc/cpuinfo'), 'processor');
?>
<!DOCTYPE html>
<html>
  <head><title>title</title></head>
  <body>
    
    <?=$class1->run()?>
    <br />
    <?=$class2?>

    <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Noti 텔레그램 테스트</h4>
            </div>
            <!-- <div class="panel-body"> -->
            <div id="panelTest">
                <div class="panel-body">
                    <form method="POST" class="form-inline" action="send.php" target="_blank">
                        <select name="prjcode" class="form-control">
                        <?php
                          print("<option value='" . Config::$TELEGRAM_PRJCODE . "'>텔레그램 전송 테스트(TELEGRAM)</option>\n");
                        ?>
                        </select>

                        <select name="to" class="form-control">
                        <?php
                          print('<option value="'.Config::$TELEGRAM_TESTID.'">.' . Config::$TELEGRAM_TESTID . '</option>\n');
                        ?>
                        </select>
                        <br>
                        <input type="text" name="subject" value="" placeholder="Subject" class="form-control" size="60"><br>
                        <input type="text" name="message" value="" placeholder="Message" class="form-control" size="80">
                        <input type="submit" value="메일 테스트 전송" class="btn btn-primary">
                    </form>
                </div>
            </div>
    </div>

  </body>
</html>