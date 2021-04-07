<?php

class Routing
{

  public static function buildRoute()
  {
    /*Контроллер и action по умолчанию*/
    $controllerName = "RegisterController";
    $modelName = "UserModel";
    $action = "register";

    $route = explode("/", $_SERVER['REQUEST_URI']);
    /*Определяем контроллер*/
    if ($route[1] != '') {
      $controllerName = ucfirst($route[1] . "Controller");
      $action = $route[1];
      // $modelName = ucfirst($route[1] . "Model");
    }

    if ($route[1] === 'api') {
      $controllerName = ucfirst("login" . "Controller");
      $action = $route[2];
    }

    if ($route[1] === 'api' && $route[2] == 'auth') {
      $controllerName = ucfirst("login" . "Controller");
      $action = $route[3];
    }

    require_once MODEL_PATH . $modelName . ".php"; //UserModel.php
    require_once CONTROLLER_PATH . $controllerName . ".php"; //IndexController.php

    $controller = new $controllerName();
    $controller->$action();
  }
}
