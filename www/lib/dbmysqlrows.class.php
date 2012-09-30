<?php
  class DBMySQLRows {
        var $res ;
        var $curRow ;

        function DBMySQLRows($conn, $sql){
//echo "zzz SQL:" . $sql ;//zzz
                $this->res = mysql_query($sql,$conn) ;
if (! $this->res) echo "zzz Bad DBMySQLRows with SQL:" . $sql ;
//echo "zzz SQL:" . $sql ;//zzz
        }

        function FetchNextRow(){
          $this->curRow = mysql_fetch_array ($this->res);
          return ($this->curRow ? true : false) ;
        }

        function valCurRow($col){
          if (isset($this->curRow[$col]))
            return $this->curRow[$col] ;
          else
            return null ;
        }
  }
?>
