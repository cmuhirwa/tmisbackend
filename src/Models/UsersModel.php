<?php
namespace Src\Models;

class UsersModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }
    public function insert($data){
      $statement = "
        INSERT 
          INTO users 
            (user_id,first_name,last_name,phone,email,role_id)
          VALUES (:user_id,:first_name,:last_name,:phone,:email,:role_id);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':user_id' => $data['user_id'],
              ':first_name' => $data['first_name'],
              ':last_name' => $data['last_name'],
              ':phone' => $data['phone'],
              ':email' => $data['email'],
              ':role_id' => $data['role_id'],
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
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

    public function findById($user_id)
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE user_id = ? AND status = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($user_id,1));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
    public function findByPhone($phone)
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE phone = ? AND status = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($phone,1));
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
              users WHERE Id = ? AND status = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($user_id,1));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
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