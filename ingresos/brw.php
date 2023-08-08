<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma

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
	$fltusuacc 		= (isset($_POST['fltusuacc']))? trim($_POST['fltusuacc']):'';
	$fltapelli 		= (isset($_POST['fltapelli']))? trim($_POST['fltapelli']):'';
	$fltcompan 		= (isset($_POST['fltcompan']))? trim($_POST['fltcompan']):'';
	$fltcorreo 		= (isset($_POST['fltcorreo']))? trim($_POST['fltcorreo']):'';
	$fltorden 		= (isset($_POST['fltorden']))? trim($_POST['fltorden']):'';
	$fltordentipo 	= (isset($_POST['fltordentipo']))? trim($_POST['fltordentipo']):'';
	$fltestado 		= (isset($_POST['fltestado']))? trim($_POST['fltestado']):'';
	$fltpertipo 	= (isset($_POST['fltpertipo']))? trim($_POST['fltpertipo']):'';
	$fltperclase 	= (isset($_POST['fltperclase']))? trim($_POST['fltperclase']):'';

	$colorpermSI 	= '#0BE000';
	$colorpermNO 	= '#FF581B';

	$tmpl->setVariable('colorPermisoSI'	, $colorpermSI);
	$tmpl->setVariable('colorPermisoNO'	, $colorpermNO);

	$where = ' 1=1 ';
	//Nombre
	if($fltusuacc!=''){
		$where .= " AND P.PERUSUACC CONTAINING '$fltusuacc' ";
	}
	//Nombre
	if($fltnombre!=''){
		$where .= " AND P.PERNOMBRE CONTAINING '$fltnombre' ";
	}
	//Correo
	if($fltcorreo!=''){
		$where .= " AND P.PERCORREO CONTAINING '$fltcorreo' ";
	}
	//Apellido
	if($fltapelli!=''){
		$where .= " AND P.PERAPELLI CONTAINING '$fltapelli' ";
	}
	//Compañia
	if($fltcompan!=''){
		$where .= " AND P.PERCOMPAN CONTAINING '$fltcompan' ";
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

	$orden = ' ORDER BY T.CONTFCH ';
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
		
	}


	$query = "	SELECT FIRST $pageSalto SKIP $pageNumero P.PERCODIGO,SUBSTRING(P.PERNOMBRE FROM 1 FOR 15) AS PERNOMBRE,SUBSTRING(P.PERAPELLI FROM 1 FOR 15) AS PERAPELLI,SUBSTRING(P.PERCOMPAN FROM 1 FOR 15) AS PERCOMPAN,P.ESTCODIGO,P.PERAVATAR,T.CONTFCH,T.PERCONTROL
				FROM PER_MAEST P
				LEFT OUTER JOIN CONT_INGRESO T ON T.PERCODIGO=P.PERCODIGO
				WHERE T.PERCODIGO=P.PERCODIGO AND $where
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
		$nombrecontrol	= trim($row['PERCONTROL']);
		$fechaingreso     = BDConvFch($row['CONTFCH']);

		$viewlibbtn = 'none';
		$viewmailbtn = 'none';
		if($estcodigo==9){ //Sin mail confirmado
			$viewmailbtn='';
		}else if($estcodigo==8){ //Apto para liberar
			$viewlibbtn='';
		}

	
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('percodigo'	, $percodigo);
		$tmpl->setVariable('pernombre'	, $pernombre);
		$tmpl->setVariable('perapelli'	, $perapelli);
		$tmpl->setVariable('percompan'	, $percompan);
		$tmpl->setVariable('nombrecontrol'	, $nombrecontrol);
	
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

		
		$haux = date('H:i', strtotime('+10800 seconds', strtotime(substr($fechaingreso, 10, 6)))); //Pongo la hora en Huso horario 0
		$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$agehorini = $haux;
		$newDate	= substr($fechaingreso, 6, 4) . '-' . substr($fechaingreso, 3, 2) . '-' . substr($fechaingreso, 0, 2).' '.$agehorini; //Formato calendario (yyyy-mm-dd)
	
		$tmpl->setVariable('fechaingreso'	, $newDate);

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
