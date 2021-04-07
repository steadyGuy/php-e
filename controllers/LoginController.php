<?php

// подключение файлов jwt 

use \Firebase\JWT\JWT;

class LoginController extends Controller
{
  public function __construct()
  {
    $this->user = new UserModel();
  }

  public function login()
  {
    // получаем данные 
    $data = json_decode(file_get_contents("php://input"));
    $bd_user = $this->user->getUser($data->email);

    if ($bd_user) {
      if (!password_verify($data->password, $bd_user["passwordHash"])) {
        http_response_code(400);
        echo json_encode(array("error" => "Неверный пароль!"));
        return;
      }

      // показывать сообщения об ошибках 
      error_reporting(E_ALL);

      // установить часовой пояс по умолчанию 
      date_default_timezone_set('Europe/Kiev');

      require_once './conf/jwt.php';
      // session_start();
      $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
          "id" => $bd_user["_id"],
          "displayName" => $bd_user["displayName"],
          "email" => $bd_user["email"]
        )
      );

      // код ответа 
      http_response_code(200);

      // создание jwt 
      $jwt = JWT::encode($token, $key);
      echo json_encode(
        array(
          "message" => "Успешный вход в систему.",
          "jwt" => $jwt,
          "user" => $token["data"],
        )
      );
    } else {
      http_response_code(400);
      echo json_encode(array("error" => "Такого пользователя не существует!"));
      return;
    }
  }

  public function validate_token()
  {
    require_once './conf/jwt.php';
    // получаем значение веб-токена JSON 
    // получаем JWT 
    $jwt = isset(getallheaders()["Authorization"]) ? getallheaders()["Authorization"] : "";
    // если JWT не пуст 
    if ($jwt) {
      // если декодирование выполнено успешно, показать данные пользователя 
      try {
        // декодирование jwt 
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // код ответа 
        http_response_code(200);

        // показать детали 
        echo json_encode(array(
          "message" => "Успешно залогинились!",
          "user" => $decoded->data
        ), JSON_UNESCAPED_UNICODE);
        return;
      }

      // если декодирование не удалось, это означает, что JWT является недействительным 
      catch (Exception $e) {

        // код ответа 
        http_response_code(401);

        // сообщить пользователю отказано в доступе и показать сообщение об ошибке 
        echo json_encode(array(
          "message" => "Доступ закрыт.",
          "error" => $e->getMessage()
        ), JSON_UNESCAPED_UNICODE);
      }
    }

    // показать сообщение об ошибке, если jwt пуст 
    else {

      // код ответа 
      http_response_code(401);

      // сообщить пользователю что доступ запрещен 
      echo json_encode(array("message" => "Доступ запрещён."), JSON_UNESCAPED_UNICODE);
    }
  }
}
