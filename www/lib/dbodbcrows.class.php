<?php
  class DBODBCRows {
        var $res ;
        var $curRow ;

        function DBODBCRows($conn, $sql){
                $this->res = odbc_exec( $conn, $sql) ;
if (! $this->res) echo "zzz Bad DBODBCRows with SQL:" . $sql ;
//echo "zzz SQL:" . $sql ;//zzz
        }

        function FetchNextRow(){
          $this->curRow = odbc_fetch_row($this->res);
          return ($this->curRow ? true : false) ;
        }

        function valCurRow($col){
          return odbc_result($this->res,$col) ;
 /*         if (isset($this->curRow[$col]))
            return $this->curRow[$col] ;
          else
            return null ;
*/
        }
  }
?>