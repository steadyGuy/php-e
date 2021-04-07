<?php

class RegisterController extends Controller
{

  public function __construct()
  {
    $this->user = new UserModel();
  }

  public function register()
  {
    // получаем данные 
    $data = json_decode(file_get_contents("php://input"));

    if ($data->password !== $data->cf_password) {
      http_response_code(400);
      echo json_encode(["error" => "Пароли не совпадают!"], JSON_UNESCAPED_UNICODE);
      return;
    }

    // устанавливаем значения 
    $this->user->name = $data->name;
    $this->user->surname = $data->surname;
    $this->user->email = $data->email;
    $this->user->password = $data->password;
    $this->user->confirm_password = $data->cf_password;

    if ($this->user->getUser($data->email)) {
      http_response_code(400);
      echo json_encode(array("error" => "Пользователь с таким email уже существует."));
      return;
    }

    // создание пользователя 
    if (
      !empty($this->user->name) &&
      !empty($this->user->surname) &&
      !empty($this->user->email) &&
      !empty($this->user->password)
    ) {
      $this->user->create();
      // устанавливаем код ответа 
      http_response_code(200);

      // покажем сообщение о том, что пользователь был создан 
      echo json_encode(array("message" => "Пользователь был создан."));
      return;
    }

    // сообщение, если не удаётся создать пользователя 
    else {

      // устанавливаем код ответа 
      http_response_code(400);

      // покажем сообщение о том, что создать пользователя не удалось 
      echo json_encode(array("error" => "Невозможно создать пользователя."));
      return;
    }
  }
}
