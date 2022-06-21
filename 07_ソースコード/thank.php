<?php session_start(); ?>
<?php
$icon = '';
$icon = $_SESSION['member']['icon'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SUCCESS!</title>
    </head>
    <body>
        <p>成功しています！</p>
    
        <?php
        echo '<img src="',$icon,'" alt="">';
        ?>

        <a href="menu.php">メニューに戻る</a>
    </body>
</html>



