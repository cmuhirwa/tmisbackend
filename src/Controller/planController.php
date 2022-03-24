<?php
    namespace Src\Controller;

    use Src\Models\PlanModel;
    use Src\System\Errors;

    class CalendarController {
    private $db;
    private $planModel;
    private $request_method;
    private $params;

    public function __construct($db,$request_method,$params)
    {
      $this->db = $db;
      $this->request_method = $request_method;
      $this->params = $params;
      $this->planModel = new PlanModel($db);
    }

    function processRequest()
    {
        switch ($this->request_method) {
            case 'GET':
              if(sizeof($this->params) == 1){
                $response = $this->getCalendar($this->params['id']);
              }else{
                $response = $this->getCalendars();
              }
              break;
            case 'POST':
              $response = $this->addPlan();
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

    // Get all calendars
    function getCalendars()
    {
        // $result = ["id"=> $params["id"]]; 
        $result = $this->planModel->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // Get a calendar by id 
    function getCalendar($params)
    {
        $result = $this->planModel->findOne($params);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // Add a plan
    function addPlan(){
      $input = (array) json_decode(file_get_contents('php://input'), TRUE);
      // Validate input if not empty
      if(!self::validatePlanInfo($input)){
          return Errors::unprocessableEntityResponse();
      }
      $userAuthData = $this->authModel->findOne($input['username']);
      
    }

    private function validatePlanInfo($input)
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
    $controller = new CalendarController($this->db, $request_method,$params);
    $controller->processRequest();
