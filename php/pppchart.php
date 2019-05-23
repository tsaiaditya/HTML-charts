<?php
$db = pg_connect("host = 127.0.0.1 port = 5432 dbname = postgres user = postgres password = password");
$sql = "select state,count(state) from ppp where mode like 'bot%' group by state";
$result = pg_query($db,$sql);
$datapoints = array();
while($row = pg_fetch_row($result))
{
    $data = array('state'=>$row[0],'bot-count'=>$row[1]);
    array_push($datapoints,$data);
}
$sql = "select state,count(state) from ppp where mode = 'ham' group by state";
$result = pg_query($db,$sql);
while($row = pg_fetch_row($result))
{
    for($i=0;$i<count($datapoints);$i++)
    {
        if($datapoints[$i]['state'] == $row[0])
            $datapoints[$i]['ham-count'] = $row[1];
    }
}
?>
<style>
body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
  font-size: 9pt;
}

#chartdiv {
  width: 100%;
  height: 400px;
}
</style>
<script src="//www.amcharts.com/lib/4/core.js"></script>
<script src="//www.amcharts.com/lib/4/charts.js"></script>
<script src="//www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="//www.amcharts.com/lib/4/themes/kelly.js"></script>
<script>
    am4core.ready(function() {

    am4core.useTheme(am4themes_animated);
    am4core.useTheme(am4themes_kelly);

// Create chart instance
    var chart = am4core.create("chartdiv", am4charts.XYChart3D);
    chart.data = <?php echo json_encode($datapoints, JSON_NUMERIC_CHECK); ?>;
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "state";
    categoryAxis.title.text = "States";

    var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Count";

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries3D());
    series.dataFields.valueY = "bot-count";
    series.dataFields.categoryX = "state";
    series.name = "Bot-Count";
    series.tooltipText = "{name}: [bold]{valueY}[/]";

    var series2 = chart.series.push(new am4charts.ColumnSeries3D());
    series2.dataFields.valueY = "ham-count";
    series2.dataFields.categoryX = "state";
    series2.name = "Ham-Count";
    series2.tooltipText = "{name}: [bold]{valueY}[/]";

    // Add cursor
    chart.cursor = new am4charts.XYCursor();
});
</script>

<div id="chartdiv"></div>