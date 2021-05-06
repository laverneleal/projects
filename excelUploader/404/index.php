<html>
<head>
	<meta charset="UTF-8">	
	<title>Under Repair</title>
</head>
<body>
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
			if($row['Control'] == 1)	{
				$wallpaper = '<div style="width:100%;height:100%;text-align:center;display:table;vertical-align: middle;background-color:#000;"><img src="UnderMaintenance.JPG" style="height:800px;"></div>';
			} else {
				$wallpaper = '<div style="width:100%;height:100%;text-align:center;display:table;vertical-align: middle;"><a href="'.$_REQUEST['prevUrl'].'" style="text-decoration:none;font-weight:bold;"><button style="padding:20px;cursor:pointer;">RESUME</button></a></div>';
			}
		}
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	echo $wallpaper;
	?>
	
</body>
</html>