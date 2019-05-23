<?php
$db = pg_connect("host = 127.0.0.1 port = 5432 dbname = postgres user = postgres password = password");
$sql = "select * from projects";
$ret = pg_query($db,$sql);
$dataPoints = array();
while($row = pg_fetch_row($ret)){
    // echo "Serial no. : ".$row[0]." Type : ".$row[1]." Value: ".$row[2]." Amount: ".$row[3]."<br>";
    $data = array("label"=> $row[1],"value"=>$row[2],"amt"=>$row[3]);
    array_push($dataPoints,$data);
}
$dataPoints[0]["color"] = "orange";
$dataPoints[1]["color"] = "red";
$dataPoints[2]["color"] = "blue";
$dataPoints[3]["color"] = "green";
$sql = "select sum(value) from projects";
$ret = pg_query($db,$sql);
// $sum = "";
while($row = pg_fetch_row($ret)){
    $sum = $row[0];
}
// var_dump($sum);
pg_close($db);
?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>
body {
  font-family: 'Times New Roman', Times, serif  ;
  position: relative;
}
path.slice{
	stroke-width:2px;
}

svg text.percent{
	fill:white;
	text-anchor:middle;
	font-size:12px;
}

div.tooltip {	
    position: absolute;			
    text-align: center;			
    width: 130px;					
    height: 28px;					
    padding: 2px;				
    font: 12px sans-serif;		
    background: lightsteelblue;	
    border: 0px;		
    border-radius: 8px;			
    pointer-events: none;			
}

</style>
<body>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="../js/doughnutd3.js"></script>
<script>

var data = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;

var svg = d3.select("body").append("svg").attr("width",900).attr("height",700);

svg.append("g").attr("id","Donut").style("opacity","0");
svg.append("g").attr("id","pie").style("opacity","0");
svg.selectAll('g').transition().duration(500).delay(300).style("opacity","1");

Donut3D.draw("Donut", data, 150, 150, 130, 100, 30, 0.4);
Donut3D.draw("pie", data, 450, 150, 130, 100, 30, 0);

//Legend    
var legend = svg.selectAll('.legend')
      .data(data.map(function(d) { return d.label; }).reverse())
  .enter().append("g")
      .attr("class", "legend")
      .attr("transform", function(d,i) { return "translate(0," + i * 20 + ")"; })
      .style("opacity","0");

legend.append("rect")
      .attr("x", 700)
      .attr("width", 18)
      .attr("height", 18)
      .style("fill", function(d) { 
        for(let i in data)
        {
            if(data[i].label == d)
                return data[i].color;
        }
      });
      
legend.append("text")
      .attr("x", 700-6)
      .attr("y", 9)
      .attr("dy", ".35em")
      .style("text-anchor", "end")
      .text(function(d) {return d; });

legend.transition().duration(500).delay(function(d,i){ return 1300 + 100 * i; }).style("opacity","1");

</script>
</body>