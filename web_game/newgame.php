<!DOCTYPE html>
<html>
<head>
	<title>Guanyu's garden</title>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="cookieFunctions.js"></script>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="basic.css" rel="stylesheet"/>
	
	<?php
	session_start();
	$var_value = $_SESSION["regName"];
	session_unset();
	?>
	
	<script>
	var userId = "<?php echo $var_value ?>";
	var availableWeapons;
	var availableArmors;
	
	if(userId == ""){
		var temp = getCookie("charId");
		document.cookie = "charId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
		userId = temp;
	}
	
	function fetchInfo()
	{	
		$.ajax({                                      
		      url: 'phpAjax/fetchInfo.php',                  //the script to call to get data       
		      data: {id:userId},                        //you can insert url argumnets here to pass to api.php
		                                       //for example "id=5&parent=6"
		      dataType: 'json',                //data format      
		      success: function(data)          //on recieve of reply
		      {
		        
		        cName = data[0];              //get id
        		user_id = data[1]; 
        		hp = data[2]; 
        		maxHp = data[3];
        		mp = data[4]; 
        		maxMp = data[5];
        		defence = data[6];
        		exp = data[7]; 
        		lvl = data[8]; 
        		profession = data[9]; 
        		weapon = data[10]; 
        		armor = data[11]; 
        		money = data[12];
			setCookie("charName",cName);
			setCookie("charId",user_id);
			setCookie("charHp",hp);
			setCookie("charMhp",maxHp);
			setCookie("charMp",mp);
			setCookie("charMmp",maxMp);
			setCookie("charDef",defence);
			setCookie("charHp",hp);
			setCookie("charExp",exp);
			setCookie("level",lvl);
			setCookie("charProf",profession);
			setCookie("charW",weapon);
			setCookie("charA",armor);
			setCookie("charM",money);
			
			
			$('#info').html("<b>name: </b>"+cName+"<b> user_id: </b>"+user_id+"<b> hp: </b>"+hp+"/"+maxHp+"<b> mp: </b>"+mp+"/"+maxMp+"<b> basic defence: </b>"+defence+"<br><b> exp: </b>"+exp+"<b> lvl: </b>"+lvl+"<b> profession: </b>"+profession+"<b> weapon: </b><b id = 'weap'>" + weapon +"</b><b> armor: </b><b id = 'armo'>" + armor + "</b><b> money: </b>" + money);
			}
		});
		
	}
	
	function fetchSkills()
	{	
		if($('#eventTable tr > td:contains("Mana cost")').length > 0){
			$("#eventTable tr").remove();
			$('#showSkill').html("Show your skills");
		}else{	
			if($('#eventTable tr > td:contains("Accuracy")').length > 0){
				$("#eventTable tr").remove();
				$('#showWeapon').html("Show your weapons");
			}else if($('#eventTable tr > td:contains("Defence")').length > 0){
				$("#eventTable tr").remove();
				$('#showArmor').html("Show your armors");
			}	
			$.ajax({                                      
			      url: 'phpAjax/fetchSkills.php',                  //the script to call to get data       
			      data: {prof:profession,lv:lvl},                        //you can insert url argumnets here to pass to api.php
			                                       //for example "id=5&parent=6"
			      dataType: 'json',                //data format      
			      success: function(data)          //on recieve of reply
			      {
			        
			        var result = data;
				var length = result.length/4;
					$('#eventTable > tbody').append("<tr><td>Name</td><td>Damage</td><td>Mana cost</td><td>Type</td></tr>");
					for(var i=0;i<length;i++){
						$('#eventTable > tbody').append("<tr><td>" +result[4*i] + "</td><td>" + result[4*i+1] + "</td><td>" + result[4*i+2] + "</td><td>" + result[4*i+3] + "</td></tr>");
						
					}
				}
			});
			
			$('#showSkill').html("Hide your skills");
		} 
	}
	
	function switchW(targetW){
		$.ajax({                                      
		      url: 'phpAjax/switchW.php',                  //the script to call to get data      
		      data: {'char':cName,'weapon':targetW}, 
		      success: function(data)          //on recieve of reply
		      {	     
		      	alert("Your weapon has been changed to " + targetW + " !!");
		      	document.getElementById("weap").innerHTML = targetW;
		      	weapon = targetW;
		      	setCookie("charW",weapon);
		      	$("#eventTable tr").remove();
		      	changeWeapon(function(returnedData){availableWeapons = returnedData;});
		      }
		});
		
	}
	
	function switchA(targetA){
		$.ajax({                                      
		      url: 'phpAjax/switchA.php',                  //the script to call to get data      
		      data: {'char':cName,'armor':targetA}, 
		      success: function(data)          //on recieve of reply
		      {	     
		      	alert("Your armor has been changed to " + targetA + " !!");
		      	document.getElementById("armo").innerHTML = targetA;
		      	armor = targetA;
		      	setCookie("charA",armor);
		      	$("#eventTable tr").remove();
		      	changeArmor(function(returnedData){availableArmors = returnedData;});
		      }
		});
	}
	
	function changeWeapon(callback){
		if($('#eventTable tr > td:contains("Accuracy")').length > 0){
			$("#eventTable tr").remove();
			$('#showWeapon').html("Show your weapons");
		}else{	
			if($('#eventTable tr > td:contains("Mana cost")').length > 0){
				$("#eventTable tr").remove();
				$('#showSkill').html("Show your skills");
			}else if($('#eventTable tr > td:contains("Defence")').length > 0){
				$("#eventTable tr").remove();
				$('#showArmor').html("Show your armors");
			}		
			$.ajax({                                      
			      url: 'phpAjax/fetchWeaponsFromItem.php',                  //the script to call to get data       
			      data: {cN:cName},                        //you can insert url argumnets here to pass to api.php
			                                       //for example "id=5&parent=6"
			      dataType: 'json',                //data format      
			      success: function(data)          //on recieve of reply
			      {
			        callback(data);
			        var result = data;
				var length = result.length/4;
					$('#eventTable > tbody').append("<tr><td>Name</td><td>Damage</td><td>Accuracy</td><td>Level Required</td><td>Equip</td></tr>");
					for(var i=0;i<length;i++){
						if(availableWeapons[4*i] == weapon){
							$('#eventTable > tbody').append("<tr><td>" +result[4*i] + "</td><td>" + result[4*i+1] + "</td><td>" + result[4*i+2] + "</td>Equiped<td>" + result[4*i+3] + "</td><td>E</td></tr>");
						} else{
							$('#eventTable > tbody').append("<tr><td><button input type='button' onclick = 'switchW(availableWeapons[4*" + i + "])'>" +result[4*i] + "</button></td><td>" + result[4*i+1] + "</td><td>" + result[4*i+2] + "</td><td>" + result[4*i+3] + "</td><td></td></tr>");
						}
						
						
					}
				}
			});
			
			$('#showWeapon').html("Hide your weapons");
		} 
	}
	
	function changeArmor(callback){
		if($('#eventTable tr > td:contains("Defence")').length > 0){
			$("#eventTable tr").remove();
			$('#showArmor').html("Show your armors");
		}else{	
			if($('#eventTable tr > td:contains("Mana cost")').length > 0){
				$("#eventTable tr").remove();
				$('#showSkill').html("Show your skills");
			}else if($('#eventTable tr > td:contains("Accuracy")').length > 0){
				$("#eventTable tr").remove();
				$('#showWeapon').html("Show your weapons");
			}			
			$.ajax({                                      
			      url: 'phpAjax/fetchArmorsFromItem.php',                  //the script to call to get data       
			      data: {cN:cName},                        //you can insert url argumnets here to pass to api.php
			                                       //for example "id=5&parent=6"
			      dataType: 'json',                //data format      
			      success: function(data)          //on recieve of reply
			      {
			        callback(data);
			        var result = data;
				var length = result.length/3;
					$('#eventTable > tbody').append("<tr><td>Name</td><td>Defence</td><td>Level Required</td><td>Equip</td></tr>");
					for(var i=0;i<length;i++){
						if(availableArmors[3*i] == armor){
							$('#eventTable > tbody').append("<tr><td>" +result[3*i] + "</td><td>" + result[3*i+1] + "</td><td>" + result[3*i+2] +"</td><td>E</td></tr>");
						}else{
							$('#eventTable > tbody').append("<tr><td><button input type='button' onclick = 'switchA(availableArmors[3*" + i + "])'>" +result[3*i] + "</button></td><td>" + result[3*i+1] + "</td><td>" + result[3*i+2] +"</td><td></td></tr>");
						}	
					}
				}
			});
			
			$('#showArmor').html("Hide your armors");
		} 
	}
	
	function findEnemy(){
		location = 'battle.php';
	}
	
	function Back(){
		location = 'login.html';
	}
	
	fetchInfo();
	</script>
</head>

<body>
<p id = "welcome" style="color:red;">This is your super powerful champion!</p>
<img src = "GameImage/champion.jpg" name = "pic" alt = "champion" style="width:323px;height:400px;">
<br>

<div id="info">this element will be accessed by jquery and this text replaced</div>
<p><button input type="button" onclick="findEnemy()">Find enemies</button>
<button input id="showSkill" type="button" onclick="fetchSkills()">Show your skills</button>
<button input id="showWeapon" type="button" onclick="changeWeapon(function(returnedData){availableWeapons = returnedData;});">Show your weapons</button>
<button input id="showArmor" type="button" onclick="changeArmor(function(returnedData){availableArmors = returnedData;});">Show your armors</button>
<button input type="button" onclick="Back()">Log out</button></p>

<table id = "eventTable" style="width:40%">
<tbody>
</tbody>
</table>


</body>
</html>