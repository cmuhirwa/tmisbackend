<?php
namespace Src\Models;

class PostRequestModel {

    private $db = null;

    public function __construct($db)
    {
      $this->db = $db;
    }

    public function getSchoolRequests($academicId, $schoolId)
    {
      $statement = " 
        SELECT  * FROM post_request WHERE academic_calendar_id = ? AND school_code = ? ";

      try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array($academicId,$schoolId));
          $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
    }

    public function getSchoolRequestsPerDistrict($academicId, $districtId)
    {
        $statement = " 
        SELECT  * FROM post_request WHERE academic_calendar_id = ? AND district_code = ? ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($academicId,$districtId));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function ddeaddarequest($data)
    {
        $sql = "
            UPDATE 
                post_request
            SET 
            dde_id_request=:dde_id_request, dde_post_request=:dde_post_request, updated_by=:updated_by
            WHERE 
            post_request_id=:post_request_id
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':post_request_id' => $data['post_request_id'],
              ':dde_id_request' => $data['dde_id_request'],
              ':dde_post_request' => $data['dde_post_request'],
              ':updated_by' =>$data['dde_id_request']
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        } 
    }


    public function addhdrequest($data){
        $statement = "
            SELECT * FROM post_request
            WHERE academic_calendar_id = ? AND school_code = ? AND position_code = ? AND qualification_id = ?
        ";
        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
            $data['academic_calendar_id'], 
            $data['school_code'], 
            $data['position_code'], 
            $data['qualification_id']));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if(sizeof($result) == 0){
                $result = $this->insertHdReauqest($data);
            }
            else{
                $result = $this->updateHdRequest($data);
            }
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }

    }

    public function updateHdRequest($data){

        $sql = "
            UPDATE 
                post_request
            SET 
                head_teacher_post_request=:head_teacher_post_request, updated_by=:updated_by
            WHERE 
                academic_calendar_id=:academic_calendar_id
                AND school_code=:school_code
                AND position_code=:position_code
                AND qualification_id=:qualification_id;
        ";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(array(
              ':head_teacher_post_request' => $data['head_teacher_post_request'],
              ':academic_calendar_id' => $data['academic_calendar_id'],
              ':school_code' => $data['school_code'],
              ':position_code' => $data['position_code'],
              ':qualification_id' =>$data['qualification_id'],
              ':updated_by' =>$data['head_teacher_id']
            ));

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        } 
    }

    public function insertHdReauqest($data){
        $statement = "
        INSERT 
          INTO post_request
            (academic_calendar_id, school_code, position_code, qualification_id, head_teacher_id, head_teacher_post_request, head_teacher_reason_id, created_by)
          VALUES 
            (:academic_calendar_id, :school_code, :position_code, :qualification_id, :head_teacher_id, :head_teacher_post_request, :head_teacher_reason_id, :created_by);
        ";
        try {
          $statement = $this->db->prepare($statement);
          $statement->execute(array(
              ':academic_calendar_id' => $data['academic_calendar_id'],
              ':school_code' => $data['school_code'],
              ':position_code' => $data['position_code'],
              ':qualification_id' => $data['qualification_id'],
              ':head_teacher_id' => $data['head_teacher_id'],
              ':head_teacher_post_request' => $data['head_teacher_post_request'],
              ':head_teacher_reason_id' => $data['head_teacher_reason_id'],
              ':created_by' => $data['head_teacher_id']
          ));
          return $statement->rowCount();
        } catch (\PDOException $e) {
          exit($e->getMessage());
        }
    }

}
?>