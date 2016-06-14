<!DOCTYPE html>
<html>
<head>
	<title>Guanyu's garden</title>
	<script language="javascript" type="text/javascript" src="jquery.js"></script>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<script>
	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length,c.length);
	        }
	    }
	    return "";
	}
	</script>
	
	<style type="text/css">
	body {
	  /*background: linear-gradient(90deg, white, gray);*/
	  background-color: #C6D7F2;
	}
	
	body, h1, p {
	  font-family: "Helvetica Neue", "Segoe UI", Segoe, Helvetica, Arial, "Lucida Grande", sans-serif;
	  font-weight: normal;
	  margin: 0;
	  padding: 0;
	  text-align: center;
	}
	  
	.container {
	  margin-left:  auto;
	  margin-right:  auto;
	  margin-top: 177px;
	  max-width: 1170px;
	  padding-right: 15px;
	  padding-left: 15px;
	}
	
	.row:before, .row:after {
	  display: table;
	  content: " ";
	}
	
	h1 {
	  font-size: 48px;
	  font-weight: 300;
	  margin: 0 0 20px 0;
	}
	
	.lead {
	  font-size: 21px;
	  font-weight: 200;
	  margin-bottom: 20px;
	}
	
	p {
	  color: black;
	  margin: 0 0 10px;
	}
	
	table {
	  margin: 0 auto;

	}
	
	a {
	  color: #3282e6;
	  text-decoration: none;
	}
	</style>
</head>

<body>

<p id = "intro"></p>
<p id = "intro2"></p>
<table id = "enemyTable" style="width:30%">
<tbody>
</tbody>
</table>

<br>
<p>Your Skills:</p>
<table id = "skillTable" style="width:30%">
<tbody>
</tbody>
</table>
<p id = "forTest"><p>

<script>
var char = getCookie("charName");
var lv = getCookie("level");
var charProf = getCookie("charProf");

document.getElementById("intro").innerHTML = "You are " + char + " the " + charProf + " and your lv is " + lv + "<br>";
document.getElementById("intro2").innerHTML = "Enemy found!!";

document.cookie = "charName=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "level=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charProf=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

var enemyInfo;
var yourSkills;

function getEnemy(callback){

		$.ajax({                                      
		      url: 'fetchEnemy.php',                  //the script to call to get data       
		      data: {charName:char,charLv:lv},                        //you can insert url argumnets here to pass to api.php
		      async: false,                                 //for example "id=5&parent=6"
		      dataType: 'json',                //data format      
		      success: function(data)          //on recieve of reply
		      {	        
			$('#enemyTable > tbody').html("<tr><td>Enemy</td><td>hp</td><td>mp</td><td>lvl</td><td>profession</td></tr><tr><td>" +data[0] + "</td><td>" + data[1] + "</td><td>" + data[2] + "</td><td>" + data[3] + "</td><td>" + data[4] + "</td></tr>");
			callback(data);
		      }
        	});
}

function useSkill(skill){
	document.getElementById("forTest").innerHTML = "The skill your are using: " + skill;
}

function availableSkills(callback){
	$.ajax({                                      
		      url: 'fetchSkills.php',                  //the script to call to get data       
		      data: {prof:charProf,lv:lv},                        //you can insert url argumnets here to pass to api.php
		      async: false,                                //for example "id=5&parent=6"
		      dataType: 'json',                //data format      
		      success: function(data)          //on recieve of reply
		      {	     		
			callback(data);
		      }
        	});
}




getEnemy(function(returnedData){ //anonymous callback function
    enemyInfo = returnedData;
 });

var enemyName = enemyInfo[0];
var enemyHp = enemyInfo[1];
var enemyMp = enemyInfo[2];
var enemyLv = enemyInfo[3];
var enemyProf = enemyInfo[4];
var enemyWeapon = enemyInfo[5];



// Amazing battle part
var battleEnd = true;
var playerTurn = 1;

availableSkills(function(returnedData){ //anonymous callback function
    yourSkills = returnedData;
});



var numOfSkills = yourSkills.length/4;
$('#skillTable > tbody').append("<tr><td>Name</td><td>Damage</td><td>Mana cost</td><td>Type</td></tr>");
for(var i=0;i<numOfSkills;i++){
	var word = "<tr><td><button input type='button' onclick = 'useSkill(yourSkills[4*" + i + "])'>" +yourSkills[4*i] + "</button></td><td>" + yourSkills[4*i+1] + "</td><td>" + yourSkills[4*i+2] + "</td><td>" + yourSkills[4*i+3] + "</td></tr>";
	$('#skillTable > tbody').append(word);
}



while(battleEnd == false){
	if(playerTurn == 1){
		
	
	
	}else{
	
	
	}
}


</script>


<br>
<button input type = "button" onclick = "location = 'login.html'">You must click this to go back</button>
<button input type = "button" onclick = "getEnemy()">generate again</button>
</body>
</html>