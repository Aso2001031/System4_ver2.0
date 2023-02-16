<?php
session_start();

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
    <link rel="stylesheet" href="./css/group_post_list.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="max-width: 550px; margin:auto; height: 100%; box-shadow: 0 0 8px gray;">
<div class="header">
    <a class="banner_title">Walk Record</a>
</div>
<form action="map.php">
    <a class="title">投稿一覧</a>
    <button type="submit"　class="return">←</button>
</form>
<div class="post_area">
    <form action="group-profile.php" method="post">
        <button type="submit" class="group_profile_button" name="groupsubmit">グループ詳細</button>
    </form>
    <?php
    $pdo=new PDO('mysql:host=mysql205.phy.lolipop.lan;
          dbname=LAA1290579-system4ver2;charset=utf8',
        'LAA1290579',
        'IZUken0626');

    $sql=$pdo->prepare('select * from member where member_id=?');
    $sql->execute([$id]);
    foreach($sql as $row){
        $_SESSION['member']['group_id'] = $row['group_id'];
    }

    $gid = $_SESSION['member']['group_id'];

    $sql=$pdo->prepare('SELECT
                post.post_id, post.post_name, post.image_file, post.date, post.post_address, member.member_id, member.member_name, member.member_icon, `group`.group_id, `group`.group_name 
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
                post.group_id=?');
    $sql->execute([$gid]);

    foreach($sql as $row){
        $post_id = $row['post_id'];

        echo '<div class="post_list_area">';
        echo '<form action="group_post_detail.php?id=',$post_id,'" method="post">'; /*form*/
        echo '<a class="date">',$row['date'],'</a><br>'; /*日付*/
        echo '<input type="text" class="post_name_box" value="「',$row['post_name'],'」" readonly maxlength="20">'; /*投稿名*/
        echo '<br>';
        echo '<a class="member_name">',$row['member_name'],'</a>';
        echo '<br>';
        echo '<input type="text" class="address_box" value="',$row['post_address'],'" readonly maxlength="20">';
        echo '<br>';
        echo '<img src="' ,$row['image_file'],'" class="post_img">'; /*画像*/
        echo '<button type="submit" class="detail">詳細</button>';
        echo '</form>';
        echo '</div>';
    }
    ?>
</div>
<div class="footer"></div>
</body>
</html>

