<?php
namespace Src\Models;

class TeacherTransferModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }

    public function getSchoolsDataPerDistrict($district_code)
    {
      $statement = " 
      SELECT * FROM 
      schools s
      INNER JOIN school_location sl ON s.village_id = sl.village_id
      WHERE sl.district_code = 11";

      try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array($district_code));
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }

    public function teacherRequestATransfer($data, $user_id)
    {
      $statement = "
      INSERT INTO teacherTransfer(
        techer_id, school_from_id, school_to_id, 
        teacher_reason, teacher_supporting_document
      )
          VALUES 
            (:techer_id, :school_from_id, :school_to_id, 
            :teacher_reason, :teacher_supporting_document
          );
        ";
        try 
        {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':techer_id' => $user_id,
              ':school_from_id' => $data['school_from_id'],
              ':school_to_id' => $data['school_to_id'],
              ':teacher_reason' => $data['teacher_reason'],
              ':teacher_supporting_document' => $data['teacher_supporting_document'],
          ));
          return $statement->rowCount();
        } 
        catch (\PDOException $e) {
          exit($e->getMessage());
        } 
    }

    public function ddeTransferDecision($data, $user_id)
    {
      if($data['decision'] == 'SUSPENDED'){
        $decided_to_suspend_from  =$data['decided_to_suspend_from'];
        $decided_to_suspend_to    =$data['decided_to_suspend_to'];
      }elseif($data['decision'] == 'TERMINATED'){
        $decided_to_suspend_from  =null;
        $decided_to_suspend_to =null;
      }
      $sql = "
            UPDATE 
              teacherTransfer
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