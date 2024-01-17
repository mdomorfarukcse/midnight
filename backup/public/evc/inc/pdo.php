<?php
use Pemm\Config;
/*
Usage Examples
Create new SimplePDO instance and open a connection:

$database = SimplePDO::getInstance();`
If there is already a open connection that one will be used automatically.

Query database and return a single row:

$database = SimplePDO::getInstance();
$database->query("SELECT `column` FROM `table` WHERE `columnValue` = :id");
$database->bind(':id', 123);
$result = $database->single();
Query database and return multiply rows:

$database = SimplePDO::getInstance();
$database->query("SELECT * FROM `table`");
$result = $database->resultSet();
Insert new row in database:

$database = SimplePDO::getInstance();
$database->query("INSERT INTO `users` (name, email) VALUES (:name, :email)");
$database->bind(':name', $name);
$database->bind(':name', $email);
$database->execute();
Update existing row:

$database = SimplePDO::getInstance();
$database->query("UPDATE `users` SET `name` = :name WHERE `id` = :id");
$database->bind(':name', $newName);
$database->bind(':id', $id);
$database->execute();
*/
class SimplePDO {
  private static $_instance = null;
  private $_stmt;

  public function __construct(){
      $sa = new Config();

    try {
      $this->dbhost = new PDO('mysql:host=' . $sa::DB_HOST . ';dbname=' . $sa::DB_NAME, $sa::DB_USER, $sa::DB_PASSWORD);
	 
	     $this->dbhost->exec("SET NAMES 'utf8'; SET CHARSET 'utf-8'");
 
		 $this->dbhost->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e){
     echo  $e->getMessage();
    }
  }
  public static function getInstance() {
    if(!isset(self::$_instance)) {
      self::$_instance = new SimplePDO();
    }
    return self::$_instance;
  }
  public function query($query) {
    $this->_stmt = $this->dbhost->prepare($query);
  }
  public function bind($param, $value, $type = null) {
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
    $this->_stmt->bindValue($param, $value, $type);
  }
  public function execute() {
     try {
    return $this->_stmt->execute();
    } catch(PDOException $e){
     echo  $e->getMessage();
    }
  }
  public function resultSet() {
    $this->execute();
    return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
  }
 
  public function rowCount() {
    return $this->_stmt->rowCount();
  }
  public function single() {
    $this->execute();
    return $this->_stmt->fetch(PDO::FETCH_ASSOC);
  }
  

}



