<?php

class Database

{

  protected $connection = null;

  public function __construct()
  {
    try {
      // Why can't postgres connections be as pretty as mysql's?
      $conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_DATABASE_NAME . " user=" . DB_USERNAME . " password=" . DB_PASSWORD;
      $this->connection = pg_connect($conn_string);
      if (pg_last_error($this->connection)) {
        throw new Exception("Could not connect to database.");
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function select($query = "" , $params = [])
  {
    try {
      $result = pg_query($this->connection, $query);
      $arr = pg_fetch_all($result);
      return $arr;
    }
    catch(Exception $e) {
      throw New Exception( $e->getMessage() );
    }
    return false;
  }
}
