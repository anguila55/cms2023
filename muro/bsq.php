<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('bsq.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo 			= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 			= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 			= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$pertipo 			= (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) : '';
	$perclase 			= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE']) : '';
	$perusuacc 			= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc 			= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin 			= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$peravatar 			= (isset($_SESSION[GLBAPPPORT.'PERAVATAR']))? trim($_SESSION[GLBAPPPORT.'PERAVATAR']) : '';
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
	if($peradmin!=1) $tmpl->setVariable('btnnuevoforo'	, 'disabled'	);
	if($perclase==47 || $perclase==48 || $perclase==49) $tmpl->setVariable('btnnuevoforo'	, ''	);
	
		
	//Nombre del Evento
	$tmpl->setVariable('SisNombreEvento', NAME_TITLE );	
	//--------------------------------------------------------------------------------------------------------------
	$imgAvatarNull = '../app-assets/img/avatar.png';
	$orientacionSwitch = 1;
	$tienereuniones=0;

	$where = '';
	$relacion = '';
	$hoy = date("Y-m-d");
	//Ver reuniones que envie y me enviaron, pero ya estan confirmadas
		$relacion = ' P.PERCODIGO=R.PERCODDST ';
		$where .= "  (R.PERCODDST=$percodigo OR R.PERCODSOL=$percodigo) AND (R.PERCODDST=R.PERCODSOL) AND R.REUESTADO=2 ";
	
	
	
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion

	
	//////////////////////// NETWORKING /////////////////////
		$query = "SELECT NETWORK_REG, NETWORK_TITULO, NETWORK_FCH, NETWORK_HORINI, NETWORK_HORFIN, 
		ESTCODIGO, NETWORK_BBB, SWITCH
		FROM NETWORK_MAEST
		WHERE ESTCODIGO=1 " ;
		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$networkreg 	= trim($row['NETWORK_REG']);
		$networktitulo 	= trim($row['NETWORK_TITULO']);
		$networkfch 	= BDConvFch($row['NETWORK_FCH']);
		$networkhorini 	= trim($row['NETWORK_HORINI']);
		$networkhorfin 	= trim($row['NETWORK_HORFIN']);
		$networkhorini= substr ( $networkhorini ,0,5);
		$networkhorfin= substr ( $networkhorfin ,0,5);
		$estcodigo 	= trim($row['ESTCODIGO']);
		$networkbbb	= trim($row['NETWORK_BBB']);
		$switch	= trim($row['SWITCH']);
		$networkfch	= substr($networkfch,6,4).'-'.substr($networkfch,3,2).'-'.substr($networkfch,0,2);


		$tmpl->setCurrentBlock('networking');			
		$tmpl->setVariable('networkreg'		, $networkreg		);
		$tmpl->setVariable('networktitulo'	, $networktitulo	);
		$tmpl->setVariable('networkfch'		, $networkfch		);
		$tmpl->setVariable('networkhorini'	, $networkhorini	);
		$tmpl->setVariable('networkhorfin'	, $networkhorfin	);
		$tmpl->setVariable('estcodigo'	, $estcodigo	);
		$tmpl->setVariable('networkbbb'	, $networkbbb	);
		if  ($switch ==1 ){

		$tmpl->setVariable('estado'	, 'imagenes-evento/homenuevo/live.png'	);
		$tmpl->setVariable('targetnet'	, '_blank'	);


		}else if  ($switch ==2 ){

		$tmpl->setVariable('estado'	, 'imagenes-evento/homenuevo/full.png'	);
		$tmpl->setVariable('networkbbb'	, ''	);
		$tmpl->setVariable('targetnet'	, ''	);

		}else {
		$tmpl->setVariable('estado'	, 'imagenes-evento/homenuevo/soon.png'	);
		$tmpl->setVariable('networkbbb'	, ''	);
		$tmpl->setVariable('targetnet'	, ''	);
		}
		$tmpl->parse('networking');
		}	

		///////////////////////////////////////////////////////////
		//////////////////////// Salas VIP /////////////////////
		$query = "	SELECT EM.EXPREUTIT, EM.EXPREULNK
		FROM EXP_MAEST EM 
		WHERE EM.EXPREUTIT IS NOT NULL AND EM.EXPREULNK IS NOT NULL ";
		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$expreutit 	= trim($row['EXPREUTIT']);
		$expreulnk 	= trim($row['EXPREULNK']);



		$tmpl->setCurrentBlock('salavip');
		$tmpl->setVariable('expreutit'		, $expreutit		);
		$tmpl->setVariable('expreulnk'	, $expreulnk	);
		$tmpl->parse('salavip');
		}	







		///////////////////////////////////////////////////////////


		/*--------------------------------- RANKING --------------------------------*/
		$query = "	SELECT FIRST 10  PERCODIGO, SUM(PUNTOS) F
		FROM GAME_PTS
		GROUP BY PERCODIGO
		ORDER BY SUM(PUNTOS) DESC ";

		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
		$row = $Table->Rows[$i];
		$perqrpun 	= trim($row['F']);
		$percodigogame = trim($row['PERCODIGO']);
		$querydes = "	SELECT PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCOMPAN AS SOLCOMPAN,PS.PERAVATAR AS SOLAVATAR
									FROM PER_MAEST PS
									WHERE PS.PERCODIGO=$percodigogame";	
				$Tabledes = sql_query($querydes, $conn);	
				$rowdes = $Tabledes->Rows[0];
				$pernombre1	= trim($rowdes['SOLNOMBRE']);
				$perapelli1	= trim($rowdes['SOLAPELLI']);
				$percompan1	= trim($rowdes['SOLCOMPAN']);
				$peravatar	= trim($rowdes['SOLAVATAR']);

		$tmpl->setCurrentBlock('ranking');

		if($peravatar!=''){
		if(strpos($peravatar, "https://") !== false){

		$tmpl->setVariable('peravatar'	, $peravatar);

		}else{
		$tmpl->setVariable('peravatar'	, '../perimg/'.$percodigo.'/'.$peravatar);
		}

		}else{
		$tmpl->setVariable('peravatar'	, $imgAvatarNull);
		}

		$tmpl->setVariable('medalla', '../app-assets/img/elements/3.png');
		if ($i<=2){
		$tmpl->setVariable('medalla', '../app-assets/img/elements/'.$i.'.png');
		}
		$tmpl->setVariable('perqrpun', $perqrpun);
		$tmpl->setVariable('posicion', $i+1);
		$tmpl->setVariable('pernombre1', $pernombre1);
		$tmpl->setVariable('perapelli1', $perapelli1);
		$tmpl->setvariable('percompan1', $percompan1);
		$tmpl->parse('ranking');
		}
		//--------------------------------------------------------------------------------------------------------------

		//--------------------------------------------------------------------------------------------------------------
		//Proximas conferencias
		$coloReunion	= '#967ADC';
		$colorBloqueado	= '#FFAD8F';

		$hoy 	= date('m/d/Y');
		$ahora 	= date('H:i');
		$ahoracero = date('H:i', strtotime(-$_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($ahora))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$ahoraargentina = date('H:i', strtotime('-10800 seconds', strtotime($ahoracero))); //Pongo la hora en Huso horario 0
		$ahora = $ahoraargentina;
		
		//$tmpl->setVariable('linksala', $ahora);
		$linksala ='';


		if ($IdiomView == 'ESP'){
		$tmpl->setVariable('charlavivo', 'No hay actividades en este dia');
		}else if($IdiomView == 'POR'){

		$tmpl->setVariable('charlavivo', 'Não há atividades neste dia');
		}else if($IdiomView == 'ING'){
		$tmpl->setVariable('charlavivo', 'There are no activities on this day');
		}


		$tmpl->setVariable('botonacceder', 'visibility:hidden');

		$query = "	SELECT FIRST 1 AGEREG, AGEYOULNK, AGEHORINI, AGEHORFIN, AGETITULO,AGEYOULNKING, AGEYOULNKPOR
		FROM AGE_MAEST
		WHERE ESTCODIGO=1 AND  (AGEFCH='$hoy' AND AGEHORFIN>(CAST('$ahora' AS TIME)))
		ORDER BY AGEFCH, AGEHORINI, AGEREG";
		$Table = sql_query($query, $conn);
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

		$tmpl->setVariable('charlavivo', $agetitulo);
		$tmpl->setVariable('horainicio', $agehorini);



		
		$tmpl->setVariable('linksala', $linksala.'&A='.$agereg);
		


		if ($ageyoulnk != '' && $ahora>=$horainicharla && $ahora<=$horafincharla  ) {

		$tmpl->setVariable('botonacceder', '');

		}else{
		$tmpl->setVariable('botonacceder', 'visibility:hidden');


		}

		}
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	
?>	
