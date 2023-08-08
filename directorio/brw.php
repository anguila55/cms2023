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
	//Diccionario de idiomas
	DDIdioma($tmpl);
	
	
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$peradmin 	= (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
	$percodlog 	= (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre 	= (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	$perapelli 	= (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
	$perusuacc 	= (isset($_SESSION[GLBAPPPORT.'PERUSUACC']))? trim($_SESSION[GLBAPPPORT.'PERUSUACC']) : '';
	$percorreo 	= (isset($_SESSION[GLBAPPPORT.'PERCORREO']))? trim($_SESSION[GLBAPPPORT.'PERCORREO']) : '';
	$pertipolog = (isset($_SESSION[GLBAPPPORT.'PERTIPO']))? trim($_SESSION[GLBAPPPORT.'PERTIPO']) 	  : '';
	$perclaselog= (isset($_SESSION[GLBAPPPORT.'PERCLASE']))? trim($_SESSION[GLBAPPPORT.'PERCLASE'])   : '';
	
	$pathimagenes = '../perimg/';
	$imgAvatarNull = '../app-assets/img/avatar.png';
	
	$fltbuscar 		= (isset($_POST['fltbuscar']))? trim($_POST['fltbuscar']) : '';
	$empresa 		= (isset($_POST['empresa']))? trim($_POST['empresa']) : '';
	$sectores 		= (isset($_POST['sectores']))? $_POST['sectores'] : '0';
	$paises 		= (isset($_POST['paises']))? $_POST['paises'] : '0';
	
	$subsectores 	= (isset($_POST['subsectores']))? $_POST['subsectores'] : '0';
	$categorias 	= (isset($_POST['categorias']))? $_POST['categorias'] : '0';
	$subcategorias 	= (isset($_POST['subcategorias']))? $_POST['subcategorias'] : '0';
	$fltrecomendado	= (isset($_POST['fltrecomendado']))? trim($_POST['fltrecomendado']) : 0;
	$expreg	= (isset($_POST['expreg']))? trim($_POST['expreg']) : 0;
	$fltfavoritos	= (isset($_POST['fltfavoritos']))? trim($_POST['fltfavoritos']) : 0;
	$fltpertipo 	= (isset($_POST['fltpertipo']))? trim($_POST['fltpertipo']) : '';
	$fltpervencomfiltro 	= (isset($_POST['fltpervencomfiltro']))? trim($_POST['fltpervencomfiltro']) : '';
	$fltparticipacion 	= (isset($_POST['fltparticipacion']))? trim($_POST['fltparticipacion']) : '';

	//Paginacion
	$pageNumero		= (isset($_POST['pageNumero']))? $_POST['pageNumero'] : '0';
	$pageSalto		= 60;
	$pageNumero 	= $pageNumero *$pageSalto;
	$new_time = date("Y-m-d H:i:s", strtotime('-3 hours'));
	$conn= sql_conectar();//Apertura de Conexion

	if ($pageNumero>0){
		$tmpl->setVariable('displaytotalasistentes'	, 'd-none' );
		//$tmpl->setVariable('displaytotalasistentesvisibles'	, 'd-none' );
	}
	/// habilito chat y reuniones
	$query = " SELECT PERUSAREU,PERUSACHA
						FROM PER_CLASE
						WHERE PERCLASE=$perclaselog AND ESTCODIGO=1";

		$Table = sql_query($query, $conn);

		if ($Table->Rows_Count > 0) {
			$row = $Table->Rows[0];
			$perusareu	= trim($row['PERUSAREU']);
		    $perusamsg 	= trim($row['PERUSACHA']);

		}

	////
	
	//Filtro de Recomendados aplicados
	if($fltrecomendado==1){
		//Filtro segun Compra-Venta
		
			$whereVenComPerLog = "PERVENCOM IN ('V','A') ";
			$whereVenComPerBsq = "PERVENCOM IN ('C','A') ";
		
	
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
		$perfilLog = array();
		//Cargo la Clasificacion de productos del Perfil logueado que VENDE, para compararlos con los que compran (segun filtro)
		$query = "	SELECT S.SECCODIGO,S.SECDESCRI,PS.PERVENCOM
					FROM PER_SECT PS
					LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
					WHERE PS.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 
						AND (PS.$whereVenComPerLog OR PS.$whereVenComPerBsq)";
		$TableSect = sql_query($query,$conn);
		for($i=0; $i<$TableSect->Rows_Count; $i++){
			$rowSect= $TableSect->Rows[$i];
			$seccodigo = trim($rowSect['SECCODIGO']);
			$secdescri = trim($rowSect['SECDESCRI']);
			$secvalor = trim($rowSect['PERVENCOM']);
			
			$perfilLog[$seccodigo]['EXISTS'] = $secvalor;
			
			//Busco si tiene un siguiente nivel / SubSector
			$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES,PSB.PERVENCOM
								FROM PER_SSEC PSB
								LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
								WHERE PSB.PERCODIGO=$percodlog AND SB.SECCODIGO=$seccodigo AND SB.ESTCODIGO<>3
								AND (PSB.$whereVenComPerLog OR PSB.$whereVenComPerBsq) ";
			$TableSSect = sql_query($querySectSub,$conn);
			for($j=0; $j<$TableSSect->Rows_Count; $j++){
				$rowSSect= $TableSSect->Rows[$j];
				$secsubcod = trim($rowSSect['SECSUBCOD']);
				$secsubdes = trim($rowSSect['SECSUBDES']);
				$secsubvalor = trim($rowSSect['PERVENCOM']);
				
				$perfilLog[$seccodigo][$secsubcod]['EXISTS'] = $secsubvalor;
				
				//Busco si tiene un siguiente nivel / Categorias
				$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI,PC.PERVENCOM
								FROM PER_CATE PC
								LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
								WHERE PC.PERCODIGO=$percodlog AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 
								AND (PC.$whereVenComPerLog OR PC.$whereVenComPerBsq) ";

				$TableCat = sql_query($queryCat,$conn);
				for($k=0; $k<$TableCat->Rows_Count; $k++){
					$rowCat= $TableCat->Rows[$k];
					$catcodigo = trim($rowCat['CATCODIGO']);
					$catdescri = trim($rowCat['CATDESCRI']);
					$catvalor = trim($rowCat['PERVENCOM']);
					
					
					$perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS'] = $catvalor;
					
					//Busco si tiene un siguiente nivel / SubCategorias
					$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES,PSC.PERVENCOM
										FROM PER_SCAT PSC
										LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
										WHERE PSC.PERCODIGO=$percodlog AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 
										AND (PSC.$whereVenComPerLog OR PSC.$whereVenComPerBsq) ";

					$TableCatSub = sql_query($queryCatSub,$conn);
					for($m=0; $m<$TableCatSub->Rows_Count; $m++){
						$rowCatSub= $TableCatSub->Rows[$m];
						$catsubcod = trim($rowCatSub['CATSUBCOD']);
						$catsubdes = trim($rowCatSub['CATSUBDES']);
						$catsubvalor = trim($rowCatSub['PERVENCOM']);
						
						$perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS'] = $catsubvalor;
					}
				}
			}
		}
		//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	}
	
	$where = '';
	
	if ($peradmin!=1){

		$vertipo = '';
		$verclase = '';

		$query = "SELECT PERTIPOPERM,PERTIPO,PERTIPCLA,PERTIPDST, PERCLADST  
					FROM PER_TIPO_PERM 
					WHERE (PERTIPO = $pertipolog AND  PERTIPCLA = $perclaselog)
					ORDER BY PERTIPOPERM";


	
		$Table = sql_query($query, $conn);
		
		
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			
			$row = $Table->Rows[$i];
			$pertipdst 	= trim($row['PERTIPDST']);
			$percladst 	= trim($row['PERCLADST']);
			$pertipoperm= trim($row['PERTIPOPERM']);
		
			
			$vertipo .= $pertipdst.',';
			
			$verclase .= $percladst.',';
		}
		
		$vertipo .= '0';
		$verclase .= '0';

		$where .= " AND (P.PERTIPO IN ($vertipo) AND P.PERCLASE IN ($verclase)) ";
	}
	

	
	//Me muestra solo los perfiles relacionados a ese expositor
	if ($expreg!=0){

		$where .= " AND EXISTS(SELECT 1 FROM EXP_PER EP WHERE EP.PERCODIGO=P.PERCODIGO AND EP.EXPREG=$expreg ) ";
	}

	if($fltbuscar!=''){
		$where .= " AND (P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar' OR P.PERCARGO CONTAINING '$fltbuscar' OR P.PEREMPDES CONTAINING '$fltbuscar' OR P.PERCIUDAD CONTAINING '$fltbuscar' OR P.PERESTADO CONTAINING '$fltbuscar' OR (EXISTS(SELECT 1 FROM TBL_PAIS TP WHERE TP.PAICODIGO=P.PAICODIGO AND P.PAICODIGO IS NOT NULL AND ( TP.PAIDESCRI CONTAINING '$fltbuscar' OR TP.PAIDESCRIING CONTAINING '$fltbuscar')))) ";
	}
	if($empresa!=''){
		$where .= " AND P.PERCOMPAN CONTAINING '$empresa' ";
	}
	if($paises != '0'){
		$where .= " AND P.PAICODIGO IN ($paises) ";
	}
	if($fltpervencomfiltro==1){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SECT S WHERE S.PERCODIGO=P.PERCODIGO AND S.PERVENCOM IN ('V','A') ) ";
	}
	if($fltpervencomfiltro==2){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SECT S WHERE S.PERCODIGO=P.PERCODIGO AND S.PERVENCOM IN ('C','A') ) ";
	}
	if($sectores!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SECT S WHERE S.PERCODIGO=P.PERCODIGO AND S.SECCODIGO IN ($sectores) ) ";
	}
	if($subsectores!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SSEC SS WHERE SS.PERCODIGO=P.PERCODIGO AND SS.SECSUBCOD IN ($subsectores) ) ";
	}
	if($categorias!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_CATE C WHERE C.PERCODIGO=P.PERCODIGO AND C.CATCODIGO IN ($categorias) ) ";
	}
	if($subcategorias!='0'){
		$where .= " AND EXISTS(SELECT 1 FROM PER_SCAT CS WHERE CS.PERCODIGO=P.PERCODIGO AND CS.CATSUBCOD IN ($subcategorias) ) ";
	}
	if($fltfavoritos==1){
		$where .= " AND EXISTS(SELECT 1 FROM PER_FAVO F WHERE F.PERCODIGO=$percodlog AND F.PERCODFAV=P.PERCODIGO) ";
	}
	if($fltpertipo!=''){
		$where .= " AND P.PERTIPO=$fltpertipo ";
	}
	if($fltparticipacion !=''){
		if($fltparticipacion == 0){
			$where .= " AND P.TIPO<>1 ";	
		}else if($fltparticipacion == 1){
			$where .= " AND P.TIPO<>0 ";	
		}else{
			
		}
		
	}


