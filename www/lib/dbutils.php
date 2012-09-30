<?php
  define("SQLFORMATDATATYPENUM","1") ;
  define("SQLFORMATDATATYPETEXT","2") ;
  define("SQLFORMATDATATYPEDATE","3") ;
  define("SQLFORMATDATATYPEDATETIME","4") ;
  define("SQLFORMATDATATYPEBOOL","5") ;

	function isDataOfType($data, $sqlFormatDataType) {
	 /* switch ($sqlFormatDataType) {
		  case SQLFORMATDATATYPEDATE:
			  return is_da
		}
	*/
	  return true ; //zzz
	}
	
  
	  function getSQLOptionalPartWithText($sqlPartBegText, $text,$sqlPartEndText = "") {
		  if (is_string($text) and strlen($text) > 0) {
			  return " " . $sqlPartBegText . " " . $text . $sqlPartEndText ." ";
			} else {
			  return "" ;
			}
		}
		
		
    function getSQLWHEREWithCond($cond) {
		  return getSQLOptionalPartWithText("WHERE", $cond) ;
		}
		
    function getSQLORDERBYWithSort($sort) {
		  return getSQLOptionalPartWithText("ORDER BY", $sort) ;
		}
		
    function getCombinedCond($sep, $cond1, $cond2){
		  $cond="" ;
			if (is_string($cond1) and (strlen($cond1) > 0)) {
			  $cond = $cond1 ;
			}
			if (is_string($cond2) and (strlen($cond2) > 0)) {
			  if (strlen($cond) == 0) {
  			  $cond = $cond2 ;
				} else {
				  $cond = "(" . $cond . " " . $sep . " " . $cond2 . " ) " ;
				}
			}
			return $cond ;
		}

   function getANDCond($cond1, $cond2) {
	   return getCombinedCond("AND", $cond1, $cond2) ;
	 }

 	function SetToGlobalVarNeutralValForSQLConstructIfNeeded($namevar, $sqlFormatDataType) {
	  if ((! isset($GLOBALS[$namevar])) || $GLOBALS[$namevar] == anyValue() ||
		       (! isDataOfType($GLOBALS[$namevar],$sqlFormatDataType))) 
			$GLOBALS[$namevar] = "" ;
	}
	

	 
   function noID() {
     return -1 ;
   }

   function anyID() {
     return -99999999 ;
   }
   function anyValue() {
     return anyID() ;
   }

	 function isInvalidID($id) {
	   return ((!is_numeric($id)) || ($id <= 0)) ;
	 }

	 function isValidID($id) {
	   return (! isInvalidID($id)) ;
	 }

  function InitUserDataModes() {
    global $userDataModes ;

    $userDataModes[0] = modeRead() ;
    $userDataModes[1] = modeInsert() ;
    $userDataModes[2] = modeUpdate() ;
    $userDataModes[3] = modeDelete() ;
  }

  function sqlFalse() {
    return "0" ;
  }
  function sqlTrue() {
    return "1" ;
  }
	
	function valSQLFromNum($s) {
	  if (is_null($s) || $s == '')
		  return "null" ;
		else
		  return $s ;
	}

	function valSQLFromString($s) {
	  if (is_null($s) || $s == '')
		  return "null" ;
		else
		  return "'" . addslashes($s) ."'" ;
	}

	function valSQLFromBool($b) {
	  if ($b)
		  return sqlTrue() ;
		else
		  return sqlFalse() ;
	}

  function cNull($val,$valIfNull) {
    $v = $val ;
          if (is_null($v)) {
          return $valIfNull ;
        } else {
          return $v;
        }
  }

  function cNull0($val) {
    $v = $val ;
        return cNull($v,0) ;
  }

  function cNullS($val) {
    $v = $val ;
        return cNull($v,'') ;
  }

  function isMode($modeComplex,$modeBasic) {
    return $modeComplex & $modeBasic ;
  }

  function isEnterValsMode($aMode) {
    return isMode($aMode,modeInsert()) || isMode($aMode,modeUpdate()) ;
  }

  function getNewMode($mode){
    if (isMode($mode,modeStoreModifs())) {
          if ( (isMode($mode, modeInsert())/* || isMode($mode, modeUpdate())*/) && ! isMode($mode,modeJustOne())) {
            return $mode ;
          } else {
            return getModeWithoutUserDataModes($mode) ;
          }
        } else {
          return $mode ;
        }
  }
  
  function getGlobTablesTableName() {
    return "a_tables" ;
  }

  function getGlobTablesPrimKeyFieldName() {
    return "tableid" ;
  }

  function getGlobTablesTableNameFieldName() {
    return "tablename" ;
  }

  function getGlobTablesPrimKeyFieldNameFieldName() {
    return "tableprimkeyfieldname" ;
  }

  function getGlobRecordsTableName() {
    return "a_records" ;
  }

  function getGlobRecordsPrimKeyFieldName() {
    return "recid" ;
  }

  function getGlobRecordsTableIDFieldName() {
    return "rectableid" ;
  }

  function getGlobRecordsStatusFieldName() {
    return "recstatusID" ;
  }

  function getGlobRecordsDateTimeLastModif() {
    return "recdatetimelastmodif" ;
  }

  function recStatusIDActive() {
    return 1 ;
  }

  function recStatusIDInactive() {
    return 256 ;
  }

  function recStatusIDDeleted() {
    return 512 ;
  }
  
  function DeletedInDB($db, $tableName, $pkv, $pkf="", $isOnlyStatus = true) {
    if ($isOnlyStatus) {
	  SetObjStatusInDB($db, $pkv, recStatusIDDeleted()) ;
	} else {
	  echo("zzzErorDeletedInDB") ;
	}
  }

  function SetObjStatusInDB($db, $pkv, $recStatusID) {
	$dbd->ExecSQL("UPDATE " . getGlobRecordsTableName() . 
	              " SET " . getGlobRecordsStatusFieldName() . " = " . $recStatusID . " " .
				  " WHERE " . getGlobRecordsPrimKeyFieldName() . " = " . $pkv) ;
  }

  function getNewValIncremWithCond($db,$tableName, $fieldName) {
    $sqlLast = "SELECT MAX(" . $fieldName . ") AS MaxVal FROM " . $tableName ;
    $lastVal = $db->GetFirstVal($sqlLast) ;
    if (is_null($lastVal))
      return 1 ;
    else
      return $lastVal + 1 ;
   }

  function getNewValIncrem($db,$tableName, $fieldName) {
    return getNewValIncremWithCond($db,$tableName, $fieldName, "0=0") ;
   }


  function insertNewRecordWithValIncrem($db,$tableName, $fieldName) {
    $sqlLastVal = "SELECT MAX(" . $fieldName . ") AS LastVal FROM " . $tableName ;
	$lastVal = $db->getFirstVal($sqlLastVal) ;
	if (is_null($lastVal)) {
	  $db->ExecSQL("INSERT INTO " . $tableName . "(" . $fieldName . ") VALUES(0)") ;
    }
    $sqlNewVal = "SELECT MAX(" . $fieldName . ") + 1 AS NewVal FROM " . $tableName ;
	$newVal = $db->getFirstVal($sqlNewVal) ;
//    $db->ExecSQL("INSERT INTO " . $tableName . "(" . $fieldName . ") " . $sqlNewVal) ;
    $db->ExecSQL("INSERT INTO " . $tableName . "(" . $fieldName . ") VALUES(" . $newVal .")") ;
    $verifNewVal = $db->getFirstVal($sqlLastVal) ;
    if ($verifNewVal == $newVal)
      return $newVal ;
    else
      return null ;
  }
	
	function getPrimKeyFieldNameFromTableWithName($db, $tableName) {
	  return $db->getFirstVal("SELECT " . getGlobTablesPrimKeyFieldNameFieldName() .
			                      " FROM " . getGlobTablesTableName() . 
														" WHERE " . getGlobTablesTableNameFieldName() . "='" . $tableName ."'") ;
	}

  function insertNewRecordWithGlobValIncrem($db,$tableName) {
    $newVal = insertNewRecordWithValIncrem($db, getGlobRecordsTableName(),getGlobRecordsPrimKeyFieldName()) ;
	  if ( ! is_null($newVal)) {
		  $rowTable = $db->GetRows("SELECT " . getGlobTablesPrimKeyFieldName(). "," . getGlobTablesPrimKeyFieldNameFieldName() .
			                            " FROM " . getGlobTablesTableName() . 
																	" WHERE " . getGlobTablesTableNameFieldName() . "='" . $tableName ."'") ;
			if ($rowTable->FetchNextRow()) {
		    $tableid = $rowTable->valCurRow(getGlobTablesPrimKeyFieldName()) ;
				$primKeyFieldName = $rowTable->valCurRow(getGlobTablesPrimKeyFieldNameFieldName()) ;
			} else
			  $tableid = null ;
			if (is_null($tableid)) {
			  $newVal = null ;
			} else {
			  $db->ExecSQL("UPDATE " . getGlobRecordsTableName() . 
				             " SET " . getGlobRecordsTableIDFieldName() . "=" . $tableid . 
										     "," . getGlobRecordsStatusFieldName() . "=" . recStatusIDActive() .
												 "," .  getGlobRecordsDateTimeLastModif() . "= NOW() " .
										 " WHERE " . getGlobRecordsPrimKeyFieldName() . "=" . $newVal) ;
    	  $db->ExecSQL("INSERT INTO " . $tableName . "(" . $primKeyFieldName . ") VALUES(" . $newVal .")") ;
			}
	  } 
    return $newVal ;
  }

  function DeleteRows($db,$tableName,$sourceDel) {
    $arVals = array() ;
		$primKeyFieldName = getPrimKeyFieldNameFromTableWithName($db,$tableName) ;
    $sql = "SELECT " . $primKeyFieldName . " FROM " . $sourceDel  ;
    $rows = $db->GetRows($sql) ;
    while ($rows->FetchNextRow()) {
      $arVals[] = $rows->valCurRow($primKeyFieldName) ;
    }
    foreach ($arVals as $val) {
		  $sql = "UPDATE " . getGlobRecordsTableName() . 
			             " SET " . getGlobRecordsStatusFieldName() . "=" . recStatusIDDeleted() .
												 "," .  getGlobRecordsDateTimeLastModif() . "= NOW() " .
									 " WHERE " . getGlobRecordsPrimKeyFieldName() . " = " . $val ;
      $db->ExecSQL($sql) ;
									 
      $db->ExecSQL("DELETE FROM " . $tableName . " WHERE " . $primKeyFieldName . " = " . $val) ;
    }
  }

  function zzzDeleteRecordWithID($db,$id) { //zzzstiill to do
    $arVals = array() ;
    $sql = "SELECT " . $primKeyField . " FROM " . $sourceDel  ;
    $rows = $db->GetRows($sql) ;
    while ($rows->FetchNextRow()) {
      $arVals[] = $rows->valCurRow($primKeyField) ;
    }
    foreach ($arVals as $val) {
      $db->ExecSQL("DELETE FROM " . $table . " WHERE " . $primKeyField . " = " . $val) ;
    }
  }

	function IncrementHitCounter($db,$pageid) {
	  $id = $db->GetFirstVal("SELECT pagehitid from pagehits where pagehitdate=CURDATE() AND pagehitpageid=" . $pageid) ;
		if (is_null($id)) {
		  $id = insertNewRecordWithGlobValIncrem($db,"pagehits") ;
			$db->ExecSQL("UPDATE pagehits SET pagehitdate=CURDATE(), pagehitpageid=" . $pageid . " WHERE pagehitid=" . $id) ;
		}
		$db->ExecSQL("UPDATE pagehits SET pagehitcounthits=pagehitcounthits+1 WHERE pagehitid=" . $id) ;
	}


  function cryptPassw($passw) {
    return md5($passw) ;
  }
?>