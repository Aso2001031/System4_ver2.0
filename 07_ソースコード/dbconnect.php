<?php
try {
    $db = new PDO('mysql:host=mysql203.phy.lolipop.lan','LAA1290633','System4');
}   catch (PDOException $e) {
    echo "データベース接続エラー　：".$e->getMessage();
}
?>