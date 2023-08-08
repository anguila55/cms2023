<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodlog = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernomlog = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelog = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	$percodigo 	= '';
	$pernombre 	= '';
	$perapelli 	= '';
	$percompan 	= '';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$percorinv 	= (isset($_POST['percorinv']))? trim($_POST['percorinv']) : '';
	//--------------------------------------------------------------------------------------------------------------
	
	if($percorinv!=''){
		$query = " 	SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCOMPAN 
					FROM PER_MAEST 
					WHERE ESTCODIGO=1 AND PERCORREO='$percorinv'
						AND PERCODIGO<>$percodlog";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$percodigo = trim($row['PERCODIGO']);
			$pernombre = trim($row['PERNOMBRE']);
			$perapelli = trim($row['PERAPELLI']);
			$percompan = trim($row['PERCOMPAN']);
			
			$errmsg='Perfil encontrado.';
		}else{
			$errcod=2;
			$errmsg='Perfil inexistente.';
		}
		
		//$query = "";
		//$err = sql_execute($query,$conn,$trans);
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{	"errcod":"'.$errcod.'",
			"errmsg":"'.$errmsg.'",
			"percodinv":"'.$percodigo.'",
			"pernominv":"'.$pernombre.'",
			"perapeinv":"'.$perapelli.'",
			"percominv":"'.$percompan.'" }';
	
?>	
