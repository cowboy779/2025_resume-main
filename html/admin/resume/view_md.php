<?php
require_once 'Parsedown.php';

# or resume2.md
$markdown_file = 'resume3.md';

if (!file_exists($markdown_file)) {
    die("파일이 존재하지 않습니다: $markdown_file");
}
$markdown_text = file_get_contents($markdown_file);

$Parsedown = new Parsedown();
$html_content = $Parsedown->text($markdown_text);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>Markdown Viewer</title>
  <style>
    body {
      font-family: 'Pretendard', sans-serif;
      line-height: 1.6;
      max-width: 800px;
      margin: 50px auto;
      padding: 0 20px;
      color: #333;
      background: #fafafa;
    }
    pre {
      background: #272822;
      color: #f8f8f2;
      padding: 10px;
      border-radius: 6px;
      overflow-x: auto;
    }
    code { font-family: Consolas, monospace; }
    h1, h2, h3 { border-bottom: 1px solid #ddd; padding-bottom: .3em; }
  </style>
</head>
<body>

<?= $html_content ?>

</body>
</html>