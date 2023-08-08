<?php

	if(!isset($_SESSION))  session_start();
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/constants.php';
	

	$conn = sql_conectar();

	$hoy 	= date('m/d/Y');
	$ahora 	= date('H:i');
	$ahoracero = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($ahora))); //Pongo la hora, segun el Huso horario establecido por el perfil
	$ahoraargentina = date('H:i', strtotime('-10800 seconds', strtotime($ahoracero))); //Pongo la hora en Huso horario 0
	$ahora = $ahoraargentina;
	$linksala ='actividades/bsq';
	
	$query = "	SELECT FIRST 1 AGEREG, AGEYOULNK, AGEHORINI, AGEHORFIN, AGETITULO,AGEYOULNKING, AGEYOULNKPOR
				FROM AGE_MAEST
				WHERE ESTCODIGO=1 AND  (AGEFCH='$hoy' AND AGEHORFIN>(CAST('$ahora' AS TIME)))
				ORDER BY AGEFCH, AGEHORINI, AGEREG";
	$Table = sql_query($query, $conn);

	if ($Table->Rows_Count>0){
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
		
			$row = $Table->Rows[$i];
			$agereg 	= trim($row['AGEREG']);
			$ageyoulnk 	= trim($row['AGEYOULNK']);
			$ageyoulnking 	= trim($row['AGEYOULNKING']);
			$ageyoulnkpor 	= trim($row['AGEYOULNKPOR']);
			$agetitulo 	= trim($row['AGETITULO']);
			$agehorini 	= trim($row['AGEHORINI']);
			$agehorfin 	= trim($row['AGEHORFIN']);
			$horainicharla = date('H:i:s', (strtotime($agehorini) - 600)); //le resto 10 minutos y lo pareso a horas
			$horafincharla = date('H:i:s', (strtotime($agehorfin) - 60)); //le resta 1 minuto para que se apague justo cuando termina
			$linksala= 'sala/bsq.php';
		
			if (($ageyoulnk != '' || $ageyoulnking != '' || $ageyoulnkpor != '') && $ahora>=$horainicharla && $ahora<=$horafincharla  ) {
				
				// $tmpl->setVariable('linksala', $linksala.'?A='.$agereg);
				echo "<script>top.window.location = 'sala/bsq.php?A=$agereg'</script>";
				//$tmpl->setVariable('linksala', 'actividades/bsq');
				
			}else{
				echo "<script>top.window.location = 'actividades/bsq'</script>";
			}
	}

	
		
	}else{
		echo "<script>top.window.location = 'actividades/bsq'</script>";
	}


	



	sql_close($conn);
	//echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	// HASTA ACA
	?>	


