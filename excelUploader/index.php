<?php
try {
	$pdo = new PDO( 'mysql:host=127.0.0.1;dbname=masterlist_master', 'root', 'admin');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
  $stmt = $pdo->query("
		SELECT Control,RedirectUrl
		FROM mastercontrol
		WHERE ProjectName = 'ExcelUploader'
	");
	$flag = $stmt->execute();
	if(!$flag){
		$info = $stmt->errorInfo();
		exit($info[2]);
	}
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($row['Control'] == 1){
			header('location: '.$row['RedirectUrl'].'?prevUrl='.$_SERVER['REQUEST_URI'].'');
		}
	}
} catch (PDOException $e) {
	echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja" oncontextmenu="return false" >
<!-- HEADER -->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
		<title>Planner's List</title>
		<script src="http://hrdapps44:8002/bootstrap/js/jquery-1.11.3.js"></script>
		<script src="http://hrdapps44:8002/bootstrap/js/jquery-3.3.1.min.js"></script>
		<script src="http://hrdapps44:8002/bootstrap/js/bootstrap.min.js"></script>
		<link href="http://hrdapps44:8002/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="http://hrdapps44:8002/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<script src="http://hrdapps44:8002/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
        <link rel="shortcut icon" href="http://hrdapps44:8002/img/Official_HPdepartment_logo.png" />
        <!--<link rel="stylesheet" href="http://hrdapps44/font-awesome-4.7.0/font-awesome-4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="http://hrdapps44/font-awesome-4.7.0/font-awesome-4.7.0/css/font-awesome.min.css">-->
		<!--[if lt IE 9]>
		<script src="./bootstrap/js/html5shiv.js"></script>
		<script src="./bootstrap/js/respond.js"></script>
		<![endif]-->	
	</head>
	</br>
	
<body style="">
<nav class="navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid" style="background-color:#14BB03;font-size:11px;">
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-center">
                    <li>
                        <div style="color:#fff;margin-top:5px;">
                            <img src="	http://hrdapps44:8002/img/Official_HPdepartment_logo.png" style="height:50px;">
                        </div>
                    </li> 
					<li>
                        <div style="color:#fff;margin-top:10px; font-size:25px;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </li>					
					<li>
                        <div style="color:#fff;margin-top:10px; font-size:25px;">
								Planner's Master List <?php echo date('Y')?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
</nav>
<!--HEADER FOR SORTING	-->
<br/><br/>

<?php

date_default_timezone_set("Asia/Taipei");
if(isset($_GET["from"])) $from=$_GET["from"];
else $from= date('Y-m-d'); 

if(isset($_GET["until"])) $until=$_GET["until"];
else $until= date('Y-m-d');

?>

<div class="options">
<nav class="navbar-inverse navbar-fixed-top" role="navigation" style="margin-top:65px;">
<div class="container-fluid" style="margin-top:-10px;font-size:11px;width:100%;height:60px;background-color:#fafafa;">
	<tr>
		<div class="form-inline" style="width:100%;margin-top:20px;">
							
				<!--	&emsp;&emsp;From : <input type="text" class="form-control input-sm" id="from" value="<?php echo $from; ?>" style="font-size:11px;width:100px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer; text-align:center;">
				
				&emsp;Until : <input type="text" class="form-control input-sm" id="until" value = "<?php echo $until;?>" style="font-size:11px;width:100px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer; text-align:center;">

			&emsp;Section : <select class="form-control input-sm" name="sec" id="sec" style="font-size:11px;width:150px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer;">
				<option value="">-- All --</option>			
				<option value="External Pers A">External Perspective A</option>
				<option value="External Pers B">External Perspective B</option>
				</select>
					
				&nbsp;&nbsp;Team :&nbsp;			
				<select class="form-control input-sm" name="teams" id="teams" style="font-size:11px;width:100px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer;">
				<option value="">-- All --</option>				
				<option value="EPA 1">EPA 1</option>				
				<option value="EPA 2">EPA 2</option>				
				<option value="EPA 3">EPA 3</option>				
				<option value="EPB 1">EPB 1</option>				
				<option value="EPB 2">EPB 2</option>				
				<option value="EPB 3">EPB 3</option>				
				<option value="EPB 4">EPB 4</option>				
				</select>
		
				&nbsp;&nbsp;Code:&nbsp;
				<select class="form-control input-sm" name="codes" id="codes" style="font-size:11px;width:100px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer;">
				<option value="">-- All --</option>
				</select>	

				&nbsp;&nbsp;Bundle :&nbsp;			
				<select class="form-control input-sm" name="bundle" id="bundle" style="font-size:11px;width:100px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer;">
				<option value="">-- All --</option>				
				<option value="BUNDLE1">Bundle 1</option>				
				<option value="BUNDLE2">Bundle 2</option>				
				<option value="BUNDLE3">Bundle 3</option>				
				<option value="BUNDLE4">Bundle 4</option>				
				<option value="BUNDLE5">Bundle 5</option>				
				<option value="BUNDLE6">Bundle 6</option>				
				<option value="BUNDLE7">Bundle 7</option>				
				</select>-->
				
				&emsp;Filename to Extract:&nbsp;
				<input type="text" class="form-control input-sm" id="fileName" name="fileName" placeholder="*.xls" style="font-size:11px;width:250px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer;" onkeyup="go()">
				
				&emsp;Search for:&nbsp;
				<input type="text" class="form-control input-sm" id="searchstr" name="searchstr" placeholder="filter as you type.." style="font-size:11px;width:120px;height:23px;padding: 0px 0px 0px 0px;cursor: pointer;">
			</div>
		</td>
	</tr>
</div>
</nav>
</div>
</nav>
	<div id="content" style ="width:100%;font-size:11px;margin-top:-5px;"></div>
	<center><div id="loading" style="margin-top:250px;"></div></center>
	<script src="myscript.js"></script>
</body>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-m">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:#6AC663;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>

</body>
</html>




