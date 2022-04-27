```uml
 @startuml
 skinparam class {
     '図の背景
     BackgroundColor Snow
     '図の枠
     BorderColor Black
     'リレーションの色
     ArrowColor Black
 }

 !define MASTER_MARK_COLOR Orange 
 !define TRANSACTION_MARK_COLOR DeepSkyBlue

 package "さんぽ" as target_system {
   entity "会員テーブル" as member <member> <<M,MASTER_MARK_COLOR>> {
     + member_id [PK]
     --
     member_name
     member_pass
     member_mail
     group_id [FK]
   }
   entity "投稿テーブル"　as post <post> <<M,MASTER_MARK_COLOR>> {
    + post_id [PK]
    --
    post_name
    image_file
    date
    coordinate_x
    coordinate_y
    comment
    member_id [FK]
   }
   entity "グループテーブル" as group <group> <<M,MASTER_MARK_COLOR>> {
    + group_id [PK]
    --
    group_pass
   }
}
@enduml
```
