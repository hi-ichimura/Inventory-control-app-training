<?php
session_start();
if(isset($_POST)){
    require_once "./db_connection.php";

    $stmt = $dbh->prepare("INSERT INTO tasks(title,detail,user_id) VALUE(?,?,?)");

    $stmt->execute([$_POST['title'],$_POST['detail'],$_SESSION['login_id']]);

    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "Todoを追加しました",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => 'Todoの追加に失敗しました',
        ];
    }
    /* $_POST データが送信されていた場合の処理、ここまで */

} else { /* $_POSTが送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
        'type' => 'danger',
        'content' => 'データが送信されていません',
    ];
}
/*一覧画面に戻る*/
header("Location:index.php");