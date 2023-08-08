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
	//--------------------------------------------------------------------------------------------------------------
	
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$percodinv 	= (isset($_POST['percodinv']))? trim($_POST['percodinv']) : 0;
	$reureginv 	= (isset($_POST['reureginv']))? trim($_POST['reureginv']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	
	if($percodinv!=0 && $reureginv!=0){
		//Elimino la invitacion
		$query = "	DELETE FROM REU_PER WHERE REUREG=$reureginv AND PERCODIGO=$percodinv ";
		$err = sql_execute($query,$conn);
		$errmsg='Invitacion eliminada.';
	}else{
		$errcod=2;
		$errmsg='Error al eliminar la invitacion.';
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{	"errcod":"'.$errcod.'",
			"errmsg":"'.$errmsg.'" }';
	
?>	
