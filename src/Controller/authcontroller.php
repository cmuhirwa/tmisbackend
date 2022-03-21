<?php
namespace Src\Controller;

use Src\Models\AuthModel;
use Src\Models\UsersModel;
use Src\Models\RolesModel;
use Src\System\Errors;
use Src\System\Token;

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
              $response = $this->getUser($this->params['id']);
            }else{
              $response = $this->getUsers();
            }
            break;
            case 'POST':
              $response = $this->login();
              break;
          default:
            $response = Errors::notFoundError();
            break;
      }
      header($response['status_code_header']);
      if ($response['body']) {
          echo $response['body'];
      }
  }

  function createAccount(){
      
    $result = $this->usersModel->insert($data);

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }
  // Get all users
  function getUsers()
  {

    $result = $this->usersModel->findAll();

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }
  // Get a user by id 
  function login()
  {
      $input = (array) json_decode(file_get_contents('php://input'), TRUE);
      // Validate input if not empty
      if(!self::validateLoginInfo($input)){
          return Errors::unprocessableEntityResponse();
      }
      $userAuthData = $this->authModel->findOne($input['username']);
      if(sizeof($userAuthData) == 0){
        $response['status_code_header'] = 'HTTP/1.1 400 success!';
        $response['body'] = json_encode([
        'message' => "Username/password does not match"
        ]);      
      }

      // Password compare
      if($input['password'] !== $userAuthData[0]['password']){
          $response['status_code_header'] = 'HTTP/1.1 400 success!';
          $response['body'] = json_encode([
          'message' => "Username/password does not match"
          ]);
          return $response;
      }
      if(Token::generate("USER_TOKEN",$userAuthData[0]['user_id'])){
        Token::setTokenExpire();
      }
      $userInfo = $this->usersModel->findById($userAuthData[0]['user_id']);

      $role = $this->rolesModel->findById($userInfo[0]['role_id']);

      $response['status_code_header'] = 'HTTP/1.1 200 OK';
      $response['body'] = json_encode([
      'message' => "Welcome",
      'token' =>  Token::getToken("USER_TOKEN"),
      "user_info" => sizeof($userInfo) > 0 ? $userInfo[0] : null,
      "role" => sizeof($role) > 0 ? $role[0] : null
      ]);
      return $response;
  }

  private function validateLoginInfo($input)
  {
      if (empty($input['username'])) {
          return false;
      }
      if (empty($input['password'])) {
          return false;
      }
      return true;
  }
}
  $controller = new AuthController($this->db, $request_method,$params);
  $controller->processRequest();
