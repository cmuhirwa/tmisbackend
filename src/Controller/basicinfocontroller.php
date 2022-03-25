<?php
namespace Src\Controller;
use Src\Models\QualificationsModel;
use Src\Models\AcademicCalenderModel;
use Src\System\Errors;
use Src\System\AuthValidation;

class BasicInfoController {
    private $db;
    private $qualificationsModel;
    private $academicCalenderModel;
    private $request_method;
    private $params;
    public function __construct($db,$request_method,$params)
    {
        $this->db = $db;
        $this->request_method = $request_method;
        $this->params = $params;
        $this->qualificationsModel = new QualificationsModel($db);
        $this->academicCalenderModel = new AcademicCalenderModel($db);
    }
    function processRequest()
    {
        switch ($this->request_method) {
            case 'GET':
                if(sizeof($this->params) == 1){
                    if($this->params['action'] == "acalqual"){
                        $response = $this->getAcademicCalenderAndQualifications();
                    }else{
                        $response = Errors::notFoundError("Route not found!");
                    }
                }
                break;
            default:
                $response = Errors::notFoundError("Route not found!");
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    function getAcademicCalenderAndQualifications(){
        
            $jwt_data = new \stdClass();
            $output = new \stdClass();

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

            $academic_calender = $this->academicCalenderModel->findCurrentAcademicYear();

            $result = $this->qualificationsModel->findAll();

            $output->qualifications = $result;
            $output->academic_calender = sizeof($academic_calender) > 0 ? $academic_calender[0] : null;

            $response['status_code_header'] = 'HTTP/1.1 200 success';
            $response['body'] = json_encode($output);
            return $response;
    }

}
  $controller = new BasicInfoController($this->db, $request_method,$params);
  $controller->processRequest();
  ?>