<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/idioma.php';//Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('sponsor.html');
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$expreg = (isset($_POST['expreg'])) ? trim($_POST['expreg']) : 0;
$estcodigo = 1; //Activo por defecto el estado
$pathPlano = '../app/';  



$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';
$tmpl->setVariable('expavatar', $imgAvatarNull);
$tmpl->setVariable('imgAvatarNull', $imgAvatarNull);
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$percodexp = 0;



$query = "	SELECT *
				FROM EXP_MAEST 
				WHERE EXPREG=$expreg  AND ESTCODIGO=1";

$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$expreg 		= trim($row['EXPREG']);
	$expnombre 		= trim($row['EXPNOMBRE']);
	$expweb 		= trim($row['EXPWEB']);
	$expmail 		= trim($row['EXPMAIL']);
	$expstand 		= trim($row['EXPSTAND']);
	$exprubros 		= trim($row['EXPRUBROS']);
	$expposx 		= trim($row['EXPPOSX']);
	$expposy 		= trim($row['EXPPOSY']);
	$estcodigo 		= trim($row['ESTCODIGO']);
	$expavatar 		= trim($row['EXPAVATAR']);
	$expcatego 		= trim($row['EXPCATEGO']);
	$percodexp 		= trim($row['PERCODIGO']);
	$exppos 		= trim($row['EXPPOS']);

	//NUEVOSDATOS
	$explinded	 	= trim($row['EXPLINKED']);
	$expfac 		= trim($row['EXPFAC']);
	$exptwi 		= trim($row['EXPTWI']);
	$expinsta 		= trim($row['EXPINSTA']);
	$expdireccion 	= trim($row['EXPDIRECCION']);
	$exptelefo 		= trim($row['EXPTELEFO']);
	$expbanimg 		= trim($row['EXPBANIMG']);
	$expyoutub 		= trim($row['EXPYOUTUB']);
	$expbanimg1 	= trim($row['EXPBANIMG1']);
	$expbanimg2 	= trim($row['EXPBANIMG2']);
	$expbanimg3 	= trim($row['EXPBANIMG3']);
	$expbrolnk 	= trim($row['EXPPRODDES4']);
	$expreutit 	= trim($row['EXPREUTIT']);
	$expreulnk 	= trim($row['EXPREULNK']);
	






	if ($expavatar == '') {
		$expavatar = $imgAvatarNull;
	} else {
		$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
		$expbanimg = $pathimagenes . $expreg . '/' . $expbanimg;
	}

	//Asignamos los datos para cargar desde el templatee
	$tmpl->setCurrentBlock('browser');
	$tmpl->setVariable('sponsor_id', $expreg);
	$tmpl->setVariable('expnombre', $expnombre);
	$tmpl->setVariable('expweb', $expweb);
	$tmpl->setVariable('expmail', $expmail);
	$tmpl->setVariable('expstand', $expstand);
	$tmpl->setVariable('exprubros', $exprubros);
	$tmpl->setVariable('expposx', $expposx);
	$tmpl->setVariable('expposy', $expposy);
	$tmpl->setVariable('estcodigo', $estcodigo);
	$tmpl->setVariable('expavatar', $expavatar);
	$tmpl->setVariable('expcatego', $expcatego);
	$tmpl->setVariable('orden', $exppos);
	$tmpl->setVariable('explinked', $explinded);
	$tmpl->setVariable('expfac', $expfac);
	$tmpl->setVariable('exptwi', $exptwi);
	$tmpl->setVariable('expinsta', $expinsta);
	$tmpl->setVariable('expdireccion', $expdireccion);
	$tmpl->setVariable('exptelefo', $exptelefo);
	$tmpl->setVariable('expbanimg', $expbanimg);
	$tmpl->setVariable('expyoutub', $expyoutub);
	$tmpl->setVariable('expbrolnk', $expbrolnk);
	$tmpl->setVariable('expreutit', $expreutit);
	$tmpl->setVariable('expreulnk', $expreulnk);

	$tmpl->setVariable('expbanimg1', $expreg . '/' . $expbanimg1);
	$tmpl->setVariable('expbanimg2', $expreg . '/' . $expbanimg2);
	$tmpl->setVariable('expbanimg3', $expreg . '/' . $expbanimg3);

	if($expbanimg1 == ''){
		$tmpl->setVariable('eliminar-banner1','d-none');

		
	}
	if($expbanimg2 == ''){
		$tmpl->setVariable('eliminar-banner2','d-none');

		
	}
	if($expbanimg3 == ''){
		$tmpl->setVariable('eliminar-banner3','d-none');

		
	}

	//parametros categoria
