<?php
$conn = mysql_connect('localhost','guanyuchen123','cgy123824');

mysql_select_db('gameguanyu',$conn);

$user_prof = $_GET['prof'];
$user_prof = mysql_real_escape_string($user_prof);

$user_lvl = $_GET['lv'];
$user_lvl = mysql_real_escape_string($user_lvl);

$query = "SELECT name,damage,mana_cost,type FROM Skill WHERE lvl_req <= '$user_lvl' and profession = '$user_prof'";
$query = mysql_query($query);

$rows = Array();
while($row = mysql_fetch_row($query)){
  array_push($rows, $row[0]);
  array_push($rows, $row[1]);
  array_push($rows, $row[2]);
  array_push($rows, $row[3]);
}
echo json_encode($rows);


?>