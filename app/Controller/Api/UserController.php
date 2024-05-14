<?php

class UserController extends BaseController
{
  public function listAction()
  {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();

    if (strtoupper($requestMethod) == 'GET') {
      try {
          $userModel = new UserModel();
          $intuids = [];
          if (isset($arrQueryStringParams['uids']) && $arrQueryStringParams['uids']) {
            $uids = explode(',', $arrQueryStringParams['uids']);
            foreach ($uids as $uid) {
              $uid = trim($uid);
              if (is_numeric($uid)) {
                $intuids[] = (int)$uid;
              }
            }
          }
          $arrUsers = $userModel->getUsers($intuids);
          $responseData = json_encode($arrUsers);
      }
      catch (Error $e) {
        $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }

  public function addAction() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if (strtoupper($requestMethod) == 'POST') {
      $_POST = json_decode(file_get_contents('php://input'), true);
      if (!empty($_POST)) {
        try {
          $database = new Database();
          $arrUsers = $database->insert($_POST);
        }
        catch (Error $e) {
          $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
          $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
      }
      $responseData = 'OK';
    }
    else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }
}
