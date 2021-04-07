<?php

class DB
{
  const URL = "mongodb+srv://niukJs:privet_medved@cluster0.tyxux.mongodb.net/medequip_db?retryWrites=true&w=majority";
  public $conn;

  public static function getConnection()
  {
    $URL = self::URL;
    $connection = new MongoDB\Client($URL);
    return $connection->medequip_db;
  }
}
