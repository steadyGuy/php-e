<?php

class UserModel extends Model
{
  // подключение к БД таблице "users" 

  // свойства объекта 
  public $name;
  public $surname;
  public $email;
  public $password;

  public function create()
  {
    // для защиты пароля 
    // хешируем пароль перед сохранением в базу данных 
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

    // инъекция 
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->surname = htmlspecialchars(strip_tags($this->surname));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));

    $doc = array(
      "displayName" => $this->name . ' ' . $this->surname,
      "email" => $this->email,
      "passwordHash" => $password_hash,
    );
    $this->db->users->insertOne($doc);
  }

  public function getUser($email)
  {
    return $this->db->users->findOne(["email" => $email]);
  }
}
