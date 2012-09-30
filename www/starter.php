<?php
define ("ctlid_datatype_id","dtid") ;
define ("ctlid_scaling_factor_figures","sff") ;
$includeRelPath="." ;
require_once("$includeRelPath/conndb/conndb.php") ;
require_once("$includeRelPath/lib/libhttp.php") ;
require_once("$includeRelPath/lib/htmldbutils.php") ;

$datatype_id = get_or_post_numeric(ctlid_datatype_id) ;
$scaling_factor_figures = cNull(get_or_post_numeric(ctlid_scaling_factor_figures), 15) ;

$connDB = ConnectDB() ;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Visualisation des chiffres-clés des hôpitaux suisses">
    <meta name="author" content="Renaud Hirsch">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
  <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
  <script>
    var geocoder;
    var mapSwitz;
    var img ;
    var markers 
    var sizes ;
    
    function initialize() {
    <?php 
    if (! is_null($datatype_id) ) {
    ?>
      markers = new Array();
      sizes = new Array() ;
      geocoder = new google.maps.Geocoder();
    <?php //size?:Size, origin?:Point, anchor?:Point, scaledSize?:Size)?>
      var latlng = new google.maps.LatLng( 46.8, 8.3);
      var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      mapSwitz = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    <?php 
      $maxval = $connDB->getFirstVal("SELECT MAX(datanumber_value) FROM data_numbers " .
                " WHERE datanumber_datatype_id=" . $datatype_id . " AND datanumber_period_id=2") ;
      $nbs = $connDB->GetRows("SELECT * FROM data_numbers INNER JOIN loc_entities ON datanumber_ent_id=ent_id " .
                " WHERE datanumber_datatype_id=" . $datatype_id . " AND datanumber_period_id=2");// ORDER BY datanumber_value DESC LIMIT 20") ;//zzz make period argument
      $i = 0 ;
      while ($nbs->FetchNextRow()) {
        $coeff = 5 * sqrt($nbs->valCurRow("datanumber_value") / $maxval) ;
        if ($coeff < 1) {
          $coeff = 1 ;
        }
        $title = number_format ( $nbs->valCurRow("datanumber_value") , 0 , '.' , '\'' ) . " / " 
                 . utf8_encode($nbs->valCurRow("ent_name"))   ; 
    ?>   
         sizes[<?php echo($i)?>] = <?php echo($coeff)?> ;
         markers[<?php echo($i)?>] = new google.maps.Marker({
           map: mapSwitz
          ,position: new google.maps.LatLng(<?php echo($nbs->valCurRow("ent_poseastdecimal"))?>,<?php echo($nbs->valCurRow("ent_posnorthdecimal"))?>)
          ,title: '<?php echo(str_replace("'","\'", $title)) ?>'
          ,icon: getMarkerImage(<?php echo($i)?>, <?php echo($scaling_factor_figures)?>)
        });
    <?php
        $i++ ;
      }
    }  
    ?>
    }
    
    function getMarkerImage(i, imgScalingFactor) {
            siz = sizes[i] * imgScalingFactor ;
            return new google.maps.MarkerImage('http://filemails.com/opendata/images/image_points_on_map.png', null,null,
                new google.maps.Point(siz / 2.0, siz / 2.0),
                new google.maps.Size(siz,siz,'px','px')) ;
    }
    
    function SetMarkerImages(imgScalingFactor) {
     for (i = 0 ; i < markers.length ; i++ ) {
             markers[i].setIcon(getMarkerImage(i, imgScalingFactor)) ;
     }
    }
    
    function SubmitForm() {
            document.getElementById('mainform').submit() ;
    }
  </script>
  </head>

  <body onload="initialize()">

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Health Data Visualization</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div>
      <form id="mainform" action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
        <label for="<?php echo(ctlid_datatype_id)?>" >Choix de valeurs &agrave; montrer:</label>
        <?php 
          $lkp = GetHtmlLookupField(ctlid_datatype_id,$connDB, 
                    "SELECT datatype_id, datatype_name FROM data_types WHERE datatype_name IS NOT NULL ORDER BY datatype_name",
                    ' onchange="SubmitForm()" style="width:100%" ',true,$datatype_id) ;
          echo($lkp) ;
        ?>
        <br />
        <!--  <label for="<?php echo(ctlid_scaling_factor_figures)?>" >Echelle de taille des cercles:</label>  -->
        <input  type="hidden" onchange="SetMarkerImages(document.getElementById('<?php echo(ctlid_scaling_factor_figures)?>').value)" id="<?php echo(ctlid_scaling_factor_figures)?>" 
        name="<?php echo(ctlid_scaling_factor_figures)?>" value="<?php echo($scaling_factor_figures) ?>" size=2/>
        <br />
        <br />
        <!-- <input type="submit" value="Montrer la carte avec les valeurs choisies">   -->
      </form>
      <div id="map_canvas" style="height:600px;width:900px"></div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>

  </body>
</html>
