<?php
/*
 * db_access.php
 * データベースへアクセスをしてデータを取得する関数をまとめたファイルです
 * このファイルをインクルードして、関数を呼び出すことで、index.phpなどのプログラム記述の見通しが良くなります
 */

/* get_todo_list()
 * データベースから、ログイン中のユーザーのTodoのコードを取り出します
 */

function get_todo_list($user_id){

    require_once "./db_connection.php";

    $stmt= $dbh->prepare("SELECT *FROM tasks WHERE user_id = ?");
    try{
        $ret = $stmt->execute([$user_id]);

        if($ret === true){
            //データ取得成功
            return(["result" => true,"stmt" => $stmt]);
        }else{
            return (["result" => false, "stmt" => $stmt]);
        }
    }
    catch(PDOException $e){
        return (["result" => false, "exeption" => $e]);
    }

}

function generate_todo_table($stmt){
    if($stmt->rowCount() === 0){
        return ("<tr><td colspan='3'>データがありません</td></tr>");
    }

    $elms = "";
    while($item = $stmt->fetch()){
        $tr = "<tr>
		    <td>{$item['title']}</td>
		    <td>{$item['detail']}</td>
			<td>
				<div>
					<form action='./done_task.php' method='POST'>
						<button type='submit' name='done_id' value='{$item['id']}'>完了</button>
					</form>
					<form action='./delete_task.php' method='POST'>
						<button type='submit' name='delete_id' value='{$item['id']}'>削除</button>
					</form>
				</div>
			</td>
		</tr>";
        /* $elmsに、今回の処理で作成した$trの内容を追記する */
		$elms .= $tr;
	}
	return ($elms); /* $elmsは、最初から最後まですべての$tr の内容を結合した内容になっている */
}