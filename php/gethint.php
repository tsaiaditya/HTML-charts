<!--Display info about selected slice-->
<?php
  $type = $_REQUEST["status"];
  $db = pg_connect("host = 127.0.0.1 port = 5432 dbname = postgres user = postgres password = password");
  $sql = "select * from projects where type = '".$type."'";
  $ret = pg_query($db,$sql);
  while($row = pg_fetch_row($ret)){
    echo "<table style = 'border: 1px solid black'>
    <tr>
      <th style = 'border:1px solid black'>TYPE</th>
      <th style = 'border:1px solid black'>VALUE</th>
      <th style = 'border:1px solid black'>AMOUNT</th>
    </tr>
    <tr>
      <td style = 'border:1px solid black'>".$row[1]."</td>
      <td style = 'border:1px solid black'>".$row[2]."</td>
      <td style = 'border:1px solid black'>".$row[3]."</td>
    </tr>
    </table>";
  }
?>