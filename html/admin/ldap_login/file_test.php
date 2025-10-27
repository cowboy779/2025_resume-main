<?php
include 'config.php';

function GetReqVal($name) {
    if (isset($_REQUEST[$name])) {
        return trim($_REQUEST[$name]);
    }
    return null;
}

function GetStrVal($key, $obj, $default = '') {
    if (isset($obj[$key])) {
        if ((string) $obj[$key] == "0") {
            return "";
        }
        return trim((string) $obj[$key]);
    }
    if ($default === null) return null;
    return (string) $default;
}

function MoveTo($file) {
    if (substr($file, 0, 5) == 'https') {
        header('Location: '.$file);
    }else {
        header('Location: '.Config::$TEST_URL.$file);
    }
    exit;
}

$action = GetReqVal('action');

if ($action == 'add_filedata') {
    AddFileData();
}
if ($action == 'del_filedata') {
    DeleteFileData();
}

PrintFileDataInputForm();
PrintFileDataList();

//=====================================================================================================================

function UpdateConferenceCallData() {
    $showBtn = GetReqVal('showbtn');
    $linkUrl = GetReqVal('linkurl');
    $data = json_encode(["showBtn" => $showBtn, "linkUrl" => $linkUrl]);
    $file = "confcall.txt";
    file_put_contents(Config::$CONF_DIR.$file, $data);

    MoveTo("index.php?service=ir_05");
}

function AddFileData() {
    $title = GetReqVal('title');
    $regdate = GetReqVal('regdate');

    $uploadFileInfo = UploadFiles();

    if ($uploadFileInfo['result'] == false) {
        if (GetStrVal('savefile1', $uploadFileInfo))
            unlink(GetStrVal('savefile1', $uploadFileInfo));
        if (GetStrVal('savefile2', $uploadFileInfo))
            unlink(GetStrVal('savefile2', $uploadFileInfo));
        // Err::PrintError("파일 업로드에 실패하였습니다.\n", true);
    }

    // if (DB_AddFileData($title, $regdate, 
    // GetStrVal('filename1', $uploadFileInfo), 
    // GetStrVal('savefile1', $uploadFileInfo), 
    // GetStrVal('filename2', $uploadFileInfo), 
    // GetStrVal('savefile2', $uploadFileInfo)) == false) {
    //     Err::PrintError("[DB실패] 파일 업로드에 실패하였습니다.\n", true);
    // }

    // MoveTo("/admin/fileupload/file_test.php");
}

function UploadFiles() {
    $uploadDir = "/cowboy779/html/admin/fileupload/";

    $uploadFileInfo = array('result' => true, 'filename1' => null, 
    'savefile1' => null, 'filename2' => null, 'savefile2' => null);

    if ($_FILES['file1']['error'] == UPLOAD_ERR_OK) {
        $filename1 = basename($_FILES['file1']['name']);
        $ext1 = strtolower(pathinfo($filename1, PATHINFO_EXTENSION));

        if (!preg_match("/png|jpg|pdf|xls|ppt|doc|xlsx|pptx|docx|hwp|hwpx|zip/", $ext1)) {
            $uploadFileInfo['result'] = false;
        }
        else {
            // $savefile1 = sha1(__FILE__ . "file1" . microtime(true));
            $savefile1 = "";
            
            if (move_uploaded_file($_FILES['file1']['tmp_name'], "{$uploadDir}/{$filename1}") == false) {
                $uploadFileInfo['result'] = false;
            } else {
                $uploadFileInfo['filename1'] = $filename1;
                $uploadFileInfo['savefile1'] = $savefile1;
            }
        }
    }
    return $uploadFileInfo;
}

function DeleteFileData() {
    $deleteFileDataIdx = GetReqVal('DELETE_IDX');
    if ($deleteFileDataIdx) {
        $data = DB_GetFileData($deleteFileDataIdx);
        // 게시물을 가져와서 있는지 검사
        if ($data == null) {
            ErrMsg("데이터가 없습니다.");
            return;
        }
        // 파일이 있다면 파일 삭제
        $uploadDir = Config::$FILE_DIR;
        $savefile1 = GetStrVal('savefile1', $data);
        if (IsValidString($savefile1))
            unlink("{$uploadDir}/{$savefile1}");
        $savefile2 = GetStrVal('savefile2', $data);
        if (IsValidString($savefile2))
            unlink("{$uploadDir}/{$savefile2}");
        // 이후 DB삭제
        if (DB_DeleteFileData($deleteFileDataIdx) == false) {
            Err::PrintError("[DB실패] 삭제에 실패하였습니다.\n");
        }
    }
}


function PrintFileDataInputForm() {
    // $today = Today();
    // $today = substr($today, 0, 4) . "-" . substr($today, 4, 2) . "-" . substr($today, 6, 2);
    
    ?>
    <section>
        <article class="page_top">
            <h2>파일업로드 자료실</h2>
        </article>
        
        <!--------------------------------------------------------------------->
        <article class="page_con ir_02_page">
            <div><b>TEST URL 링크</b></div>
            
        </article>

        <!--------------------------------------------------------------------->
        <article class="page_con ir_02_page">
            <div><b>파일업로드 자료실 등록</b></div>
            <form id="form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_filedata">
                <fieldset>
                    <legend>등록</legend>
                    <table class="table_style2">
                        <caption>WRITE</caption>
                        <colgroup>
                            <col style="width:10%">
                            <col style="width:90%">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="row">제목1</th>
                                <td class="align_left"><input type="text" class="input_style2" id="title" name="title" title="제목" maxlength="100"></td>
                            </tr>
                            
                            <tr>
                                <th scope="row">파일1</th>
                                <td class="align_left">
                                    <input type="file" name="file1" id="upload"> (업로드 가능 파일 : png, jpg, pdf, xls, xlsx, ppt, pptx, doc, docx, hwp, hwpx, zip)
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <div>
                        <button type="submit" class="btn_style1">등록</button>
                    </div>
                </fieldset>
            </form>
        </article>
    </section>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(function () {
            $('#form').on('submit', function (e) {
                e.preventDefault();
                var $form = $(this);

                // 검증
                var title = $('#title').val().trim();
                var file1 = $('#upload').val().trim();

                if (title === '') {
                    alert('제목을 입력해주세요.');
                    $('#title').focus();
                    return false;
                }

                if (file1 === '') {
                    alert('파일을 선택해주세요.');
                    $('#upload').focus();
                    return false;
                }

                // 검증 통과하면 폼 제출
                $form.off('submit'); // 무한 루프 방지
                $form.submit();
            });
        });
    </script>
    <?php
}

function PrintFileDataList() {
    ?>
    <script type="text/javascript">
        function deleteFileData(idx) {
            fn_confirm(idx + '번 항목을 삭제하시겠습니까?', function () {
                location.href = "index.php?service=ir_05&action=del_filedata&DELETE_IDX=" + idx;
            });
        }
    </script>
    
    
    <?php
}
?>