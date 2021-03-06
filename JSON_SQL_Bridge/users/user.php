<?php
/*
 JSON_SQL_Bridge 1.0
 Copyright 2016 Frank Vanden berghen
 All Right reserved.

 JSON_SQL_Bridge is not a free software. The JSON_SQL_Bridge software is NOT licensed under the "Apache License".
 If you are interested in distributing, reselling, modifying, contibuting or in general creating
 any derivative work from JSON_SQL_Bridge, please contact Frank Vanden Berghen at frank@timi.eu.
 */
namespace kibella;

require_once __DIR__ . '/../config.php';

class User {

  private $db_connection = null;

  public function me() {
    if($this->isLoggedIn()) {
      $id = addslashes(htmlentities($_SESSION['id'], ENT_QUOTES));

      $this->db_connection = dbCreateDBConnection(KIBELLADB);

      $sql = 'SELECT firstname, lastname, email, is_admin
              FROM Users
              WHERE id = "' . $id . '"
              LIMIT 1';
      
      $result = dbDBHExecuteSqlQuery($this->db_connection->getDBHandle(), $sql, $mode="query");

      if(isset($result[0]))
        return $result[0];
    }

    return false;
  }

  public function isLoggedIn() {
    if(!isset($_SESSION))
      session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['user_is_logged_in']) && $_SESSION['user_is_logged_in']) {
      return true;
    }

    return false;
  }

  public function isAdmin() {
    if($this->isLoggedIn()) {
      $id = addslashes(htmlentities($_SESSION['id'], ENT_QUOTES));

      $this->db_connection = dbCreateDBConnection(KIBELLADB);

      $sql = 'SELECT is_admin
              FROM Users
              WHERE id = "' . $id . '"
              LIMIT 1';
      
      $result = dbDBHExecuteSqlQuery($this->db_connection->getDBHandle(), $sql, $mode="query");

      if($result[0]['is_admin'] == "true" || $result[0]['is_admin'] == "TRUE")
        return true;
    }

    return false;
  }
}