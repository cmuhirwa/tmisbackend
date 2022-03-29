<?php
  namespace Src\Controller;
  use Src\System\Errors;
  use Src\Models\TeacherTransferModel;
  use Src\System\AuthValidation;

    class teacherTransferController {
    private $db;
    private $teacherTransferModel;
    private $request_method;
    private $params;

    public function __construct($db,$request_method,$params)
    {
      $this->db = $db;
      $this->request_method = $request_method;
      $this->params = $params;
      $this->teacherTransferModel = new TeacherTransferModel($db);
    }

    function processRequest()
    {
        switch ($this->request_method) {

            // GET DATA
            case 'GET':
              if(sizeof($this->params) > 0){
                if($this->params['action'] == 'getschoolsperdistrict'){
                  $response = $this->getSchoolsPerDistrict($this->params['id']);
                }
              }
              elseif(sizeof($this->params) == 0){
                $response = $this->getTeacherTreansferRequest();
              }
            break;

            // INSERT DATA
            case 'POST':
              $response = $this->teacherRequestATransfer();
            break;

            // UPDATE DATA
            case 'PATCH':
              $response = $this->ddeTransferDecision();
            break;
            default:
              $response = Errors::notFoundError("no request provided");
            break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    function getTeacherTreansferRequest(){
      $jwt_data = new \stdClass();

      $all_headers = getallheaders();
      if(isset($all_headers['Authorization'])){
        $jwt_data->jwt = $all_headers['Authorization'];
      }
      // Decoding jwt
      if(empty($jwt_data->jwt)){
        return Errors::notAuthorized();
      }
      if(!AuthValidation::isValidJwt($jwt_data)){
        return Errors::notAuthorized();
      }

      $user_id = AuthValidation::decodedData($jwt_data)->data->id;
      $result = $this->teacherTransferModel->getTeacherTreansferRequest($user_id);
        
      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
    }

    function getSchoolsPerDistrict($districtId){
        
        // CALL MODELS TO REQUEST TO ERMINATE
        $result = $this->teacherTransferModel->getSchoolsDataPerDistrict($districtId);
    
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    function teacherRequestATransfer(){
      $jwt_data = new \stdClass();

      $all_headers = getallheaders();
      if(isset($all_headers['Authorization'])){
        $jwt_data->jwt = $all_headers['Authorization'];
      }
      // Decoding jwt
      if(empty($jwt_data->jwt)){
        return Errors::notAuthorized();
      }
      if(!AuthValidation::isValidJwt($jwt_data)){
        return Errors::notAuthorized();
      }

      $user_id = AuthValidation::decodedData($jwt_data)->data->id;

      $data = (array) json_decode(file_get_contents('php://input'), TRUE);

         // Validate input if not empty
        if(!self::validateTeacherTransferRequestInfo($data)){
              return Errors::unprocessableEntityResponse();
        }
        // CALL MODELS TO REQUEST TO A TRANSFER
          $result = $this->teacherTransferModel->teacherRequestATransfer($data, $user_id);
        
      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
    }

    function ddeTransferDecision(){
      $jwt_data = new \stdClass();

      $all_headers = getallheaders();
      if(isset($all_headers['Authorization'])){
        $jwt_data->jwt = $all_headers['Authorization'];
      }
      // Decoding jwt
      if(empty($jwt_data->jwt)){
        return Errors::notAuthorized();
      }
      if(!AuthValidation::isValidJwt($jwt_data)){
        return Errors::notAuthorized();
      }

      $user_id = AuthValidation::decodedData($jwt_data)->data->id;

      $data = (array) json_decode(file_get_contents('php://input'), TRUE);

      // CHECK IF ITS AN APPROVAL OR REJECTION
      if($data['incoming_decision']== 'APPROVED'){
         // Validate input if not empty
        if(!self::validateIncomingDdeApproveDecisionInfo($data)){
              return Errors::unprocessableEntityResponse();
        }
      }elseif($data['incoming_decision']== 'REJECTED')
      {
        if(!self::validateIncomingDdeRejectDecisionInfo($data)){
          return Errors::unprocessableEntityResponse();
        }
      }
        // CALL MODELS TO REQUEST TO A TRANSFER
          $result = $this->teacherTransferModel->ddeTransferDecision($data, $user_id);
        
      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
    }

    private function validateTeacherTransferRequestInfo($input)
    {
      if (empty($input['school_from_id'])) {
        return false;
      }
      if (empty($input['school_to_id'])) {
        return false;
      }
      if (empty($input['teacher_reason'])) {
          return false;
      }
      if (empty($input['teacher_supporting_document'])) {
          return false;
      }
      // upload a supporting doc
      	/*
                      $fileName  =  $_FILES['teacher_supporting_document']['name'];
                      $tempPath  =  $_FILES['teacher_supporting_document']['tmp_name'];
                      $fileSize  =  $_FILES['teacher_supporting_document']['size'];
                          
                      if(empty($fileName))
                      {
                        $errorMSG = json_encode(array("message" => "please select image", "status" => false));	
                        return $errorMSG;
                      }
                      else
                      {
                        $upload_path = './'; // set upload folder path 
                        
                        $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); // get image extension
                          
                        // valid image extensions
                        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
                                
                        // allow valid image file formats
                        if(in_array($fileExt, $valid_extensions))
                        {				
                          //check file not exist our upload folder path
                          if(!file_exists($upload_path . $fileName))
                          {
                            // check file size '5MB'
                            if($fileSize < 5000000){
                              move_uploaded_file($tempPath, $upload_path . $fileName); // move file from system temporary path to our upload folder path 
                            }
                            else{		
                              $errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));	
                              return $errorMSG;
                            }
                          }
                          else
                          {		
                            $errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "status" => false));	
                            return $errorMSG;
                          }
                        }
                        else
                        {		
                          $errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));	
                          return $errorMSG;		
                        }
                      }
                      $input['teacher_supporting_document'];
                      // if no error caused, continue ....
                      
                      */

      return true;
    }

    private function validateIncomingDdeApproveDecisionInfo($input)
    {
      if (empty($input['teacherTransfer_id'])) {
        return false;
      }
      if (empty($input['incoming_decision'])) {
        return false;
      }
      if (empty($input['incoming_approved_on_school_id'])) {
          return false;
      }
      if (empty($input['incoming_comment'])) {
          return false;
      }
      return true;
    }

    private function validateIncomingDdeRejectDecisionInfo($input)
    {
      if (empty($input['teacherTransfer_id'])) {
        return false;
      }
      if (empty($input['incoming_decision'])) {
        return false;
      }
      return true;
    }
  }
    $controller = new teacherTransferController($this->db, $request_method,$params);
    $controller->processRequest();
