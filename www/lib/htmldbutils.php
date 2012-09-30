<?php
  require_once("dbutils.php") ;



  function GetHtmlLookupField($name, $db, $sql, $additionaltextinselect = "",$isNullAllowed = true, $initialval="", $initialindexifnoval=0) {
    $html = "" ;
    $html .= '<select id="' . $name .'" name="' . $name. '" ' . $additionaltextinselect . '>' ;
    if ($isNullAllowed) 
      $html .= '<option value="' . anyID(). '"></option>' ;
    $rows = $db->GetRows($sql) ;
		$i = 0 ;
		while ($rows->FetchNextRow()) {
		  $i++ ;
		  $val = $rows->valCurRow(0) ;
			$text = $rows->valCurRow(1) ;
			$isselected= false ;
			if ($initialval != "") {
			  if ($val == $initialval)
				  $isselected = true ;
			} else if ($i == $initialindexifnoval) {
				  $isselected = true ;
			}
		  $selecttext=(($isselected) ? ('selected="selected"') : ("")) ;
      $html .= '<option value="' . htmlentities($val) . '" ' . $selecttext . '>'
               . utf8_encode(htmlentities($text)).'</option>' ;
    }
    $html .= '</select>' ;
    return $html ;
  }
?>
