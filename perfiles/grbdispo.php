<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$percodigo 			= (isset($_POST['percodigo']))? trim($_POST['percodigo']) : 0;
	$dataDisponibilidad	= (isset($_POST['dataDisponibilidad']))? trim($_POST['dataDisponibilidad']) : '';



	
	//Controlo si el usuario logueado es administrador
	$peradminlog = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	if($peradminlog!=1) $peradmin=0;

	
	if($errcod==2){
		echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
		exit;
	}
	//--------------------------------------------------------------------------------------------------------------
	
	$percodigo		= VarNullBD($percodigo		, 'N');	
	
	//Almaceno la Disponibilidad
	if($err == 'SQLACCEPT' && $errcod==0){
		
		if($dataDisponibilidad!='' && $percodigo!=0){

			
			
			$dataDisponibilidad = json_decode($dataDisponibilidad);
			foreach($dataDisponibilidad as $ind => $data){
				$fecha 	= $data->fecha;
				$hora 	= $data->hora;
				$dispbool 	= $data->dispbool;
				
				$perdisfch 	= ConvFechaBD($fecha);
				$perdishor 	= VarNullBD($hora  , 'S');
				$dispo 	= VarNullBD($dispbool  , 'N');

				//Verifiquemos que los datos del nuevo usuario no coincidan con uno ya existente
				$query= "SELECT PERCODIGO FROM PER_DISP WHERE (PERDISFCH=$perdisfch AND PERDISHOR=$perdishor AND PERCODIGO=$percodigo)";
				$Table = sql_query($query,$conn,$trans);
				if ($Table->Rows_Count>0) {
					$deleteDisp =  "DELETE FROM PER_DISP WHERE (PERDISFCH=$perdisfch AND PERDISHOR=$perdishor AND PERCODIGO=$percodigo)";
					$err =  sql_execute($deleteDisp,$conn,$trans);
				}
				if ($dispbool==1){

					$inserDisp =  "INSERT INTO PER_DISP(PERCODIGO,PERDISFCH,PERDISHOR,DISP_BOOL) VALUES($percodigo,$perdisfch,$perdishor,$dispo)";
					$err = sql_execute($inserDisp, $conn, $trans);

				}
				
				
				
			}
		}
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
		
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	

