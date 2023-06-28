<?php

    date_default_timezone_set("Asia/Tokyo");

    $comment_array = array();
    $pdo = null;
    $stmt = null;
    $error_messages = array();

    try{
    //mysqlにデータを接続しているとき    
    $pdo = new PDO('mysql:host=localhost;dbname=2channel', "root", "root");
    } catch (PDOException $e) {
    //mysqlのデータを取得できていない時
        echo $e->getMessage();
    }
    //formタグで送信された内容を投稿する
    //submitButtonに文字列が存在しているとき
    if(!empty($_POST["submitButton"])){

        if(empty($_POST["username"])){
            echo "名前を入力してください";
            $error_messages = "名前を入力してください";
        }
        if(empty($_POST["comment"])){
            echo "本文を入力してください";
            $error_messages = "本文を入力してください";
        }

        if(empty($error_messages)){
        $postDate = date("Y-m-d H:i:s");

        try{
            $stmt = $pdo->prepare("INSERT INTO `2channel-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");
            $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
            $stmt->bindParam(':postDate', $postDate, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            //mysqlのデータを取得できていない時
            echo $e->getMessage();
            }
        }
    }
    //DBから掲示板コメントデータを取得する
    $sql = "SELECT `id`, `username`, `comment`, `postDate` FROM `2channel-table`;";
    $comment_array = $pdo->query($sql);

    //DBの接続を閉じる
    $pdo = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PHP掲示板</title>
</head>
<body>
    <h1 class="title">2ちゃん寝る</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach($comment_array as $comment): ?>
            <article>
                <div class="wrapper">
                    <div class="nameArea">
                        <span>名前：</span>
                        <p class="username"><?php echo $comment["username"]; ?></p>
                        <time><?php echo $comment["postDate"]?></time>
                    </div>
                    <p class="comment"><?php echo $comment["comment"]; ?></p>
                </div>
            </article>
            <?php endforeach ;?>
        </section>
        <form action="" class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="">名前:</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>
</body>
</html>