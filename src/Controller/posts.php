<?php
namespace Src\Controller;

use Src\Models\PostsModel;
use Src\Models\RebDistributionsModel;
use Src\System\Errors;
use Src\System\AuthValidation;
use Src\Validations\LimitsValidation;



  class PostsController {
    private $db;
    private $postsModel;
    private $rebDistributionsModel;
    private $request_method;
    private $params;

    public function __construct($db,$request_method,$params)
    {
        $this->db = $db;
        $this->request_method = $request_method;
        $this->params = $params;
        $this->postsModel = new PostsModel($db);
        $this->rebDistributionsModel = new RebDistributionsModel($db);
    }

    function processRequest()
    {
        switch ($this->request_method) {
            case 'PATCH':
                $response = $this->districtDistributionToSchool();
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

    function districtDistributionToSchool(){
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

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
        
        if (!LimitsValidation::distDistribution($input)) {
            return Errors::unprocessableEntityResponse();
        }
        $district_limits = $this->rebDistributionsModel->findDistrictQualLimits($input['academic_year_id'],$input['qualification_id'],$input['district_code']);

        $remainLimits = [];
        foreach ($district_limits as $value) {
         array_push($remainLimits,self::getLimitsByAcademicQualificationAndDistrict($input['position_code'],$value['academic_year_id'],$value['qualification_id'],$value['district_code'],$value['limits']));
        }

        if(!self::isExceedLimits($input)){
              $response['status_code_header'] = 'HTTP/1.1 200 success';
              $response['body'] = json_encode([
                "message" => "Distribution is exceeding allocated qualification post!",
                "academic_distributed" => null,
                "total_distributed" => $remainLimits
              ]);
              return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 success';
        $response['body'] = json_encode($remainLimits);
        return $response;
 
    }
    function isExceedLimits($input){

      $dist_limits = $this->postsModel->findSumOfDistributed($input['position_code'],$input['academic_year_id'],$input['district_code']);
      $new_remain = sizeof($dist_limits) > 0 ? ($input['dde_post_distribution'] + $dist_limits[0]['dde_post_distribution']) : $dist_limits + 0;

      $dist_limits_set = $this->rebDistributionsModel->findDistrictQualLimits($input['academic_year_id'],$input['qualification_id'],$input['district_code']);
      $qual_limits = sizeof($dist_limits_set) > 0 ? $dist_limits_set[0]['limits'] : 0;

      $decision = $new_remain <= $qual_limits ? true : false;

      return $decision;
    }
    function getLimitsByAcademicQualificationAndDistrict($position_id,$academic_year_id,$qualification_id,$district_code,$limits){
      $remaining = new \stdClass();


      $reb_limits = $this->postsModel->findSumOfDistributed($district_code,$qualification_id,$academic_year_id);

      $remaining->qualification_id = $qualification_id;
      $remaining->limits = $limits;
      $remaining->remaining_limits = sizeof($reb_limits) > 0 ? ($limits - $reb_limits[0]['dde_post_distribution']) : ($limits - 0);
      
      return $remaining;
    }
  }
$controller = new PostsController($this->db, $request_method,$params);
$controller->processRequest();