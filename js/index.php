<?php
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>VWI Kalender</title> 
    <link rel="shortcut icon" href="favicon.ico">
  	<link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.2.min.css">
  	<? 
    #<link rel="stylesheet" href="_assets/css/jqm-demos.css">    
    #<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    #<script src="_assets/js/index.js"></script>  
    #<script type="text/javascript" src="http://services.gisgraphy.com/scripts/gisgraphyapi.js"></script> 
    ?>
  	
    
    <link rel="stylesheet" href="https://rawgithub.com/arschmitz/jquery-mobile-datepicker-wrapper/v0.1.1/jquery.mobile.datepicker.css" /> 
  	<script src="js/jquery.js"></script>
  	
  	<script src="js/jquery.mobile-1.4.2.min.js"></script>  
    <script src="js/jquery.ui.datepicker.js"></script> 
    <script id="mobile-datepicker" src="js/jquery.mobile.datepicker.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script> 
    
    <script type="text/javascript"> 
      var geocoder;
  
      //Get the latitude and the longitude;
      function successFunction(position) {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          geocoder = new google.maps.Geocoder();
          codeLatLng(lat, lng)
      }
  
      function errorFunction(){
          alert("Geocoder failed");
      }
      
        function initialize() {
          geocoder = new google.maps.Geocoder();
        }
      
        function codeLatLng(lat, lng) {
          var latlng = new google.maps.LatLng(lat, lng);
          geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
            console.log(results)
              if (results[1]) {
               //formatted address
               //alert(results[0].formatted_address)
               
              //find country name
                   for (var i=0; i<results[0].address_components.length; i++) {
                  for (var b=0;b<results[0].address_components[i].types.length;b++) {
      
                  //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                      if (results[0].address_components[i].types[b] == "locality") {
                          //this is the object you are looking for
                          city= results[0].address_components[i];
                          break;
                      }
                  }
              }
              //city data
              document.getElementById("geo_ort").value = city.short_name;
              //alert(city.short_name + " " + city.long_name)
      
              } else {
                alert("No results found");
              }
            } else {
              alert("Geocoder failed due to: " + status);
            }
          });
      }
      function getLocation(){
      
         if(navigator.geolocation){  
            navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
         }else{
            alert("Sorry, browser does not support geolocation!");
         }
      } 
      $(function() {
        $( "#datepicker" ).datepicker();
      });          
    </script>

 
    
<script type='text/javascript'>

var _ues = {
host:'toli.userecho.com',
forum:'31282',
lang:'de',
tab_corner_radius:5,
tab_font_size:20,
tab_image_hash:'ZmVlZGJhY2s%3D',
tab_chat_hash:'',
tab_alignment:'bottom',
tab_text_color:'#FFFFFF',
tab_text_shadow_color:'#00000055',
tab_bg_color:'#57A957',
tab_hover_color:'#F45C5C'
};

(function() {
    var _ue = document.createElement('script'); _ue.type = 'text/javascript'; _ue.async = true;
    _ue.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.userecho.com/js/widget-1.4.gz.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(_ue, s);
  })();

