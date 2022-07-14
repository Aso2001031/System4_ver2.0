<?php
    session_start();

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
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/post.css">
        <title>投稿一覧</title>
    </head>
    <body>
        <!-- バナー表示 -->
        <div class="header">
            <a class="banner_title">お散歩</a>
            <a class="member_icon" type="image" src="./<?php $icon?>" name="member_icon"></a>
        </div>
        <!-- バナーココまで -->
        <h1>投稿一覧</h1>
        <div>
            <?php
                $pdo=new PDO('mysql:host=mysql203.phy.lolipop.lan;
                dbname=LAA1290633-system4ver2;charset=utf8',
                'LAA1290633',
                'System4');
                


                $sql=$pdo->prepare('select * from post where member_id=?');
                $sql->execute([$id]);

                foreach($sql as $row){
                    $post_id = $row['post_id'];

                    echo '<div class="post">';
                    echo '<form action="post_detail.php?id=',$post_id,'" method="post">'; /*form*/
                    echo '<img src="' ,$row['image_file'],'" class="post_img">'; /*画像*/
                    echo '<a class="date">',$row['date'],'</a><br>'; /*日付*/
                    echo '<a class="post_name">',$row['post_name'],'</a><br>'; /*投稿名*/
                    echo '<a class="address">',$row['post_address'],'</a>';
                    echo '<button type="submit" class="detail">詳細</button>';
                    echo '</form>';
                    echo '</div>';
                }
                    
      
            ?>
        </div>

        <a href="http://aso2001007.versus.jp/System4_Ver2.0/menu.php">メニューへ戻る</a>

        <?php
        echo '<a href="' . $_SERVER['HTTP_REFERER'] . 'class="back">前に戻る</a>';
        ?>

    </body>
</html>
