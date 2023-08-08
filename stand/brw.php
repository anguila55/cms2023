<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('brw.html');
//Diccionario de idiomas
DDIdioma($tmpl);


//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodlog 	= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre 	= (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli 	= (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc 	= (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$percorreo 	= (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$perusareu 	= (isset($_SESSION[GLBAPPPORT . 'PERUSAREU'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSAREU']) : '';
$pertipolog = (isset($_SESSION[GLBAPPPORT . 'PERTIPO'])) ? trim($_SESSION[GLBAPPPORT . 'PERTIPO']) 	  : '';
$perclaselog = (isset($_SESSION[GLBAPPPORT . 'PERCLASE'])) ? trim($_SESSION[GLBAPPPORT . 'PERCLASE'])   : '';

$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$imgbanNULL = '../app-assets/img/imgnull.png';



$fltbuscar 		= (isset($_POST['fltbuscar']))? trim($_POST['fltbuscar']) : '';
$empresa 		= (isset($_POST['empresa']))? trim($_POST['empresa']) : '';
$sectores 		= (isset($_POST['sectores']))? $_POST['sectores'] : '0';
$subsectores 		= (isset($_POST['subsectores']))? $_POST['subsectores'] : '0';
$fltrecomendado	= (isset($_POST['fltrecomendado']))? trim($_POST['fltrecomendado']) : 0;


$conn = sql_conectar(); //Apertura de Conexion


$where = '';
if($fltrecomendado==1){
	//Filtro segun Compra-Venta
	
		$whereVenComPerLog = "PERVENCOM IN ('V','A') ";
		$whereVenComPerBsq = "PERVENCOM IN ('C','A') ";
	

	//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	$perfilLog = array();
	//Cargo la Clasificacion de productos del Perfil logueado que VENDE, para compararlos con los que compran (segun filtro)
	$query = "	SELECT S.SECCODIGO,S.SECDESCRI
				FROM PER_SECT PS
				LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
				WHERE PS.PERCODIGO=$percodlog AND S.ESTCODIGO<>3 
					AND (PS.$whereVenComPerLog OR PS.$whereVenComPerBsq)";
	$TableSect = sql_query($query,$conn);
	for($i=0; $i<$TableSect->Rows_Count; $i++){
		$rowSect= $TableSect->Rows[$i];
		$seccodigo = trim($rowSect['SECCODIGO']);
		$secdescri = trim($rowSect['SECDESCRI']);
		
		$perfilLog[$seccodigo]['EXISTS'] = 1;
		
		//Busco si tiene un siguiente nivel / SubSector
		$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
							FROM PER_SSEC PSB
							LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
							WHERE PSB.PERCODIGO=$percodlog AND SB.SECCODIGO=$seccodigo AND SB.ESTCODIGO<>3
							AND (PSB.$whereVenComPerLog OR PSB.$whereVenComPerBsq) ";
		$TableSSect = sql_query($querySectSub,$conn);
		for($j=0; $j<$TableSSect->Rows_Count; $j++){
			$rowSSect= $TableSSect->Rows[$j];
			$secsubcod = trim($rowSSect['SECSUBCOD']);
			$secsubdes = trim($rowSSect['SECSUBDES']);
			
			$perfilLog[$seccodigo][$secsubcod]['EXISTS'] = 1;
			
			//Busco si tiene un siguiente nivel / Categorias
			$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
							FROM PER_CATE PC
							LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
							WHERE PC.PERCODIGO=$percodlog AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 
							AND (PC.$whereVenComPerLog OR PC.$whereVenComPerBsq) ";

			$TableCat = sql_query($queryCat,$conn);
			for($k=0; $k<$TableCat->Rows_Count; $k++){
				$rowCat= $TableCat->Rows[$k];
				$catcodigo = trim($rowCat['CATCODIGO']);
				$catdescri = trim($rowCat['CATDESCRI']);
				
				$perfilLog[$seccodigo][$secsubcod][$catcodigo]['EXISTS'] = 1;
				
				//Busco si tiene un siguiente nivel / SubCategorias
				$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
									FROM PER_SCAT PSC
									LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
									WHERE PSC.PERCODIGO=$percodlog AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 
									AND (PSC.$whereVenComPerLog OR PSC.$whereVenComPerBsq) ";

				$TableCatSub = sql_query($queryCatSub,$conn);
				for($m=0; $m<$TableCatSub->Rows_Count; $m++){
					$rowCatSub= $TableCatSub->Rows[$m];
					$catsubcod = trim($rowCatSub['CATSUBCOD']);
					$catsubdes = trim($rowCatSub['CATSUBDES']);
					
					$perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod]['EXISTS'] = 1;
				}
			}
		}
	}
	//*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
	
}



