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
            (user_id,role_id,first_name,last_name,phone_numbers,email,username,password,created_by)
          VALUES (:user_id,:role_id,:first_name,:last_name,:phone_numbers,:email,:username,:password,:created_by);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':user_id' => $data['user_id'],
              ':role_id' => $data['role_id'],
              ':first_name' => $data['first_name'],
              ':last_name' => $data['last_name'],
              ':phone_numbers' => $data['phone_numbers'],
              ':email' => $data['email'],
              ':username' => $data['username'],
              ':password' => $data['password'],
              ':created_by' => $data['created_by'],
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
    }
    public function updateUser($data){
      $sql = "
          UPDATE 
              users
          SET 
          first_name=:first_name,last_name=:last_name,phone_numbers=:phone_numbers,email=:email,updated_by=:updated_by,updated_date=:updated_date
          WHERE user_id = :user_id AND status =:status;
      ";
      try {

          $statement = $this->db->prepare($sql);
          $statement->execute(array(
            ':first_name' => $data['first_name'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':phone_numbers' => $data['phone_numbers'],
            ':email' => $data['email'],
            ':user_id' => $data['user_id'],
            ':updated_by' => $data['updated_by'],
            ':updated_date' => date("Y-m-d H:i:s"),
            ':status' =>1
          ));

          return $statement->rowCount();
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
    public function changePassword($data){
      $sql = "
          UPDATE 
              users
          SET 
          password=:password,updated_by=:updated_by,updated_date=:updated_date
          WHERE user_id=:user_id AND status=:status;
      ";
      try {
        
          $statement = $this->db->prepare($sql);
          $statement->execute(array(
            ':password' => $data['password'],
            ':user_id' => $data['user_id'],
            ':updated_by' => $data['updated_by'],
            ':updated_date' => date("Y-m-d H:i:s"),
            ':status' =>1
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

    public function findById($user_id,$status)
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE user_id = ? AND status = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($user_id,$status));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
    public function findByUsername($username)
    {
      $statement = "
          SELECT 
              *
          FROM
              users WHERE username = ? AND status = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($username,1));
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
              users WHERE user_id = ? AND status = ? LIMIT 1
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
    public function changeStatus($user_id,$updated_by,$status)
    {
        $sql = "
            UPDATE 
                users
            SET 
                status=:status,updated_by=:updated_by,updated_date=:updated_date
            WHERE user_id = :user_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':user_id' => $user_id,
              ':updated_by' => $updated_by,
              ':updated_date' => date("Y-m-d H:i:s"),
              ':status' =>$status
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

}
?>