<?php
  namespace Src\Controller;

  use Src\Models\UsersModel;

    class UsersController {
    private $db;
    private $usersModel;
    private $request_method;

    public function __construct($db,$request_method)
    {
      $this->db = $db;
      $this->request_method = $request_method;
      $this->usersModel = new UsersModel($db);
    }

    function processRequest()
    {
        switch ($this->request_method) {
            case 'GET':
              $response = $this->getUsers();
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
    
    function getUsers()
    {
        $result = $this->usersModel->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
  }
    $controller = new UsersController($this->db, $request_method);
    $controller->processRequest();
