<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('mst.html');
	DDIdioma($tmpl);
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$prereg = (isset($_POST['prereg']))? trim($_POST['prereg']) : 0;
	
	
	$imgnull = '../app-assets/img/pages/sativa.png';
	$tmpl->setVariable('imgnull', $imgnull );
	$percodexp = 0;
	$precatego='';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	
	if($prereg!=0){
		$query = "	SELECT PREREG, PRETITULO, PREDESCRI, PREIMG, PREURL, PERCODIGO, PRECATEGO, EXPREG,PREESTADO, PREFUENTE, PREBAJADA, PREFECHA, PRETIPO, PRETAMANO,PREN_ORD
					FROM PREN_MAEST
					WHERE PREREG = $prereg
					ORDER BY PREREG ";
		
		$Table = sql_query($query,$conn);
		if($Table->Rows_Count>0){
			$row= $Table->Rows[0];
			$prereg 		= trim($row['PREREG']);
			$pretitulo 		= trim($row['PRETITULO']);
			$predescri 		= trim($row['PREDESCRI']);
			$preimg		    = trim($row['PREIMG']);
			$percodexp     	= trim($row['PERCODIGO']);
			$preurl    		= trim($row['PREURL']);
			$precatego    	= trim($row['PRECATEGO']);
			$expreg     	= trim($row['EXPREG']);
			$preestado    	= trim($row['PREESTADO']);
			$prefuente     	= trim($row['PREFUENTE']);
			$prebajada    	= trim($row['PREBAJADA']);
			$prefecha    	= BDConvFch($row['PREFECHA']);
			$pretipo     	= trim($row['PRETIPO']);
			$pretamano    	= trim($row['PRETAMANO']);
			$prepos    		= trim($row['PREN_ORD']);


			$prefecha	= substr($prefecha,6,4).'-'.substr($prefecha,3,2).'-'.substr($prefecha,0,2);


			$tmpl->setVariable('prereg'			, $prereg);
			$tmpl->setVariable('pretitulo'		, $pretitulo);
			htmlspecialchars_decode($predescri);
			$tmpl->setVariable('predescri'	, htmlspecialchars_decode($predescri));
			$tmpl->setvariable('preimg'			, $preimg);
			$tmpl->setvariable('percodigopre'	, $percodexp);
			$tmpl->setvariable('preurl'			, $preurl);
			$tmpl->setvariable('precatego'		, $precatego);
			
			$tmpl->setvariable('expreg'			, $expreg);
			$tmpl->setvariable('preestado'		, $preestado);
			$tmpl->setvariable('prefuente'			, $prefuente);
			$tmpl->setvariable('prebajada'	, $prebajada);
			$tmpl->setvariable('prefecha'			, $prefecha);
			$tmpl->setvariable('pretipo'		, $pretipo);
			$tmpl->setvariable('prepos'		, $prepos);
			if ($pretipo == 1) {
				$tmpl->setVariable('selectednota', 'selected');
			}
			if ($pretipo == 2) {
				$tmpl->setVariable('selectedpubli', 'selected');
			}
			
			$tmpl->setvariable('pretamano'			, $pretamano);
			if ($pretamano == 1) {
				$tmpl->setVariable('selectedchico', 'selected');
			}
			if ($pretamano == 2) {
				$tmpl->setVariable('selectedmediano', 'selected');
			}
			if ($pretamano == 3) {
				$tmpl->setVariable('selectedgrande', 'selected');
			}
			
		
		}
	}


	$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1 AND PERTIPO=57
				ORDER BY PERCOMPAN ";
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$percod 	= trim($row['PERCODIGO']);
	$pernombre	= trim($row['PERNOMBRE']);
	$perapelli	= trim($row['PERAPELLI']);
	$percompan	= trim($row['PERCOMPAN']);


	$tmpl->setCurrentBlock('perfiles');
	$tmpl->setVariable('percodigo', $percod);
	$tmpl->setVariable('pernombre', $pernombre);
	$tmpl->setVariable('perapelli', $perapelli);
	$tmpl->setVariable('percompan', $percompan);
	if ($percodexp == $percod) {
		$tmpl->setVariable('persel', 'selected');
	}
	$tmpl->parse('perfiles');
}

//categorias
$categorias ="SELECT  CATREG,CATDESCRI
FROM PRE_CAT";

$Table_categorias = sql_query($categorias, $conn); 

for ($index_categorias = 0; $index_categorias < $Table_categorias->Rows_Count; $index_categorias++) {

	$row_cateogoria = $Table_categorias->Rows[$index_categorias];

	$catreg 		= trim($row_cateogoria['CATREG']);
	$catdescri 		= trim($row_cateogoria['CATDESCRI']);

	$tmpl->setCurrentBlock('categorias');

	if($precatego == $catdescri){
	$tmpl->setVariable('selected'		,'selected');
	}
	$tmpl->setVariable('catdescri'		,$catdescri);
	$tmpl->setVariable('catreg'			,$catreg);
	$tmpl->parse('categorias');


}

	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
