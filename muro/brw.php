<?php include('../val/valuser.php'); ?>
<?
	// ***********************************************************
	// REVEER FUNCIONAMIENTO GENERAL Y QUERYS
	// ***********************************************************

	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
	require_once GLBRutaFUNC.'/constants.php';//Idioma	
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	
	//Diccionario de idiomas
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
	$pertipo   = (isset($_SESSION[GLBAPPPORT . 'PERTIPO'])) ? trim($_SESSION[GLBAPPPORT . 'PERTIPO']) : '';


	$orientacionSwitch =1;
	$pathimagenes = '../murimg/';
	$pathlinkimagenes = URL_WEB.'murimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	$pin=0;
	$where = '';
	if($fltdescri!=''){
		$where .= " AND MUR.MURTAG CONTAINING '$fltdescri' ";
	}
	if ($peradmin!=1 && $pertipo!=3){
		
		$tmpl->setVariable('visiblepublicar'	, 'display:;');
	}
	$pageNumero1		= (isset($_POST['pageNumero']))? $_POST['pageNumero'] : '0';
	$pageSalto		= 60;
	$pageNumero 	= $pageNumero1 *$pageSalto;
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT FIRST $pageSalto SKIP $pageNumero MUR.MURTITULO,MUR.MURTAG,MUR.MURIMG,MUR.MURENLACE,MUR.PERCODIGO,MUR.MURREG, MUR.ESTCODIGO,MUR.MURDESCRI,MUR.MURFCH,P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERAVATAR, P.PERCOMPAN, P.PERCLASE
				FROM MUR_MAEST AS MUR
				LEFT OUTER JOIN PER_MAEST  P ON P.PERCODIGO =  MUR.PERCODIGO
				LEFT OUTER JOIN MUR_PIN F ON MUR.MURREG =  F.MURREG
				WHERE MUR.ESTCODIGO=1 $where
				ORDER BY F.MURREG DESC, MUR.MURFCH DESC";	
				
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		
		if ($Table->Rows_Count<$pageSalto){

			$tmpl->setVariable('display-boton', 'display:none;');

		}
		$row = $Table->Rows[$i];
		$murreg		= trim($row['MURREG']);
		$murtitulo	= trim($row['MURTITULO']);
		$murenlace	= trim($row['MURENLACE']);
		$murtag		= trim($row['MURTAG']);
	
		$murimg		= trim($row['MURIMG']);
		//$murarchivo	= trim($row['MURARCHIVO']);
		$murdescri	= trim($row['MURDESCRI']);
		$murfch		= trim($row['MURFCH']);


		$now = time(); // or your date as well
		$your_date = strtotime($murfch);
		$datediff = $now - $your_date;

		#PERFIL
		$percodmur = trim($row['PERCODIGO']);
		$peravatar = trim($row['PERAVATAR']);
		$pernombre = trim($row['PERNOMBRE']);
		$perapelli = trim($row['PERAPELLI']);
		$percompan = trim($row['PERCOMPAN']);
		$perclasemur = trim($row['PERCLASE']);
		$tmpl->setVariable('ofertasinvitado', '');
		$tmpl->setVariable('botoninvitado', '');
		$tmpl->setVariable('botoninvitado1', '');
		$tmpl->setVariable('ofertasinvitado1', 'd-none');


		if ($percodigo == 210888){

			$tmpl->setVariable('ofertasinvitado', 'd-none');
			$tmpl->setVariable('botoninvitado', '');
			$tmpl->setVariable('botoninvitado1', 'botoninvitado');
			$tmpl->setVariable('ofertasinvitado1', '');


		}

		$querypin = "	SELECT PERCODIGO
							FROM MUR_PIN 
							WHERE MURREG=$murreg ";				
		$Tablepin = sql_query($querypin,$conn);
		if($Tablepin->Rows_Count>0){
			$pin = 1;
		}

		if ($murtag=='foro')
		{
			$tmpl->setCurrentBlock('browser1');
			

		}else{
			$tmpl->setCurrentBlock('browser');
			
		}
		$tmpl->setVariable('murtitulo'	, $murtitulo);
		$tmpl->setVariable('murtag'	, time_elapsed_string($murfch));
		$tmpl->setVariable('time'	,  time_elapsed_string($murfch));
		$tmpl->setVariable('murreg'	, $murreg);
		$tmpl->setVariable('murdescri'	, nl2br($murdescri));
		$tmpl->setVariable('murimg'	, $pathimagenes.$murreg.'/'.$murimg);
		$tmpl->setVariable('murimglink'	, $pathlinkimagenes.$murreg.'/'.$murimg);
		
		$tmpl->setVariable('display-archivo'	,'display:none;');
		$tmpl->setVariable('pagesalto'	, $pageNumero1+1);
		$tmpl->setVariable('displaytag'	,'display:none;');

		if($pin==1){

			$tmpl->setVariable('colorpin'	, 'color:red;');
			$tmpl->setVariable('backmuropin'	, 'background-color:#fffcf7;');
			$tmpl->setVariable('colorbordepin'	, 'orange');
			$pin=0;
		}else{

			if ($perclasemur == 47){

			$tmpl->setVariable('colorpin'	, 'color:inherited;');
			$tmpl->setVariable('backmuropin'	, 'background-color:#fffcf7;');
			$tmpl->setVariable('colorbordepin'	, 'orange');
			$tmpl->setVariable('displaytag'	,'display:;');
			$tmpl->setVariable('tagsponsor'	,'VENDEDOR');
			
			} else if ($perclasemur == 45){

			$tmpl->setVariable('colorpin'	, 'color:inherited;');
			$tmpl->setVariable('backmuropin'	, 'background-color:#f8f7ff;');
			$tmpl->setVariable('colorbordepin'	, 'blue');
			$tmpl->setVariable('displaytag'	,'display:;');
			$tmpl->setVariable('tagsponsor'	,'CONTACTO');


			} else if ($perclasemur == 42) {

				$tmpl->setVariable('colorpin'	, 'color:inherited;');
			$tmpl->setVariable('backmuropin'	, 'background-color:#f7fff7;');
			$tmpl->setVariable('colorbordepin'	, 'green');
			$tmpl->setVariable('displaytag'	,'display:;');
			$tmpl->setVariable('tagsponsor'	,'ADMINISTRADOR');

			} else {

				$tmpl->setVariable('colorpin'	, 'color:inherited;');
			$tmpl->setVariable('backmuropin'	, 'background-color:#FFFFFF;');
			$tmpl->setVariable('colorbordepin'	, 'grey');
			}
			
			
		}

		

		$tmpl->setVariable('display-enlace'	,'display:none;');

		if($murenlace != ''){
			$tmpl->setVariable('display-enlace'	,'display:;');
			$tmpl->setVariable('murenlace'	,$murenlace);

		}

		if($murimg == ''){

			$tmpl->setVariable('display-img','d-none');

		} else{
			$tmpl->setVariable('display-img','d-block');

		}

		if ($orientacionSwitch == 1) {
			$orientacion = 'left';
			$tmpl->setVariable('orientacion', $orientacion);
			
			$orientacionSwitch = 0;
		} else {
			$orientacion = 'right';
			$tmpl->setVariable('orientacion', $orientacion);
			$tmpl->setVariable('margin', 'mt-5');
			$orientacionSwitch = 1;
		}

		/* ------------------------------ DATOS PERFIL ------------------------------ */
		
		$displaycoment = 'd-none';
		$displayeditcoment = 'd-none';

		if($peravatar != ''){
			$tmpl->setVariable('peravatar'	, '../perimg/'.$percodmur.'/'.$peravatar);
		}else{
			$tmpl->setVariable('peravatar'	, $imgAvatarNull);
		}

		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('percod'	, $percodigo);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);

		$tmpl->setVariable('display-options','d-none');


		if($percodigo == $percodmur){
			$tmpl->setVariable('display-options','d-block');

		}
		if($percodigo!=0){
			$queryTipo = "	SELECT PERTIPO, PERAVATAR,PERNOMBRE,PERAPELLI
							FROM PER_MAEST
							WHERE PERCODIGO=$percodigo";

			$TableTipo = sql_query($queryTipo,$conn);
			if($TableTipo->Rows_Count>0){
				$rowTipo= $TableTipo->Rows[0];
				$pertipo = trim($rowTipo['PERTIPO']);
				$fotocomentario = trim($rowTipo['PERAVATAR']);
				$nombrecomentario = trim($rowTipo['PERNOMBRE']);
				$apellidocomentairo = trim($rowTipo['PERAPELLI']);

				
			if($peradmin == 1){

				$tmpl->setVariable('display-options','d-block');
				$displaycoment = 'd-block';
				$displayeditcoment = 'd-block';


			}

			if($fotocomentario != ''){

				$tmpl->setVariable('fotocomentario'	, '../perimg/'.$percodigo.'/'.$fotocomentario);
			}else{
				$tmpl->setVariable('fotocomentario'	, $imgAvatarNull);

			}
				
			$tmpl->setVariable('nombrecomentario'	, $nombrecomentario.' '.$apellidocomentairo);

			
			
				
			}
		}

	/* ------------------------------- COMENTARIOS ------------------------------- */	


	$queryComentarios = "SELECT C.COMREG, C.COMDESCRI, P.PERNOMBRE,P.PERAPELLI,P.PERAVATAR,P.PERCODIGO
			FROM MUR_COMENT AS C
			INNER JOIN PER_MAEST P ON P.PERCODIGO = C.PERCODIGO
			WHERE C.MURREG =  $murreg";
	$TableComentario = sql_query($queryComentarios,$conn);


	for($u=0; $u<$TableComentario->Rows_Count; $u++){
		$rowComentario = $TableComentario->Rows[$u];

		$count = 0;

		if($TableComentario->Rows_Count != 1){
			$count = $TableComentario->Rows_Count;
		}
		$comreg				= trim($rowComentario['COMREG']);
		$comdescri			= trim($rowComentario['COMDESCRI']);
		$percodcom			= trim($rowComentario['PERCODIGO']);
		$pernombrecom		= trim($rowComentario['PERNOMBRE']);
		$perapellicom		= trim($rowComentario['PERAPELLI']);
		$peravatarcom		= trim($rowComentario['PERAVATAR']);

		$tmpl->setCurrentBlock('comentarios');
		$tmpl->setVariable('comreg'		, $comreg);
		$tmpl->setVariable('count'		, $count);
		$tmpl->setVariable('comdescri'	, $comdescri);
		$tmpl->setVariable('nombrecom'	, $pernombrecom .' '.$perapellicom);
		if($peravatarcom!=''){
			$tmpl->setVariable('avatarcom'	, '../perimg/'.$percodcom.'/'.$peravatarcom);
		}else{
			$tmpl->setVariable('avatarcom'	, $imgAvatarNull);
		}



		$tmpl->setVariable('display-coment',$displaycoment);
		$tmpl->setVariable('display-editcoment',$displayeditcoment);


		if($percodigo == $percodcom){

			$tmpl->setVariable('display-coment','d-block');
			$tmpl->setVariable('display-editcoment','d-block');

		}
		
		$tmpl->parse('comentarios');



		}
		
		
	
		/* -------------------------------- ME GUSTA -------------------------------- */

		$mg =  "SELECT * FROM MUR_MGT WHERE PERCODIGO = $percodigo AND MURREG = $murreg";
		$TableMg = sql_query($mg,$conn);

		if($TableMg->Rows_Count>0){
			

			$tmpl->setVariable('megusta','fa-thumbs-up');
			$tmpl->setVariable('data-mg', 1);

			
		}else {
			$tmpl->setVariable('megusta','fa-thumbs-up');
			$tmpl->setVariable('data-mg', 2);

			
		}



		/* -------------------------------- METRICAS -------------------------------- */
		
		$metricas =  "SELECT COUNT(MURREG) AS TOTAL FROM MUR_MGT WHERE MURREG = $murreg";
		$TableMetrica = sql_query($metricas,$conn);

		if($TableMetrica->Rows_Count!=-1){
			$rowMetrica = $TableMetrica->Rows[0];
			$totalMg = trim($rowMetrica['TOTAL']);
			$tmpl->setVariable('countMg',$totalMg);
		

			
		}
		
		$metricas =  "SELECT COUNT(MURREG) AS TOTAL FROM MUR_COMENT WHERE MURREG = $murreg";
		$TableMetrica = sql_query($metricas,$conn);

		
		if($TableMetrica->Rows_Count!=-1){
			$rowMetrica = $TableMetrica->Rows[0];

			$totalcom = trim($rowMetrica['TOTAL']);
			$tmpl->setVariable('countCom',$totalcom);
			
		}


		
		if ($murtag=='foro')
		{
			$tmpl->parse('browser1');
		}else{

			$tmpl->parse('browser');
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
		
	$tmpl->show();







	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'año',
			'm' => 'mes',
			'w' => 'semana',
			'd' => 'dia',
			'h' => 'hora',
			'i' => 'minuto',
			's' => 'segundo',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? ' '.' Hace ' . implode(', ', $string) : 'hace instantes';
	}
	
?>