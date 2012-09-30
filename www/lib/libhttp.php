<?php
  function get_or_post($varName, $nomagicquotes = true) {
    if (array_key_exists($varName, $_GET))
      $val = $_GET[$varName] ;
    else if (array_key_exists($varName, $_POST))
      $val = $_POST[$varName] ;
    else
      return Null ;

    if ($nomagicquotes && get_magic_quotes_gpc()) {
      return stripslashes($val) ;
    }
    else
      return $val ;
  }
  
  function get_or_post_numeric($varName, $nomagicquotes = true) {
    $val = get_or_post($varName, $nomagicquotes) ;
    if (is_numeric($val))
      return $val;
    else
      return null ;
  }

  
  function http_request($host, $path, $args) {
    $fp = fsockopen($host, 80);

    fputs($fp, "GET " . $path . "?" . $args . " HTTP/1.0\r\n");
    fputs($fp, "Host: " . $host . "\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    
    $buf = "" ;
    while (!feof($fp)) {
        $buf .= fgets($fp,128);
    }
    fclose($fp);
    $endOfHeaderStr = "\r\n\r\n" ;
    $begContentStrPos = strpos($buf,$endOfHeaderStr) ;
    if ($begContentStrPos === FALSE) {
      $endOfHeaderStr = "\n\n" ;
      $begContentStrPos = strpos($buf,$endOfHeaderStr) ;
    }
    if ($begContentStrPos === false)
      return false ;
    else
      return substr($buf, $begContentStrPos + strlen($endOfHeaderStr)) ;
  }
    ?>
