<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma
	require_once GLBRutaFUNC . '/constants.php';

	$tmpl= new HTML_Template_Sigma();
	$tmpl->loadTemplateFile('brw.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	//$tmpl->setVariable('pernombre'	, $pernombre	);
	//$tmpl->setVariable('perapelli'	, $perapelli	);
	//$tmpl->setVariable('perusuacc'	, $perusuacc	);
	//$tmpl->setVariable('percorreo'	, $percorreo	);

	if($peradmin!=1){
		header('Location: ../index');
	}

	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';

	//Paginacion
	$pageNumero		= (isset($_POST['pageNumero']))? $_POST['pageNumero'] : '0';
	$pageSalto		= 30;
	$pageNumero 	= $pageNumero *$pageSalto;

	$conn= sql_conectar();//Apertura de Conexion

	$fltnombre 		= (isset($_POST['fltnombre']))? trim($_POST['fltnombre']):'';
	$fltorden 		= (isset($_POST['fltorden']))? trim($_POST['fltorden']):'';
	$fltordentipo 	= (isset($_POST['fltordentipo']))? trim($_POST['fltordentipo']):'';
	$fltestado 		= (isset($_POST['fltestado']))? trim($_POST['fltestado']):'';
	$fltpertipo 	= (isset($_POST['fltpertipo']))? trim($_POST['fltpertipo']):'';
	$fltperclase 	= (isset($_POST['fltperclase']))? trim($_POST['fltperclase']):'';
	$tmpl->setVariable('fltnombre'	, $fltnombre);
	$tmpl->setVariable('fltorden'	, $fltorden);
	$tmpl->setVariable('fltordentipo'	, $fltordentipo);
	$tmpl->setVariable('fltestado'	, $fltestado);
	$tmpl->setVariable('fltpertipo'	, $fltpertipo);
	$tmpl->setVariable('fltperclase'	, $fltperclase);

	$colorpermSI 	= '#0BE000';
	$colorpermNO 	= '#FF581B';

	$tmpl->setVariable('colorPermisoSI'	, $colorpermSI);
	$tmpl->setVariable('colorPermisoNO'	, $colorpermNO);

	$where = ' 1=1 ';

	//Nombre
	if($fltnombre!=''){
		$where .= " AND (P.PERNOMBRE CONTAINING '$fltnombre' OR P.PERCORREO CONTAINING '$fltnombre' OR P.PERUSUACC CONTAINING '$fltnombre' OR P.PERAPELLI CONTAINING '$fltnombre' OR P.PERCOMPAN CONTAINING '$fltnombre' OR P.PERCARGO CONTAINING '$fltnombre' OR P.PEREMPDES CONTAINING '$fltnombre' OR P.PERCIUDAD CONTAINING '$fltnombre' OR P.PERESTADO CONTAINING '$fltnombre' OR (EXISTS(SELECT 1 FROM TBL_PAIS TP WHERE TP.PAICODIGO=P.PAICODIGO AND P.PAICODIGO IS NOT NULL AND ( TP.PAIDESCRI CONTAINING '$fltnombre' OR TP.PAIDESCRIING CONTAINING '$fltnombre')))) ";
	}

	//Estado
	if($fltestado!=''){
		if ($fltestado == 0){

			$where .= " AND P.ESTCODIGO!=3 ";
			
		}else{

			$where .= " AND P.ESTCODIGO=$fltestado ";

		}
		
	}
	//Tipo de Perfiles
	if($fltpertipo!=''){
		$where .= " AND P.PERTIPO=$fltpertipo ";
	}
	//Clase de Perfiles
	if($fltperclase!=''){
		$where .= " AND P.PERCLASE=$fltperclase ";
	}

	$orden = ' ORDER BY PERNOMBRE ';
	switch($fltorden){
		case 1: //Nombre
			$orden = ' ORDER BY UPPER(P.PERNOMBRE) ';
			break;
		case 2: //Apellido
			$orden = ' ORDER BY UPPER(P.PERAPELLI) ';
			break;
		case 3: //Empresa
			$orden = ' ORDER BY UPPER(P.PERCOMPAN) ';
			break;
		case 4: //Reuniones
			$orden = ' ORDER BY 7 ';
			break;
	}

	//TIpo de Orden: 2=Descendente / 1=Ascendente
	if($fltordentipo==2){
		$orden .= ' DESC';
	}

	$query = "	SELECT FIRST $pageSalto SKIP $pageNumero P.PERCODIGO,P.PERCLASE, P.QRCODE,SUBSTRING(P.PERNOMBRE FROM 1 FOR 15) AS PERNOMBRE,SUBSTRING(P.PERAPELLI FROM 1 FOR 15) AS PERAPELLI,SUBSTRING(P.PERCOMPAN FROM 1 FOR 15) AS PERCOMPAN,P.ESTCODIGO,P.PERAVATAR,
						(SELECT COUNT(*)
						FROM REU_CABE R
						WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.PERCODDST!=R.PERCODSOL AND R.REUESTADO=2) AS REUCANT,
						(SELECT COUNT(*)
						FROM REU_CABE R
						WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.PERCODDST!=R.PERCODSOL AND R.REUESTADO!=5) AS REUTOTAL
				FROM PER_MAEST P
				WHERE P.PERCODIGO NOT IN ($arraysuperadmin1) AND $where
				$orden ";
	$Table = sql_query($query,$conn);


	

	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$percompan	= trim($row['PERCOMPAN']);
		$estcodigo	= trim($row['ESTCODIGO']);
		$peravatar	= trim($row['PERAVATAR']);
		$reucant	= trim($row['REUCANT']);
		$reutotal	= trim($row['REUTOTAL']);
		$perclase	= trim($row['PERCLASE']);
		
		$qrcode     = trim($row['QRCODE']);

		$viewlibbtn = 'fa-unlock';
		$viewmailbtn = 'none';
		if($estcodigo==9){ //Sin mail confirmado
			$viewmailbtn='';
			$viewlibbtn='fa-lock';
		}else if($estcodigo==8){ //Apto para liberar
			$viewlibbtn='fa-lock';
		}

	
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('percodigo'	, $percodigo);
		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);
	
		if($peravatar!=''){
			$tmpl->setVariable('peravatar'	, $pathimagenes.$percodigo.'/'.$peravatar);
		}else{
			$tmpl->setVariable('peravatar'	, $imgAvatarNull);
		}

		if($estcodigo==3){
			$tmpl->setVariable('btneliminar'	, 'display:none;');
		}else{
			$tmpl->setVariable('btnactivar'	, 'display:none;');
		}

	
		$queryPermisos = "SELECT PERUSAREU,PERUSACHA
		FROM PER_CLASE
		WHERE PERCLASE=$perclase AND ESTCODIGO=1";


		$TablePermisos = sql_query($queryPermisos, $conn);

		if ($TablePermisos->Rows_Count > 0) {
		$rowPermiso = $TablePermisos->Rows[0];
		$perusareu	= trim($rowPermiso['PERUSAREU']);
		$perusamsg	= trim($rowPermiso['PERUSACHA']);
		$perusadis	= trim($rowPermiso['PERUSAREU']);	
		}
		

		//ANCHOR Seteo de permisosz
		//Permisos
		$tmpl->setVariable('perusadis'	, $perusadis);
		$tmpl->setVariable('perusareu'	, $perusareu);
		$tmpl->setVariable('perusamsg'	, $perusamsg);
		$tmpl->setVariable('qrcodevisible'	, 'd-none');
		if ($qrcode!=''){
			$tmpl->setVariable('qrcodevisible'	, '');
			$tmpl->setVariable('qrcode'	, $qrcode);
		}
		

		if($perusadis==1){ //Permiso de Disponibilidad
			$tmpl->setVariable('permdispcolor'	, $colorpermSI);
		}else{
			$tmpl->setVariable('permdispcolor'	, $colorpermNO);
		}
		if($perusareu==1){ //Permiso de Reuniones
			$tmpl->setVariable('permreuncolor'	, $colorpermSI);
		}else{
			$tmpl->setVariable('permreuncolor'	, $colorpermNO);
		}
		if($perusamsg==1){ //Permiso de Mensajeria
			$tmpl->setVariable('permmsgcolor'	, $colorpermSI);
		}else{
			$tmpl->setVariable('permmsgcolor'	, $colorpermNO);
		}


		$tmpl->setVariable('viewmailbtn', $viewmailbtn);
		$tmpl->setVariable('viewlibbtn'	, $viewlibbtn);
		$tmpl->setVariable('reucant'	, $reucant.'/'.$reutotal);
		$tmpl->parse('browser');
	}

	if ($IdiomView=='esp') {
		$tmpl->setVariable('ACTIVAR','ACTIVAR');
	}else{
		$tmpl->setVariable('ACTIVAR','ACTIVATE');
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);
	$tmpl->show();

?>
