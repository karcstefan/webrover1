<?php
error_reporting(E_ALL ^ E_NOTICE);
$file=file_get_contents("output.txt", FILE_USE_INCLUDE_PATH);
$content = explode("\n", $file);
$vertices = array();
foreach($content as $singleline)
{	
	$part = explode(" ", $singleline);
	$vertices[$part[0]] = $part[1];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Draw</title>
<!-- 	<link href="../examples.css" rel="stylesheet" type="text/css">-->
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery.flot.js"></script>
	<script type="text/javascript">

	$(function() {

		// generate data set from a parametric function with a fractal look
		var d=[];
		<?php 
		foreach($vertices as $part1 => $part2)
		{
			echo 'd.push(['.$part1.', '.$part2.']);';	
		};
		?>
		console.log("length: " + d.length);
		var data = [d],	
			placeholder = $("#placeholder");
			$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"font-size": "20px",
			"background-color": "#E7FFFF",
			opacity: 0.80
		}).appendTo("body");
		
		var plot = $.plot("#placeholder", [
			{ data: d, label: "Vertices"}
		], {
			series: {
				lines: {
					show: true
				},
				points: {
					show: true
				},
				shadowSize: 0
			},
			grid: {
				hoverable: true,
				clickable: true
			}
		});
		
		$("#placeholder").bind("plothover", function (event, pos, item) {

				var str = "(" + pos.x.toFixed(6) + ", " + pos.y.toFixed(6) + ")";
				$("#hoverdata").text(str);

				if (item) {
					var x = item.datapoint[0].toFixed(6),
						y = item.datapoint[1].toFixed(6);

					$("#tooltip").html("Vertex "+ item.dataIndex + "[" + x + "," + y+"]")
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} else {
					$("#tooltip").hide();
				}
		});
	});

	</script>
</head>
<body>
		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder" style="height: 800px; width: 800px;"></div>
		</div>
</html>