<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	

			
	//--------------------------------------------------------------------------------------------------------------

	
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';

	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	$evefch 		= (isset($_POST['evefch']))? trim($_POST['evefch']) : '';
	$evedur 		= (isset($_POST['evedur']))? trim($_POST['evedur']) : 0;
	$evehorini 		= (isset($_POST['evehorini']))? trim($_POST['evehorini']) : '';
	$evehorfin 		= (isset($_POST['evehorfin']))? trim($_POST['evehorfin']) : '';
	$reudur 		= (isset($_POST['reudur']))? trim($_POST['reudur']) : 0;
	$reudes 		= (isset($_POST['reudes']))? trim($_POST['reudes']) : 0;
	$dataDisponibilidad	= (isset($_POST['dataDisponibilidad']))? trim($_POST['dataDisponibilidad']) : '';
	$tipoevento = (isset($_POST['tipoevento']))? trim($_POST['tipoevento']) : '';
	$compartirdatos = (isset($_POST['compartirdatos']))? trim($_POST['compartirdatos']) : '';
	$tiporeunion = (isset($_POST['tiporeunion']))? trim($_POST['tiporeunion']) : '';

	//--------------------------------------------------------------------------------------------------------------
	$fechaeventoinicial = explode('-',$evefch);
	$fechaeventoinicial = $fechaeventoinicial[2].'/'.$fechaeventoinicial[1].'/'.$fechaeventoinicial[0];

	$haux = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($evehorini))); //Pongo la hora, segun el Huso horario establecido por el perfil
	$haux = date('H:i', strtotime('-10800 seconds', strtotime($haux))); //Pongo la hora en Huso horario 0
	
	$evehorini = $haux;
 
	$haux2 = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($evehorfin))); //Pongo la hora, segun el Huso horario establecido por el perfil
	$haux2 = date('H:i', strtotime('-10800 seconds', strtotime($haux2))); //Pongo la hora en Huso horario 0
	
	$evehorfin = $haux2;

	$horaevento=$evehorini.'-'.$evehorfin;
	
	$query = "	UPDATE ZZZ_CONF SET
				ZVALUE='$fechaeventoinicial'
				WHERE ZPARAM='SisEventoDiaInicio' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZVALUE='$evedur'
				WHERE ZPARAM='SisEventoDuracionDias' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZVALUE='$horaevento'
				WHERE ZPARAM='SisEventoHorario' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZVALUE='$reudur'
				WHERE ZPARAM='SisEventoHorarioIntervalo' ";
	$err = sql_execute($query,$conn,$trans);
	$query = "	UPDATE ZZZ_CONF SET
				ZVALUE='$reudes'
				WHERE ZPARAM='SisEventoDescanso' ";
	$err = sql_execute($query,$conn,$trans);

	$query = " UPDATE ZZZ_CONF SET ZVALUE='$compartirdatos' WHERE ZPARAM='CompartirDatos' ";
	$err = sql_execute($query,$conn,$trans);
	$query = " UPDATE ZZZ_CONF SET ZVALUE='$tipoevento' WHERE ZPARAM='TipoEvento' ";
	$err = sql_execute($query,$conn,$trans);
	$query = " UPDATE ZZZ_CONF SET ZVALUE='$tiporeunion' WHERE ZPARAM='TipoReunion' ";
	$err = sql_execute($query,$conn,$trans);

				//Busco los parametros de configuracion
		$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row= $Table->Rows[$i];
			$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
		}



	if($dataDisponibilidad!=''){

		$dataDisponibilidad = json_decode($dataDisponibilidad);
		foreach($dataDisponibilidad as $ind => $data){
			$fecha 	= $data->fecha;
			$hora 	= $data->hora;
			$dispbool 	= $data->dispbool;
			
			$perdisfch 	= ConvFechaBD($fecha);
			$perdishor 	= VarNullBD($hora  , 'S');
			$dispo 	= VarNullBD($dispbool  , 'N');

			$query = "	SELECT DISFCHBLQ,DISHORBLQ FROM DIS_BLOQ ";
			//Verifiquemos que los datos del nuevo usuario no coincidan con uno ya existente
			$query= "SELECT DISFCHBLQ FROM DIS_BLOQ WHERE (DISFCHBLQ=$perdisfch AND DISHORBLQ=$perdishor)";
			$Table = sql_query($query,$conn,$trans);
			if ($Table->Rows_Count>0) {
				$deleteDisp =  "DELETE FROM DIS_BLOQ WHERE (DISFCHBLQ=$perdisfch AND DISHORBLQ=$perdishor)";
				$err =  sql_execute($deleteDisp,$conn,$trans);
			}
			if ($dispbool==1){

				$inserDisp =  "INSERT INTO DIS_BLOQ(DISFCHBLQ,DISHORBLQ) VALUES($perdisfch,$perdishor)";
				$err = sql_execute($inserDisp, $conn, $trans);

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
		$errmsg = ($errmsg=='')? 'Guardado correctamente!' : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
