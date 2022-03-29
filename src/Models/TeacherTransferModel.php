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
      INNER JOIN school_location sl ON s.region_code = sl.village_id
      WHERE sl.district_code = ?";

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
      if($data['incoming_decision'] == 'APPROVED'){
        $incoming_approved_on_school_id  = $data['incoming_approved_on_school_id'];
      }elseif($data['incoming_decision'] == 'REJECTED'){
        $incoming_approved_on_school_id  =null;
      }
      $sql = "
            UPDATE 
              teacherTransfer
            SET 
            incoming_dde_id=:incoming_dde_id,incoming_decision=:incoming_decision,incoming_approved_on_school_id=:incoming_approved_on_school_id,incoming_comment=:incoming_comment,incoming_decision_date=:incoming_decision_date

            WHERE teacherTransfer_id=:teacherTransfer_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':incoming_dde_id' => $user_id,
              ':incoming_decision' => $data['incoming_decision'],
              ':incoming_approved_on_school_id' => $incoming_approved_on_school_id,
              ':incoming_comment' => $data['incoming_comment'],
              ':incoming_decision_date' => date("Y-m-d"),
              ':teacherTransfer_id' => $data['teacherTransfer_id'],
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getTeacherTreansferRequest($user_id)
    {
      $statement = " 
        SELECT tt.teacherTransfer_id, s.school_name, sl.district_name, sl.sector_name, tt.requested_date, tt.incoming_decision, tt.incoming_decision, tt.incoming_decision_date, tt.outgoing_dde_decision, 
        tt.outgoing_dde_comment, tt.outgoing_dde_decision_date

        FROM teacherTransfer tt
        INNER JOIN schools s ON s.school_id = tt.school_to_id
        INNER JOIN school_location sl ON sl.village_id = s.region_code

        WHERE  tt.techer_id = ?";

      try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array($user_id));
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
}
?>