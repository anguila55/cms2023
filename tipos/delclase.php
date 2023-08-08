
<?php 

include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod 	= 0;
	$errmsg 	= '';
	$err 		= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	//Control de Datos
	$perclase 	= (isset($_POST['perclase']))? trim($_POST['perclase']) : 0;
	$pertipo 	= (isset($_POST['pertipo']))? trim($_POST['pertipo']) : 0;
	//--------------------------------------------------------------------------------------------------------------
	$perclase = VarNullBD($perclase , 'N');
	$pertipo = VarNullBD($pertipo , 'N');
	
	
		if($pertipo!=0 && $perclase!=0){
			//Elimino el registro
			//Elimino el registro
			$query = "	SELECT PC.PERCLASE,PT.PERTIPO
					FROM PER_TIPO PT
					LEFT OUTER JOIN PER_CLASE PC ON PC.PERTIPO=PT.PERTIPO
					WHERE PC.ESTCODIGO <> 3 AND PT.ESTCODIGO <> 3 AND PT.PERTIPO=$pertipo ";
		$Table = sql_query($query,$conn);
		if ($Table->Rows_Count == 1){
			
			$query = "UPDATE PER_TIPO SET ESTCODIGO=3 WHERE PERTIPO=$pertipo ";
			$err = sql_execute($query,$conn);
			$query = "UPDATE PER_CLASE SET ESTCODIGO=3 WHERE PERCLASE=$perclase ";
			$err = sql_execute($query,$conn);
			
		}else{
			$query = "UPDATE PER_CLASE SET ESTCODIGO=3 WHERE PERCLASE=$perclase ";
			$err = sql_execute($query,$conn);
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		$errcod = 0;
		$errmsg = 'Clase eliminada!';      
	}else{            
		$errcod = 2;
		$errmsg = ($errmsg=='')? 'Error al eliminar la clase!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
