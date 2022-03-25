<?php
namespace Src\Models;

class PlanModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }

    public function findAll()
    {
      $statement = " 
        SELECT  * FROM academic_calendar ";

      try {
          $statement = $this->db->query($statement);
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }

    public function findOne($calendar_Id)
    {
      $statement = "
        SELECT * FROM academic_calendar
        WHERE academic_year_id = ? AND archive = ?
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($calendar_Id,0));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        //$result[0]['testvaluable'] = 'testvalue';
        if(sizeof($result) == 0){
          return null;
        }
        return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }

    public function insert($data){
      $statement = "
        INSERT 
          INTO plan 
            (plan_name, plan_description, plan_type, 
            academic_year_code, start, end, createdB_by)
          VALUES 
            (:plan_name, :plan_description, :plan_type, 
            :academic_year_code, :start, :end, :createdB_by);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':plan_name' => $data['plan_name'],
              ':plan_description' => $data['plan_description'],
              ':plan_type' => $data['plan_type'],
              ':academic_year_code' => $data['academic_year_code'],
              ':start' => $data['start'],
              ':end' => $data['end'],
              ':createdB_by' => $data['createdB_by'],
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
    }

    public function delete($plan_id, $archived_by)
    {
      $sql = "
            UPDATE 
                plan
            SET 
                archive=:archive,archived_by=:archived_by,archived_date=:archived_date
            WHERE plan_id=:plan_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':archive' => 1,
              ':archived_by' => $archived_by,
              ':archived_date' => date("Y-m-d H:i:s"),
              ':plan_id' =>$plan_id
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        } 
    }
}
?>