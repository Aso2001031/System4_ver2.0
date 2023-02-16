<?php session_start(); ?>

<?php
$id=$name=$mail=$password=$icon='';
if (!empty($_SESSION['member'])) {
    $id=$_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/post_detail.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Walk Record - POST DETAIL</title>
</head>
<body style="max-width: 550px; margin:auto; height: 100%; box-shadow: 0 0 8px gray;">
<!-- バナー表示 -->
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<!-- バナーココまで -->
<form action="post_list.php">
    <a class="title">投稿詳細</a>
    <button type="submit" class="return">←</button>
</form>
<div class="post_detail_area">
    <?php
    $pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
        'LAA1290579',
        'IZUken0626');
    $text = '削除しますか？';
    $pid = ($_GET['id']);

    $sql=$pdo->prepare('select * from post where post_id=?');
    $sql->execute([$pid]);
;//
    foreach($sql as $row){
        echo '<form action="post_delete.php?id=',$pid,'" method="post">'; /*form*/
        echo '<a class="datetime">',$row['date'],'</a>';
        echo '<br>';
        echo '<a class="post_name">',$row['post_name'],'</a>';
        echo '<br>';
        echo '<img src="',$row['image_file'],'"><br>';
        echo '<a class="address">',$row['post_address'],'</a>';
        echo '<br>';
        echo '<div class="comment_area">';
        echo '<a class="comment">',$row['comment'],'</a>';
        echo '</div>';
        echo '<button type="submit" class="delete_button" onclick="return confirm(',$text,')">削除</button>';
        echo '</form>';
    }
    ?>
</div>
</body>
</html>


