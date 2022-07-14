<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="./css/style.css">
        <title>投稿詳細</title>
    </head>
    <body>
        <!-- バナー表示 -->
        <div class="header">
            <a class="banner_title">お散歩</a>
            <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
        </div>
        <!-- バナーココまで -->
        <h1>投稿詳細</h1>
        <div>
            <?php
                $pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
                dbname=LAA1290633-system4ver2;charset=utf8',
                'LAA1290633',
                'System4');

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
                    echo '<div class="member_name">',$row['member_name'],'</div>';
                    echo '<div class="post_name">',$row['post_name'],'</div>';
                    echo '<img src="',$row['image_file'],'"><br>';
                    echo '<div class="datetime">',$row['date'],'</div>';
                    echo '<div class="address">',$row['post_address'],'</div>';
                    echo '<div class="comment">',$row['comment'],'</div>';
                }
            ?>
        </div>

        <a href="http://aso2001007.versus.jp/System4_Ver2.0/menu.php">メニューへ戻る</a>

        <?php
        echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
        ?>

    </body>
</html>
