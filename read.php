<?php

if(@$_REQUEST["perpage"]) {
	$perpage = @$_REQUEST["perpage"];
}else{
	$perpage = 10;
}

if(!@$_REQUEST["page"]) {
	$page = 1;
}else{
	$page = @$_REQUEST["page"];
}


if($_REQUEST["fileName"]) {
	$inputFileName = $_REQUEST["fileName"].'.xls';
}else{
	$inputFileName = '';
}
  include('PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');
  
  try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
  } catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
        $e->getMessage());
  }

  $sheet = $objPHPExcel->getSheet(0);
  $highestRow = $sheet->getHighestRow();
  $highestColumn = $sheet->getHighestColumn();
	$tbody = '';
	$num = 1;

	for ($row = 2; $row <= $highestRow; $row++) { 
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
																				
		$rowData1 = $rowData[0][0];
		$rowData2 = $rowData[0][1]; 
		$rowData3 = $rowData[0][2]; 
		$rowData4 = $rowData[0][3]; 
		$rowData5 = $rowData[0][4];
		$rowData6 = $rowData[0][5]; 
		$rowData7 = $rowData[0][6]; 
		 //

		
		date_default_timezone_set("Asia/Taipei");
		$dateTime = date('Y-m-d H:i:s');
		
		if($rowData7==true){
			$tbody .= '<tr>';
			$tbody .= '<td>'.$num++.'</td>';
			$tbody .= '<td>'.$rowData1.'</td>';
			$tbody .= '<td>'.$rowData2.'</td>';
			$tbody .= '<td>'.$rowData3.'</td>';
			$tbody .= '<td>'.$rowData4.'</td>';
			$tbody .= '<td>'.$rowData5.'</td>';
			$tbody .= '<td>'.$rowData6.'</td>';
			$tbody .= '<td>'.$rowData7.'</td>';
			$tbody .= '<td>'.password_hash('ichijo', PASSWORD_DEFAULT).'</td>';
			$tbody .= '</tr>';	
		}else{
			$tbody .='';
	}
		
     //insertDatatoDatabase;
	$conn = new mysqli( "10.168.71.81:3308", "sys-committee", "h78r78d", "masterlistforplanner" );
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$query = "INSERT INTO users( empcode, name, email, password ) VALUES ( '".$rowData5."','".$rowData6."', '".$rowData7."' ,'".password_hash('ichijo', PASSWORD_DEFAULT)."') ";
	$result = mysqli_query($conn,$query); 
  }
  
?>

<div class="data" style="overflow:auto; height:85%; margin-top:60px; margin-left:0px;border:3;background: #ffffff !important; position:fixed; width:100%;" >
	<table class="table table-bordered table-hovered table-condensed" id="tblData1"  cellspacing="0" width="100%">
		<thead style=" margin-top:0px;font-weight: bold; background:lightgray !important;">
		<tr>
		<th colspan=23  style="color:blue;">&nbsp;&nbsp;There are <?php echo $num-1;?> record(s) found.</th>
		</tr>
		<tr>
			<th style="vertical-align: middle;border: 1px solid #b009fe8; background:#a6a6a6; "><center>No.</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>営業所コード</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>営業所名</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>所属展示場CD</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>所属展示場名</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>社員CD</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>社員名</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>メールアドレス</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>PASSWORD</th>
		</tr>
	</thead>
	<tbody>
		<?php echo @$tbody;?>
	</tbody>
	</table>
</div>

<?php
	//$conn = new mysqli( "127.0.0.1:3308", "root", "admin", "checklist" );
	//if ($conn->connect_error) {
	//	die("Connection failed: " . $conn->connect_error);
	//} 
	//$queryDelete2 = "DELETE FROM `wakubundle` WHERE constructionCode=''" ;
	//$result = mysqli_query($conn,$queryDelete2);
?>
