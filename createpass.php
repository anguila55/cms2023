<?
if (!isset($_SESSION))	session_start();
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

$updreg=0;
$err='SQLACCEPT';

//Regenero claves de perfiles
$query = " 	SELECT PERCODIGO,PERCPF,PERNOMBRE,PERAPELLI,PERCORREO
			FROM PER_MAEST
			WHERE PERCODIGO BETWEEN 12000 AND 13359 ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percodigo 	= trim($row['PERCODIGO']);
	$percpf 	= trim($row['PERCPF']);
	$pernombre 	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percorreo 	= trim($row['PERCORREO']);
	
	$updreg++;
	
	$percpf = 'APRE123';
	
	$perpasacc = md5('BenVido'.$percpf.'PassAcceso'.$percorreo);
	$perpasacc = 'B#SD'.md5(substr($perpasacc,1,10).'BenVidO'.substr($perpasacc,5,8)).'E##$F';
		//PERUSUACC='$percorreo', 
	$query = "	UPDATE PER_MAEST SET
				PERPASACC='$perpasacc'
				WHERE PERCODIGO=$percodigo ";
				
	$err = sql_execute($query,$conn,$trans);
	if($err != 'SQLACCEPT'){
		$i=$Table->Rows_Count;//salgo del for
	}
}

if($err == 'SQLACCEPT'){
	sql_commit_trans($trans);
	echo "Actualizados: $updreg <br>";
}else{            
	sql_rollback_trans($trans);
	echo "Error al actualizar <br>";
}

//--------------------------------------------------------------------------------------------------------------


?>