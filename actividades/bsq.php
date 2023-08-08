<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
	require_once GLBRutaFUNC.'/constants.php';
	require_once GLBRutaAPI  . '/timezone_bot.php';
	 
		
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('bsq.html');
	DDIdioma($tmpl);
	;
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$perusadis = (isset($_SESSION[GLBAPPPORT.'PERUSADIS']))? trim($_SESSION[GLBAPPPORT.'PERUSADIS']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar = (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
	$btnsectores 		= (isset($_SESSION[GLBAPPPORT.'SECTORES']))? trim($_SESSION[GLBAPPPORT.'SECTORES']) : '';
	$btnsubsectores 	= (isset($_SESSION[GLBAPPPORT.'SUBSECTORES']))? trim($_SESSION[GLBAPPPORT.'SUBSECTORES']) : '';
	$btncategorias 		= (isset($_SESSION[GLBAPPPORT.'CATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'CATEGORIAS']) : '';
	$btnsubcategorias 	= (isset($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']))? trim($_SESSION[GLBAPPPORT.'SUBCATEGORIAS']) : '';
	
	$tmpl->setVariable('percodnotif', $percodigo	);
	$tmpl->setVariable('pernombre'	, $pernombre	);
	$tmpl->setVariable('perapelli'	, $perapelli	);
	$tmpl->setVariable('perusuacc'	, $perusuacc	);
	$tmpl->setVariable('perpasacc'	, $perpasacc	);
	$tmpl->setVariable('peravatar'	, $peravatar	);
		
	//Nombre del Evento
	// $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );

	
	if($peradmin!=1) $tmpl->setVariable('viewadmin'	, 'none'	);
	if($btnsectores!=1) $tmpl->setVariable('btnsectores'	, 'display:;'	);
	if($btnsubsectores!=1) $tmpl->setVariable('btnsubsectores'	, 'display:;'	);
	if($btncategorias!=1) $tmpl->setVariable('btncategorias'	, 'display:none;'	);
	if($btnsubcategorias!=1) $tmpl->setVariable('btnsubcategorias'	, 'display:none;'	);
	
	//Habilito las opciones del Menu
	if(json_decode($_SESSION['PARAMETROS']['MenuActividades']) == false){
		$tmpl->setVariable('ParamMenuActividades'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuAgenda']) == false){
		$tmpl->setVariable('ParamMenuAgenda'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuMensajes']) == false){
		$tmpl->setVariable('ParamMenuMensajes'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuNoticias']) == false){
		$tmpl->setVariable('ParamMenuNoticias'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuExportar']) == false){
		$tmpl->setVariable('ParamMenuExportar'	, 'display:;'	);
	}
	if(json_decode($_SESSION['PARAMETROS']['MenuEncuesta']) == false){
		$tmpl->setVariable('ParamMenuEncuesta'	, 'display:none;'	);
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//--------------------------------------------------------------------------------------------------------------
//Boton Vivo
$hoy 	= date('m/d/Y');
$hoyfecha	= substr($hoy, 3, 2) .'.'. substr($hoy, 0, 2) .'.'. substr($hoy, 6, 4); //Formato 

$ahora 	= date('H:i');
$haux = date('H:i', strtotime('+10800 seconds', strtotime($ahora))); //Pongo la hora en Huso horario 0
$ahora = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
//$haux = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($ahora)));   // pongo en horario 0
//$ahora = date('H:i', strtotime('-10800 seconds', strtotime($haux))); //Pongo la hora en huso horario argentino
$tmpl->setVariable('linksalavivo', $ahora);
$linksala ='';

$tmpl->setVariable('botonacceder', 'd-none');

$query = "	SELECT FIRST 1 AGEREG, AGEYOULNK, AGEHORINI, AGEHORFIN, AGETITULO
			FROM AGE_MAEST
			WHERE ESTCODIGO=1 AND  (AGEFCH='$hoy' AND AGEHORFIN>(CAST('$haux' AS TIME)))
			ORDER BY AGEFCH, AGEHORINI, AGEREG";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	
	$row = $Table->Rows[$i];
	$agereg 	= trim($row['AGEREG']);
	$ageyoulnk 	= trim($row['AGEYOULNK']);
	$agetitulo 	= trim($row['AGETITULO']);
	$agehorini 	= trim($row['AGEHORINI']);
	$agehorfin 	= trim($row['AGEHORFIN']);
	$horainicharla = date('H:i:s', (strtotime($agehorini) - 600)); //le resto 10 minutos y lo pareso a horas
	$horafincharla = date('H:i:s', (strtotime($agehorfin) - 60)); //le resta 1 minuto para que se apague justo cuando termina
	
	$haux = date('H:i', strtotime('+10800 seconds', strtotime($horainicharla))); //Pongo la hora en Huso horario 0
	$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
	$horainicharla = $haux;

	$haux2 = date('H:i', strtotime('+10800 seconds', strtotime($horafincharla))); //Pongo la hora en Huso horario 0
	$haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
	$horafincharla = $haux2;
	//$horafin=strotime($horafincharla);
	//$horain=strotime($horainicharla);
	$hourdiff = round((strtotime($horafincharla) - strtotime($horainicharla))/3600, 1);
	//var_dump($hourdiff);die;

	$tmpl->setVariable('charlavivo', $agereg);
	
	if ($hourdiff>0){
		if (($ageyoulnk != '') && $ahora>=$horainicharla && $ahora<=$horafincharla  ) {
		
			$tmpl->setVariable('botonacceder', '');
			
		}else{
			$tmpl->setVariable('botonacceder', 'd-none');
			
			
		}

	}else{
		if (($ageyoulnk != '') && $ahora>=$horainicharla && $ahora>=$horafincharla  ) {
		
			$tmpl->setVariable('botonacceder', '');
			
		}else{
			$tmpl->setVariable('botonacceder', 'd-none');
			
			
		}
	}

	
	
}




	$idfechainicial=0;

	//--------------------------------------------------------------------------------------------------------------
	$cantdias =0;
	$query="SELECT DISTINCT A.AGEFCH FROM AGE_MAEST A WHERE A.ESTCODIGO=1";
					//logerror($query);
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$agefch	= trim($row['AGEFCH']);
		$agefecha	= BDConvFch($row['AGEFCH']);
		

		
		
		$tmpl->setCurrentBlock('browserfecha');

		if (($IdiomView=='ING')){

			setlocale(LC_ALL,"en_US");
		}else{
			setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
		}

		
		$agefecha	= substr($agefecha, 0, 2) .'.'. substr($agefecha, 3, 2) .'.'. substr($agefecha, 6, 4); //Formato 

		$date = DateTime::createFromFormat("Y-m-d", $agefch);


		$tmpl->setVariable('agefecha'	, $agefecha);
		if ($i==0){

			$tmpl->setVariable('agefechainicial'	, $agefecha);
		}
		//var_dump($agefecha,$hoyfecha);die;
		if ($agefecha==$hoyfecha){

			$tmpl->setVariable('agefechainicial'	, $agefecha);
			$idfechainicial=$i;
		}
		$tmpl->setVariable('agefch'	, utf8_encode(strftime("%A %d %B",$date->getTimestamp())));
		$tmpl->setVariable('idfecha'	, $i);
		$cantdias++;
		$tmpl->parse('browserfecha');

		
	}
	$tmpl->setVariable('idfechainicial'	, $idfechainicial);
	$tmpl->setVariable('cantdias'	, $cantdias);

	/////////////////NOMBRE BANNERS/////////////////////
	$queryparam = " SELECT PARCODIGO,PARNOM$IdiomView AS PARNOMBRE
				FROM PAR_MAEST 
				WHERE PARCODIGO='actividades'";
		$Tableparam = sql_query($queryparam, $conn);
		$rowparam = $Tableparam->Rows[0];
		$parnombre = trim($rowparam['PARNOMBRE']);
		$paneladmin = trim($rowparam['PARCODIGO']);
		$tmpl->setVariable('nombre'.$paneladmin, $parnombre);

	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
