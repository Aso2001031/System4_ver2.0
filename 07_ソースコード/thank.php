<!DOCTYPE html>
<html>
    <head>
        <title>SUCCESS!</title>
    </head>
    <body>
        <p>成功しています！</p>

        <?php if(!empty($img_path)){;?>
        <img src="<?php echo $img_path;?>" alt="">
        <?php } ;?>

        <a href="menu.php">メニューに戻る</a>
    </body>
</html>
