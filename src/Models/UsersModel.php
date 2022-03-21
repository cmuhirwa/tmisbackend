<?php
namespace Src\Models;

class UsersModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }
    public function insert(){
      $statement = "
        INSERT 
          INTO users 
            (firstName,lastName,phone,email,username,password,role,createdBy,updatedBy)
          VALUES (:firstName,:lastName,:phone,:email,:username,:password,:role,:createdBy);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':firstName' => $data['firstName'],
              ':lastName' => $data['lastName'],
              ':phone' => $data['phone'],
              ':email' => $data['email'],
              ':username' => $data['username'],
              ':password' => $data['password'],
              ':role' => $data['role'],
              ':createdBy' => $data['createdBy']
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
              users WHERE user_id = ? AND status = ?
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

    public function findOne($user_id)
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE Id = ? AND status = ?
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