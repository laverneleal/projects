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
	$inputFileName = '//hrdapps44/new_Imanager_data/DISTRIBUTION/'.$_REQUEST["fileName"].'.xls';
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
																				
		$rowData1 = $rowData[0][0]; //ConstructionCode
		$rowData2 = $rowData[0][1]; //PlanNo
		$rowData3 = $rowData[0][2]; //ShioNo
		$rowData4 = $rowData[0][3]; //DrawingType
		$rowData5 = $rowData[0][4]; //Process
		$rowData6 = $rowData[0][5]; //3DXF
		$rowData7 = $rowData[0][6]; //TEAM
		$rowData8 = $rowData[0][7]; //RequestType
		$rowData9 = $rowData[0][8]; //Remarks	
		$rowData10 = $rowData[0][9]; //BossDueDate	
		$rowData11 = $rowData[0][10]; //SiteEmaiSentDate
		$rowData12 = $rowData[0][11]; //SiteBossDueDateDue
		$rowData13 = $rowData[0][12]; //ShiyoushoBossDuelSentDate
		$rowData14 = $rowData[0][13]; //ShiyoushoEmailSentDate
		$rowData15 = $rowData[0][14]; //CadEmailDate
		$rowData16 = $rowData[0][15]; //PlanKind
		$rowData17 = $rowData[0][16]; //Condition
		$rowData18 = $rowData[0][17]; //HouseTypeCode
		$rowData19 = $rowData[0][18]; //Bundle
		$rowData20 = $rowData[0][19]; //Bundledate
		$rowData21 = $rowData[0][20]; //category
		
		date_default_timezone_set("Asia/Taipei");
		$dateTime = date('Y-m-d h:i:s');
		
		if($rowData20==true){
			$tbody .= '<tr>';
			$tbody .= '<td>'.$num++.'</td>';
			$tbody .= '<td>'.$rowData1.'</td>';
			$tbody .= '<td>'.$rowData2.'</td>';
			$tbody .= '<td>'.$rowData3.'</td>';
			$tbody .= '<td>'.$rowData4.'</td>';
			$tbody .= '<td>'.$rowData5.'</td>';
			$tbody .= '<td>'.$rowData6.'</td>';
			$tbody .= '<td>'.$rowData7.'</td>';
			$tbody .= '<td>'.$rowData8.'</td>';
			$tbody .= '<td>'.$rowData9.'</td>';
			$tbody .= '<td>'.$rowData10.'</td>';
			$tbody .= '<td>'.$rowData11.'</td>';
			$tbody .= '<td>'.$rowData12.'</td>';
			$tbody .= '<td>'.$rowData13.'</td>';
			$tbody .= '<td>'.$rowData14.'</td>';
			$tbody .= '<td>'.$rowData15.'</td>';
			$tbody .= '<td>'.$rowData16.'</td>';
			$tbody .= '<td>'.$rowData17.'</td>';
			$tbody .= '<td>'.$rowData18.'</td>';
			$tbody .= '<td>'.$rowData19.'</td>';
			$tbody .= '<td>'.$rowData20.'</td>';
			$tbody .= '<td>'.$rowData21.'</td>';
			$tbody .= '</tr>';	
		}else{
			$tbody .='';
	}

	$unique = $rowData1.$rowData2.$rowData20.$rowData4.$rowData5.$rowData21;
