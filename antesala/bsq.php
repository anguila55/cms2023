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
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc = (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$perpasacc = (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
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
	//--------------------------------------------------------------------------------------------------------------
	$fltbuscar 	= (isset($_GET['T']))? $_GET['T']:1;
	$tmpl->setVariable('fltbuscar'	, $fltbuscar);

	$conn= sql_conectar();//Apertura de Conexion
	
	$query = "	SELECT AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG,AGEYOULNK
				FROM AGE_MAEST A 
				WHERE A.AGEREG=$fltbuscar ";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
		$row = $Table->Rows[0];
		$agereg 	= trim($row['AGEREG']);
		$ageyoulnk 	= trim($row['AGEYOULNK']);
		$agetitulo 	= trim($row['AGETITULO']);
		$agedescri 	= trim($row['AGEDESCRI']);
		$agelugar   = trim($row['AGELUGAR']);
		$spkreg   	= trim($row['SPKREG']);
		$agefch     = BDConvFch($row['AGEFCH']);
		$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
		$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

		//$agefch	= substr($agefch, 0, 2). '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 6, 4) ; //Formato calendario (yyyy-mm-dd)
		$agehorini = ($agehorini != '') ?  $agehorini : '';
		$agehorfin = ($agehorfin != '') ?  $agehorfin : '';
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
	
	
							
							$tmpl->setCurrentBlock('spkimg');
						
							$tmpl->setVariable('spkimg', $spkimg);
							$tmpl->setVariable('skpnombre', $spknombre);
	
							if($count == 0){
								$tmpl->setVariable('imagespk', '40px');
							}else{
								$tmpl->setVariable('imagespk', '40px');
								
							}
							$count =1;
							$tmpl->setVariable('spkreg', $spkregnew);;
							$tmpl->parse('spkimg');
	
						}
					}
				}

				if($agefch<'23.08.2020'){
					if ($ageyoulnk != '') {
						$tmpl->setVariable('ageyoulnk', $ageyoulnk);
						$tmpl->setVariable('verageopc', 1);
						
					}else{
						
		
					}
				}
				if($agefch>'23.08.2020' && $peradmin!=1){
					if ($ageyoulnk != '') {
						$tmpl->setVariable('ageyoulnk', $ageyoulnk);
						$tmpl->setVariable('verageopc', 0);
						
						//Verifico si el perfil posee acceso a esta cuenta, para mostrar mensaje
						$queryTik = "SELECT PERTIKREG FROM PER_TICK_SALD WHERE PERCODIGO=$percodigo AND AGEREG=$agereg  ";
						$TableTik = sql_query($queryTik, $conn);
						if($TableTik->Rows_Count>0){ //Posee un ticket para ver la charla
							$tmpl->setVariable('verageopc', 1);
						}
						
					}else{
						
		
					}
				}
		$tmpl->setVariable('agereg'	, $agereg);
		$tmpl->setVariable('agetitulo'	, $agetitulo);
		$tmpl->setVariable('agedescri', $agedescri);
		$tmpl->setvariable('agelugar', $agelugar);
		$tmpl->setVariable('agehorini', $agehorini);
		$tmpl->setVariable('agehorfin', $agehorfin);
		$tmpl->setVariable('agefch', $agefch);
	}
	//--------------------------------------------------------------------------------------------------------------
	

	$tmpl->setVariable('view1'	, 'display:none;'	);
	$tmpl->setVariable('view2'	, 'display:none;'	);
	$tmpl->setVariable('view3'	, 'display:none;'	);
	$tmpl->setVariable('view4'	, 'display:none;'	);
	$tmpl->setVariable('view5'	, 'display:none;'	);
	$tmpl->setVariable('view6'	, 'display:none;'	);
	$tmpl->setVariable('view7'	, 'display:none;'	);
	$tmpl->setVariable('view8'	, 'display:none;'	);
	
	if($agelugar == 'Plenaria 1'){ //SALAS EN ORDEN DEL INDEX 
		$tmpl->setVariable('view1'	, 'display:true;'	);
	}elseif($agelugar == 'Plenaria 2'){ //
		$tmpl->setVariable('view2'	, 'display:true;'	);
		}elseif ($agelugar == 'Taller CQ 1'){ //
			$tmpl->setVariable('view3'	, 'display:true;'	);
			}elseif ($agelugar == 'Taller CQ 2'){ //
				$tmpl->setVariable('view4'	, 'display:true;'	);
				}elseif ($agelugar == 'Taller CQ 3'){ //
					$tmpl->setVariable('view5'	, 'display:true;'	);
					}elseif ($agelugar == 'Taller CQ 4'){ //
						$tmpl->setVariable('view6'	, 'display:true;'	);
						}elseif ($agelugar == 'Taller CQ 5'){ //
							$tmpl->setVariable('view7'	, 'display:true;'	);
							}elseif ($agelugar == 'Taller CQ 6'){ //
								$tmpl->setVariable('view8'	, 'display:true;'	);
								}




	sql_close($conn);	
	$tmpl->show();
	
?>	