if($fltbuscar!=''){
	$where .= " AND (EXISTS(SELECT 1
		FROM EXP_PER EP
		LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=EP.PERCODIGO
		WHERE EP.EXPREG=E.EXPREG AND (E.EXPNOMBRE CONTAINING '$fltbuscar' OR P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar')) OR EXISTS(SELECT 1
		FROM EXP_MAEST EPX
		LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=EPX.PERCODIGO
		WHERE EPX.EXPREG=E.EXPREG AND (E.EXPNOMBRE CONTAINING '$fltbuscar' OR P.PERNOMBRE CONTAINING '$fltbuscar' OR P.PERAPELLI CONTAINING '$fltbuscar' OR P.PERCOMPAN CONTAINING '$fltbuscar')) ) ";
}
/*if($empresa!=''){

	$where .= " AND EXISTS(SELECT 1
		FROM EXP_PER EP
		LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO=EP.PERCODIGO
		WHERE EP.EXPREG=E.EXPREG  AND (E.EXPNOMBRE CONTAINING '$empresa' OR P.PERCOMPAN CONTAINING '$empresa')) ";
}*/

if($sectores!=0){
	$where .= " AND (EXISTS(SELECT 1
	FROM EXP_PER EP
	LEFT OUTER JOIN PER_SECT S ON S.PERCODIGO=EP.PERCODIGO
	WHERE EP.EXPREG=E.EXPREG AND S.SECCODIGO IN ($sectores)) OR EXISTS(SELECT 1
	FROM EXP_MAEST EPX
	LEFT OUTER JOIN PER_SECT S ON S.PERCODIGO=EPX.PERCODIGO
	WHERE EPX.EXPREG=E.EXPREG AND S.SECCODIGO IN ($sectores)))";
}
if($subsectores!=0){
	$where .= " AND (EXISTS(SELECT 1
	FROM EXP_PER EP
	LEFT OUTER JOIN PER_SSEC S ON S.PERCODIGO=EP.PERCODIGO
	WHERE EP.EXPREG=E.EXPREG AND S.SECSUBCOD IN ($subsectores)) OR EXISTS(SELECT 1
	FROM EXP_MAEST EPX
	LEFT OUTER JOIN PER_SSEC S ON S.PERCODIGO=EPX.PERCODIGO
	WHERE EPX.EXPREG=E.EXPREG AND S.SECSUBCOD IN ($subsectores)))";
}

