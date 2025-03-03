<?php
require 'header.php';
if(!isset($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {
	header("Location: index.php");
	exit();
}
else if(!isset($_SESSION['encarta_id'])) {
	header("Location: userid.php");
	exit();
}

$database = new Database;

$database->query("SELECT * FROM events WHERE id = :id");
$database->bind(":id", $_SESSION['encarta_id']);
$row = $database->single();
if($database->rowCount() == 0){
	try {
		$database->query("INSERT INTO events(id) VALUES (:id)");
		$database->bind(":id", $_SESSION['encarta_id']);
		$database->execute();
		$database->query("SELECT * FROM events WHERE id = :id");
		$database->bind(":id", $_SESSION['encarta_id']);
		$row = $database->single();
	}
	catch(PDOException $e){
		alert('<center><strong>Failure!</strong> An error occurred. '.$e->getMessage().'.</center>', "danger");
		exit();
	}
}
if($row['locked']==1) {
	alert("<center>Sorry your registration is locked now. Please go to the desk if you want to change your events.</center>", "info"); ?>
	<script type="text/javascript">
		window.onload = function() {
		  xxx();
		};
	</script>
<?php }
else {
	if(isset($_POST['event_update']))
	{
		$sql = "UPDATE events SET ";
		$temp_arr = array();
		foreach ($_POST as $key => $value) {
			if($key != "event_update")
			{
				if($key == "total1")
					$key = "total";
				$temp_arr[] = "$key = $value";
			}
		}
		$sql .= implode(", ", $temp_arr);
		$sql .= ", table_no = :table_no, locked = 1 WHERE id = :id AND locked=0";
		$database->query($sql);
		$database->bind(":table_no", $_SESSION['id']);
		$database->bind(":id", $_SESSION['encarta_id']);
		try {
			$database->execute();
			alert("<center><strong>Success!</strong> Event list has been updated. Please collect <strong>Rs. ".$_POST['total1']."</strong><br>Click <a href='userid.php'>here</a> to select a different Encarta id</center>");
		}
		catch(PDOException $e){
			alert('<center><strong>Failure!</strong> An error occurred. '.$e->getMessage().'.</center>', "danger");
		}
	}
}
?>

  <div class="container-fluid">
	<div class="row">
		<div class="col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10" style="margin-top:50px;margin-bottom:50px;">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<p class="panel-title" style="text-align: center; font-size: 36px; line-height: 1.5;">
						Select the events you want to participate in
					</p>
				</div>
				<div class="panel-body">
					<h3> Packages: </h3>
					<div class="form-group">
						<div class="radio">
							<label>
								<input type="radio" name="package" value="general"  onclick="package(this.value)" >
								General Registration(Rs. 150)
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="package" value="gamesonly" onclick="package(this.value)" >
								Gamezvilla Only(Rs. 100)
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="package" value="allevents" onclick="package(this.value)" >
								All Events(Rs. 305)
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="package" value="customize" id="custom">
								Customize Yourself(Select as per your choice)
							</label>
						</div>
					</div>

					<hr>
					<form name="myForm" action="<?php $_SERVER['PHP_SELF']; ?>"  method="post">
						<div class="form-group">
							CODE FIESTA
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="code_marathon" onclick="total()" <?php if($row['code_marathon']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="code_marathon" onclick="total()" <?php if($row['code_marathon']) echo "checked" ?>> CODE MARATHON
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="insomnia" onclick="total()" <?php if($row['insomnia']) echo "checked" ?>>
									<input  class="general" type="checkbox" value="1" name="insomnia" onclick="total()" <?php if($row['insomnia']) echo "checked" ?>> INSOMNIA
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="code_mutants" onclick="total()" <?php if($row['code_mutants']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="code_mutants" onclick="total()" <?php if($row['code_mutants']) echo "checked" ?>> CODE MUTANTS
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="clean_the_bugs" onclick="total()" <?php if($row['clean_the_bugs']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="clean_the_bugs" onclick="total()" <?php if($row['clean_the_bugs']) echo "checked" ?>> CLEAN THE BUGS
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="python_phreakers" onclick="total()" <?php if($row['python_phreakers']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="python_phreakers" onclick="total()" <?php if($row['python_phreakers']) echo "checked" ?>> PYTHON PHREAKERS
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="klutzy_code" onclick="total()" <?php if($row['klutzy_code']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="klutzy_code" onclick="total()" <?php if($row['klutzy_code']) echo "checked" ?>> KLUTZY CODE
								</label>
							</div>
							<div class="checkbox" >
								<label>
									<input type="hidden" value="0" name="chaos" onclick="total()" <?php if($row['chaos']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="chaos" onclick="total()" <?php if($row['chaos']) echo "checked" ?>> CHAOS
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="bragging" onclick="total()" <?php if($row['bragging']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="bragging" onclick="total()" <?php if($row['bragging']) echo "checked" ?>> BRAGGING
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="the_gauntlet" onclick="total()" <?php if($row['the_gauntlet']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="the_gauntlet" onclick="total()" <?php if($row['the_gauntlet']) echo "checked" ?>> THE GAUNTLET
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="bandit" onclick="total()" <?php if($row['bandit']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="bandit" onclick="total()" <?php if($row['bandit']) echo "checked" ?>> BANDIT
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="webcrats" onclick="total()" <?php if($row['webcrats']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="webcrats" onclick="total()" <?php if($row['webcrats']) echo "checked" ?>> WEBCRATS
								</label>
							</div>
						</div>
						<div class="form-group">
							QUIZZARD
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="euts" onclick="total()" <?php if($row['euts']) echo "checked" ?>>
									<input  class="general" type="checkbox" value="1" name="euts" onclick="total()" <?php if($row['euts']) echo "checked" ?>> KBC
								</label>
							</div>
							<div class="checkbox" >
								<label>
									<input type="hidden" value="0" name="head_rush" onclick="total()" <?php if($row['head_rush']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="head_rush" onclick="total()" <?php if($row['head_rush']) echo "checked" ?>> HEAD RUSH
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="digital_fortress" onclick="total()" <?php if($row['digital_fortress']) echo "checked" ?>>
									<input  class="general" type="checkbox" value="1" name="digital_fortress" onclick="total()" <?php if($row['digital_fortress']) echo "checked" ?>> DIGITAL FORTRESS
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="quiz_up" onclick="total()" <?php if($row['quiz_up']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="quiz_up" onclick="total()" <?php if($row['quiz_up']) echo "checked" ?>> QUIZ UP
								</label>
							</div>
						</div>
						<div class="form-group">
							CREATRIX
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="accelera" onclick="total()" <?php if($row['accelera']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="accelera" onclick="total()" <?php if($row['accelera']) echo "checked" ?>> ACCELERA
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="youth_parliament" onclick="total()" <?php if($row['youth_parliament']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="youth_parliament" onclick="total()" <?php if($row['youth_parliament']) echo "checked" ?>> YOUTH PARLIAMENT
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="mad_about_ads" onclick="total()" <?php if($row['mad_about_ads']) echo "checked" ?>>
									<input  class="general" type="checkbox" value="1" name="mad_about_ads" onclick="total()" <?php if($row['mad_about_ads']) echo "checked" ?>> MAD ABOUT ADS
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="art_design" onclick="total()" <?php if($row['art_design']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="art_design" onclick="total()" <?php if($row['art_design']) echo "checked" ?>> ART DESIGN
								</label>
							</div>
						</div>
						<div class="form-group">
							INGENIEUR
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="blue_print" onclick="total()" <?php if($row['blue_print']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="blue_print" onclick="total()" <?php if($row['blue_print']) echo "checked" ?>> BLUE PRINT
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="circuit_cipher" onclick="total()" <?php if($row['circuit_cipher']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="circuit_cipher" onclick="total()" <?php if($row['circuit_cipher']) echo "checked" ?>> CIRCUIT CIPHER
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="line_follower_race" onclick="total()" <?php if($row['line_follower_race']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="line_follower_race" onclick="total()" <?php if($row['line_follower_race']) echo "checked" ?>> LINE FOLLOWER RACE
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="open_hardware" onclick="total()" <?php if($row['open_hardware']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="open_hardware" onclick="total()" <?php if($row['open_hardware']) echo "checked" ?>> OPEN HARDWARE
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="open_software" onclick="total()" <?php if($row['open_software']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="open_software" onclick="total()" <?php if($row['open_software']) echo "checked" ?>> OPEN SOFTWARE
								</label>
							</div>
						</div>
						<div class="form-group">
							MANIGMA
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="dalal_street"  onclick="total()" <?php if($row['dalal_street']) echo "checked" ?>>
									<input  type="checkbox" value="1" name="dalal_street" id="dalal_street" onclick="total()" <?php if($row['dalal_street']) echo "checked" ?>> DALAL STREET(Rs.25)
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="encarta_hunt"  onclick="total()" <?php if($row['encarta_hunt']) echo "checked" ?>>
									<input  type="checkbox" value="1" name="encarta_hunt" id="encarta_hunt" onclick="total()" <?php if($row['encarta_hunt']) echo "checked" ?>> ALOHOMORA(Rs.50)
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="pick_a_pic" onclick="total()" <?php if($row['pick_a_pic']) echo "checked" ?>>
									<input class="general" type="checkbox" value="1" name="pick_a_pic" onclick="total()" <?php if($row['pick_a_pic']) echo "checked" ?>> PICK A PIC
								</label>
							</div>
						</div>
						<div class="form-group">
							GAMEZVILLA
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="counter_strike"  onclick="total()" <?php if($row['counter_strike']) echo "checked" ?>>
									<input class="games" type="checkbox" value="1" name="counter_strike" id="counter_strike" onclick="total()" <?php if($row['counter_strike']) echo "checked" ?>> COUNTER STRIKE(Rs.50)
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="need_for_speed" onclick="total()" <?php if($row['need_for_speed']) echo "checked" ?>>
									<input class="general games" type="checkbox" value="1" name="need_for_speed" id="need_for_speed" onclick="total()" <?php if($row['need_for_speed']) echo "checked" ?>> NEED FOR SPEED
								</label>
							</div>
							<div class="checkbox">
								<label>
									<input type="hidden" value="0" name="mini_militia"  onclick="total()" <?php if($row['mini_militia']) echo "checked" ?>>
									<input class="games" type="checkbox" value="1" name="mini_militia" id="mini_militia" onclick="total();" <?php if($row['mini_militia']) echo "checked" ?>> MINI MILITIA(Rs.30)
								</label>
							</div>
						</div>
						<button type="submit" class="btn btn-success btn-raised btn-lg" name="event_update" value="true" id="sub_btn">Submit</button>
						<label class="pull-right" style="color: #333;">
							Total
							<input type="text" name="Total" id="Total" style="width:50px;" value="0" disabled>
							<input type="number" name="total1" id="total2" style="width:50px;" value="0" hidden="true">
						</label>
						<div class="clearfix">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

  <script>
  	function package(abc)
  	{
  		var inputCheck = document.getElementsByTagName("input");
  		//refresh each checkbox every time radio button is clicked
  		for (var i=0; i<inputCheck.length; i++) {
  			if (inputCheck[i].type == "checkbox" )
  			inputCheck[i].checked=false;
  		}
  		if(abc=='general'){
  			for(i=0;i<document.getElementsByClassName('general').length;i++)
  				document.getElementsByClassName('general')[i].checked=true;
  		}
  		else if(abc=='gamesonly'){
  			for(i=0;i<document.getElementsByClassName('games').length;i++)
  				document.getElementsByClassName('games')[i].checked=true;
  		}
  		else if(abc=='allevents'){
  			for (var i=0; i<inputCheck.length; i++) {
  				if (inputCheck[i].type == "checkbox" )
  					inputCheck[i].checked=true;
  			}
  		}
  		var inputElems = document.getElementsByTagName("input");
  		count = 0;
  		var t = 0;
  		for (var i=0; i<inputElems.length; i++) {
  			if (inputElems[i].type == "checkbox" && inputElems[i].checked == true){
  				count++;
  			}
  		}

      if(document.getElementById("dalal_street").checked)
        {t=t+25;count--;}
      if(document.getElementById("encarta_hunt").checked)
        {t=t+50;count--;}

  		if(document.getElementById("counter_strike").checked && document.getElementById("need_for_speed").checked && document.getElementById("mini_militia").checked && count==3)
  			{t=100;count=0;}

  		else {
  			if(document.getElementById("counter_strike").checked)
  				{t=t+50;count--;}
  			if(document.getElementById("mini_militia").checked)
  				{t=t+30;count--;}
  			if(document.getElementById("need_for_speed").checked)
  				{t=t+50;count--;}
  		}


  		if(count>0)
  			{t=t+150;
  				if(document.getElementById("need_for_speed").checked)
  				{
  					t=t-50;
  				}

  			}

  			document.getElementById("Total").value = t;
  			document.getElementById("total2").value = t;
  	}

  </script>
  <script>

  	function total()
  	{
  		//console.log('called total()');

  		document.getElementById("custom").checked = true;


  		var inputElems = document.getElementsByTagName("input");
  		count = 0;
  		var t = 0;
  		for (var i=0; i<inputElems.length; i++) {
  			if (inputElems[i].type == "checkbox" && inputElems[i].checked == true){
  				count++;
  			}
  		}
      if(document.getElementById("dalal_street").checked)
        {t=t+25;count--;}
      if(document.getElementById("encarta_hunt").checked)
        {t=t+50;count--;}

  		if(document.getElementById("counter_strike").checked && document.getElementById("need_for_speed").checked && document.getElementById("mini_militia").checked && count==3)
  			{t=t+100;count=0;}

  		else {
  			if(document.getElementById("counter_strike").checked)
  				{t=t+50;count--;}
  			if(document.getElementById("mini_militia").checked)
  				{t=t+30;count--;}
  			if(document.getElementById("need_for_speed").checked)
  				{t=t+50;count--;}
  		}


  		if(count>0)
  			{t=t+150;
  				if(document.getElementById("need_for_speed").checked)
  				{
  					t=t-50;
  				}

  			}

  			document.getElementById("Total").value = t;
  			document.getElementById("total2").value = t;
  	}
  	total();
  	function xxx(){
  		var inputElems = document.getElementsByTagName("input");
  		for (var i=0; i<inputElems.length; i++) {
  			inputElems[i].disabled=true;
  		}
  		document.getElementById("sub_btn").disabled=true;
  	}

  </script>
  </body>
  </html>
