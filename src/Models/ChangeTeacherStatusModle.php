<?php
namespace Src\Models;

class ChangeTeacherStatusModle {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }

    public function changeTeacherStatusRequest($data, $user_id)
    {
        if($data['changeStatusType'] == 'PENDING SUSPENTION'){
            $requested_to_suspend_from  =$data['requested_to_suspend_from'];
            $requested_to_suspend_to    =$data['requested_to_suspend_to'];
        }elseif($data['changeStatusType'] == 'PENDING TERMINATION'){
          $requested_to_suspend_from  =null;
          $requested_to_suspend_to =null;
        }
        $statement = "
          INSERT INTO change_staff_status
            (staff_to_changestatus_id, requested_by_id, requested_by_reason_id, 
            requested_by_comment, requested_to_suspend_from, requested_to_suspend_to, status)
          VALUES 
            (:staff_to_changestatus_id, :requested_by_id, :requested_by_reason_id, 
            :requested_by_comment, :requested_to_suspend_from, :requested_to_suspend_to, :status
          );
        ";
        try {
          
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':staff_to_changestatus_id' => $data['staff_to_changestatus_id'],
              ':requested_by_id' => $user_id,
              ':requested_by_reason_id' => $data['requested_by_reason_id'],
              ':requested_by_comment' => $data['requested_by_comment'],
              ':requested_to_suspend_from' => $requested_to_suspend_from,
              ':requested_to_suspend_to' => $requested_to_suspend_to,
              ':status' => $data['changeStatusType'],
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
    }

    

    public function changeTeacherStatusDecision($data, $user_id){
        if($data['decision'] == 'SUSPENDED'){
          $decided_to_suspend_from  =$data['decided_to_suspend_from'];
          $decided_to_suspend_to    =$data['decided_to_suspend_to'];
        }elseif($data['decision'] == 'TERMINATED'){
          $decided_to_suspend_from  =null;
          $decided_to_suspend_to =null;
        }
      $sql = "
            UPDATE 
              change_staff_status
            SET 
            decided_by_id=:decided_by_id,status=:status,decided_to_suspend_from=:decided_to_suspend_from,decided_to_suspend_to=:decided_to_suspend_to,
            decided_by_comment=:decided_by_comment,decided_by_date=:decided_by_date
            WHERE change_staff_status_id=:change_staff_status_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':change_staff_status_id' => $data['change_staff_status_id'],
              ':decided_by_id' => $user_id,
              ':status' => $data['decision'],
              ':decided_to_suspend_from' => $decided_to_suspend_from,
              ':decided_to_suspend_to' => $decided_to_suspend_to,
              ':decided_by_comment' => $data['decided_by_comment'],
              ':decided_by_date' => date("Y-m-d"),
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
?>