<?php
include_once( dirname(__FILE__) . '/class/Weather.php' );
include_once( dirname(__FILE__) . '/class/GPS.php' );

if (!isset($_GET['device_id'])){
    die();
}else{
    $device_id = $_GET['device_id'];
    $lastPosition = GPS::getLastPosition($device_id);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/reset.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />

        <title>M-Future</title>

        <style type="text/css">
            /* Set a size for our map container, the Google Map will take up 100% of this container */
            #map {
                height: 200px;
                width: 460px;
                margin-left: 5px;
            }
            #placeholder{height: 250px; width: 100%; margin-top: 35px; margin-bottom: 20px; background: #00080D;}
        </style>

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery-mobile.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc&sensor=false"></script>
        <script type="text/javascript" src="js/jquery.flot.js"></script>
        <script type="text/javascript" src="js/socket.io.min.js"></script>
        
        <script type="text/javascript">
            // When the window has finished loading create our google map below
            google.maps.event.addDomListener(window, 'load', init);

            function init() {
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions

                var myLatlng = new google.maps.LatLng(42.000474, 21.398672);

                var mapOptions = {
                    zoom: 13,
                    center: myLatlng,
                    styles: [{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#003753"}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#2c5a71"}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#067fbc"}, {"lightness": 7}]}, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#406d80"}]}, {"featureType": "transit", "elementType": "geometry", "stylers": [{"color": "#ff5e5e"}]}, {"elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#3e606f"}, {"weight": 2}, {"gamma": 0.84}]}, {"elementType": "labels.text.fill", "stylers": [{"color": "#ffffff"}]}, {"featureType": "administrative", "elementType": "geometry", "stylers": [{"weight": 0.6}, {"color": "#0785c2"}]}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#2c5a71"}]}]
                };

                var mapElement = document.getElementById('map');

                // Create the Google Map using out element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                });

            }
        </script>
    </head>
    <body>
        <?php
//The Latitude of Prilep is 41.351730000000000000. The Longitude of Prilep is 21.562140000000000000.
        $lon = $lastPosition->lon;
        $lat = $lastPosition->lat;
          
        if ($lon!=0 && $lat!=0) {
            $lon = $lastPosition->lon;
            $lat = $lastPosition->lat;
            $weather = new Weather($lat, $lon);
        } 
        ?>
        <div id="latitude" style="display: none;"><?php echo $lat; ?></div>
        <div id="longitude" style="display: none;"><?php echo $lon; ?></div>
        <div id="deviceid" style="display: none;"><?php echo $device_id; ?></div>

        <div id="wrapper">
            <div id="inner-wrapper">
                <div class="half-wrapper margin-right">
                    <div class="block"><div class="header-h2"><span class="header-icons" id="position"></span><h2>YOUR POSITION</h2></div>
                      <div id="map"></div>
                    </div>
                    <div class="block">
                        <div class="header-h2"><span class="header-icons" id="weather"></span><h2>WEATHER INFO</h2></div>
                        <div style="clear:both;"></div>
                        <div class="weather-infos">
                            <div class="thermometer-demo">
                                <h2></h2>
                                <div class="thermo-wrapper">
                                    <div class="oter2">
                                        <div class="thermometer thermometer-noconfig" id="thermometer-f" data-percent="75" data-orientation="vertical"></div>
                                    </div>
                                    <div class="thermo-bg"></div>
                                </div>
                            </div>
                            <h3 id="current-temperature"><?php
                                if (isset($weather->temp_C) && $weather->temp_C != "")
                                    echo $weather->temp_C;
                                else
                                    echo "NaN";
                                ?></h3>
                            <span class="celsius"></span>
                            <img class="current-weather" src="images/current-weather.png"/>
                            <div style="clear:both;"></div>
                            <h3 id="current-place">Place, Country</h3>
                            <h4>Humidity<span> <?php
                                    if (isset($weather->humidity) && $weather->humidity != "")
                                        echo $weather->humidity;
                                    else
                                        echo "NaN";
                                    ?> %</span></h4>
                            <h4>Pressure<span> <?php
                                    if (isset($weather->pressure) && $weather->pressure != "")
                                        echo $weather->pressure;
                                    else
                                        echo "NaN";
                                    ?> mb </span></h4>
                            <h4>Wind Direction<span> <?php
                                    if (isset($weather->winddirDegree) && $weather->winddirDegree != "")
                                        echo $weather->winddirDegree;
                                    else
                                        echo "NaN";
                                    ?> &deg </span></h4> 
                            <h4>Wind Speed<span> <?php
                                    if (isset($weather->windspeedKmph) && $weather->windspeedKmph != "")
                                        echo $weather->windspeedKmph;
                                    else
                                        echo "NaN";
                                    ?> km/h </span></h4> 
                        </div>
                    </div>
                </div>
                <div class="half-wrapper">
                    <div class="block"><div class="header-h2"><span class="header-icons" id="camera"></span><h2>CAMERA</h2></div>
                        <iframe src="http://192.168.1.101:8082" width="220" height="450" frameborder=1 style="-ms-transform: rotate(90deg);-webkit-transform: rotate(90deg);transform: rotate(90deg); margin-top:-90px;"></iframe> 

                    </div>
                    <div class="block1">
                        <div id="controls">
                            <span id="up"></span>
                            <span id="left"></span>
                            <span id="down"></span>
                            <span id="right"></span>
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
                                        <div class="demo-container">
                            <div id="placeholder" class="demo-placeholder" ></div>
                        </div>
            </div>

        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="js/jquery.thermometer.js"></script>

        <script>
            (function($) {
                setTimeout(function() {
                    thermometer();
                }, 1000);
            })(jQuery);


            function thermometer() {
                $('.thermometer-demo').each(function() {
                    /* build table of contents for each section */
                    var demoName = $(this).find('h2').html(),
                            demoAnchor = demoName.replace(/ /g, '-').toLowerCase();
                    $(this).prepend('<a name="' + demoAnchor + '"></a>');
                    $('#toc').append('<li><a href="#' + demoAnchor + '">' + demoName + '</a></li>');
                });

                /* thermometers with no config */
                $('.thermometer-noconfig').thermometer();

                /* thermometers with config */
                $('.thermometer-config').thermometer({
                    percent: 75,
                    speed: 'slow'
                });
            }

        </script>
        <script>
            var latGlobal = 0;
            var lonGlobal = 0;
            
            $(document).ready(function() {
                codeLatLng();
                
                latGlobal = $("#latitude").html();
                lonGlobal = $("#longitude").html();
            });

            var geocoder;
            var map;
            var infowindow = new google.maps.InfoWindow();
            var marker;

            function codeLatLng() {
                var latlng;
                if(latGlobal == 0)
                    var lat = $("#latitude").html();
                else
                    var lat = latGlobal;
                    
                if(lonGlobal == 0)
                    var lon = $("#longitude").html();
                else
                    var lon = latGlobal;
                    
                latlng = new google.maps.LatLng(lat, lon);
                geocoder = new google.maps.Geocoder();

                geocoder.geocode({'latLng': latlng}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            $("#current-place").html(results[1].formatted_address);
                        } else {
                            alert('Place not found!');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
                });
            }

            var req = new XMLHttpRequest();
            $(window).keyup(function(data) {
				setTimeout(function() {
				   req.open('GET', 'http://localhost/droidcommand.php?msg=hover', false);
				   req.send();
			   }, 1000);
               
            });

//            $('#up').click(function() {
//                req.open('GET', 'http://localhost/socket.php?msg=napred', false);
//                req.send();
//            });
//
//            $('#down').click(function() {
//                 req.open('GET', 'http://localhost/socket.php?msg=nazad', false);
//                 req.send();
//            });
//
//            $('#left').click(function() {
//                 req.open('GET', 'http://localhost/socket.php?msg=levo', false);
//                 req.send();
//            });
//
//            $('#right').click(function() {
//                req.open('GET', 'http://localhost/socket.php?msg=desno', false);
//                req.send();
//
//            });
              $(document).ready(function() {
                $(window).keydown(function(data) {

                switch (data.keyCode)
                {
                    // u    // takeoff
                    case 85:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=takeoff', false);
                        req.send();
                        break;
                    
                    // j    // land
                    case 74:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=land', false);
                        req.send();
                        break;
                    
                    // i    // up
                    case 73:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=up', false);
                        req.send();
                        break;
                    
                    // k    // down
                    case 75:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=down', false);
                        req.send();
                        break;
                    
                    // h    // hover
                    case 72:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=forward', false);
                        req.send();
                        break;
                    
                    // w
                    case 87:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=forward', false);
                        req.send();
                        break;
                    // a        
                    case 65:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=left', false);
                        req.send();
                        break;
                    // d
                    case 68:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=right', false);
                        req.send();
                        break;

                    // s
                    case 83:
                        req.open('GET', 'http://localhost/droidcommand.php?msg=backward', false);
                        req.send();
                        break;
                }

                if (req.status == 200) {
                    ///alert(req.responseText);
                }

            });
            });
//            $("#up").on("touchstart", function() {
//                req.open('GET', 'http://192.168.1.100/socket.php?msg=napred', false);
//                req.send();
//            });
//            $("#down").on("touchstart", function() {
//                req.open('GET', 'http://192.168.1.100/socket.php?msg=nazad', false);
//                req.send();
//            });
//            $("#left").on("touchstart", function() {
//                req.open('GET', 'http://192.168.1.100/socket.php?msg=levo', false);
//                req.send();
//            });
//            $("#right").on("touchstart", function() {
//                req.open('GET', 'http://192.168.1.100/socket.php?msg=desno', false);
//                req.send();
//            });
//
//            $(document).on("touchend", function() {
//                req.open('GET', 'http://192.168.1.100/socket.php?msg=zapri', false);
//                req.send();
//            });
        </script>
        <script>
            function iceplot(e){var t=[e],n=$("#placeholder");$("<div id='tooltip'></div>").css({position:"absolute",display:"none",border:"1px solid #fdd",padding:"2px","font-size":"20px","background-color":"#E7FFFF",opacity:.8}).appendTo("body");var r=$.plot("#placeholder",[{data:e,label:"Vertices"}],{series:{lines:{show:true},points:{show:true},shadowSize:0},grid:{hoverable:true,clickable:true}});$("#placeholder").bind("plothover",function(e,t,n){var r="("+t.x.toFixed(6)+", "+t.y.toFixed(6)+")";$("#hoverdata").text(r);if(n){var i=n.datapoint[0].toFixed(6),s=n.datapoint[1].toFixed(6);$("#tooltip").html("Vertex "+n.dataIndex+"["+i+","+s+"]").css({top:n.pageY+5,left:n.pageX+5}).fadeIn(200)}else{$("#tooltip").hide()}})}(function(e){e.color={};e.color.make=function(t,n,r,i){var s={};s.r=t||0;s.g=n||0;s.b=r||0;s.a=i!=null?i:1;s.add=function(e,t){for(var n=0;n<e.length;++n)s[e.charAt(n)]+=t;return s.normalize()};s.scale=function(e,t){for(var n=0;n<e.length;++n)s[e.charAt(n)]*=t;return s.normalize()};s.toString=function(){if(s.a>=1){return"rgb("+[s.r,s.g,s.b].join(",")+")"}else{return"rgba("+[s.r,s.g,s.b,s.a].join(",")+")"}};s.normalize=function(){function e(e,t,n){return t<e?e:t>n?n:t}s.r=e(0,parseInt(s.r),255);s.g=e(0,parseInt(s.g),255);s.b=e(0,parseInt(s.b),255);s.a=e(0,s.a,1);return s};s.clone=function(){return e.color.make(s.r,s.b,s.g,s.a)};return s.normalize()};e.color.extract=function(t,n){var r;do{r=t.css(n).toLowerCase();if(r!=""&&r!="transparent")break;t=t.parent()}while(t.length&&!e.nodeName(t.get(0),"body"));if(r=="rgba(0, 0, 0, 0)")r="transparent";return e.color.parse(r)};e.color.parse=function(n){var r,i=e.color.make;if(r=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(n))return i(parseInt(r[1],10),parseInt(r[2],10),parseInt(r[3],10));if(r=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(n))return i(parseInt(r[1],10),parseInt(r[2],10),parseInt(r[3],10),parseFloat(r[4]));if(r=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(n))return i(parseFloat(r[1])*2.55,parseFloat(r[2])*2.55,parseFloat(r[3])*2.55);if(r=/rgba\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(n))return i(parseFloat(r[1])*2.55,parseFloat(r[2])*2.55,parseFloat(r[3])*2.55,parseFloat(r[4]));if(r=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(n))return i(parseInt(r[1],16),parseInt(r[2],16),parseInt(r[3],16));if(r=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(n))return i(parseInt(r[1]+r[1],16),parseInt(r[2]+r[2],16),parseInt(r[3]+r[3],16));var s=e.trim(n).toLowerCase();if(s=="transparent")return i(255,255,255,0);else{r=t[s]||[0,0,0];return i(r[0],r[1],r[2])}};var t={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0]}})(jQuery);(function(e){function n(t,n){var r=n.children("."+t)[0];if(r==null){r=document.createElement("canvas");r.className=t;e(r).css({direction:"ltr",position:"absolute",left:0,top:0}).appendTo(n);if(!r.getContext){if(window.G_vmlCanvasManager){r=window.G_vmlCanvasManager.initElement(r)}else{throw new Error("Canvas is not available. If you're using IE with a fall-back such as Excanvas, then there's either a mistake in your conditional include, or the page has no DOCTYPE and is rendering in Quirks Mode.")}}}this.element=r;var i=this.context=r.getContext("2d");var s=window.devicePixelRatio||1,o=i.webkitBackingStorePixelRatio||i.mozBackingStorePixelRatio||i.msBackingStorePixelRatio||i.oBackingStorePixelRatio||i.backingStorePixelRatio||1;this.pixelRatio=s/o;this.resize(n.width(),n.height());this.textContainer=null;this.text={};this._textCache={}}function r(t,r,s,o){function E(e,t){t=[w].concat(t);for(var n=0;n<e.length;++n)e[n].apply(this,t)}function S(){var t={Canvas:n};for(var r=0;r<o.length;++r){var i=o[r];i.init(w,t);if(i.options)e.extend(true,a,i.options)}}function x(n){e.extend(true,a,n);if(n&&n.colors){a.colors=n.colors}if(a.xaxis.color==null)a.xaxis.color=e.color.parse(a.grid.color).scale("a",.22).toString();if(a.yaxis.color==null)a.yaxis.color=e.color.parse(a.grid.color).scale("a",.22).toString();if(a.xaxis.tickColor==null)a.xaxis.tickColor=a.grid.tickColor||a.xaxis.color;if(a.yaxis.tickColor==null)a.yaxis.tickColor=a.grid.tickColor||a.yaxis.color;if(a.grid.borderColor==null)a.grid.borderColor=a.grid.color;if(a.grid.tickColor==null)a.grid.tickColor=e.color.parse(a.grid.color).scale("a",.22).toString();var r,i,s,o=t.css("font-size"),u=o?+o.replace("px",""):13,f={style:t.css("font-style"),size:Math.round(.8*u),variant:t.css("font-variant"),weight:t.css("font-weight"),family:t.css("font-family")};s=a.xaxes.length||1;for(r=0;r<s;++r){i=a.xaxes[r];if(i&&!i.tickColor){i.tickColor=i.color}i=e.extend(true,{},a.xaxis,i);a.xaxes[r]=i;if(i.font){i.font=e.extend({},f,i.font);if(!i.font.color){i.font.color=i.color}if(!i.font.lineHeight){i.font.lineHeight=Math.round(i.font.size*1.15)}}}s=a.yaxes.length||1;for(r=0;r<s;++r){i=a.yaxes[r];if(i&&!i.tickColor){i.tickColor=i.color}i=e.extend(true,{},a.yaxis,i);a.yaxes[r]=i;if(i.font){i.font=e.extend({},f,i.font);if(!i.font.color){i.font.color=i.color}if(!i.font.lineHeight){i.font.lineHeight=Math.round(i.font.size*1.15)}}}if(a.xaxis.noTicks&&a.xaxis.ticks==null)a.xaxis.ticks=a.xaxis.noTicks;if(a.yaxis.noTicks&&a.yaxis.ticks==null)a.yaxis.ticks=a.yaxis.noTicks;if(a.x2axis){a.xaxes[1]=e.extend(true,{},a.xaxis,a.x2axis);a.xaxes[1].position="top"}if(a.y2axis){a.yaxes[1]=e.extend(true,{},a.yaxis,a.y2axis);a.yaxes[1].position="right"}if(a.grid.coloredAreas)a.grid.markings=a.grid.coloredAreas;if(a.grid.coloredAreasColor)a.grid.markingsColor=a.grid.coloredAreasColor;if(a.lines)e.extend(true,a.series.lines,a.lines);if(a.points)e.extend(true,a.series.points,a.points);if(a.bars)e.extend(true,a.series.bars,a.bars);if(a.shadowSize!=null)a.series.shadowSize=a.shadowSize;if(a.highlightColor!=null)a.series.highlightColor=a.highlightColor;for(r=0;r<a.xaxes.length;++r)O(d,r+1).options=a.xaxes[r];for(r=0;r<a.yaxes.length;++r)O(v,r+1).options=a.yaxes[r];for(var l in b)if(a.hooks[l]&&a.hooks[l].length)b[l]=b[l].concat(a.hooks[l]);E(b.processOptions,[a])}function T(e){u=N(e);M();_()}function N(t){var n=[];for(var r=0;r<t.length;++r){var i=e.extend(true,{},a.series);if(t[r].data!=null){i.data=t[r].data;delete t[r].data;e.extend(true,i,t[r]);t[r].data=i.data}else i.data=t[r];n.push(i)}return n}function C(e,t){var n=e[t+"axis"];if(typeof n=="object")n=n.n;if(typeof n!="number")n=1;return n}function k(){return e.grep(d.concat(v),function(e){return e})}function L(e){var t={},n,r;for(n=0;n<d.length;++n){r=d[n];if(r&&r.used)t["x"+r.n]=r.c2p(e.left)}for(n=0;n<v.length;++n){r=v[n];if(r&&r.used)t["y"+r.n]=r.c2p(e.top)}if(t.x1!==undefined)t.x=t.x1;if(t.y1!==undefined)t.y=t.y1;return t}function A(e){var t={},n,r,i;for(n=0;n<d.length;++n){r=d[n];if(r&&r.used){i="x"+r.n;if(e[i]==null&&r.n==1)i="x";if(e[i]!=null){t.left=r.p2c(e[i]);break}}}for(n=0;n<v.length;++n){r=v[n];if(r&&r.used){i="y"+r.n;if(e[i]==null&&r.n==1)i="y";if(e[i]!=null){t.top=r.p2c(e[i]);break}}}return t}function O(t,n){if(!t[n-1])t[n-1]={n:n,direction:t==d?"x":"y",options:e.extend(true,{},t==d?a.xaxis:a.yaxis)};return t[n-1]}function M(){var t=u.length,n=-1,r;for(r=0;r<u.length;++r){var i=u[r].color;if(i!=null){t--;if(typeof i=="number"&&i>n){n=i}}}if(t<=n){t=n+1}var s,o=[],f=a.colors,l=f.length,c=0;for(r=0;r<t;r++){s=e.color.parse(f[r%l]||"#666");if(r%l==0&&r){if(c>=0){if(c<.5){c=-c-.2}else c=0}else c=-c}o[r]=s.scale("rgb",1+c)}var h=0,p;for(r=0;r<u.length;++r){p=u[r];if(p.color==null){p.color=o[h].toString();++h}else if(typeof p.color=="number")p.color=o[p.color].toString();if(p.lines.show==null){var m,g=true;for(m in p)if(p[m]&&p[m].show){g=false;break}if(g)p.lines.show=true}if(p.lines.zero==null){p.lines.zero=!!p.lines.fill}p.xaxis=O(d,C(p,"x"));p.yaxis=O(v,C(p,"y"))}}function _(){function x(e,t,n){if(t<e.datamin&&t!=-r)e.datamin=t;if(n>e.datamax&&n!=r)e.datamax=n}var t=Number.POSITIVE_INFINITY,n=Number.NEGATIVE_INFINITY,r=Number.MAX_VALUE,i,s,o,a,f,l,c,h,p,d,v,m,g,y,w,S;e.each(k(),function(e,r){r.datamin=t;r.datamax=n;r.used=false});for(i=0;i<u.length;++i){l=u[i];l.datapoints={points:[]};E(b.processRawData,[l,l.data,l.datapoints])}for(i=0;i<u.length;++i){l=u[i];w=l.data;S=l.datapoints.format;if(!S){S=[];S.push({x:true,number:true,required:true});S.push({y:true,number:true,required:true});if(l.bars.show||l.lines.show&&l.lines.fill){var T=!!(l.bars.show&&l.bars.zero||l.lines.show&&l.lines.zero);S.push({y:true,number:true,required:false,defaultValue:0,autoscale:T});if(l.bars.horizontal){delete S[S.length-1].y;S[S.length-1].x=true}}l.datapoints.format=S}if(l.datapoints.pointsize!=null)continue;l.datapoints.pointsize=S.length;h=l.datapoints.pointsize;c=l.datapoints.points;var N=l.lines.show&&l.lines.steps;l.xaxis.used=l.yaxis.used=true;for(s=o=0;s<w.length;++s,o+=h){y=w[s];var C=y==null;if(!C){for(a=0;a<h;++a){m=y[a];g=S[a];if(g){if(g.number&&m!=null){m=+m;if(isNaN(m))m=null;else if(m==Infinity)m=r;else if(m==-Infinity)m=-r}if(m==null){if(g.required)C=true;if(g.defaultValue!=null)m=g.defaultValue}}c[o+a]=m}}if(C){for(a=0;a<h;++a){m=c[o+a];if(m!=null){g=S[a];if(g.autoscale!==false){if(g.x){x(l.xaxis,m,m)}if(g.y){x(l.yaxis,m,m)}}}c[o+a]=null}}else{if(N&&o>0&&c[o-h]!=null&&c[o-h]!=c[o]&&c[o-h+1]!=c[o+1]){for(a=0;a<h;++a)c[o+h+a]=c[o+a];c[o+1]=c[o-h+1];o+=h}}}}for(i=0;i<u.length;++i){l=u[i];E(b.processDatapoints,[l,l.datapoints])}for(i=0;i<u.length;++i){l=u[i];c=l.datapoints.points;h=l.datapoints.pointsize;S=l.datapoints.format;var L=t,A=t,O=n,M=n;for(s=0;s<c.length;s+=h){if(c[s]==null)continue;for(a=0;a<h;++a){m=c[s+a];g=S[a];if(!g||g.autoscale===false||m==r||m==-r)continue;if(g.x){if(m<L)L=m;if(m>O)O=m}if(g.y){if(m<A)A=m;if(m>M)M=m}}}if(l.bars.show){var _;switch(l.bars.align){case"left":_=0;break;case"right":_=-l.bars.barWidth;break;default:_=-l.bars.barWidth/2}if(l.bars.horizontal){A+=_;M+=_+l.bars.barWidth}else{L+=_;O+=_+l.bars.barWidth}}x(l.xaxis,L,O);x(l.yaxis,A,M)}e.each(k(),function(e,r){if(r.datamin==t)r.datamin=null;if(r.datamax==n)r.datamax=null})}function D(){t.css("padding",0).children().filter(function(){return!e(this).hasClass("flot-overlay")&&!e(this).hasClass("flot-base")}).remove();if(t.css("position")=="static")t.css("position","relative");f=new n("flot-base",t);l=new n("flot-overlay",t);h=f.context;p=l.context;c=e(l.element).unbind();var r=t.data("plot");if(r){r.shutdown();l.clear()}t.data("plot",w)}function P(){if(a.grid.hoverable){c.mousemove(at);c.bind("mouseleave",ft)}if(a.grid.clickable)c.click(lt);E(b.bindEvents,[c])}function H(){if(ot)clearTimeout(ot);c.unbind("mousemove",at);c.unbind("mouseleave",ft);c.unbind("click",lt);E(b.shutdown,[c])}function B(e){function t(e){return e}var n,r,i=e.options.transform||t,s=e.options.inverseTransform;if(e.direction=="x"){n=e.scale=g/Math.abs(i(e.max)-i(e.min));r=Math.min(i(e.max),i(e.min))}else{n=e.scale=y/Math.abs(i(e.max)-i(e.min));n=-n;r=Math.max(i(e.max),i(e.min))}if(i==t)e.p2c=function(e){return(e-r)*n};else e.p2c=function(e){return(i(e)-r)*n};if(!s)e.c2p=function(e){return r+e/n};else e.c2p=function(e){return s(r+e/n)}}function j(e){var t=e.options,n=e.ticks||[],r=t.labelWidth||0,i=t.labelHeight||0,s=r||(e.direction=="x"?Math.floor(f.width/(n.length||1)):null),o=e.direction+"Axis "+e.direction+e.n+"Axis",u="flot-"+e.direction+"-axis flot-"+e.direction+e.n+"-axis "+o,a=t.font||"flot-tick-label tickLabel";for(var l=0;l<n.length;++l){var c=n[l];if(!c.label)continue;var h=f.getTextInfo(u,c.label,a,null,s);r=Math.max(r,h.width);i=Math.max(i,h.height)}e.labelWidth=t.labelWidth||r;e.labelHeight=t.labelHeight||i}function F(t){var n=t.labelWidth,r=t.labelHeight,i=t.options.position,s=t.direction==="x",o=t.options.tickLength,u=a.grid.axisMargin,l=a.grid.labelMargin,c=true,h=true,p=true,g=false;e.each(s?d:v,function(e,n){if(n&&n.reserveSpace){if(n===t){g=true}else if(n.options.position===i){if(g){h=false}else{c=false}}if(!g){p=false}}});if(h){u=0}if(o==null){o=p?"full":5}if(!isNaN(+o))l+=+o;if(s){r+=l;if(i=="bottom"){m.bottom+=r+u;t.box={top:f.height-m.bottom,height:r}}else{t.box={top:m.top+u,height:r};m.top+=r+u}}else{n+=l;if(i=="left"){t.box={left:m.left+u,width:n};m.left+=n+u}else{m.right+=n+u;t.box={left:f.width-m.right,width:n}}}t.position=i;t.tickLength=o;t.box.padding=l;t.innermost=c}function I(e){if(e.direction=="x"){e.box.left=m.left-e.labelWidth/2;e.box.width=f.width-m.left-m.right+e.labelWidth}else{e.box.top=m.top-e.labelHeight/2;e.box.height=f.height-m.bottom-m.top+e.labelHeight}}function q(){var t=a.grid.minBorderMargin,n,r;if(t==null){t=0;for(r=0;r<u.length;++r)t=Math.max(t,2*(u[r].points.radius+u[r].points.lineWidth/2))}var i={left:t,right:t,top:t,bottom:t};e.each(k(),function(e,t){if(t.reserveSpace&&t.ticks&&t.ticks.length){var n=t.ticks[t.ticks.length-1];if(t.direction==="x"){i.left=Math.max(i.left,t.labelWidth/2);if(n.v<=t.max){i.right=Math.max(i.right,t.labelWidth/2)}}else{i.bottom=Math.max(i.bottom,t.labelHeight/2);if(n.v<=t.max){i.top=Math.max(i.top,t.labelHeight/2)}}}});m.left=Math.ceil(Math.max(i.left,m.left));m.right=Math.ceil(Math.max(i.right,m.right));m.top=Math.ceil(Math.max(i.top,m.top));m.bottom=Math.ceil(Math.max(i.bottom,m.bottom))}function R(){var t,n=k(),r=a.grid.show;for(var i in m){var s=a.grid.margin||0;m[i]=typeof s=="number"?s:s[i]||0}E(b.processOffset,[m]);for(var i in m){if(typeof a.grid.borderWidth=="object"){m[i]+=r?a.grid.borderWidth[i]:0}else{m[i]+=r?a.grid.borderWidth:0}}e.each(n,function(e,t){t.show=t.options.show;if(t.show==null)t.show=t.used;t.reserveSpace=t.show||t.options.reserveSpace;U(t)});if(r){var o=e.grep(n,function(e){return e.reserveSpace});e.each(o,function(e,t){z(t);W(t);X(t,t.ticks);j(t)});for(t=o.length-1;t>=0;--t)F(o[t]);q();e.each(o,function(e,t){I(t)})}g=f.width-m.left-m.right;y=f.height-m.bottom-m.top;e.each(n,function(e,t){B(t)});if(r){G()}it()}function U(e){var t=e.options,n=+(t.min!=null?t.min:e.datamin),r=+(t.max!=null?t.max:e.datamax),i=r-n;if(i==0){var s=r==0?1:.01;if(t.min==null)n-=s;if(t.max==null||t.min!=null)r+=s}else{var o=t.autoscaleMargin;if(o!=null){if(t.min==null){n-=i*o;if(n<0&&e.datamin!=null&&e.datamin>=0)n=0}if(t.max==null){r+=i*o;if(r>0&&e.datamax!=null&&e.datamax<=0)r=0}}}e.min=n;e.max=r}function z(t){var n=t.options;var r;if(typeof n.ticks=="number"&&n.ticks>0)r=n.ticks;else r=.3*Math.sqrt(t.direction=="x"?f.width:f.height);var s=(t.max-t.min)/r,o=-Math.floor(Math.log(s)/Math.LN10),u=n.tickDecimals;if(u!=null&&o>u){o=u}var a=Math.pow(10,-o),l=s/a,c;if(l<1.5){c=1}else if(l<3){c=2;if(l>2.25&&(u==null||o+1<=u)){c=2.5;++o}}else if(l<7.5){c=5}else{c=10}c*=a;if(n.minTickSize!=null&&c<n.minTickSize){c=n.minTickSize}t.delta=s;t.tickDecimals=Math.max(0,u!=null?u:o);t.tickSize=n.tickSize||c;if(n.mode=="time"&&!t.tickGenerator){throw new Error("Time mode requires the flot.time plugin.")}if(!t.tickGenerator){t.tickGenerator=function(e){var t=[],n=i(e.min,e.tickSize),r=0,s=Number.NaN,o;do{o=s;s=n+r*e.tickSize;t.push(s);++r}while(s<e.max&&s!=o);return t};t.tickFormatter=function(e,t){var n=t.tickDecimals?Math.pow(10,t.tickDecimals):1;var r=""+Math.round(e*n)/n;if(t.tickDecimals!=null){var i=r.indexOf(".");var s=i==-1?0:r.length-i-1;if(s<t.tickDecimals){return(s?r:r+".")+(""+n).substr(1,t.tickDecimals-s)}}return r}}if(e.isFunction(n.tickFormatter))t.tickFormatter=function(e,t){return""+n.tickFormatter(e,t)};if(n.alignTicksWithAxis!=null){var h=(t.direction=="x"?d:v)[n.alignTicksWithAxis-1];if(h&&h.used&&h!=t){var p=t.tickGenerator(t);if(p.length>0){if(n.min==null)t.min=Math.min(t.min,p[0]);if(n.max==null&&p.length>1)t.max=Math.max(t.max,p[p.length-1])}t.tickGenerator=function(e){var t=[],n,r;for(r=0;r<h.ticks.length;++r){n=(h.ticks[r].v-h.min)/(h.max-h.min);n=e.min+n*(e.max-e.min);t.push(n)}return t};if(!t.mode&&n.tickDecimals==null){var m=Math.max(0,-Math.floor(Math.log(t.delta)/Math.LN10)+1),g=t.tickGenerator(t);if(!(g.length>1&&/\..*0$/.test((g[1]-g[0]).toFixed(m))))t.tickDecimals=m}}}}function W(t){var n=t.options.ticks,r=[];if(n==null||typeof n=="number"&&n>0)r=t.tickGenerator(t);else if(n){if(e.isFunction(n))r=n(t);else r=n}var i,s;t.ticks=[];for(i=0;i<r.length;++i){var o=null;var u=r[i];if(typeof u=="object"){s=+u[0];if(u.length>1)o=u[1]}else s=+u;if(o==null)o=t.tickFormatter(s,t);if(!isNaN(s))t.ticks.push({v:s,label:o})}}function X(e,t){if(e.options.autoscaleMargin&&t.length>0){if(e.options.min==null)e.min=Math.min(e.min,t[0].v);if(e.options.max==null&&t.length>1)e.max=Math.max(e.max,t[t.length-1].v)}}function V(){f.clear();E(b.drawBackground,[h]);var e=a.grid;if(e.show&&e.backgroundColor)K();if(e.show&&!e.aboveData){Q()}for(var t=0;t<u.length;++t){E(b.drawSeries,[h,u[t]]);Y(u[t])}E(b.draw,[h]);if(e.show&&e.aboveData){Q()}f.render();ht()}function J(e,t){var n,r,i,s,o=k();for(var u=0;u<o.length;++u){n=o[u];if(n.direction==t){s=t+n.n+"axis";if(!e[s]&&n.n==1)s=t+"axis";if(e[s]){r=e[s].from;i=e[s].to;break}}}if(!e[s]){n=t=="x"?d[0]:v[0];r=e[t+"1"];i=e[t+"2"]}if(r!=null&&i!=null&&r>i){var a=r;r=i;i=a}return{from:r,to:i,axis:n}}function K(){h.save();h.translate(m.left,m.top);h.fillStyle=bt(a.grid.backgroundColor,y,0,"rgba(255, 255, 255, 0)");h.fillRect(0,0,g,y);h.restore()}function Q(){var t,n,r,i;h.save();h.translate(m.left,m.top);var s=a.grid.markings;if(s){if(e.isFunction(s)){n=w.getAxes();n.xmin=n.xaxis.min;n.xmax=n.xaxis.max;n.ymin=n.yaxis.min;n.ymax=n.yaxis.max;s=s(n)}for(t=0;t<s.length;++t){var o=s[t],u=J(o,"x"),f=J(o,"y");if(u.from==null)u.from=u.axis.min;if(u.to==null)u.to=u.axis.max;if(f.from==null)f.from=f.axis.min;if(f.to==null)f.to=f.axis.max;if(u.to<u.axis.min||u.from>u.axis.max||f.to<f.axis.min||f.from>f.axis.max)continue;u.from=Math.max(u.from,u.axis.min);u.to=Math.min(u.to,u.axis.max);f.from=Math.max(f.from,f.axis.min);f.to=Math.min(f.to,f.axis.max);if(u.from==u.to&&f.from==f.to)continue;u.from=u.axis.p2c(u.from);u.to=u.axis.p2c(u.to);f.from=f.axis.p2c(f.from);f.to=f.axis.p2c(f.to);if(u.from==u.to||f.from==f.to){h.beginPath();h.strokeStyle=o.color||a.grid.markingsColor;h.lineWidth=o.lineWidth||a.grid.markingsLineWidth;h.moveTo(u.from,f.from);h.lineTo(u.to,f.to);h.stroke()}else{h.fillStyle=o.color||a.grid.markingsColor;h.fillRect(u.from,f.to,u.to-u.from,f.from-f.to)}}}n=k();r=a.grid.borderWidth;for(var l=0;l<n.length;++l){var c=n[l],p=c.box,d=c.tickLength,v,b,E,S;if(!c.show||c.ticks.length==0)continue;h.lineWidth=1;if(c.direction=="x"){v=0;if(d=="full")b=c.position=="top"?0:y;else b=p.top-m.top+(c.position=="top"?p.height:0)}else{b=0;if(d=="full")v=c.position=="left"?0:g;else v=p.left-m.left+(c.position=="left"?p.width:0)}if(!c.innermost){h.strokeStyle=c.options.color;h.beginPath();E=S=0;if(c.direction=="x")E=g+1;else S=y+1;if(h.lineWidth==1){if(c.direction=="x"){b=Math.floor(b)+.5}else{v=Math.floor(v)+.5}}h.moveTo(v,b);h.lineTo(v+E,b+S);h.stroke()}h.strokeStyle=c.options.tickColor;h.beginPath();for(t=0;t<c.ticks.length;++t){var x=c.ticks[t].v;E=S=0;if(isNaN(x)||x<c.min||x>c.max||d=="full"&&(typeof r=="object"&&r[c.position]>0||r>0)&&(x==c.min||x==c.max))continue;if(c.direction=="x"){v=c.p2c(x);S=d=="full"?-y:d;if(c.position=="top")S=-S}else{b=c.p2c(x);E=d=="full"?-g:d;if(c.position=="left")E=-E}if(h.lineWidth==1){if(c.direction=="x")v=Math.floor(v)+.5;else b=Math.floor(b)+.5}h.moveTo(v,b);h.lineTo(v+E,b+S)}h.stroke()}if(r){i=a.grid.borderColor;if(typeof r=="object"||typeof i=="object"){if(typeof r!=="object"){r={top:r,right:r,bottom:r,left:r}}if(typeof i!=="object"){i={top:i,right:i,bottom:i,left:i}}if(r.top>0){h.strokeStyle=i.top;h.lineWidth=r.top;h.beginPath();h.moveTo(0-r.left,0-r.top/2);h.lineTo(g,0-r.top/2);h.stroke()}if(r.right>0){h.strokeStyle=i.right;h.lineWidth=r.right;h.beginPath();h.moveTo(g+r.right/2,0-r.top);h.lineTo(g+r.right/2,y);h.stroke()}if(r.bottom>0){h.strokeStyle=i.bottom;h.lineWidth=r.bottom;h.beginPath();h.moveTo(g+r.right,y+r.bottom/2);h.lineTo(0,y+r.bottom/2);h.stroke()}if(r.left>0){h.strokeStyle=i.left;h.lineWidth=r.left;h.beginPath();h.moveTo(0-r.left/2,y+r.bottom);h.lineTo(0-r.left/2,0);h.stroke()}}else{h.lineWidth=r;h.strokeStyle=a.grid.borderColor;h.strokeRect(-r/2,-r/2,g+r,y+r)}}h.restore()}function G(){e.each(k(),function(e,t){var n=t.box,r=t.direction+"Axis "+t.direction+t.n+"Axis",i="flot-"+t.direction+"-axis flot-"+t.direction+t.n+"-axis "+r,s=t.options.font||"flot-tick-label tickLabel",o,u,a,l,c;f.removeText(i);if(!t.show||t.ticks.length==0)return;for(var h=0;h<t.ticks.length;++h){o=t.ticks[h];if(!o.label||o.v<t.min||o.v>t.max)continue;if(t.direction=="x"){l="center";u=m.left+t.p2c(o.v);if(t.position=="bottom"){a=n.top+n.padding}else{a=n.top+n.height-n.padding;c="bottom"}}else{c="middle";a=m.top+t.p2c(o.v);if(t.position=="left"){u=n.left+n.width-n.padding;l="right"}else{u=n.left+n.padding}}f.addText(i,u,a,o.label,s,null,null,l,c)}})}function Y(e){if(e.lines.show)Z(e);if(e.bars.show)nt(e);if(e.points.show)et(e)}function Z(e){function t(e,t,n,r,i){var s=e.points,o=e.pointsize,u=null,a=null;h.beginPath();for(var f=o;f<s.length;f+=o){var l=s[f-o],c=s[f-o+1],p=s[f],d=s[f+1];if(l==null||p==null)continue;if(c<=d&&c<i.min){if(d<i.min)continue;l=(i.min-c)/(d-c)*(p-l)+l;c=i.min}else if(d<=c&&d<i.min){if(c<i.min)continue;p=(i.min-c)/(d-c)*(p-l)+l;d=i.min}if(c>=d&&c>i.max){if(d>i.max)continue;l=(i.max-c)/(d-c)*(p-l)+l;c=i.max}else if(d>=c&&d>i.max){if(c>i.max)continue;p=(i.max-c)/(d-c)*(p-l)+l;d=i.max}if(l<=p&&l<r.min){if(p<r.min)continue;c=(r.min-l)/(p-l)*(d-c)+c;l=r.min}else if(p<=l&&p<r.min){if(l<r.min)continue;d=(r.min-l)/(p-l)*(d-c)+c;p=r.min}if(l>=p&&l>r.max){if(p>r.max)continue;c=(r.max-l)/(p-l)*(d-c)+c;l=r.max}else if(p>=l&&p>r.max){if(l>r.max)continue;d=(r.max-l)/(p-l)*(d-c)+c;p=r.max}if(l!=u||c!=a)h.moveTo(r.p2c(l)+t,i.p2c(c)+n);u=p;a=d;h.lineTo(r.p2c(p)+t,i.p2c(d)+n)}h.stroke()}function n(e,t,n){var r=e.points,i=e.pointsize,s=Math.min(Math.max(0,n.min),n.max),o=0,u,a=false,f=1,l=0,c=0;while(true){if(i>0&&o>r.length+i)break;o+=i;var p=r[o-i],d=r[o-i+f],v=r[o],m=r[o+f];if(a){if(i>0&&p!=null&&v==null){c=o;i=-i;f=2;continue}if(i<0&&o==l+i){h.fill();a=false;i=-i;f=1;o=l=c+i;continue}}if(p==null||v==null)continue;if(p<=v&&p<t.min){if(v<t.min)continue;d=(t.min-p)/(v-p)*(m-d)+d;p=t.min}else if(v<=p&&v<t.min){if(p<t.min)continue;m=(t.min-p)/(v-p)*(m-d)+d;v=t.min}if(p>=v&&p>t.max){if(v>t.max)continue;d=(t.max-p)/(v-p)*(m-d)+d;p=t.max}else if(v>=p&&v>t.max){if(p>t.max)continue;m=(t.max-p)/(v-p)*(m-d)+d;v=t.max}if(!a){h.beginPath();h.moveTo(t.p2c(p),n.p2c(s));a=true}if(d>=n.max&&m>=n.max){h.lineTo(t.p2c(p),n.p2c(n.max));h.lineTo(t.p2c(v),n.p2c(n.max));continue}else if(d<=n.min&&m<=n.min){h.lineTo(t.p2c(p),n.p2c(n.min));h.lineTo(t.p2c(v),n.p2c(n.min));continue}var g=p,y=v;if(d<=m&&d<n.min&&m>=n.min){p=(n.min-d)/(m-d)*(v-p)+p;d=n.min}else if(m<=d&&m<n.min&&d>=n.min){v=(n.min-d)/(m-d)*(v-p)+p;m=n.min}if(d>=m&&d>n.max&&m<=n.max){p=(n.max-d)/(m-d)*(v-p)+p;d=n.max}else if(m>=d&&m>n.max&&d<=n.max){v=(n.max-d)/(m-d)*(v-p)+p;m=n.max}if(p!=g){h.lineTo(t.p2c(g),n.p2c(d))}h.lineTo(t.p2c(p),n.p2c(d));h.lineTo(t.p2c(v),n.p2c(m));if(v!=y){h.lineTo(t.p2c(v),n.p2c(m));h.lineTo(t.p2c(y),n.p2c(m))}}}h.save();h.translate(m.left,m.top);h.lineJoin="round";var r=e.lines.lineWidth,i=e.shadowSize;if(r>0&&i>0){h.lineWidth=i;h.strokeStyle="rgba(0,0,0,0.1)";var s=Math.PI/18;t(e.datapoints,Math.sin(s)*(r/2+i/2),Math.cos(s)*(r/2+i/2),e.xaxis,e.yaxis);h.lineWidth=i/2;t(e.datapoints,Math.sin(s)*(r/2+i/4),Math.cos(s)*(r/2+i/4),e.xaxis,e.yaxis)}h.lineWidth=r;h.strokeStyle=e.color;var o=rt(e.lines,e.color,0,y);if(o){h.fillStyle=o;n(e.datapoints,e.xaxis,e.yaxis)}if(r>0)t(e.datapoints,0,0,e.xaxis,e.yaxis);h.restore()}function et(e){function t(e,t,n,r,i,s,o,u){var a=e.points,f=e.pointsize;for(var l=0;l<a.length;l+=f){var c=a[l],p=a[l+1];if(c==null||c<s.min||c>s.max||p<o.min||p>o.max)continue;h.beginPath();c=s.p2c(c);p=o.p2c(p)+r;if(u=="circle")h.arc(c,p,t,0,i?Math.PI:Math.PI*2,false);else u(h,c,p,t,i);h.closePath();if(n){h.fillStyle=n;h.fill()}h.stroke()}}h.save();h.translate(m.left,m.top);var n=e.points.lineWidth,r=e.shadowSize,i=e.points.radius,s=e.points.symbol;if(n==0)n=1e-4;if(n>0&&r>0){var o=r/2;h.lineWidth=o;h.strokeStyle="rgba(0,0,0,0.1)";t(e.datapoints,i,null,o+o/2,true,e.xaxis,e.yaxis,s);h.strokeStyle="rgba(0,0,0,0.2)";t(e.datapoints,i,null,o/2,true,e.xaxis,e.yaxis,s)}h.lineWidth=n;h.strokeStyle=e.color;t(e.datapoints,i,rt(e.points,e.color),0,false,e.xaxis,e.yaxis,s);h.restore()}function tt(e,t,n,r,i,s,o,u,a,f,l){var c,h,p,d,v,m,g,y,b;if(f){y=m=g=true;v=false;c=n;h=e;d=t+r;p=t+i;if(h<c){b=h;h=c;c=b;v=true;m=false}}else{v=m=g=true;y=false;c=e+r;h=e+i;p=n;d=t;if(d<p){b=d;d=p;p=b;y=true;g=false}}if(h<o.min||c>o.max||d<u.min||p>u.max)return;if(c<o.min){c=o.min;v=false}if(h>o.max){h=o.max;m=false}if(p<u.min){p=u.min;y=false}if(d>u.max){d=u.max;g=false}c=o.p2c(c);p=u.p2c(p);h=o.p2c(h);d=u.p2c(d);if(s){a.fillStyle=s(p,d);a.fillRect(c,d,h-c,p-d)}if(l>0&&(v||m||g||y)){a.beginPath();a.moveTo(c,p);if(v)a.lineTo(c,d);else a.moveTo(c,d);if(g)a.lineTo(h,d);else a.moveTo(h,d);if(m)a.lineTo(h,p);else a.moveTo(h,p);if(y)a.lineTo(c,p);else a.moveTo(c,p);a.stroke()}}function nt(e){function t(t,n,r,i,s,o){var u=t.points,a=t.pointsize;for(var f=0;f<u.length;f+=a){if(u[f]==null)continue;tt(u[f],u[f+1],u[f+2],n,r,i,s,o,h,e.bars.horizontal,e.bars.lineWidth)}}h.save();h.translate(m.left,m.top);h.lineWidth=e.bars.lineWidth;h.strokeStyle=e.color;var n;switch(e.bars.align){case"left":n=0;break;case"right":n=-e.bars.barWidth;break;default:n=-e.bars.barWidth/2}var r=e.bars.fill?function(t,n){return rt(e.bars,e.color,t,n)}:null;t(e.datapoints,n,n+e.bars.barWidth,r,e.xaxis,e.yaxis);h.restore()}function rt(t,n,r,i){var s=t.fill;if(!s)return null;if(t.fillColor)return bt(t.fillColor,r,i,n);var o=e.color.parse(n);o.a=typeof s=="number"?s:.4;o.normalize();return o.toString()}function it(){if(a.legend.container!=null){e(a.legend.container).html("")}else{t.find(".legend").remove()}if(!a.legend.show){return}var n=[],r=[],i=false,s=a.legend.labelFormatter,o,f;for(var l=0;l<u.length;++l){o=u[l];if(o.label){f=s?s(o.label,o):o.label;if(f){r.push({label:f,color:o.color})}}}if(a.legend.sorted){if(e.isFunction(a.legend.sorted)){r.sort(a.legend.sorted)}else if(a.legend.sorted=="reverse"){r.reverse()}else{var c=a.legend.sorted!="descending";r.sort(function(e,t){return e.label==t.label?0:e.label<t.label!=c?1:-1})}}for(var l=0;l<r.length;++l){var h=r[l];if(l%a.legend.noColumns==0){if(i)n.push("</tr>");n.push("<tr>");i=true}n.push('<td class="legendColorBox"><div style="border:1px solid '+a.legend.labelBoxBorderColor+';padding:1px"><div style="width:4px;height:0;border:5px solid '+h.color+';overflow:hidden"></div></div></td>'+'<td class="legendLabel">'+h.label+"</td>")}if(i)n.push("</tr>");if(n.length==0)return;var p='<table style="font-size:smaller;color:'+a.grid.color+'">'+n.join("")+"</table>";if(a.legend.container!=null)e(a.legend.container).html(p);else{var d="",v=a.legend.position,g=a.legend.margin;if(g[0]==null)g=[g,g];if(v.charAt(0)=="n")d+="top:"+(g[1]+m.top)+"px;";else if(v.charAt(0)=="s")d+="bottom:"+(g[1]+m.bottom)+"px;";if(v.charAt(1)=="e")d+="right:"+(g[0]+m.right)+"px;";else if(v.charAt(1)=="w")d+="left:"+(g[0]+m.left)+"px;";var y=e('<div class="legend">'+p.replace('style="','style="position:absolute;'+d+";")+"</div>").appendTo(t);if(a.legend.backgroundOpacity!=0){var b=a.legend.backgroundColor;if(b==null){b=a.grid.backgroundColor;if(b&&typeof b=="string")b=e.color.parse(b);else b=e.color.extract(y,"background-color");b.a=1;b=b.toString()}var w=y.children();e('<div style="position:absolute;width:'+w.width()+"px;height:"+w.height()+"px;"+d+"background-color:"+b+';"> </div>').prependTo(y).css("opacity",a.legend.backgroundOpacity)}}}function ut(e,t,n){var r=a.grid.mouseActiveRadius,i=r*r+1,s=null,o=false,f,l,c;for(f=u.length-1;f>=0;--f){if(!n(u[f]))continue;var h=u[f],p=h.xaxis,d=h.yaxis,v=h.datapoints.points,m=p.c2p(e),g=d.c2p(t),y=r/p.scale,b=r/d.scale;c=h.datapoints.pointsize;if(p.options.inverseTransform)y=Number.MAX_VALUE;if(d.options.inverseTransform)b=Number.MAX_VALUE;if(h.lines.show||h.points.show){for(l=0;l<v.length;l+=c){var w=v[l],E=v[l+1];if(w==null)continue;if(w-m>y||w-m<-y||E-g>b||E-g<-b)continue;var S=Math.abs(p.p2c(w)-e),x=Math.abs(d.p2c(E)-t),T=S*S+x*x;if(T<i){i=T;s=[f,l/c]}}}if(h.bars.show&&!s){var N,C;switch(h.bars.align){case"left":N=0;break;case"right":N=-h.bars.barWidth;break;default:N=-h.bars.barWidth/2}C=N+h.bars.barWidth;for(l=0;l<v.length;l+=c){var w=v[l],E=v[l+1],k=v[l+2];if(w==null)continue;if(u[f].bars.horizontal?m<=Math.max(k,w)&&m>=Math.min(k,w)&&g>=E+N&&g<=E+C:m>=w+N&&m<=w+C&&g>=Math.min(k,E)&&g<=Math.max(k,E))s=[f,l/c]}}}if(s){f=s[0];l=s[1];c=u[f].datapoints.pointsize;return{datapoint:u[f].datapoints.points.slice(l*c,(l+1)*c),dataIndex:l,series:u[f],seriesIndex:f}}return null}function at(e){if(a.grid.hoverable)ct("plothover",e,function(e){return e["hoverable"]!=false})}function ft(e){if(a.grid.hoverable)ct("plothover",e,function(e){return false})}function lt(e){ct("plotclick",e,function(e){return e["clickable"]!=false})}function ct(e,n,r){var i=c.offset(),s=n.pageX-i.left-m.left,o=n.pageY-i.top-m.top,u=L({left:s,top:o});u.pageX=n.pageX;u.pageY=n.pageY;var f=ut(s,o,r);if(f){f.pageX=parseInt(f.series.xaxis.p2c(f.datapoint[0])+i.left+m.left,10);f.pageY=parseInt(f.series.yaxis.p2c(f.datapoint[1])+i.top+m.top,10)}if(a.grid.autoHighlight){for(var l=0;l<st.length;++l){var h=st[l];if(h.auto==e&&!(f&&h.series==f.series&&h.point[0]==f.datapoint[0]&&h.point[1]==f.datapoint[1]))vt(h.series,h.point)}if(f)dt(f.series,f.datapoint,e)}t.trigger(e,[u,f])}function ht(){var e=a.interaction.redrawOverlayInterval;if(e==-1){pt();return}if(!ot)ot=setTimeout(pt,e)}function pt(){ot=null;p.save();l.clear();p.translate(m.left,m.top);var e,t;for(e=0;e<st.length;++e){t=st[e];if(t.series.bars.show)yt(t.series,t.point);else gt(t.series,t.point)}p.restore();E(b.drawOverlay,[p])}function dt(e,t,n){if(typeof e=="number")e=u[e];if(typeof t=="number"){var r=e.datapoints.pointsize;t=e.datapoints.points.slice(r*t,r*(t+1))}var i=mt(e,t);if(i==-1){st.push({series:e,point:t,auto:n});ht()}else if(!n)st[i].auto=false}function vt(e,t){if(e==null&&t==null){st=[];ht();return}if(typeof e=="number")e=u[e];if(typeof t=="number"){var n=e.datapoints.pointsize;t=e.datapoints.points.slice(n*t,n*(t+1))}var r=mt(e,t);if(r!=-1){st.splice(r,1);ht()}}function mt(e,t){for(var n=0;n<st.length;++n){var r=st[n];if(r.series==e&&r.point[0]==t[0]&&r.point[1]==t[1])return n}return-1}function gt(t,n){var r=n[0],i=n[1],s=t.xaxis,o=t.yaxis,u=typeof t.highlightColor==="string"?t.highlightColor:e.color.parse(t.color).scale("a",.5).toString();if(r<s.min||r>s.max||i<o.min||i>o.max)return;var a=t.points.radius+t.points.lineWidth/2;p.lineWidth=a;p.strokeStyle=u;var f=1.5*a;r=s.p2c(r);i=o.p2c(i);p.beginPath();if(t.points.symbol=="circle")p.arc(r,i,f,0,2*Math.PI,false);else t.points.symbol(p,r,i,f,false);p.closePath();p.stroke()}function yt(t,n){var r=typeof t.highlightColor==="string"?t.highlightColor:e.color.parse(t.color).scale("a",.5).toString(),i=r,s;switch(t.bars.align){case"left":s=0;break;case"right":s=-t.bars.barWidth;break;default:s=-t.bars.barWidth/2}p.lineWidth=t.bars.lineWidth;p.strokeStyle=r;tt(n[0],n[1],n[2]||0,s,s+t.bars.barWidth,function(){return i},t.xaxis,t.yaxis,p,t.bars.horizontal,t.bars.lineWidth)}function bt(t,n,r,i){if(typeof t=="string")return t;else{var s=h.createLinearGradient(0,r,0,n);for(var o=0,u=t.colors.length;o<u;++o){var a=t.colors[o];if(typeof a!="string"){var f=e.color.parse(i);if(a.brightness!=null)f=f.scale("rgb",a.brightness);if(a.opacity!=null)f.a*=a.opacity;a=f.toString()}s.addColorStop(o/(u-1),a)}return s}}var u=[],a={colors:["#edc240","#afd8f8","#cb4b4b","#4da74d","#9440ed"],legend:{show:true,noColumns:1,labelFormatter:null,labelBoxBorderColor:"#ccc",container:null,position:"ne",margin:5,backgroundColor:null,backgroundOpacity:.85,sorted:null},xaxis:{show:null,position:"bottom",mode:null,font:null,color:null,tickColor:null,transform:null,inverseTransform:null,min:null,max:null,autoscaleMargin:null,ticks:null,tickFormatter:null,labelWidth:null,labelHeight:null,reserveSpace:null,tickLength:null,alignTicksWithAxis:null,tickDecimals:null,tickSize:null,minTickSize:null},yaxis:{autoscaleMargin:.02,position:"left"},xaxes:[],yaxes:[],series:{points:{show:false,radius:3,lineWidth:2,fill:true,fillColor:"#ffffff",symbol:"circle"},lines:{lineWidth:2,fill:false,fillColor:null,steps:false},bars:{show:false,lineWidth:2,barWidth:1,fill:true,fillColor:null,align:"left",horizontal:false,zero:true},shadowSize:3,highlightColor:null},grid:{show:true,aboveData:false,color:"#545454",backgroundColor:null,borderColor:null,tickColor:null,margin:0,labelMargin:5,axisMargin:8,borderWidth:2,minBorderMargin:null,markings:null,markingsColor:"#f4f4f4",markingsLineWidth:2,clickable:false,hoverable:false,autoHighlight:true,mouseActiveRadius:10},interaction:{redrawOverlayInterval:1e3/60},hooks:{}},f=null,l=null,c=null,h=null,p=null,d=[],v=[],m={left:0,right:0,top:0,bottom:0},g=0,y=0,b={processOptions:[],processRawData:[],processDatapoints:[],processOffset:[],drawBackground:[],drawSeries:[],draw:[],bindEvents:[],drawOverlay:[],shutdown:[]},w=this;w.setData=T;w.setupGrid=R;w.draw=V;w.getPlaceholder=function(){return t};w.getCanvas=function(){return f.element};w.getPlotOffset=function(){return m};w.width=function(){return g};w.height=function(){return y};w.offset=function(){var e=c.offset();e.left+=m.left;e.top+=m.top;return e};w.getData=function(){return u};w.getAxes=function(){var t={},n;e.each(d.concat(v),function(e,n){if(n)t[n.direction+(n.n!=1?n.n:"")+"axis"]=n});return t};w.getXAxes=function(){return d};w.getYAxes=function(){return v};w.c2p=L;w.p2c=A;w.getOptions=function(){return a};w.highlight=dt;w.unhighlight=vt;w.triggerRedrawOverlay=ht;w.pointOffset=function(e){return{left:parseInt(d[C(e,"x")-1].p2c(+e.x)+m.left,10),top:parseInt(v[C(e,"y")-1].p2c(+e.y)+m.top,10)}};w.shutdown=H;w.destroy=function(){H();t.removeData("plot").empty();u=[];a=null;f=null;l=null;c=null;h=null;p=null;d=[];v=[];b=null;st=[];w=null};w.resize=function(){var e=t.width(),n=t.height();f.resize(e,n);l.resize(e,n)};w.hooks=b;S(w);x(s);D();T(r);R();V();P();var st=[],ot=null}function i(e,t){return t*Math.floor(e/t)}var t=Object.prototype.hasOwnProperty;n.prototype.resize=function(e,t){if(e<=0||t<=0){throw new Error("Invalid dimensions for plot, width = "+e+", height = "+t)}var n=this.element,r=this.context,i=this.pixelRatio;if(this.width!=e){n.width=e*i;n.style.width=e+"px";this.width=e}if(this.height!=t){n.height=t*i;n.style.height=t+"px";this.height=t}r.restore();r.save();r.scale(i,i)};n.prototype.clear=function(){this.context.clearRect(0,0,this.width,this.height)};n.prototype.render=function(){var e=this._textCache;for(var n in e){if(t.call(e,n)){var r=this.getTextLayer(n),i=e[n];r.hide();for(var s in i){if(t.call(i,s)){var o=i[s];for(var u in o){if(t.call(o,u)){var a=o[u].positions;for(var f=0,l;l=a[f];f++){if(l.active){if(!l.rendered){r.append(l.element);l.rendered=true}}else{a.splice(f--,1);if(l.rendered){l.element.detach()}}}if(a.length==0){delete o[u]}}}}}r.show()}}};n.prototype.getTextLayer=function(t){var n=this.text[t];if(n==null){if(this.textContainer==null){this.textContainer=e("<div class='flot-text'></div>").css({position:"absolute",top:0,left:0,bottom:0,right:0,"font-size":"smaller",color:"#545454"}).insertAfter(this.element)}n=this.text[t]=e("<div></div>").addClass(t).css({position:"absolute",top:0,left:0,bottom:0,right:0}).appendTo(this.textContainer)}return n};n.prototype.getTextInfo=function(t,n,r,i,s){var o,u,a,f;n=""+n;if(typeof r==="object"){o=r.style+" "+r.variant+" "+r.weight+" "+r.size+"px/"+r.lineHeight+"px "+r.family}else{o=r}u=this._textCache[t];if(u==null){u=this._textCache[t]={}}a=u[o];if(a==null){a=u[o]={}}f=a[n];if(f==null){var l=e("<div></div>").html(n).css({position:"absolute","max-width":s,top:-9999}).appendTo(this.getTextLayer(t));if(typeof r==="object"){l.css({font:o,color:r.color})}else if(typeof r==="string"){l.addClass(r)}f=a[n]={width:l.outerWidth(true),height:l.outerHeight(true),element:l,positions:[]};l.detach()}return f};n.prototype.addText=function(e,t,n,r,i,s,o,u,a){var f=this.getTextInfo(e,r,i,s,o),l=f.positions;if(u=="center"){t-=f.width/2}else if(u=="right"){t-=f.width}if(a=="middle"){n-=f.height/2}else if(a=="bottom"){n-=f.height}for(var c=0,h;h=l[c];c++){if(h.x==t&&h.y==n){h.active=true;return}}h={active:true,rendered:false,element:l.length?f.element.clone():f.element,x:t,y:n};l.push(h);h.element.css({top:Math.round(n),left:Math.round(t),"text-align":u})};n.prototype.removeText=function(e,n,r,i,s,o){if(i==null){var u=this._textCache[e];if(u!=null){for(var a in u){if(t.call(u,a)){var f=u[a];for(var l in f){if(t.call(f,l)){var c=f[l].positions;for(var h=0,p;p=c[h];h++){p.active=false}}}}}}}else{var c=this.getTextInfo(e,i,s,o).positions;for(var h=0,p;p=c[h];h++){if(p.x==n&&p.y==r){p.active=false}}}};e.plot=function(t,n,i){var s=new r(e(t),n,i,e.plot.plugins);return s};e.plot.version="0.8.2";e.plot.plugins=[];e.fn.plot=function(t,n){return this.each(function(){e.plot(this,t,n)})}})(jQuery)
            var d = [[12, 12], [12, 12.3]];
            iceplot(d);
            //$.(".ui-loader h1").remove();
            var socket = io.connect('http://localhost:3000');
            socket.on('connect', function() {
                socket.on('pageview', function(msg) {
                    var json = JSON.parse(msg.url);
//                    alert(msg.url);
                     d.push([json.lat,json.lon]);
                     latGlobal = json.lat;
                     lonGlobal = json.lon;
                     codeLatLng();
                     
                    iceplot(d);
                });
            });
        </script>
		<script>
                $(document).ready(function() {
                    var deviceid = $("#deviceid").html();
                    var r = confirm("Create new session?!");
                    if (r == true){
                        var address = 'http://localhost/nasa1/newsession.php';
                         $.get(address, { device_id:deviceid });
                    }
                });
		</script>
    </body>
</html>