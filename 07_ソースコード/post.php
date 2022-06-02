<?php session_start(); ?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>post</title>
    <link rel="stylesheet" href="./css/post.css">
    <script src="./script/script.js"></script>
</head>
<body>onclick リダイレクト
<button type="submit" id="return" href="map.php">←</button>
<form action="map.php" method="post">
    <input required type="text" id="post_name" name="post_name" placeholder="タイトル">
    <hr width="500">
    <p id="image-txt" name="image-txt">写真を選択</p>
    <input required type="file" id="image_file" name="image_file">
    <input type="text" id="comment" name="comment" placeholder="コメント">
    <?php
$pdo =new PDO(//DB接続
    'mysql:dbname=LAA1290633-system4ver2; host=mysql203.phy.lolipop.lan',
    'LAA1290633','System4');
    $member_id = $_SESSION['member_id'];
    echo '<button type="submit" id="post" onclick=>投稿する</button>';
    echo '</form>';

    if(!empty($_POST['title'] && $_POST['image_file'] && $_POST['comment'])){
        try {
            $sql = 'INSERT INTO post(post_id,post_name,image_file,comment,date,member_id) VALUES(null,:post_name,:image_file,:comment,date(Y/m/d),:member_id)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':post_name',$_POST['post_name'], PDO::PARAM_STR);
            $stmt->bindParam(':comment',$_POST['comment'], PDO::PARAM_STR);
            $stmt->bindParam(':image_file',$image_file,PDO::PARAM_LOB);
            $stmt->bindParam(':post_id',$_POST['post_id'], PDO::PARAM_INT);
            $stmt->bindParam(':member_id',$_member_id, PDO::PARAM_INT);
            $stmt->execute();
        }catch (PDOException $e) {
            echo '実行エラー'.$e->getMessage();
        }
    }elseif (!empty($_POST['title'] && $_POST['image_file'])){
        try {
            $sql = 'INSERT INTO post(post_id,post_name,image_file,date,member_id) VALUES(null,:title,:image_file,date(Y/m/d),:member_id)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':post_name',$_POST['post_name'], PDO::PARAM_STR);
            $stmt->bindParam(':post_id',$_POST['post_id'], PDO::PARAM_INT);
            $stmt->bindParam(':member_id',$_member_id, PDO::PARAM_INT);
            $stmt->execute();
        }catch (PDOException $e) {
            echo '実行エラー'.$e->getMessage();
        }
    }
?>
</body>
</html>
場所の入力ができてないのと、onclickでのsqlの実行方法がわからないからそれもできてない