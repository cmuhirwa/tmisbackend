<?php
namespace Src\Models;
use Src\Models\QualificationsModel;
use Src\System\Errors;
use Src\System\Encrypt;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
  private $db;
  private $qualificationsModel;
  private $request_method;
  private $params;

  public function __construct($db,$request_method,$params)
  {
    $this->db = $db;
    $this->request_method = $request_method;
    $this->params = $params;
    $this->qualificationsModel = new QualificationsModel($db);
  }

  function processRequest()
  {
      switch ($this->request_method) {
          case 'GET':
            if(sizeof($this->params) == 1){
              $response = $this->getOneQualification($this->params['qualification_id']);
            }else{
              $response = $this->getAllQualifications();
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
  // Get all users
  function getAllQualifications()
  {
    $data = new \stdClass();
    $rlt = new \stdClass();

    $all_headers = getallheaders();
    if(isset($all_headers['Authorization'])){
      $data->jwt = $all_headers['Authorization'];
    }
    // Decoding jwt
    if(empty($data->jwt)){
      return Errors::notAuthorized();
    }
    try {
      $secret_key = "owt125";
      $decoded_data = JWT::decode($data->jwt, new Key($secret_key,'HS512'));

      $result = $this->qualificationsModel->findAll();

      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
      } catch (\Throwable $e) {
        return Errors::notAuthorized();
      }

  }
  // Get one user
  function getOneQualification($qualification_id)
  {
    $data = new \stdClass();
    $rlt = new \stdClass();

    $all_headers = getallheaders();
    if(isset($all_headers['Authorization'])){
      $data->jwt = $all_headers['Authorization'];
    }
    // Decoding jwt
    if(empty($data->jwt)){
      return Errors::notAuthorized();
    }
    try {
      $secret_key = "owt125";
      $decoded_data = JWT::decode($data->jwt, new Key($secret_key,'HS512'));

      $result = $this->qualificationsModel->findById($qualification_id,0);

      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
      } catch (\Throwable $e) {
        return Errors::notAuthorized();
      }

  }
}
  $controller = new AuthController($this->db, $request_method,$params);
  $controller->processRequest();
?>