// Cuento el total de asistentes
$querytotales = "	SELECT PERCODIGO
FROM PER_MAEST P
WHERE P.ESTCODIGO=1 AND P.PERCODIGO<>$percodlog $where";

$Tabletotales = sql_query($querytotales,$conn);
$totalasistentes = $Tabletotales->Rows_Count;

if($totalasistentes == -1){
	$totalasistentes=0;
}

if($IdiomView=='ING'){
$tmpl->setVariable('totalasistentes'	,''.$totalasistentes.' participants found' );
}else if ($IdiomView=='ESP'){
$tmpl->setVariable('totalasistentes'	, ''.$totalasistentes.' asistentes encontrados' );
}else{
$tmpl->setVariable('totalasistentes'	, ''.$totalasistentes.' assistentes encontrados' );
}


	
	$campo = ($IdiomView=='ING')? 'P.PERDESING' : 'P.PEREMPDES';

	$where1='';
	if ($fltrecomendado==1){
	}else{
		$where1 .= " FIRST $pageSalto SKIP $pageNumero";
	}
	$query = "	SELECT $where1 P.PERCARGO,P.PERFAC,P.PERTWI,P.PERURLWEB,P.PERINS, P.PERCODIGO,P.PERNOMBRE,P.PERAPELLI,P.PERCOMPAN,PERAVATAR,P.PERTIPO,P.PERLINKED,P.PERURLWEB,
						COALESCE((	SELECT 1
									FROM PER_FAVO F
									WHERE F.PERCODIGO=$percodlog AND F.PERCODFAV=P.PERCODIGO),0) AS ESFAVO
				FROM PER_MAEST P
				WHERE P.ESTCODIGO=1 AND P.PERCODIGO<>$percodlog AND P.PERCODIGO NOT IN ($arraysuperadmin1) $where
				ORDER BY P.PERNOMBRE ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$percodigo 	= trim($row['PERCODIGO']);
		$percargo 	= trim($row['PERCARGO']);
		$pernombre	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
		$percompan	= trim($row['PERCOMPAN']);
		// $perempdes	= trim($row['PEREMPDES']);
		$peravatar	= trim($row['PERAVATAR']);
		$esfavo		= trim($row['ESFAVO']);
		$pertipo	= trim($row['PERTIPO']);
		$perins		= trim($row['PERINS']);
		$pertwi		= trim($row['PERTWI']);
		$perfac		= trim($row['PERFAC']);
		$perlinked  = trim($row['PERLINKED']);
		$perurlweb  = trim($row['PERURLWEB']);

		$match 		= true;
		
		
		//Filtro de Recomendados aplicados
		if($fltrecomendado==1){
			$match = false;
		
			//Busco todos los sectores que tiene el perfil que COMPRA
			$query = "	SELECT S.SECCODIGO,S.SECDESCRI,PS.PERVENCOM
						FROM PER_SECT PS
						LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
						WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 
						AND (PS.$whereVenComPerLog OR PS.$whereVenComPerBsq) ";
			$TableSect = sql_query($query,$conn);
			for($n=0; $n<$TableSect->Rows_Count; $n++){
				$rowSect= $TableSect->Rows[$n];
				$seccodigo = trim($rowSect['SECCODIGO']);
				$secdescri = trim($rowSect['SECDESCRI']);
				$secvalor = trim($rowSect['PERVENCOM']);
				
				//Busco si tiene un siguiente nivel / SubSector
				$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES,PSB.PERVENCOM
									FROM PER_SSEC PSB
									LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
									WHERE PSB.PERCODIGO=$percodigo AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 
									AND (PSB.$whereVenComPerLog OR PSB.$whereVenComPerBsq) ";
				$TableSSect = sql_query($querySectSub,$conn);
				for($j=0; $j<$TableSSect->Rows_Count; $j++){
					$rowSSect= $TableSSect->Rows[$j];
					$secsubcod = trim($rowSSect['SECSUBCOD']);
					$secsubdes = trim($rowSSect['SECSUBDES']);
					$secsubvalor = trim($rowSSect['PERVENCOM']);
					
					//Busco si tiene un siguiente nivel / Categorias
					$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI,PC.PERVENCOM
									FROM PER_CATE PC
									LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
									WHERE PC.PERCODIGO=$percodigo AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 
									AND (PC.$whereVenComPerLog OR PC.$whereVenComPerBsq) ";

					$TableCat = sql_query($queryCat,$conn);
					for($k=0; $k<$TableCat->Rows_Count; $k++){
						$rowCat= $TableCat->Rows[$k];
						$catcodigo = trim($rowCat['CATCODIGO']);
						$catdescri = trim($rowCat['CATDESCRI']);
						$catvalor = trim($rowCat['PERVENCOM']);
						
						//Busco si tiene un siguiente nivel / SubCategorias
						$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES,PSC.PERVENCOM
											FROM PER_SCAT PSC
											LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
											WHERE PSC.PERCODIGO=$percodigo AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 
											AND (PSC.$whereVenComPerLog OR PSC.$whereVenComPerBsq) ";

						$TableCatSub = sql_query($queryCatSub,$conn);
						for($m=0; $m<$TableCatSub->Rows_Count; $m++){
							$rowCatSub= $TableCatSub->Rows[$m];
							$catsubcod = trim($rowCatSub['CATSUBCOD']);
							$catsubdes = trim($rowCatSub['CATSUBDES']);
							$catsubvalor = trim($rowCatSub['PERVENCOM']);
							
							if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod])){
								if (($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS'] == 'A') || ($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS']!= $catsubvalor))
								{
									$match = true;
								}
								
							}
						}
						if($TableCatSub->Rows_Count==-1){
							if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo])){
								if (($perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS'] == 'A') || ($perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS']!= $catvalor))
								{
									$match = true;
								}
							}
						}
					}
					if($TableCat->Rows_Count==-1){
						if(isset($perfilLog[$seccodigo][$secsubcod])){
							if (($perfilLog[$seccodigo][$secsubcod]['EXISTS'] == 'A') || ($perfilLog[$seccodigo][$secsubcod]['EXISTS']!= $secsubvalor))
								{
									$match = true;
								}
						}
					}
				}
				
				if($TableSSect->Rows_Count==-1){
					if(isset($perfilLog[$seccodigo])){
						if (($perfilLog[$seccodigo]['EXISTS'] == 'A') || ($perfilLog[$seccodigo]['EXISTS']!= $secvalor))
								{
									$match = true;
								}
					}
				}
			}
		}

		if($perfac ==""){
			$perfac = "";
		}else{
			if(strpos($perfac, "https://") === false){
				$perfac = "href='https://". $perfac."'";
			}else{
				$perfac = "href='". $perfac."'";
			}
		}
		if($perins ==""){
			$perins = "";
		}else{
			if(strpos($perins, "https://") === false){
				$perins = "href='https://". $perins."'";
			}else{
				$perins = "href='". $perins."'";
			}
		}
		if($pertwi ==""){
			$pertwi = "";
		}else{
			if(strpos($pertwi, "https://") === false){
				$pertwi = "href='https://". $pertwi."'";
			}else{
				$pertwi = "href='". $pertwi."'";
			}
		}
		if($perlinked ==""){
			$perlinked = "";
		}else{
			if(strpos($perlinked, "https://") === false){
				$perlinked = "href='https://". $perlinked."'";
			}else{
				$perlinked = "href='". $perlinked."'";
			}
		}
		if($perurlweb ==""){
			$perurlweb = "";
		}else{
			if(strpos($perurlweb, "https://") === false){
				$perurlweb = "href='https://". $perurlweb."'";
			}else{
				$perurlweb = "href='". $perurlweb."'";
			}
			
		}
		
		
		if($match){
			$tmpl->setCurrentBlock('browser');
			
			$tmpl->setVariable('percodigo'	, $percodigo);
			$tmpl->setVariable('percargo'	, $percargo);
			$tmpl->setVariable('pernombre'	, $pernombre);
			$tmpl->setVariable('perapelli'	, $perapelli);
			$tmpl->setVariable('percompan'	, $percompan);
			// $tmpl->setVariable('perempdes'	, $perempdes);
			$tmpl->setVariable('pertipo'	, $pertipo);
			$tmpl->setVariable('perfac'	,	$perfac);
			$tmpl->setVariable('pertwi'	, 	$pertwi);
			$tmpl->setVariable('perins'	, 	$perins);
			$tmpl->setVariable('perlinked'	, 	$perlinked);
			$tmpl->setVariable('perurlweb'	, 	$perurlweb);

			if ($IdiomView == 'ESP'){
				$tmpl->setVariable('title1'	, "Agendar una reunión"	);
				$tmpl->setVariable('title2'	, "Más información");
				$tmpl->setVariable('title3'	, "Escribir un mensaje");
				}else if($IdiomView == 'POR'){
					$tmpl->setVariable('title1'	, "Agendar uma reunião"	);
					$tmpl->setVariable('title2'	, "Mais informações");
					$tmpl->setVariable('title3'	, "Escreva uma mensagem");
				}else if($IdiomView == 'ING'){
					$tmpl->setVariable('title1'	, "Request a meeting"	);
				$tmpl->setVariable('title2'	, "More information");
				$tmpl->setVariable('title3'	, "Write a message");
				}

			$hallcomercial = ($IdiomView=='ING')? 'Go to Stand' : 'Ir a Stand';
			$tmpl->setVariable('hallcomercial3'	, 	$hallcomercial);

			//Es favorito
			$tmpl->setVariable('esfavorito'	, $esfavo);
			if($esfavo==1){
				//logerror('entrocorazon lleno');
				$tmpl->setVariable('colorfavo'	, 'fa-star');
			
			}else{
				//logerror('entrocorazon vacio');
				
				$tmpl->setVariable('colorfavo'	, 'fa-star-o');
			}

			//busco sponsor

		$querySponsor =  "SELECT EXPREG FROM EXP_PER WHERE PERCODIGO = $percodigo";
		$TablSponsor = sql_query($querySponsor,$conn);

		$tmpl->setVariable('display-sponsor'		, 'hidden');

		if ($TablSponsor->Rows_Count != -1) {
			
			$rowsponsor= $TablSponsor->Rows[0];
			$expreg1 	= trim($rowsponsor['EXPREG']);
			$tmpl->setVariable('display-sponsor'		, '');
			$tmpl->setVariable('sponsorcod'		,$expreg1 );

		}

		//busco sponsor

		$querySponsorcod =  "SELECT EXPREG FROM EXP_MAEST WHERE PERCODIGO = $percodigo";
		$TablSponsorcod = sql_query($querySponsorcod,$conn);

		$tmpl->setVariable('display-sponsor'		, 'hidden');

		if ($TablSponsorcod->Rows_Count != -1) {
			
			$rowsponsorcod= $TablSponsorcod->Rows[0];
			$expreg2 	= trim($rowsponsorcod['EXPREG']);
			$tmpl->setVariable('display-sponsor'		, '');
			$tmpl->setVariable('sponsorcod'		,$expreg2 );

		}
			//Busco si ya tengo una reunion con este perfil
			$queryReu = " 	SELECT REUREG 
							FROM REU_CABE 
							WHERE (PERCODSOL=$percodlog OR PERCODDST=$percodlog) AND (PERCODSOL=$percodigo OR PERCODDST=$percodigo) AND REUESTADO<>3 ";
			$TableReu = sql_query($queryReu,$conn);
			if($TableReu->Rows_Count>0){
				////// Muestro el boton todo el tiempo///////////////
				$tmpl->setVariable('btnviewreunion'	, 'display:;'	);
			}

			if($perusareu!=1){
				$tmpl->setVariable('btnviewreunion'	, 'display:none;'	);
			}
			if ($expreg!=0){
				$tmpl->setVariable('btnviewreunion'	, 'display:none;'	);
			}

			if ($perusamsg !=1){
				$tmpl->setVariable('btnviewmsg'	, 'd-none'	);
			}else{
				$tmpl->setVariable('btnviewmsg'	, ''	);
			}
			if($peravatar!=''){
				if(strpos($peravatar, "https://") !== false){

					$tmpl->setVariable('peravatar'	, $peravatar);
				
				}else{
					$tmpl->setVariable('peravatar'	, $pathimagenes.$percodigo.'/'.$peravatar);
				}
				
			}else{
				$tmpl->setVariable('peravatar'	, $imgAvatarNull);
			}
			
			///////////// Ultimos conectados ///////////////////
				$queryonline = "	SELECT PERCODIGO
				FROM PER_MAEST 
				WHERE PERCODIGO=$percodigo AND (PERINGLOG IS NOT NULL) AND (PERULTLOG>='$new_time') AND ESTCODIGO=1";	
			$Tablelinea = sql_query($queryonline, $conn);
			if ($Tablelinea->Rows_Count>0){	

			$tmpl->setVariable('perfilesenlinea'	, 'background:green;');
			}else{
			$tmpl->setVariable('perfilesenlinea'	, "background:red;");
			}
			///////////////////
			$tmpl->parse('browser');
		}
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
