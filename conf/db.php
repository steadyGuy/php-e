<?php

class DB
{
  const URL = "";
  public $conn;

  public static function getConnection()
  {
    $URL = self::URL;
    $connection = new MongoDB\Client($URL);
    return $connection->medequip_db;
  }
}