</script>
    
   <?
        require 'class.iCalReader.php';
   
      function _make_url_clickable_cb($matches) {
      	$ret = '';
      	$url = $matches[2];
       
      	if ( empty($url) )
      		return $matches[0];
      	// removed trailing [.,;:] from URL
      	if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
      		$ret = substr($url, -1);
      		$url = substr($url, 0, strlen($url)-1);
      	}
      	return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
      }
       
      function _make_web_ftp_clickable_cb($matches) {
      	$ret = '';
      	$dest = $matches[2];
      	$dest = 'http://' . $dest;
       
      	if ( empty($dest) )
      		return $matches[0];
      	// removed trailing [,;:] from URL
      	if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
      		$ret = substr($dest, -1);
      		$dest = substr($dest, 0, strlen($dest)-1);
      	}
      	return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
      }
       
      function _make_email_clickable_cb($matches) {
      	$email = $matches[2] . '@' . $matches[3];
      	return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
      }
       
      function make_clickable($ret) {
      	$ret = ' ' . $ret;
      	// in testing, using arrays here was found to be faster
      	$ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
      	$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
      	$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);
       
      	// this one is not in an array because we need it to run last, for cleanup of accidental links within links
      	$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
      	$ret = trim($ret);
      	return $ret;
      } 
      
      function iCalTime($icalDate)          # gibt Uhrzeit aus ical ZUrück
      { 
          $icalDate = str_replace('T', '', $icalDate); 
          $icalDate = str_replace('Z', '', $icalDate); 
  
          $pattern  = '/([0-9]{4})';   // 1: YYYY
          $pattern .= '([0-9]{2})';    // 2: MM
          $pattern .= '([0-9]{2})';    // 3: DD
          $pattern .= '([0-9]{0,2})';  // 4: HH
          $pattern .= '([0-9]{0,2})';  // 5: MM
          $pattern .= '([0-9]{0,2})/'; // 6: SS
          preg_match($pattern, $icalDate, $date); 
  
          // Unix timestamp can't represent dates before 1970
          if ($date[1] <= 1970) {
              return false;
          } 
          if(date( I )){$date[4]=$date[4]+1;}   # prüft ob Sommerzeit
          $time= $date[4].":".$date[5];
          return  $time;
      } 
    function distance($lat1, $lng1, $lat2, $lng2)
    {
     $pi80 = M_PI / 180;
     $lat1 *= $pi80;
     $lng1 *= $pi80;
     $lat2 *= $pi80;
     $lng2 *= $pi80;
     
     $r = 6372.797; // mean radius of Earth in km
     $dlat = $lat2 - $lat1;
     $dlng = $lng2 - $lng1;
     $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
     $km = $r * $c;
     
     return ($km);
    }  
             
   
    ## wann
    if ($_GET['caldate']){  
      $datum_geted = $_GET['caldate']; 
      $_SESSION['s_caldate'] = $datum_geted; 
    }
    else{   
      $datum_geted =$_SESSION['s_caldate'];
      if ($datum_geted==null){
        $timestamp = time();
        $datum_geted = date("m/d/y", $timestamp);
      }  
    }
    
    ## wo
    if ($_GET['ort']){  
      $ort_geted = $_GET['ort']; 
      $_SESSION['s_ort'] = $ort_geted; 
    }
    else{   
      $ort_geted =$_SESSION['s_ort'];
      if ($ort_geted==null){
        $ort_geted = "&uuml;berall";   
      }  
    }
    
    ## umkreis
    if ($_GET['umkreis']){  
      $umkreis_geted = $_GET['umkreis']; 
      $_SESSION['s_umkreis'] = $umkreis_geted; 
    }
    else{
      $umkreis_geted =$_SESSION['s_umkreis'];
    }
    if ($umkreis_geted==null){
    }else{ 
      $umkreis_getedn= "(".$umkreis_geted."km)";
    }
    
    
    ##wer
    if ($_GET['wer']){  
      $wer_geted = $_GET['wer']; 
      $_SESSION['s_wer'] = $wer_geted; 
    }
    else{   
      $wer_geted =$_SESSION['s_wer'];
      if ($wer_geted==null){
        $wer_geted = "1";   #
      }  
    } 
    if  ($wer_geted==1){
      $wer_geted= "alle Mitglieder";
    }
    else if  ($wer_geted==2){
      $wer_geted= "ordentliche Mitglieder";
    } else{$wer_geted= "studentische Mitglieder";}
   ?>   
  </head>
  <body>
    <div data-role="page">
      <div data-role="header" data-position="fixed">
          <div data-role="navbar" data-iconpos="left">
              <ul>      
                  <li><a href="#wann<? $_SERVER['PHP_SELF'] ?>" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="calendar">Wann?<br><? echo $datum_geted; ?></a></li>
                  <li><a href="#wo<? $_SERVER['PHP_SELF'] ?>" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="location">Wo?<br><? echo $ort_geted." ".$umkreis_getedn; ?></a></li>
                  <li><a href="#wer<? $_SERVER['PHP_SELF'] ?>" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="tag">Wer?<br><? echo $wer_geted; ?></a></li>
              </ul>
          </div><!-- /navbar -->
      </div>
    	<div role="main" class="ui-content">
        <?php
       
        
        $tage = array("Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag");
         
        $ical   = new ICal('https://www.google.com/calendar/ical/google%40vwi.org/public/basic.ics');
        $events2 = $ical->eventsFromRange($datum_geted, false);
        $events = $ical->sortEventsWithOrder($events2,'SORT_ASC');
        
        $gaddress = $ort_geted;
        $gpath = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($gaddress).'&sensor=false';
        $ggeocode = file_get_contents($gpath);
        $goutput = json_decode($ggeocode);
        $get_latitude  = $goutput->results[0]->geometry->location->lat;
        $get_longitude = $goutput->results[0]->geometry->location->lng;

        echo "<div data-role=\"collapsibleset\" data-theme=\"a\" data-content-theme=\"a\">";
        foreach ($events as $event) {  
          if  (((stristr($event['SUMMARY'], '[O]') or stristr($event['SUMMARY'], '[A]')) and $wer_geted=="ordentliche Mitglieder") or ((stristr($event['SUMMARY'], '[S]') or stristr($event['SUMMARY'], '[A]')) and $wer_geted=="studentische Mitglieder") or $wer_geted=="alle Mitglieder"){
            #echo trim(stristr($event['SUMMARY'], '[A]'),'[A]') ;  
            $address = $event['LOCATION'];
            $path = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';
            $geocode = file_get_contents($path);
            $output = json_decode($geocode);
            $latitude  = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng; 
            $dist=ceil(distance($latitude,$longitude,$get_latitude,$get_longitude));  
            if (($ort_geted != "&uuml;berall" and $dist <= (int)$umkreis_geted)or($ort_geted == "&uuml;berall")){
                echo "<div data-role=\"collapsible\">";
                $tag = date("w",$ical->iCalDateToUnixTimestamp($event['DTSTART']));
                $eventdate=date("d.m.y",$ical->iCalDateToUnixTimestamp($event['DTSTART']));
                $eventtime=iCalTime($event['DTSTART']); 
                $eventdateb=date("d.m.y",$ical->iCalDateToUnixTimestamp($event['DTEND'])); 
                $eventtimeb=iCalTime($event['DTEND']);             
                #$eventtimeb=date("H:m",$ical->iCalDateToUnixTimestamp($event['DTEND']));
                $del_slash=str_replace("\\", "", $event['SUMMARY']);
                echo "<h3>".$eventdate." | ".$del_slash."</h3>"; 
                echo "<img src=\"images/icons-png/clock-black.png\"> ".$tage[$tag].", ".$eventdate." ab ".$eventtime."Uhr";
                #echo " bis ".$eventdateb.", ".$eventtimeb."Uhr";
                if (stristr($event['SUMMARY'], '[O]')){
                  $wen="ordentliche Mitglieder"; 
                }elseif (stristr($event['SUMMARY'], '[S]')){
                  $wen="studentische Mitglieder";
                 }else{$wen="alle Mitglieder";} 
                echo "<br><img src=\"images/icons-png/user-black.png\"> f&uuml;r ".$wen."<br/>";
                if($ort_geted != "&uuml;berall"){
                  $dist=" - Entfernung: ".$dist."km";
                }else{
                  $dist="";
                }
                echo "<a href='http://maps.google.de/maps?daddr=".str_replace("\\", "", $event['LOCATION'])."'><img src=\"images/icons-png/home-black.png\"> ".str_replace("\\", "", $event['LOCATION'])."</a>".$dist." ";
                echo "<br/><br/>";
                
                echo "<img src=\"images/icons-png/info-black.png\"> ".$del_slash."<br/>";
                $beschreibung = $event['DESCRIPTION'];
                $beschreibung= make_clickable($beschreibung); 
                $beschreibung = str_replace( "\\n", '<br />', $beschreibung );
                $beschreibung = str_replace( "\\", '', $beschreibung );    
                echo $beschreibung;
                echo "</p>";
                echo "</div>";
            }
          } 
        }
        
        
        echo "</div>";
        ?>
      </div><!-- /content -->
    
    	<div data-role="footer" data-position="fixed">
        <div data-role="controlgroup" data-type="horizontal">
          <a href="javascript:window.history.go(-1)" class="ui-btn ui-corner-all ui-icon-arrow-l ui-btn-icon-left">zur&uuml;ck</a>
          <a href="#impressum" class="ui-btn ui-corner-all">Impressum</a>
      	</div>	
    	</div><!-- /footer -->
    </div><!-- /page -->
    
    
    <!-- Start of second page: #2 -->
    <div data-role="page" id="wann" data-theme="a">
      <div data-role="header" data-position="fixed">
          <div data-role="navbar" data-iconpos="left">
              <ul>      
                  <li><a href="#wann" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left ui-btn-active" data-icon="calendar">Wann?</a></li>
                  <li><a href="#wo" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="location">Wo?</a></li>
                  <li><a href="#wer" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="tag">Wer?</a></li>
              </ul>
          </div><!-- /navbar -->
      </div>
      <!-- /header -->
      
      <div role="main" class="ui-content">
      		<h2>Wann suchst du Veranstaltungen?</h2>
      		<div role="main" class="ui-content">
            <form name="calendar" method="get" action="<? $_SERVER['PHP_SELF'] ?>" >  
              <input type="text" name="caldate" data-role="date" data-inline="true"> 
              <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Veranstaltung finden</button>
            </form>
          </div>  
      </div><!-- /content -->
    
    	<div data-role="footer" data-position="fixed">
        <div data-role="controlgroup" data-type="horizontal">
          <a href="javascript:window.history.go(-1)" class="ui-btn ui-corner-all ui-icon-arrow-l ui-btn-icon-left">zur&uuml;ck</a>
          <a href="#impressum" class="ui-btn ui-corner-all">Impressum</a>
      	</div>	
    	</div><!-- /footer -->
    </div><!-- /page 2 -->
    
    <!-- Start of second page: #3 -->
    <div data-role="page" id="wo" data-theme="a">
      <div data-role="header" data-position="fixed">
          <div data-role="navbar" data-iconpos="left">
              <ul>      
                  <li><a href="#wann" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="calendar">Wann?</a></li>
                  <li><a href="#wo" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left ui-btn-active" data-icon="location">Wo?</a></li>
                  <li><a href="#wer" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="tag">Wer?</a></li>
              </ul>
          </div><!-- /navbar -->
      </div>
      <!-- /header -->
      
      <div role="main" class="ui-content">
      		<h2>Wo suchst du Veranstaltungen?</h2>
      		<div role="main" class="ui-content">
            <form name="ort" method="get" action="<? $_SERVER['PHP_SELF'] ?>" >
              <label for="ort">Ort</label> 
              <input name="ort" id="geo_ort" value="" type="text">   
              <input type="button" onclick="javascript:getLocation()" value="Standort feststellen"/>  
              <label for="umkreis">Umkreis in km:</label>
              <input name="umkreis" id="slider-1" min="20" max="500" value="50" type="range">
              <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Veranstaltung finden</button>
            </form>
          </div>
      </div><!-- /content -->
    
    	<div data-role="footer" data-position="fixed">
        <div data-role="controlgroup" data-type="horizontal">
          <a href="javascript:window.history.go(-1)" class="ui-btn ui-corner-all ui-icon-arrow-l ui-btn-icon-left">zur&uuml;ck</a>
          <a href="#impressum" class="ui-btn ui-corner-all">Impressum</a>
      	</div>	
    	</div><!-- /footer -->
    </div><!-- /page 3 -->
   
    <!-- Start of second page: #4 -->
    <div data-role="page" id="wer" data-theme="a">
      <div data-role="header" data-position="fixed">
          <div data-role="navbar" data-iconpos="left">
              <ul>      
                  <li><a href="#wann" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="calendar">Wann?</a></li>
                  <li><a href="#wo" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="location">Wo?</a></li>
                  <li><a href="#wer" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left ui-btn-active" data-icon="tag">Wer?</a></li>
              </ul>
          </div><!-- /navbar -->
      </div>
      <!-- /header -->
      
      <div role="main" class="ui-content">
      		<h2>F&uuml;r welche Zielgruppe suchst du Veranstaltungen?</h2>
      		<div role="main" class="ui-content">
            <form name="wer" method="get" action="<? $_SERVER['PHP_SELF'] ?>" >
                <div class="ui-field-contain">
                  <label for="select-custom-1">Zielgruppe:</label>
                  <select name="wer" id="wer" data-native-menu="false">
                      <option value="1">alle Mitglieder</option>
                      <option value="2">ordentliche Mitglieder</option>
                      <option value="3">studentische Mitglieder</option>
                  </select>
                </div>
              <button type="submit" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check">Veranstaltung finden</button>
            </form>
          </div>  
      </div><!-- /content -->
    
    	<div data-role="footer" data-position="fixed">
        <div data-role="controlgroup" data-type="horizontal">
          <a href="javascript:window.history.go(-1)" class="ui-btn ui-corner-all ui-icon-arrow-l ui-btn-icon-left">zur&uuml;ck</a>
          <a href="#impressum" class="ui-btn ui-corner-all">Impressum</a>
      	</div>	
    	</div><!-- /footer -->
    </div><!-- /page4 -->
    
        <!-- Start of second page: #5 -->
    <div data-role="page" id="impressum" data-theme="a">
      <div data-role="header" data-position="fixed">
          <div data-role="navbar" data-iconpos="left">
              <ul>      
                  <li><a href="#wann" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="calendar">Wann?</a></li>
                  <li><a href="#wo" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="location">Wo?</a></li>
                  <li><a href="#wer" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline  ui-btn-icon-left" data-icon="tag">Wer?</a></li>
              </ul>
          </div><!-- /navbar -->
      </div>
      <!-- /header -->
      
      <div role="main" class="ui-content">
      		<h2>Impressum</h2>
      		<div role="main" class="ui-content">
            Verantwortlich i.S.v. &sect;&sect; 6, 8 TDG und &sect; 6 MDStV:  <br>
              Tobias Lindner <br>
              tobias.lindner [at] catl.de  <br>
              Augsburger Str. 34   <br>
              85221 Dachau  <br>
              Deutschland <br>
              Tel: 01711553499   <br>
                <br>
                <br>
           <h3>Credits</h3> 
           <ul>
            <li><a href="http://jquerymobile.com" target="_blank">jQuery Mobil</a></li>  
            <li><a href="https://github.com/johngrogg/ics-parser" target="_blank">ics-parser</a></li> 
            <li><a href="https://github.com/arschmitz/jquery-mobile-datepicker-wrapper" target="_blank">jQuery Mobil Datepicker</a></li>   
           </ul>    
          </div>  
      </div><!-- /content -->
    
    	<div data-role="footer" data-position="fixed">
        <div data-role="controlgroup" data-type="horizontal">
          <a href="javascript:window.history.go(-1)" class="ui-btn ui-corner-all ui-icon-arrow-l ui-btn-icon-left">zur&uuml;ck</a>
          <a href="#impressum" class="ui-btn ui-corner-all">Impressum</a>
      	</div>	
    	</div><!-- /footer -->
    </div><!-- /page5 -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1241347-4', 'litoo.de');
  ga('send', 'pageview');

</script>
  </body>
</html>

