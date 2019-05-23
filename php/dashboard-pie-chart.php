<!-- PHP code to connect to database and extract data -->
<?php
$db = pg_connect("host = 127.0.0.1 port = 5432 dbname = postgres user = postgres password = password");
$sql = "select * from projects";
$ret = pg_query($db,$sql);
$dataPoints = array();
while($row = pg_fetch_row($ret)){
    $data = array("label"=> $row[1],"y"=>$row[2],"amt"=>$row[3]);
    array_push($dataPoints,$data);
}
$sql = "select sum(value) from projects";
$ret = pg_query($db,$sql);
while($row = pg_fetch_row($ret)){
    $sum = $row[0];
}
pg_close($db);
?>
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
    function captitalize(t){
        return t.charAt(0).toUpperCase() + t.slice(1);
    }
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

var chart = am4core.create("chartdiv", am4charts.PieChart3D);
chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

chart.legend = new am4charts.Legend();
//chart.innerRadius = am4core.percent(40);

chart.data = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
var series = chart.series.push(new am4charts.PieSeries3D());
series.labels.template.text = "{category}: {value.value}";
series.dataFields.value = "y";
series.dataFields.category = "label";

// Appearance of the chart
series.slices.template.strokeWidth = 2;
series.slices.template.strokeOpacity = 1;
series.slices.template.stroke = am4core.color("#4a2abb");
series.interpolationDuration = 5000;
series.sequencedInterpolation = true;
series.slices.template.states.getKey("active").properties.shiftRadius = 0;

// Onclicking the specified slice of pie, what to be done
series.slices.template.events.on("hit",function(e){
    var type = e.target.dataItem.sprites[1].currentText;
    type = type.split(':')[0];
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("info").innerHTML = this.responseText;
                document.getElementById("status").innerHTML = captitalize(type);
            }
        };
    xmlhttp.open("GET", "gethint.php?status=" + type, true);
    xmlhttp.send();
},this);
series.slices.template.tooltipPosition = "pointer";
}); // end am4core.ready()

</script>

<!-- HTML -->
<center>
<h1>Project Status</h1>
<h3>Total number of projects : <?php echo $sum; ?><h3>
</center>
<div id="chartdiv"></div><br>
<h2 id = "status"></h2>
<div id="info"></div>
