<?php
  namespace Src\Controller;

  use Src\Models\UsersModel;
  use Src\Models\AuthModel;
  use Src\System\Errors;
  use \Firebase\JWT\JWT;
  use Firebase\JWT\Key;

    class UsersController {
    private $db;
    private $usersModel;
    private $authModel;
    private $request_method;
    private $params;

    public function __construct($db,$request_method,$params)
    {
      $this->db = $db;
      $this->request_method = $request_method;
      $this->params = $params;
      $this->usersModel = new UsersModel($db);
      $this->authModel = new AuthModel($db);
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
            case 'PATCH':
              if($this->params['action'] == "suspend"){
                $response = $this->suspendUser($this->params['user_id']);
              }elseif($this->params['action'] == "activate"){
                $response = $this->activateUser($this->params['user_id']);
              }elseif($this->params['action'] == "update"){
                $response = $this->updateUserInfo();
              }else{
                $response = Errors::notFoundError("Route not found!");
              }
              break;
            default:
              $response = Errors::notFoundError("ROute not found!");
              break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    function updateUserInfo(){
      
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
    function getUser($params)
    {

        $result = $this->usersModel->findOne($params);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    // Suspend a user by id
    function suspendUser($user_id)
    {
        $data = new \stdClass();

        $all_headers = getallheaders();

        $data->jwt = $all_headers['Authorization'];

        // Decoding jwt
        if(empty($data->jwt)){
          return Errors::notAuthorized();
        }

        try {
          $secret_key = "owt125";
          $decoded_data = JWT::decode($data->jwt, new Key($secret_key,'HS512'));

          $user = $this->usersModel->findById($user_id,1);
          if (sizeof($user) == 0) {
              return Errors::notFoundError("User not found!");
          }

          $this->usersModel->changeStatus($user_id,$decoded_data->data->id,0);

          $response['status_code_header'] = 'HTTP/1.1 200 OK';
          $response['body'] = json_encode(["message"=>"Account Suspended!"]);
          return $response;
        } catch (\Throwable $e) {
          return Errors::notAuthorized();
        }
    }
    // Suspend a user by id
    function activateUser($user_id)
    {
        $data = new \stdClass();

        $all_headers = getallheaders();
        $data->jwt = $all_headers['Authorization'];

        // Decoding jwt
        if(empty($data->jwt)){
          return Errors::notAuthorized();
        }
        try {
          $secret_key = "owt125";
          $decoded_data = JWT::decode($data->jwt, new Key($secret_key,'HS512'));

          $user = $this->usersModel->findById($user_id,0);
          if (sizeof($user) == 0) {
              return Errors::notFoundError("User not found!");
          }
          $this->usersModel->changeStatus($user_id,$decoded_data->data->id,1);

          $response['status_code_header'] = 'HTTP/1.1 200 OK';
          $response['body'] = json_encode(["message"=>"Account Activated!"]);
          return $response;
        } catch (\Throwable $e) {
          return Errors::notAuthorized();
        }
    }
  }
    $controller = new UsersController($this->db, $request_method,$params);
    $controller->processRequest();
