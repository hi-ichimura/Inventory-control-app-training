<?php
session_start();
$err_msg = "";


//sessionが一致していればindex.phpに飛ぶ
//値が入っているか、trueかどうか（省略して書いている）
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location: index.php");
}






//入力エラーの確認
if (empty($_POST) === false) {
    if (empty($_POST["user_email"]) === false || empty($_POST["password"]) === false) {
        /* ログイン判定用の関数を実行 */
        $err_msg = auth_check();
    } else {
        $err_msg = "<p>ID・パスワードを入力してください</p>";
    }
}

/*ログイン判定*/
function auth_check()
{

    require_once "db_connection.php";

    try {
        //入力したユーザーIDに時のデータのIDとパスワードを選択する
        $stmt = $dbh->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$_POST['user_email']]);
        //検索結果を配列にする
        $correct_user = $stmt->fetch();

        if ($stmt->rowCount() > 0) { /* SQLの検索結果が1件以上のとき */
            if (md5($_POST["password"]) === $correct_user["password"]) {
                /* ハッシュ化した入力パスワードと、データベースの保存内容が同じ -> 認証成功) */
                $_SESSION["login_id"] = $correct_user["id"];//セッションid
                $_SESSION["login_name"] = $_POST["user_email"];//セッションidの名前
                $_SESSION["logged_in"] = true;//sessionセット完了

                $dbh = null;
                header("Location: index.php");//マイページに飛ぶ
                exit();
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }

    $dbh = null;
    $_SESSION["logged_in"] = false;
    return ("IDまたはパスワードが正しくありません");
}

?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
/* エラーメッセージを表示する */
if ($err_msg !== "") {
print('<div class="alert alert-danger" role="alert">');
print($err_msg);
print('</div>');
}
?>

    <form method="POST" action="login.php">
            <div>
                <p>メールアドレス</p>
                <input name="user_email" type="text" placeholder="メールアドレス">
            </div>
            <div>
                <p>パスワード</p>
                <input name="password" type="password" placeholder="パスワード">
            </div>
            <button type="submit" name="syounin">認証</button>
        </form>
</body>
</html>
