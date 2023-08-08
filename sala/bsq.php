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
	$percompan = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
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
	$tmpl->setVariable('percorreo'	, $perpasacc	);
	$tmpl->setVariable('percompan'	, $percompan	);
		
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
	$agereg = (isset($_GET['A']))? trim($_GET['A']) : 0;
	$ageyoulnk = '';
	$agetitulo = '';
	$agelugar = '';
	$agefch = '';
	$errcod = 0;
	
	$tmpl->setVariable('agereg'	, $agereg	);
	
	//No hay agenda
	if($agereg==0){
		header('Location: ../index');
		exit;
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	
	
	$tmpl->setVariable('visiblechat', 'd-none');
	$tmpl->setVariable('scriptbrwslido'	, "mostrarinfo();"	);
	$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoDireccion'";
	$Table2 = sql_query($query2, $conn);
	for ($i = 0; $i < $Table2->Rows_Count; $i++) {
		$row = $Table2->Rows[$i];
		$linkslido = trim($row['ZDESCRI']);
		if ($linkslido!=''){
			$tmpl->setVariable('visiblechat', '');
			$tmpl->setVariable('scriptbrwslido'	, "mostrarslido();"	);
		}
			$tmpl->setVariable('linkslido', $linkslido);
		
	}
	$pathimagenes = '../admimg/';
$imgBannerHomeNull	= '../assets-nuevodisenio/img/bannerhome.jpg';
$tmpl->setVariable('imgProductoNull'	, $imgBannerHomeNull 	);
$tmpl->setVariable('displaybannervideo'	, 'd-none' 	);
$query = "	SELECT *
			FROM ADM_IMG
            WHERE BANID>5";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
    $bannerhomeimgchico 	= trim($row['BANNERS']);
	$urlbannerchico 	= trim($row['URLBAN']);
	$estcodigochico 	= trim($row['ESTCODIGO']);
	$banneridchico 	= trim($row['BANID']);
	if ($banneridchico==6){
		if($bannerhomeimgchico==''){ 
			$bannerhomeimgchico = $imgBannerHomeNull;
		}else{
			$bannerhomeimgchico = $pathimagenes.$bannerhomeimgchico;
		}
		if ($estcodigochico==1){
	
			$tmpl->setVariable('displaybannersuperior'	, '' 	);
	
		}else{
	
			$tmpl->setVariable('displaybannersuperior'	, 'd-none' 	);
		}
	
		$tmpl->setVariable('bannerhomeimgsuperior'	, $bannerhomeimgchico	);
		$tmpl->setVariable('urlbannersuperior'	, $urlbannerchico	);

	}
	if ($banneridchico==7){
		if($bannerhomeimgchico==''){ 
			$bannerhomeimgchico = $imgBannerHomeNull;
		}else{
			$bannerhomeimgchico = $pathimagenes.$bannerhomeimgchico;
		}
		if ($estcodigochico==1){
	
			$tmpl->setVariable('displaybannerinferior'	, '' 	);
	
		}else{
	
			$tmpl->setVariable('displaybannerinferior'	, 'd-none' 	);
		}
	
		$tmpl->setVariable('bannerhomeimginferior'	, $bannerhomeimgchico	);
		$tmpl->setVariable('urlbannerinferior'	, $urlbannerchico	);

	}
    

}
	$query = "	SELECT AGEYOULNK, AGETITULO, AGEFCH,AGEDESCRI,SPKREG, AGEHORINI, AGEHORFIN, AGEYOULNKING, AGEYOULNKPOR
				FROM AGE_MAEST A 
				WHERE A.AGEREG=$agereg ";
	$Table = sql_query($query,$conn);
	if($Table->Rows_Count>0){
		$row = $Table->Rows[0];
		$ageyoulnk 	= trim($row['AGEYOULNK']);
		$ageyoulnking 	= trim($row['AGEYOULNKING']);
		$ageyoulnkpor 	= trim($row['AGEYOULNKPOR']);
		$agedescri 	= trim($row['AGEDESCRI']);
		$spkreg   	= trim($row['SPKREG']);
		$agetitulo 	= trim($row['AGETITULO']);
		$agefch     = BDConvFch($row['AGEFCH']);
		$agehorini  = substr(trim($row['AGEHORINI']), 0, 5);
		$agehorfin  = substr(trim($row['AGEHORFIN']), 0, 5);

		//$agefch	= substr($agefch, 0, 2). '-' . substr($agefch, 3, 2) . '-' . substr($agefch, 6, 4) ; //Formato calendario (yyyy-mm-dd)
		// $agehorini = ($agehorini != '') ?  $agehorini : '';
		// $agehorfin = ($agehorfin != '') ?  $agehorfin : '';

		///CAMBIOS DE HORARIOS 

		$haux = date('H:i', strtotime('+10800 seconds', strtotime($agehorini))); //Pongo la hora en Huso horario 0
		$haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$agehorini = $haux;
		$haux2 = date('H:i', strtotime('+10800 seconds', strtotime($agehorfin))); //Pongo la hora en Huso horario 0
		$haux2 = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
		$agehorfin = $haux2;
		///
		
		
		$tmpl->setVariable('colorfiltro0', 'banderanoseleccionada');
		$tmpl->setVariable('colorfiltro1', 'banderanoseleccionada');
		$tmpl->setVariable('colorfiltro2', 'banderanoseleccionada');
		$tmpl->setVariable('visibility0', 'd-none');
		$tmpl->setVariable('visibility1', 'd-none');
		$tmpl->setVariable('visibility2', 'd-none');
		$tmpl->setVariable('visbleconstreaming', 'd-none');
		$tmpl->setVariable('visblesinstreaming', '');
		if ($ageyoulnk != ''){
			$tmpl->setVariable('visibility0', '');
			$tmpl->setVariable('botonlink0', $ageyoulnk);
			$tmpl->setVariable('visbleconstreaming', '');
		$tmpl->setVariable('visblesinstreaming', 'd-none');
		}
		if ($ageyoulnking != '') {
			$tmpl->setVariable('visibility1', '');
			$tmpl->setVariable('botonlink1', $ageyoulnking);
			$tmpl->setVariable('visbleconstreaming', '');
		$tmpl->setVariable('visblesinstreaming', 'd-none');	
			}
		if ($ageyoulnkpor != ''){
			$tmpl->setVariable('visibility2', '');
			$tmpl->setVariable('botonlink2', $ageyoulnkpor);
			$tmpl->setVariable('visbleconstreaming', '');
		$tmpl->setVariable('visblesinstreaming', 'd-none');
				}
		if (($IdiomView=='ESP')){
			
			if ($ageyoulnk != '')
			{
				$tmpl->setVariable('ageyoulnk', $ageyoulnk);
				$tmpl->setVariable('colorfiltro0', 'banderaseleccionada');
			}else{

				if ($ageyoulnking != '') {
				
					$tmpl->setVariable('ageyoulnk', $ageyoulnking);
					$tmpl->setVariable('colorfiltro1', 'banderaseleccionada');
					
				}else{
					
					
					$tmpl->setVariable('ageyoulnk', $ageyoulnkpor);
					$tmpl->setVariable('colorfiltro2', 'banderaseleccionada');
					
				}


			}
				
				

		}else if (($IdiomView=='POR')){

			if ($ageyoulnkpor != '')
			{
				$tmpl->setVariable('ageyoulnk', $ageyoulnkpor);
				$tmpl->setVariable('colorfiltro2', 'banderaseleccionada');
			}else{

				if ($ageyoulnking != '') {
				
					$tmpl->setVariable('ageyoulnk', $ageyoulnking);
					$tmpl->setVariable('colorfiltro1', 'banderaseleccionada');
					
				}else{
					
					
					$tmpl->setVariable('ageyoulnk', $ageyoulnk);
					$tmpl->setVariable('colorfiltro0', 'banderaseleccionada');
					
				}


			}

			
		}else{

			if ($ageyoulnking != '')
			{
				$tmpl->setVariable('ageyoulnk', $ageyoulnking);
				$tmpl->setVariable('colorfiltro1', 'banderaseleccionada');
			}else{

				if ($ageyoulnk != '') {
				
					$tmpl->setVariable('ageyoulnk', $ageyoulnk);
					$tmpl->setVariable('colorfiltro0', 'banderaseleccionada');
					
				}else{
					
					
					$tmpl->setVariable('ageyoulnk', $ageyoulnkpor);
					$tmpl->setVariable('colorfiltro2', 'banderaseleccionada');
					
				}


			}



		}
		
		$tmpl->setVariable('agetitulo'	, $agetitulo);
		$tmpl->setVariable('agehorini', $agehorini);
		$tmpl->setVariable('agehorfin', $agehorfin);
		$tmpl->setVariable('agefch', $agefch);
		$tmpl->setVariable('agedescri', $agedescri);


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



	}
	
	//--------------------------------------------------------------------------------------------------------------
	if($errcod==0){
		$tmpl->setVariable('scriptbrwprofile'	, "showPuntossala($agereg);"	);
		//$tmpl->setVariable('ageyoulnk'	, $ageyoulnk);
		$tmpl->setVariable('agetitulo'	, $agetitulo);
	}
	//--------------------------------------------------------------------------------------------------------------
	
	
	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
