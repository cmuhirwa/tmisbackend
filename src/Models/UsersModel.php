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
            (user_id,first_name,last_name,phone,email,role_id,password,created_by)
          VALUES (:user_id,:first_name,:last_name,:phone,:email,:role_id,:password,:created_by);
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
              ':password' => $data['password'],
              ':created_by' => $data['created_by'],
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
    public function delete($user_id,$archive,$archived_by,$status)
    {
        $sql = "
            UPDATE 
                users
            SET 
                status = :status,archive=:archive,archived_by=:archived_by,archived_bate=:archived_bate
            WHERE user_id = :user_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':user_id' => $user_id,
              ':archive' => $archive,
              ':archived_by' => $archived_by,
              ':archived_bate' => date("Y-m-d H:i:s"),
              ':status' =>$status
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

}
?>