$querycategoria = "	SELECT CATDESCRI, CATVALOR, CATREG
				FROM EXP_CAT
				ORDER BY CATVALOR
			 ";
	$Tablecategoria = sql_query($querycategoria,$conn);
	for($t=0; $t<$Tablecategoria->Rows_Count; $t++){
		$rowcategoria = $Tablecategoria->Rows[$t];
		$catdescri 	= trim($rowcategoria['CATDESCRI']);
		$catvalor 	= trim($rowcategoria['CATVALOR']);
		$catreg	= trim($rowcategoria['CATREG']);
		$tmpl->setCurrentBlock('browsercat');
		$tmpl->setVariable('catdescri'	, $catdescri);
		$tmpl->setVariable('catvalor'	, $catvalor);
		$tmpl->setVariable('catreg'		, $catreg);
		

		$query = "SELECT E.EXPREG, E.EXPNOMBRE,E.PERCODIGO,E.EXPBANIMG1,E.EXPBANIMG2,E.EXPAVATAR
		FROM EXP_MAEST AS E
		INNER JOIN EXP_CAT AS C ON  C.CATREG = E.EXPCATEGO
		WHERE ESTCODIGO<>3 AND E.EXPCATEGO=$catreg $where
		ORDER BY E.EXPPOS ASC";
		$Table = sql_query($query, $conn);
		for ($i = 0; $i < $Table->Rows_Count; $i++) {

			$row = $Table->Rows[$i];
			$expreg 	= trim($row['EXPREG']);
			$expnombre 	= trim($row['EXPNOMBRE']);
			$expavatar 	= trim($row['EXPAVATAR']);
			$expbanimg1 = trim($row['EXPBANIMG1']);
			$expbanimg2 = trim($row['EXPBANIMG2']);
			$percodigocontacto = trim($row['PERCODIGO']);
			$percodigo=0;
			//var_dump($explinked,$expfac,$exptwi,$expsinsta); die;
			$match 		= true;
					//Perfiles
			
			//Filtro de Recomendados aplicados
			if($fltrecomendado==1){
				if ($percodigocontacto==''){
					$percodigocontacto=0;
				}
				$match = false;
				$perfiles ="SELECT  PM.PERCODIGO, PM.PERCOMPAN, PM.PERTIPO, PM.PERCLASE
			FROM PER_MAEST PM
			WHERE PM.PERCODIGO=$percodigocontacto";

				$Table_Perfiles = sql_query($perfiles, $conn); 
				for ($perfiles_index = 0; $perfiles_index < $Table_Perfiles->Rows_Count; $perfiles_index++) {

				$row_perfiles = $Table_Perfiles->Rows[$perfiles_index];
				$percodigo 	= trim($row_perfiles['PERCODIGO']);
				$pertipo 	= trim($row_perfiles['PERTIPO']);

				}
				
			
				//Busco todos los sectores que tiene el perfil que COMPRA
				$query = "	SELECT S.SECCODIGO,S.SECDESCRI
							FROM PER_SECT PS
							LEFT OUTER JOIN SEC_MAEST S ON S.SECCODIGO=PS.SECCODIGO
							WHERE PS.PERCODIGO=$percodigo AND S.ESTCODIGO<>3 
							AND (PS.$whereVenComPerLog OR PS.$whereVenComPerBsq) ";
				$TableSect = sql_query($query,$conn);
				for($n=0; $n<$TableSect->Rows_Count; $n++){
					$rowSect= $TableSect->Rows[$n];
					$seccodigo = trim($rowSect['SECCODIGO']);
					$secdescri = trim($rowSect['SECDESCRI']);
					
					//Busco si tiene un siguiente nivel / SubSector
					$querySectSub = "	SELECT SB.SECSUBCOD,SB.SECSUBDES
										FROM PER_SSEC PSB
										LEFT OUTER JOIN SEC_SUB SB ON SB.SECSUBCOD=PSB.SECSUBCOD
										WHERE PSB.PERCODIGO=$percodigo AND SB.SECCODIGO=$seccodigo AND sb.ESTCODIGO<>3 
										AND (PSB.$whereVenComPerLog OR PSB.$whereVenComPerBsq) ";
					$TableSSect = sql_query($querySectSub,$conn);
					for($j=0; $j<$TableSSect->Rows_Count; $j++){
						$rowSSect= $TableSSect->Rows[$j];
						$secsubcod = trim($rowSSect['SECSUBCOD']);
						$secsubdes = trim($rowSSect['SECSUBDES']);
						
						//Busco si tiene un siguiente nivel / Categorias
						$queryCat = "	SELECT C.CATCODIGO,C.CATDESCRI
										FROM PER_CATE PC
										LEFT OUTER JOIN CAT_MAEST C ON C.CATCODIGO=PC.CATCODIGO
										WHERE PC.PERCODIGO=$percodigo AND C.SECSUBCOD=$secsubcod AND C.ESTCODIGO<>3 
										AND (PC.$whereVenComPerLog OR PC.$whereVenComPerBsq) ";

						$TableCat = sql_query($queryCat,$conn);
						for($k=0; $k<$TableCat->Rows_Count; $k++){
							$rowCat= $TableCat->Rows[$k];
							$catcodigo = trim($rowCat['CATCODIGO']);
							$catdescri = trim($rowCat['CATDESCRI']);
							
							//Busco si tiene un siguiente nivel / SubCategorias
							$queryCatSub = "	SELECT CS.CATSUBCOD,CS.CATSUBDES
												FROM PER_SCAT PSC
												LEFT OUTER JOIN CAT_SUB CS ON CS.CATSUBCOD=PSC.CATSUBCOD
												WHERE PSC.PERCODIGO=$percodigo AND CS.CATCODIGO=$catcodigo AND CS.ESTCODIGO<>3 
												AND (PSC.$whereVenComPerLog OR PSC.$whereVenComPerBsq) ";

							$TableCatSub = sql_query($queryCatSub,$conn);
							for($m=0; $m<$TableCatSub->Rows_Count; $m++){
								$rowCatSub= $TableCatSub->Rows[$m];
								$catsubcod = trim($rowCatSub['CATSUBCOD']);
								$catsubdes = trim($rowCatSub['CATSUBDES']);
								
								if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo][$catsubcod])){
									$match = true;
								}
							}
							if($TableCatSub->Rows_Count==-1){
								if(isset($perfilLog[$seccodigo][$secsubcod][$catcodigo])){
									$match = true;
								}
							}
						}
						if($TableCat->Rows_Count==-1){
							if(isset($perfilLog[$seccodigo][$secsubcod])){
								$match = true;
							}
						}
					}
					
					if($TableSSect->Rows_Count==-1){
						if(isset($perfilLog[$seccodigo])){
							$match = true;
						}
					}
				}
			}

			if ($expavatar == '') {
				$expavatar = $imgAvatarNull;
			} else {
				$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
			}
			//var_dump($match);die;
			if($match){
				
					
						$tmpl->setCurrentBlock('oro');
						$tmpl->setVariable('exprego', $expreg);
						$tmpl->setVariable('expnombreo', $expnombre);
						$tmpl->setVariable('expavataro', $expavatar);
						
						if ($expbanimg1 == '') {
							$tmpl->setVariable('expbanimg1o', $imgbanNULL);
							
						}else{

							$tmpl->setVariable('expbanimg1o', '../expimg/' . $expreg . '/' . $expbanimg1);
						}
						if ($expbanimg2 == '') {
							$tmpl->setVariable('expbanimg2o', $imgbanNULL);
						}else{

							$tmpl->setVariable('expbanimg2o', '../expimg/' . $expreg . '/' . $expbanimg2);
						}
						$tmpl->parse('oro');
		
				
			}
		}
		$tmpl->parse('browsercat');
	}

		


sql_close($conn);
$tmpl->show();

?>	




