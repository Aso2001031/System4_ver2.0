<?php
session_start();

$id=$name=$mail=$password=$icon=$gid='';

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
    <title>投稿一覧</title>
</head>
<body>
<h1>投稿一覧</h1>
<?php
if ($_SESSION['member']['group_id'] != 1) {
    echo '<a href="group-profile.php">group-profile</a>';
}
?>
<div>
    <?php
    $pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
                dbname=LAA1290590-system4ver2;charset=utf8',
        'LAA1290590',
        'System4');

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

        echo '<div class="post">';
        echo '<form action="group_detail.php?id=',$post_id,'" method="post">'; /*form*/
        echo '<img src="',$row['member_icon'],'" class="member_icon">';
        echo '<a class="member_name">',$row['member_name'],'</a>';
        echo '<img src="' ,$row['image_file'],'" class="post_img">'; /*画像*/
        echo '<a class="post_name">',$row['post_name'],'</a>'; /*投稿名*/
        echo '<a class="date">',$row['date'],'</a>'; /*日付*/
        echo '<a class="address">',$row['post_address'],'</a>';
        echo '<button type="submit" class="detail">詳細</button>';
        echo '</form>';
        echo '</div>';
    }


    ?>
</div>

<a href="http://aso2001027.angry.jp/system4_ver2/menu.php">メニューへ戻る</a>

<?php
echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
?>

</body>
</html>