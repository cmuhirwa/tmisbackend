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
        SELECT  * FROM plan ";

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
        SELECT p.plan_id, p.plan_name, p.plan_description, p.plan_type, a.academic_year_name, p.start, p.end
        FROM plan p 
        INNER JOIN academic_year a ON p.academic_year_code = a.academic_year_id 
        WHERE p.plan_id = ? AND p.archive = ?
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
}
?>