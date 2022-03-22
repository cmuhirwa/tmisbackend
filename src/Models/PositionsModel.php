<?php
namespace Src\Models;

class PositionsModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }
    public function insert($data){
      $statement = "
        INSERT 
          INTO positions 
            (post_code,post_name,)
          VALUES (:post_code,:post_name);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':post_code' => $data['post_code'],
              ':post_name' => $data['post_name']
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
              positions WHERE archive = 1;
      ";

      try {
          $statement = $this->db->query($statement);
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }

    public function findById($position_id,$archive)
    {
      $statement = "
          SELECT 
              *
          FROM
              positions WHERE position_id = ? AND archive = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($position_id,$archive));
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
              positions WHERE phone = ? AND archive = ? LIMIT 1
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
    public function findOne($position_id)
    {
      $statement = "
          SELECT 
              *
          FROM
              positions WHERE position_id = ? AND archive = ? LIMIT 1
      ";

      try {
        $statement = $this->db->prepare($statement);
        $statement->execute(array($position_id,1));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if(sizeof($result) == 0){
          return null;
        }
        return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }
    public function delete($position_id,$archived_by,$archive)
    {
        $sql = "
            UPDATE 
                positions
            SET 
                archive = :archive,archive=:archive,archived_by=:archived_by,archived_bate=:archived_bate
            WHERE position_id = :position_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':position_id' => $position_id,
              ':archive' => $archive,
              ':archived_by' => $archived_by,
              ':archived_bate' => date("Y-m-d H:i:s"),
              ':archive' =>$archive
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

}
?>