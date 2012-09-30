<?php
  require_once("$includeRelPath/lib/dbmysqlconn.class.php") ;
  require_once("$includeRelPath/lib/dbmysqlrows.class.php") ;
  require_once("$includeRelPath/lib/dbodbcconn.class.php") ;
  require_once("$includeRelPath/lib/dbodbcrows.class.php") ;


  function ConnectDB() {
    return new DBMySQLConn("localhost","my_db_name","my_user_name","my_password") ;
  }



?>