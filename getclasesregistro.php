<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC . '/constants.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$pertipo = trim($_POST['pertipo']);
	
	$jsonData = '[';
	//Cargo los Subsectores segun los sectores seleccionados
	$query = "	SELECT PERCLASE,PERCLADES
				FROM PER_CLASE
				WHERE PERTIPO=$pertipo ";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$perclase 		= trim($row['PERCLASE']);
		$perclades 		= trim($row['PERCLADES']);
		
		$jsonData .= '{	"perclase":"'.$perclase.'",
						"perclades":"'.$perclades.'"},';
	}
	if($Table->Rows_Count>0){
		$jsonData = substr($jsonData,0,strlen($jsonData)-1);
	}
	$jsonData .= ']';
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	
	echo $jsonData;
?>	
