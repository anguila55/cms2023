<?php
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	
// echo file_get_contents('php://input');

$conn= sql_conectar();//Apertura de Conexion
$data = json_decode(file_get_contents('php://input'), true);


foreach($data as $index => $val){
	$pernombre = '';
	$perapelli = '';
	$percargo  = '';
	$percompan = '';
	$percorreo = '';
	
            
   foreach ($val as $key => $val2 ) {
	   //PERNOMBRE
	 	if ($key == 639321) {
		 $pernombre = $val2;
		}
		if ($key == 639340 ) {
			$pernombre = $val2;
		}

		//PERAPELLI
		if ($key == 639322) {
			$perapelli = $val2;
		}
		if ($key == 639341) {
			$perapelli = $val2;
		}

		//Cargo

		if ($key == 639323) {
			$percargo = $val2;
		}
		if ($key == 639342) {
			$percargo = $val2;
		}

		//Compañia

		if ($key == 639324) {
			$percompan = $val2;
		}
		if ($key == 639343) {
			$percompan = $val2;
		}

		//Correo
		if ($key == 639325) {
			$percorreo = $val2;
		}
		if ($key == 639344) {
			$percorreo = $val2;
		}


		
		
	}

	$query1 = "	SELECT PERCODIGO,PERCORREO
					FROM PER_MAEST
					WHERE PERCORREO='$percorreo'";
	logerror($query1);
		$Table1 = sql_query($query1,$conn);
		if($Table1->Rows_Count>0){
			$row= $Table1->Rows[0];
			$percodigo1 = trim($row['PERCODIGO']);
			logerror($percodigo1);
			$query = " 	UPDATE PER_MAEST SET 
					PERNOMBRE='$pernombre',PERCORREO='$percorreo' WHERE PERCODIGO = $percodigo1";  
		}else{

			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
			//Genero un ID 
			$query 		= 'SELECT GEN_ID(G_PERFILES,1) AS ID FROM RDB$DATABASE';
			$TblId		= sql_query($query,$conn);
			$RowId		= $TblId->Rows[0];			
			$percodigo 	= trim($RowId['ID']);
			//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			
			$query = " 	INSERT INTO PER_MAEST(PERCODIGO,PERNOMBRE,PERCORREO)
						VALUES($percodigo,'$pernombre','$percorreo') ";	
		}	
	$err = sql_execute($query,$conn);	
	
}
