<?php
  class DBODBCConn {
    var $sce ;

          function DBODBCConn($dsn,$user,$pwd) {
            $this->sce = odbc_connect ($dsn,$user,$pwd) ;
          }

          function GetRows($sql) {
//echo "zzzODBCGetRows" . $sql  . "<br>" ;
            return new DBODBCRows($this->sce, $sql) ;
      }

      function ExecSQL($sql) {
//                echo "ExecSQL" . $sql  . "<br>" ;
            $res = odbc_exec( $this->sce,$sql) ;
//                if (! $res) echo "Bad Exec SQL " . htmlspecialchars($sql) . "<br>" ; //zzzzz
                return ( $res ? true : false) ;
      }

      function getFirstVal($sql) {
        $q = new DBMySQLRows($this->connSrv, $sql) ;
//                echo "getFirstVa" . $sql  . "<br>" ;
           if ($q->FetchNextRow())
             return $q->valCurRow(0) ;
           else
             return null ;
      }

  }
?>