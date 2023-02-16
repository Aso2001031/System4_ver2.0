<?php session_start()?>
<?php
$id=$name=$mail=$password=$icon=$gid='';
if (!empty($_SESSION['member'])) {
    $id=$_SESSION['member']['id'];
    $name=$_SESSION['member']['name'];
    $mail=$_SESSION['member']['mail'];
    $password=$_SESSION['member']['pass'];
    $icon=$_SESSION['member']['icon'];
}else{
    header('location:https://aso2001031.perma.jp/System4_ver2.0/login.php');//リダイレクト処理
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Walk Record - GROUP POST LIST</title>
    <link rel="stylesheet" href="./css/post_detail.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="max-width: 550px; margin:auto; height: 100%; box-shadow: 0 0 8px gray;">
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<form action="group_post_list.php">
    <a class="title">投稿詳細</a>
    <button type="submit" class="return">←</button>
</form>
<div class="post_detail_area">
    <?php
    $pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
        'LAA1290579',
        'IZUken0626');

    $pid = ($_GET['id']);

    $sql=$pdo->prepare('SELECT
                post.post_id, post.post_name, post.image_file, post.date, post.post_address, post.comment, member.member_id, member.member_name, member.member_icon, `group`.group_id, `group`.group_name 
                FROM
                post
                JOIN
                member
                ON
                post.member_id = member.member_id
                RIGHT JOIN
                `group`
                ON
                post.group_id = `group`.group_id
                WHERE
                post.post_id=?');
    $sql->execute([$pid]);

    foreach($sql as $row){
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
        echo '<br>';
        echo '<a class="post_member">投稿者：',$row['member_name'],'</a>';
    }
    ?>
</div>
</body>
</html>
