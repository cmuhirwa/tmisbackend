<?php
namespace Src\Models;

class RolesModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }
    public function findById($role_id)
    {
      $statement = "
          SELECT 
              *
          FROM
              roles WHERE role_id = ? AND status = ?
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($role_id,1));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
}
?>