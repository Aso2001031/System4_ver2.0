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
   entity "会員情報" as member <member> <<M,MASTER_MARK_COLOR>> {
     + user_id [PK]
     --
     name
     address
     tel
     mail
     pass
   }
}
   @enduml
   ```
