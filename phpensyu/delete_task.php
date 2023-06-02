<?php
session_start();

if(isset($_POST['delete_id'])){
    require_once "./db_connection.php";
    $stmt = $dbh->prepare("DELETE FROM tasks WHERE id=:id AND user_id =:user_id");
    $stmt->bindValue(':id',$_POST['delete_id']);
    $stmt->bindValue(':user_id',$_SESSION['login_id']);
    $stmt->execute();

 /* $stmt->rowCount() はexecuteで実行したSQLが影響したデータベースレコードの件数を取得する
     * これを使って、データの削除ができたかを確認し、フラッシュメッセージをセットする
     */
    if ($stmt->rowCount() > 0) { /* 削除できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
//        XXX done_task.php を参考に、$_SESSIONにフラッシュメッセージのタイプ(type)を設定する XXX
            'type' => 'delete',
//        XXX done_task.php を参考に、$_SESSIONにフラッシュメッセージの内容(content)を設定する XXX
            'content' => "id: {$_POST['delete_id']} のTodoを削除しました",
        ];
    } else { /* > 0 ではない場合(データを削除できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
//        XXX done_task.php を参考に、$_SESSIONにフラッシュメッセージのタイプ(type)を設定する XXX
            'type' => 'danger',
//        XXX done_task.php を参考に、$_SESSIONにフラッシュメッセージの内容(content)を設定する XXX
            'content' => '存在しないタスクのIDが指定されました',
        ];
    }
    /* $_POST['delete_id'] が送信されていた場合の処理、ここまで */

}else { /* $_POST['delete_id']が送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
//        XXX done_task.php を参考に、$_SESSIONにフラッシュメッセージのタイプ(type)を設定する XXX
            'type' => 'danger',
//        XXX done_task.php を参考に、$_SESSIONにフラッシュメッセージの内容(content)を設定する XXX
            'content' => '存在しないタスクのIDが指定されました',
    ];
}

/* 一覧画面に遷移する */
header("Location: index.php");