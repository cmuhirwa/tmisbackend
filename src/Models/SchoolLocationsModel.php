<?php
namespace Src\Models;

class SchoolLocationsModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }
    public function districts()
    {
      $statement = "
        SELECT DISTINCT 
            district_code, district_name 
        FROM schoollocation ORDER BY district_code, district_name;
      ";
      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array(1));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
  public function findDistrictByCode($district_code)
  {
    $statement = "
        SELECT 
            *
        FROM
            schoollocation WHERE district_code = ?
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($district_code));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
        exit($e->getMessage());
    }
  }
}
?>