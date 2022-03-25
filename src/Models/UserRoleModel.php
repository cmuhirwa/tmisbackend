<?php
namespace Src\Models;

class UserRoleModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }
    public function insert($data,$user_id){
      $statement = "
        INSERT 
          INTO user_to_role 
            (user_id,role_id,country_id,district_code,sector_code,school_code,qualification_id,created_by)
          VALUES (:user_id,:role_id,:country_id,:district_code,:sector_code,:school_code,:qualification_id,:created_by);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':user_id' => $data['user_id'],
              ':role_id' => $data['role_id'],
              ':country_id' => $data['country_id'],
              ':district_code' => $data['district_code'],
              ':sector_code' => $data['sector_code'],
              ':school_code' => $data['school_code'],
              ':qualification_id' => $data['qualification_id'],
              ':academic_year_id' => $data['academic_year_id'],
              ':stakeholder_id' => $data['stakeholder_id'],
              ':created_by' => $user_id,
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
    }
    public function update($data,$user_id){
      $statement = "
        UPDATE 
           user_to_role 
        SET
            role_id=:role_id,country_id=:country_id,district_code=:district_code,sector_code=:sector_code,school_code=:school_code,qualification_id=:qualification_id,updated_by=:updated_by
          WHERE user_id=:user_id AND status=:status);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':user_id' => $data['user_id'],
              ':role_id' => $data['role_id'],
              ':country_id' => $data['country_id'],
              ':district_code' => $data['district_code'],
              ':sector_code' => $data['sector_code'],
              ':school_code' => $data['school_code'],
              ':qualification_id' => $data['qualification_id'],
              ':academic_year_id' => $data['academic_year_id'],
              ':stakeholder_id' => $data['stakeholder_id'],
              ':updated_by' => $user_id,
              ':status' => 1
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
    }
    public function findCurrentUserRole($user_id){
        $sql = "
        SELECT * FROM 
            user_to_role 
        WHERE 
            user_id=:user_id AND status=:status";
        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
                ':user_id' => $user_id,
                ':status' => "active"
            ));

            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        } 
    }
    public function findCurrentSchoolTeachers($school_id){
        $sql = "
            SELECT 
                user_id,role_id,qualification_id,academic_year_id 
            FROM 
                user_to_role
            WHERE school_id=:school_id
        ";      
        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
                ':user_id' => $school_id,
            ));
        } catch (\PDOException $e) {
            exit($e->getMessage());
        } 
    }
    
}
