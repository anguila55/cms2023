<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');

DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------

$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$fecha = (isset($_POST['fecha']))? trim($_POST['fecha']):'';

$invreg=0;
$conn = sql_conectar(); //Apertura de Conexion
//Busco los parametros de configuracion
$query = "	SELECT ZPARAM,ZVALUE FROM ZZZ_CONF WHERE ZPARAM CONTAINING 'SisEvento' ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$params[trim($row['ZPARAM'])] = trim($row['ZVALUE']);
}

$diasini = $params['SisEventoDiaInicio']; 		 			//Evento - Dia de Inicio
$diasdur = intval($params['SisEventoDuracionDias']); 	 	//Evento - Cantidad de Dias de duracion
$horaini = $params['SisEventoHorario']; 		 			//Evento - Horario de Inicio y Fin
$horaint = intval($params['SisEventoHorarioIntervalo']); 	//Evento - Intervalo de tiempo (min)

$tmpl->setVariable('horaint', $horaint);

$inicio = substr($diasini, 6, 4) . '-' . substr($diasini, 3, 2) . '-' . substr($diasini, 0, 2); //Formato calendario (yyyy-mm-dd)



$hoy = date('Y-m-d');
$tmpl->setVariable('hoy', $inicio);


$coloReunion	= '#967ADC';
$colorBloqueado	= '#FFAD8F';
$where 	= '';


