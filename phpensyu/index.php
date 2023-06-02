<?php
session_start();

/*ログイン状態の確認*/
if(isset($_SESSION["logged_in"]) == false || $_SESSION["logged_in"] != true){
    header("Location:login.php");
}

/* 共通のデータベース連携処理をまとめたphpファイルをインクルード */
include_once "./db_access.php";

$res = get_todo_list($_SESSION['login_id']);
if ($res["result"] === true){
    /* データの取得に成功していた場合、htmlに埋め込むtable要素の内容を作る */
    $todo_items = generate_todo_table($res["stmt"]);
} else {
    /* データベースからレコードが取得できなかったら、$elmにはエラーメッセージを入れておく */
    $todo_items = "<tr><td class='alert alert-danger' colspan='3'>データの取得に失敗しました</td></tr>";
}
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>My ToDo リスト</h1>
    <!---ログアウト--->
    <div>
        <?php echo $_SESSION['login_name'] ?>
        <a href="./logout.php"><button>ログアウト</button></a>
    </div>
    <!---リスト追加--->
    <form method="POST" action="add_task.php">
        <div>
            <h2>リストを追加する</h2>
            <p>タイトル</p>
            <textarea name="title" ></textarea>
            <p>詳細</p>
            <textarea name="detail" ></textarea><br>
            <button type="submit" name="add_list">追加</button>
        </div>
    </form>

    <!---タスク編集--->
    <a href="edit_task.php?id= [$_SESSION('id')] ">タスク編集</a>

    <table>
        <thead>
            <tr>
                <th scope="col">件名</th>
                <th scope="col">詳細</th>
            </tr>
        </thead>
        <tbody>     
                    <?php
                        /* データベースから取得したTodoの内容を一覧表示
                         * Todoのデータがなかった場合、またはエラーが発生している場合は、
                         * Todoの一覧の代わりにそのメッセージが表示される
                         */
                        print($todo_items);
                    ?>
                </tbody>
    </table>
</body>
</html>
