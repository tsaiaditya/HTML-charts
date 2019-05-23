<?php
$db = pg_connect("host = 127.0.0.1 port = 5432 dbname = postgres user = postgres password = password");
$sql = "select * from projects";
$ret = pg_query($db,$sql);
$dataPoints = array();
while($row = pg_fetch_row($ret)){
    // echo "Serial no. : ".$row[0]." Type : ".$row[1]." Value: ".$row[2]." Amount: ".$row[3]."<br>";
    $data = array("label"=> $row[1],"y"=>$row[2],"amt"=>$row[3]);
    array_push($dataPoints,$data);
}
$sql = "select sum(value) from projects";
$ret = pg_query($db,$sql);
// $sum = "";
while($row = pg_fetch_row($ret)){
    $sum = $row[0];
}
// var_dump($sum);
pg_close($db);
?>
 <!DOCTYPE HTML>
 <html>
 <head>  
 <script>
 window.onload = function () {
  
 var chart = new CanvasJS.Chart("chartContainer", {
     animationEnabled: true,
     //exportEnabled: true,
     title:{
         text: "Projects status"
     },
     subtitles: [{
         text: "Total projects : <?php echo $sum; ?>"
     }],
     data: [{
         type: "pie",
         showInLegend: "true",
         legendText: "{label} - {y}",
         indexLabelFontSize: 16,
         indexLabel: "{label} - #percent%",
         yValueFormatString: "#,##0",
         dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        //  click: function(e){
        //      alert(e.dataPoint.label);
        //  }
     }]
 });
 chart.render();
 }
 </script>
 </head>
 <body>
 <div id="chartContainer" style="height: 370px; width: 100%;"></div>
 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
 </body>
 </html>