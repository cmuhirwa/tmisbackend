<?php
  namespace Src\Controller;

  use Src\Models\UsersModel;

    class UsersController {
    private $db;
    private $usersModel;
    private $request_method;
    private $params;

    public function __construct($db,$request_method,$params)
    {
      $this->db = $db;
      $this->request_method = $request_method;
      $this->params = $params;
      $this->usersModel = new UsersModel($db);
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
            default:
              $response = notFoundResponse();
              break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    // Get all users
    function getUsers()
    {

        // $result = ["id"=> $params["id"]]; 
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
  }
    $controller = new UsersController($this->db, $request_method,$params);
    $controller->processRequest();
