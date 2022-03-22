<?php
namespace Src\Controller;

use Src\Models\AuthModel;
use Src\Models\UsersModel;
use Src\Models\RolesModel;
use Src\System\Errors;
use Src\System\Token;
use Src\System\Encrypt;
use Src\System\UuidGenerator;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;



  class AuthController {
  private $db;
  private $usersModel;
  private $authModel;
  private $rolesModel;
  private $request_method;
  private $params;

  public function __construct($db,$request_method,$params)
  {
    $this->db = $db;
    $this->request_method = $request_method;
    $this->params = $params;
    $this->authModel = new AuthModel($db);
    $this->usersModel = new UsersModel($db);
    $this->rolesModel = new RolesModel($db);
  }

  function processRequest()
  {
      switch ($this->request_method) {
          case 'GET':
            if(sizeof($this->params) == 1){
              $response = $this->getUser($this->params['phone']);
            }else{
              $response = $this->getCurrentUser();
            }
            break;
            case 'POST':
              if($this->params['action'] == "create"){
                $response = $this->createAccount();
              }elseif($this->params['action'] == "login"){
                $response = $this->login();
              }else{
                $response = Errors::notFoundError("Route not found!");
              }
              break;
            case 'DELETE':
              if($this->params['action'] == "suspend"){
                $response = $this->createAccount();
              }else{
                $response = $this->login();
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

  function createAccount(){
      
    $data = (array) json_decode(file_get_contents('php://input'), TRUE);

    if (! self::validateAccountData($data)) {
        return Errors::unprocessableEntityResponse();
    }
    
    // Check if user is registered
    $user = $this->usersModel->findByPhone($data['phone']);
    if(sizeof($user) > 0) {
        return Errors::ExistError("Phone is already exist");
    }
    // Encrypting default password
    $default_password = 12345;
    $default_password = Encrypt::saltEncryption($default_password);

    // Generate user id 
    $user_id = UuidGenerator::gUuid();

    $authData['user_id'] = $user_id;
    $authData['username'] = $data['phone'];
    $authData['password'] = $default_password;

    $data['user_id'] = $user_id;
    $data['created_by'] = $user_id;

    $result = $this->usersModel->insert($data);

    if($result == 1){
      $this->authModel->insert($authData);
    }

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode([
      'message' => "Created"
    ]);
    return $response;
  }
  // Get all users
  function getCurrentUser()
  {
    $data = new \stdClass();
    $rlt = new \stdClass();

    $all_headers = getallheaders();
    $data->jwt = $all_headers['Authorization'];

    // Decoding jwt
    if(empty($data->jwt)){
      return Errors::notAuthorized();
    }
    try {
      $secret_key = "owt125";
      $decoded_data = JWT::decode($data->jwt, new Key($secret_key,'HS512'));

      $result = $this->usersModel->findById($decoded_data->data->id,1);
      if(sizeof($result) > 0){
        $role = $this->rolesModel->findById($result[0]['role_id']);

        $rlt->user_info = $result[0];
        $rlt->role = sizeof($role) > 0 ? $role[0] : null;
      }else{
        $rlt = null;
      }

      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($rlt);
      return $response;
      } catch (\Throwable $e) {
        return Errors::notAuthorized();
      }

  }
  // Get a user by id 
  function login()
  {
      $input = (array) json_decode(file_get_contents('php://input'), TRUE);
      // Validate input if not empty
      if(!self::validateCredential($input)){
          return Errors::unprocessableEntityResponse();
      }
      $userAuthData = $this->authModel->findOne($input['username']);
      if(sizeof($userAuthData) == 0){
        $response['status_code_header'] = 'HTTP/1.1 400 bad request!';
        $response['body'] = json_encode([
        'message' => "Username/password does not match"
        ]);    
        return $response;
      }
      $input_password = Encrypt::saltEncryption($input['password']);
      // Password compare
      if($input_password !== $userAuthData[0]['password']){
          $response['status_code_header'] = 'HTTP/1.1 400 bad request!';
          $response['body'] = json_encode([
          'message' => "Username/password does not match"
          ]);
          return $response;
      }

      $userInfo = $this->usersModel->findById($userAuthData[0]['user_id'],1);

      $iss = "localhost";
      $iat = time();
      $nbf = $iat + 10;
      $eat = $iat + 21600;
      $aud = "myusers";
      $user_array_data = array(
      "id"=>$userInfo[0]['user_id'],
      "phone"=>$userInfo[0]['phone'],
      "email"=>$userInfo[0]['email'],
      );

      $secret_key = "owt125";
      $payload_info = array(
        "iss"=>$iss,
        "iat"=>$iat,
        "nbf"=>$nbf,
        "eat"=>$eat,
        "aud"=>$aud,
        "data"=>$user_array_data
        );

      $jwt = JWT::encode($payload_info,$secret_key,'HS512');

      $role = $this->rolesModel->findById($userInfo[0]['role_id']);

      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode([
      'jwt' =>  $jwt,
      "user_info" => sizeof($userInfo) > 0 ? $userInfo[0] : null,
      "role" => sizeof($role) > 0 ? $role[0] : null
      ]);
      return $response;
  }
  // Get all user by username
  // Get a user by id 
  function getUser($params)
  {

      $result = $this->usersModel->findOne($params);

      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode($result);
      return $response;
  }
  private function validateCredential($input)
  {
      if (empty($input['username'])) {
          return false;
      }
      if (empty($input['password'])) {
          return false;
      }
      return true;
  }
  
  private function validateAccountData($input)
  {
      if (empty($input['first_name'])) {
          return false;
      }
      if (empty($input['last_name'])) {
          return false;
      }
      if (empty($input['phone'])) {
          return false;
      }
      if (empty($input['email'])) {
          return false;
      }
      if (empty($input['role_id'])) {
          return false;
      }
      return true;
  }
}
  $controller = new AuthController($this->db, $request_method,$params);
  $controller->processRequest();
