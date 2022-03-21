<?php
namespace Src\Models;

class UsersModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }

    public function findAll()
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE status = 1;
      ";

      try {
          $statement = $this->db->query($statement);
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }

    public function findOne($user_id)
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE user_id = ? AND status = ?
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($user_id,1));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0];
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
}
?>