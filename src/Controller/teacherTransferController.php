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

      // CHECK IF ITS A SUSPENSION OR TERMINATION
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

      // CHECK IF ITS A SUSPENSION OR TERMINATION
         // Validate input if not empty
        if(!self::validateTeacherTransferRequestInfo($data)){
              return Errors::unprocessableEntityResponse();
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
      return true;
    }

    private function validateIncomingDdeDecisionInfo($input)
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
      return true;
    }
  }
    $controller = new teacherTransferController($this->db, $request_method,$params);
    $controller->processRequest();
