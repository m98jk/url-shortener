<?php
/* 
   *  PDO DATABASE CLASS
   *  Connects Database Using PDO
	 *  Creates Prepeared Statements
	 * 	Binds params to values
	 *  Returns rows and results
   */
class Database
{
  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;
  private $dbname2 = DB_NAME2;

  private $dbh;
  private $error;
  private $stmt;

  public function __construct($db2 = 0)
  {
    if ($db2 != 1) {
      // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
      );

      // Create a new PDO instanace
      try {
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      }    // Catch any errors
      catch (PDOException $e) {
        $this->error = $e->getMessage();
      }
    } elseif ($db2 == 1) {
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname2;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
      );

      // Create a new PDO instanace
      try {
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      }    // Catch any errors
      catch (PDOException $e) {
        $this->error = $e->getMessage();
      }
    }
  }

  // Prepare statement with query
  public function query($query)
  {
    $this->stmt = $this->dbh->prepare($query);
  }

  // Bind values
  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }

  // Execute the prepared statement
  public function execute()
  {
    return $this->stmt->execute();
  }

  // Get result set as array of objects
  public function resultset()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  // Get single record as object
  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  // Get record row count
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }

  // Returns the last inserted ID
  public function lastInsertId()
  {
    return $this->dbh->lastInsertId();
  }
}