//Habilito las opciones del Menu
if (json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false) {
	$tmpl->setVariable('ParamMenuActividades', 'display:;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false) {
	$tmpl->setVariable('ParamMenuAgenda', 'display:none;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false) {
	$tmpl->setVariable('ParamMenuMensajes', 'display:none;');
}
if (json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false) {
	$tmpl->setVariable('ParamMenuNoticias', 'display:none;');
}



$diaInicial =  substr($diasini, 0, 2) + 1 - 1;
$finalEvento =  $diasdur +  $diaInicial;
$contadorDias = 0;
$where 			= '';
$active 		= 'active';




		
	$hoy;
	$query = "SELECT WORK_REG, WORK_FCH, WORK_TITULO, WORK_DESCRI, WORK_HORINI, WORK_HORFIN, SPKREG, WORK_BBB, WORK_LINK, WORK_PDF
				FROM WORK_MAEST
				WHERE ESTCODIGO<>3 AND WORK_FCH='$fecha'
				ORDER BY WORK_FCH,WORK_HORINI,WORK_TITULO ";
	

	$Table = sql_query($query, $conn);


	$tmpl->setCurrentBlock('dias');
	$tmpl->setVariable('active', $active);
	if ($Table->Rows_Count != -1) {
		for ($u = 0; $u < $Table->Rows_Count; $u++) {

			$row = $Table->Rows[$u];
			$workreg 	= trim($row['WORK_REG']);
			$worktitulo 	= trim($row['WORK_TITULO']);
			$workdescri 	= trim($row['WORK_DESCRI']);
			$spkreg   	= trim($row['SPKREG']);
			$workfch     = BDConvFch($row['WORK_FCH']);
			$workbbb 	= trim($row['WORK_BBB']);
			$worklink 	= trim($row['WORK_LINK']);
			$workpdf 	= trim($row['WORK_PDF']);

			$workhorini  = substr(trim($row['WORK_HORINI']), 0, 5);
			$workhorfin  = substr(trim($row['WORK_HORFIN']), 0, 5);

			///CAMBIOS DE HORARIOS 

			$haux = date('H:i', strtotime('+10800 seconds', strtotime($workhorini))); //Pongo la hora en Huso horario 0
			$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$workhorini = $haux;
			$haux2 = date('H:i', strtotime('+10800 seconds', strtotime($workhorfin))); //Pongo la hora en Huso horario 0
			$haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
			$workhorfin = $haux2;
			///

			$workfch	= substr($workfch, 6, 4) . '-' . substr($workfch, 3, 2) . '-' . substr($workfch, 0, 2); //Formato calendario (yyyy-mm-dd)
			// $workhorini = ($workhorini != '') ? 'T' . $workhorini : '';
			// $workhorfin = ($workhorfin != '') ? 'T' . $workhorfin : '';


			if (($IdiomView=='ING')){
				$tmpl->setVariable('Idioma_Descripcion'		, ''); 
				
			}else if (($IdiomView=='ESP')){
		
				$tmpl->setVariable('Idioma_Descripcion'		, '');
			}else{
				$tmpl->setVariable('Idioma_Descripcion'		, ''); 
			}



			$ahora = date('H:i');

			$horaCharla = substr(trim($workhorini), 1, 2);
			$minutosCharla = substr(trim($workhorini), 4, 5);


			$minutosFinCharla = substr(trim($workhorfin), 4, 5);
			$horaFinCharla = substr(trim($workhorfin), 1, 2);
			$ahoraMinutos = substr($ahora, 3, 5);
			$ahoraHora = substr($ahora, 0, 2);

				//Busco el primer expositor que tenga el perfil relacionado
			$queryExp = "	SELECT FIRST 1 WORK_REG
							FROM WORK_PER
							WHERE PERCODIGO=$percodigo AND WORK_REG=$workreg";
			$TableExp = sql_query($queryExp,$conn);
			if($TableExp->Rows_Count>0){
			$rowExp= $TableExp->Rows[0];
			$invreg = $rowExp['WORK_REG'];
			}
			$tmpl->setVariable('video', 'd-none');
			if($invreg!=0 && $workbbb != ''){
					$tmpl->setVariable('video', 'd-block');

					$tmpl->setVariable('workbbb', $workbbb);
					$invreg=0;
			}

			

				$spkreglen = strlen($spkreg);

				$prueba  =  explode(',',$spkreg);
				$count = 0;
				foreach ($prueba as $key => $value) {
	
					if($value != 0){
						$queryspk = "	SELECT SPKREG, SPKNOMBRE, SPKDESCRI, SPKIMG, SPKPOS, ESTCODIGO,SPKEMPRES,SPKCARGO
							FROM SPK_MAEST
							WHERE SPKREG=$value";
							
						$Tablespk = sql_query($queryspk, $conn);
						if ($Tablespk->Rows_Count > 0) {
	
							$rowspk = $Tablespk->Rows[0];
							$spkimg  	= trim($rowspk['SPKIMG']);
							$spkregnew  	= trim($rowspk['SPKREG']);
							$spknombre  	= trim($rowspk['SPKNOMBRE']);
							$spkempres  	= trim($rowspk['SPKEMPRES']);
							$spkcargo  	= trim($rowspk['SPKCARGO']);
	
							
							$tmpl->setCurrentBlock('spkimg');
						
							$tmpl->setVariable('spkimg', $spkimg);
							$tmpl->setVariable('skpnombre', $spknombre);
							$tmpl->setVariable('skpempres', $spkempres);
							$tmpl->setVariable('skpcargo', $spkcargo);
	
							if($count == 0){
								$tmpl->setVariable('imagespk', '50px');
							}else{
								$tmpl->setVariable('imagespk', '50px');
								
							}
							$count =1;
							$tmpl->setVariable('spkreg', $spkregnew);;
							$tmpl->parse('spkimg');
	
						}
					}
				}
			
				$tmpl->setVariable('display', 'flex');

				$tmpl->setVariable('workreg', $workreg);
				
				$tmpl->setVariable('worktitulo',  $worktitulo);
				$tmpl->setVariable('workdescri',nl2br($workdescri)  );
				$tmpl->setVariable('workhorini', $workfch . $workhorini);
				$tmpl->setVariable('hora', $workhorini);
				$tmpl->setVariable('workhorfin',$workhorfin);
				$tmpl->setVariable('worklink',  $worklink);
				$tmpl->setVariable('workpdf',  $workpdf);
				//$tmpl->setVariable('video', 'd-block');



			if (($IdiomView=='ING')){

				$tmpl->setVariable('Idioma_Ver'		, 'More Info'); 
				$tmpl->setVariable('Idioma_Ingresar'		, 'Enter'); 
				$tmpl->setVariable('Idioma_Descargar'		, 'Download'); 
			}else{
			
				$tmpl->setVariable('Idioma_Ver'		, 'Ver');
				$tmpl->setVariable('Idioma_Ingresar'		, 'Ingresar'); 
				$tmpl->setVariable('Idioma_Descargar'		, 'Descargar'); 
			}
				

				$tmpl->setVariable('workbbb', $workbbb);

				if ($worklink==''){

					$tmpl->setVariable('displaylink', 'd-none');
				}else{
					$tmpl->setVariable('displaylink', 'd-flex d-block');
				}
				if ($workpdf==''){

					$tmpl->setVariable('displaypdf', 'd-none');
				}else{
					$tmpl->setVariable('displaypdf', 'd-flex d-block');
				}
				if ($workbbb==''){

					$tmpl->setVariable('video', 'd-none');
				}else{
					$tmpl->setVariable('video', 'd-flex d-block');
				}

				$tmpl->parse('workshops');
			
		  }
		
	} else {
		$tmpl->setCurrentBlock('workshops');
		if (($IdiomView=='ING')){

			$tmpl->setVariable('msg'		, 'No Round Tabled in this day');
		}else{
	
			$tmpl->setVariable('msg'		, 'No se encontraron Mesas Redondas para este dia');
		}
		$tmpl->setVariable('display', 'none');

		$tmpl->parse('workshops');
	}
	$tmpl->parse('dias');
	
	$contadorDias++;

   










sql_close($conn);
$tmpl->show();

?>	