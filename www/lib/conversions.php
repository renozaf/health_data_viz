<?php
function langFrench() {
  return 1;
}									 

function langEnglish() {
  return 2;
}									 


  function unchangedVal($val) {
	  return $val ;
	}
	
	function dateToSQL($d) {
	  $da = getdate($d) ;
	  return "'" . $da["year"] . "-" . $da["mon"] ."-" . $da["mday"] ."'" ;  
	}
	
	function dateSQLToDDMMYYYY($dateSQL) {
	  return $dateSQL ;  
	}
	
	function isDateLooseDDMMYYYY($dateInput) {
	  return  true ;
	}
	
	function dateLooseDDMMYYYYYToSQL($dateInput) {
	  $dd = substr($dateInput, 0, 2) ;
	  $mm = substr($dateInput, 3, 2) ;
	  $yyyy = substr($dateInput, 6, 4) ;
	  return "'" . $yyyy . "-" . $mm . "-" . $dd ."'" ;
	}
	
	function GetOrPostStringToSQL($textInput) {
	  if (get_magic_quotes_gpc()) {
  	  return "'" . $textInput . "'" ;
		} else {
  	  return "'" . addslashes($textInput) . "'" ;
		}
	}
	
	function convTextToHTML($s) {
	  if (is_null($s))
		  return "" ;
	  return str_replace(chr(10),"<br>",str_replace(chr(10). chr(13),"<br>",htmlentities($s))) ;
	}
	
	function getMonthHTMLFromMonthNumber($month,$langid) {
	  if ($langid == langFrench()) {
  		switch ($month) {
  		  case 1 : return "janvier" ;
  		  case 2 : return "février" ;
  		  case 3 : return "mars" ;
  		  case 4 : return "avril" ;
  		  case 5 : return "mai" ;
  		  case 6 : return "juin" ;
  		  case 7 : return "juillet" ;
  		  case 8 : return "août" ;
  		  case 9 : return "septembre" ;
  		  case 10 : return "octobre" ;
  		  case 11 : return "novembre" ;
  		  case 12 : return "décembre" ;
  		}
   	} else {
  		switch ($month) {
  		  case 1 : return "January" ;
  		  case 2 : return "February" ;
  		  case 3 : return "March" ;
  		  case 4 : return "April" ;
  		  case 5 : return "May" ;
  		  case 6 : return "June" ;
  		  case 7 : return "July" ;
  		  case 8 : return "August" ;
  		  case 9 : return "September" ;
  		  case 10 : return "October" ;
  		  case 11 : return "November" ;
  		  case 12 : return "December" ;
  		}
  	}
		return NULL ;
	}
	
  function dateSQLToHTMLInLanguage($d,$langid) {
	  if (is_null($d))
		  return "" ;
	  list($year,$month, $day) = sscanf($d, "%d-%d-%d");
    return $day . " " . getMonthHTMLFromMonthNumber($month,$langid) . " " . $year;  
	}
	
  function dateSQLToDD_MM_YYYY($d, $sep=".") {
	  if (is_null($d))
		  return "" ;
	  list($year,$month, $day) = sscanf($d, "%d-%d-%d");
    return sprintf("%02d" . $sep . "%02d" . $sep. "%04d", $day, $month,$year);  
	}
	

?>