if($expcatego != ''){
	
	$categorias ="SELECT  CATIMG,CATVID,CATTXT,CATPROD
	FROM EXP_CAT
	WHERE CATREG = $expcatego";

	$Table_categorias = sql_query($categorias, $conn); 

		
		for ($index_categorias = 0; $index_categorias < $Table_categorias->Rows_Count; $index_categorias++) {
			
			$row_cateogoria = $Table_categorias->Rows[$index_categorias];
			
			$catimg 		= trim($row_cateogoria['CATIMG']);
			$catvid 		= trim($row_cateogoria['CATVID']);
			$cattxt 		= trim($row_cateogoria['CATTXT']);
			$catprod 		= trim($row_cateogoria['CATPROD']);
			
			$tmpl->setVariable('catimg'			,$catimg);
			$tmpl->setVariable('catvid'			,$catvid);
			$tmpl->setVariable('cattxt'			,$cattxt);
			$tmpl->setVariable('catprod'		,$catprod);
			
			
	}
}
	
//Productos
$productos ="SELECT  PRODREG,PRODNOMBRE, PRODIMG, PRODFOLLETO
FROM EXP_PROD
WHERE EXPREG = $expreg";

$Table_Productos = sql_query($productos, $conn); 

for ($prod_index = 0; $prod_index < $Table_Productos->Rows_Count; $prod_index++) {

$row_prod = $Table_Productos->Rows[$prod_index];
$prodnombre 	= trim($row_prod['PRODNOMBRE']);
$prodreg 		= trim($row_prod['PRODREG']);
$prodimg 		= trim($row_prod['PRODIMG']);
$prodfolleto 	= trim($row_prod['PRODFOLLETO']);
$cantidad_productos 	= $prod_index;



$folderProd =  '../expimg/'.$expreg.'/';

$tmpl->setCurrentBlock('productos');
$tmpl->setVariable('prodnombre'		, $prodnombre);
$tmpl->setVariable('prodreg'		, $prodreg);
$tmpl->setVariable('cantidad_productos'		, $cantidad_productos);

$tmpl->setVariable('prodimg'		, $folderProd.$prodimg);
$tmpl->setVariable('prodfolleto'	, $prodfolleto);
$tmpl->parse('productos');

$prodActive = '';
}


//Texto
$textos ="SELECT  TXTNOMBRE ,TXTDESCRI, TXTREG
					FROM EXP_TXT
					WHERE EXPREG = $expreg";

$Table_textos = sql_query($textos, $conn); 

for ($index_txt = 0; $index_txt < $Table_textos->Rows_Count; $index_txt++) {
	
	$row_txt = $Table_textos->Rows[$index_txt];
	$txtnombre 		= trim($row_txt['TXTNOMBRE']);
	$txtreg 		= trim($row_txt['TXTREG']);
	$txtdescri 		= trim($row_txt['TXTDESCRI']);
	$tmpl->setCurrentBlock('textos');
	$tmpl->setVariable('txtnombre'		, $txtnombre);
	$tmpl->setVariable('txtreg'			, $txtreg);
	$tmpl->setVariable('txtdescri'		, $txtdescri);
	$tmpl->parse('textos');
	
	
}	

//videos
$videos ="SELECT  VIDURLID, VIDNOMBRE,VIDREG
					FROM EXP_VID
					WHERE EXPREG = $expreg";

$Table_videos = sql_query($videos, $conn); 

for ($index_videos = 0; $index_videos < $Table_videos->Rows_Count; $index_videos++) {
	
	$row_video = $Table_videos->Rows[$index_videos];

	$vidurlid 		= trim($row_video['VIDURLID']);
	$vidreg 		= trim($row_video['VIDREG']);
	$vidnombre 		= trim($row_video['VIDNOMBRE']);

	$tmpl->setCurrentBlock('videos');
	$tmpl->setVariable('vidurlid'		, $vidurlid);
	$tmpl->setVariable('vidreg'			, $vidreg);
	$tmpl->setVariable('vidnombre'		, $vidnombre);
	$tmpl->parse('videos');
	
	
}	


	//imagenes
	$imagenes ="SELECT  EXPIMG ,IMGREG
						FROM EXP_IMG
						WHERE EXPREG = $expreg";

	$Table_imagenes = sql_query($imagenes, $conn); 
	$folderProd =  '../expimg/'.$expreg.'/';

	for ($index_img = 0; $index_img < $Table_imagenes->Rows_Count; $index_img++) {
		
		$row_img = $Table_imagenes->Rows[$index_img];

		$expimg 		= trim($row_img['EXPIMG']);
		$imgreg 		= trim($row_img['IMGREG']);

		$tmpl->setCurrentBlock('imagenes');
		$tmpl->setVariable('expimgsrc'		,$folderProd.$expimg);
		$tmpl->setVariable('imgreg'		,$imgreg);
		$tmpl->parse('imagenes');
		
		
	}

//Seleccionamos los rubros
$query = "	SELECT PERCOMPAN,PERNOMBRE,PERAPELLI,PERCODIGO
				FROM PER_MAEST 
				WHERE ESTCODIGO=1
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


	

	



	$tmpl->parse('browser');
}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