try{									//Instance...
	$pdo = new PDO( 'mysql:host=localhost;dbname=checklist;charset=utf8;', 'root', 'admin' );
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//update record
		$stmt=$pdo->prepare("
			INSERT INTO `wakubundle` (
				`unique_id`, `constructionCode`, `planNo`, `shioNo`, `drawingType`, `process`, `dxf`, `team`, `requestType`, `remarks`, `bossDueDate`, `siteEmaiSentDate`, `siteBossDueDate`, `shiyoushoBossDue`, `shiyoushoEmailSentDate`, `cadEmailDate`, `planKind`, `conditions`, `houseTypeCode`, `bundle`, `date`, `category`, `dateTime`
			)
			values(
				:unique,
				:rowData1,
				:rowData2,
				:rowData3,
				:rowData4,
				:rowData5,
				:rowData6,
				:rowData7,
				:rowData8,
				:rowData9,
				:rowData10,
				:rowData11,
				:rowData12,
				:rowData13,
				:rowData14,
				:rowData15,
				:rowData16,
				:rowData17,
				:rowData18,
				:rowData19,
				:rowData20,
				:rowData21,
				:dateTime
			)
			");
			
	$stmt->bindValue(':unique',$unique,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData1',$$rowData1,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData2',$$rowData2,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData3',$rowData3,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData4',$rowData4,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData5',$rowData5,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData6',$rowData6,PDO::PARAM_STR);
	$stmt->bindValue(':rowData7',$rowData7,PDO::PARAM_STR);
	$stmt->bindValue(':rowData8',$rowData8,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData9',$rowData9,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData10',$rowData10,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData11',$rowData11,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData12',$rowData12,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData13',$rowData13,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData14',$rowData14,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData15',$rowData15,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData16',$rowData16,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData17',$rowData17,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData18',$rowData18,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData19',$rowData19,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData20',$rowData20,PDO::PARAM_STR); 
	$stmt->bindValue(':rowData21',$rowData21,PDO::PARAM_STR); 
	$stmt->bindValue(':dateTime',$dateTime,PDO::PARAM_STR); 
	$stmt->execute();

}catch(PDOExeption $e){					//show message if failed on try
	var_dump($e->getmeddage());
}

$pdo=null;
	
/*      //insertDatatoDatabase;
	$conn = new mysqli( "localhost", "root", "admin", "checklist" );
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$unique = $rowData1.$rowData2.$rowData20.$rowData4.$rowData5.$rowData21;

	$query = "INSERT INTO wakubundle( unique_id, constructionCode, planNo, shioNo, drawingType, process, dxf, team, requestType, remarks, bossDueDate, siteEmaiSentDate, siteBossDueDate, shiyoushoBossDue, shiyoushoEmailSentDate, cadEmailDate, planKind, conditions, houseTypeCode, bundle, date, category, dateTime  ) VALUES ( '".$unique."','".$rowData1."', '".$rowData2."', '".$rowData3."', '".$rowData4."', '".$rowData5."', '".$rowData6."', '".$rowData7."', '".$rowData8."', '".$rowData9."', '".$rowData10."', '".$rowData11."', '".$rowData12."', '".$rowData13."', '".$rowData14."', '".$rowData15."', '".$rowData16."', '".$rowData17."', '".$rowData18."', '".$rowData19."', '".$rowData20."', '".$rowData21."', '".$dateTime."' ) ";
	$result = mysqli_query($conn,$query); 
  } */
  
?>

<div class="data" style="overflow:auto; height:85%; margin-top:60px; margin-left:0px;border:3;background: #ffffff !important; position:fixed; width:100%;" >
	<table class="table table-bordered table-hovered table-condensed" id="tblData1"  cellspacing="0" width="100%">
		<thead style=" margin-top:0px;font-weight: bold; background:lightgray !important;">
		<tr>
		<th colspan=21  style="color:blue;">&nbsp;&nbsp;There are <?php echo $num-1;?> record(s) found.</th>
		</tr>
		<tr>
			<th style="vertical-align: middle;border: 1px solid #b009fe8; background:#a6a6a6; "><center>No.</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Construction<br/>Code</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Plan<br/>No</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Shio<br/>No</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Drawing<br/>Type</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Process</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Code</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Team</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Request<br/>Type</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Remarks</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>BossDue<br/>Date</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>SiteEmai<br/>SentDate</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>SiteBoss<br/>DueDate</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Shiyousho<br/>BossDue</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Shiyousho<br/>Email<br/>SentDate</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Cad<br/>Email<br/>Date</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Plan<br/>Kind</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>Condition</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8;"><center>House<br/>Type<br/>Code</th>
			<th style="vertical-align: middle;border: 1px solid #b009fe8; background:#a6a6a6;;"><center>Bundle</th>		
			<th style="vertical-align: middle;border: 1px solid #b009fe8; background:#a6a6a6;;"><center>BundleDate</th>		
			<th style="vertical-align: middle;border: 1px solid #b009fe8; background:#a6a6a6;;"><center>Category</th>		
		</tr>
	</thead>
	<tbody>
		<?php echo @$tbody;?>
	</tbody>
	</table>
</div>

<?php
	$conn = new mysqli( "localhost", "root", "admin", "checklist" );
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$queryDelete2 = "DELETE FROM `wakubundle` WHERE constructionCode=''" ;
	$result = mysqli_query($conn,$queryDelete2);
?>


