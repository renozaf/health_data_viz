<?php
  class DBMySQLConn {
    var $connSrv ;
        var $selDB ;

          function DBMySQLConn($server,$db,$user,$pwd) {
            $this->connSrv = mysql_connect($server,$user,$pwd) ;
                $this->selDB = mysql_select_db($db, $this->connSrv) ;
          }

          function GetRows($sql) {
//echo "zzzGetRows" . $sql  . "<br>" ;
            return new DBMySQLRows($this->connSrv, $sql) ;
      }

      function ExecSQL($sql) {
//echo "zzzExecSQL" . $sql  . "<br>" ;
            $res = mysql_query($sql, $this->connSrv) ;
//                if (! $res) echo "Bad Exec SQL " . htmlspecialchars($sql) . "<br>" ; //zzzzz
                return ( $res ? true : false) ;
      }

      function getFirstVal($sql) {
        $q = new DBMySQLRows($this->connSrv, $sql) ;
//                echo "zzgetFirstVa" . $sql  . "<br>" ;
           if ($q->FetchNextRow())
             return $q->valCurRow(0) ;
           else
             return null ;
      }

  }
 ?>
