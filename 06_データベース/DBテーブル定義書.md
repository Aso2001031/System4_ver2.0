# DBテーブル定義書
 ## ER図

 # DBテーブルカラム詳細一覧

 # データベース詳細

 ### 会員テーブル（member）
 |和名|属性名|型|PK|NN|FK|
 |:---|:---|:---|:---|:---:|:----:|
 |会員ID|member_id|int(10)|○|○||
 |会員名|membeer_name|varcahr(20)||○||
 |会員パスワード|member_pass|varchar(15)||○||
 |メールアドレス|mamber_mail|varchar(50)||○||
 |ユーザーアイコン|member_icon|mediumblob||||
 |グループID|group_id|int(10)|||○|
 
 ### 投稿テーブル (post)
 |和名|属性名|型|PK|NN|FK|
 |:---|:---|:---|:---|:---:|:----:|
 |投稿ID|post_id|int(10)|○|○||
 |題名|post_name|varchar(20)||○||
 |写真|image_file|mediumblob||||
 |日付|date|datetime||○||
 |座標X|coordinate_x|varchar(50)||○||
 |座標Y|coordinate_y|varchar(50)||○||
 |コメント|comment|varchar(300)||○||
 |会員ID|member_id|int(10)||○|○|
 
 ### グループテーブル (group) 
 |和名|属性名|型|PK|NN|FK|
 |:---|:---|:---|:---|:---:|:----:|
 |グループID|group_id|int(10)|○|○||
 |グループパスワード|group_pass|varchar(15)||○||
 |グループ名|group_name|varchar(10)||○||
 |イメージ|group_image|mediumblob||○||
 |ルーム|group_room|varchar(8)||○||
