<?php include('../../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php';
$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mst.html');
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$fltbuscar 	= (isset($_POST['fltbuscar']))? $_POST['fltbuscar']:0;
$imgnull = '../../app-assets/img/pages/sativa.png';
$tmpl->setVariable('imgnull', $imgnull);
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

	$tmpl->setVariable('displaytitulosuperior', '');
	$tmpl->setVariable('displaytitulo', '');
	$tmpl->setVariable('displaydescri', '');
	$tmpl->setVariable('displayimagen', '');
	$tmpl->setVariable('tamanoimagen', '');
	$tmpl->setVariable('displaynombreseccion', '');
	$tmpl->setVariable('displayopcional1', 'd-none');
	$tmpl->setVariable('tituloopcional1', 'd-none');
	$tmpl->setVariable('displayopcional2', 'd-none');
	$tmpl->setVariable('tituloopcional2', 'd-none');
	$tmpl->setVariable('displayopcional3', 'd-none');
	$tmpl->setVariable('tituloopcional3', 'd-none');
	$tmpl->setVariable('displayopcional4', 'd-none');
	$tmpl->setVariable('tituloopcional4', 'd-none');
	
	if ($fltbuscar==0){
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('tamanoimagen'	, 'Se recomienda 1600 x 1336px en formato .jpg'	);
		$tmpl->setVariable('displaynombreseccion', 'd-none');
	}else if ($fltbuscar==1){
		$tmpl->setVariable('tamanoimagen'	, 'Se recomienda 1058 x 1095px en formato .png'	);
	}else if ($fltbuscar==2){
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
		
	}else if ($fltbuscar==3){
		$tmpl->setVariable('displaynombreseccion', 'd-none');
		$tmpl->setVariable('displaytitulosuperior', 'd-none');
		$tmpl->setVariable('tamanoimagen'	, 'Se recomienda 1100 x 1250px en formato .jpg'	);
		
	}else if ($fltbuscar==4){
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
	}else if ($fltbuscar==5){
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
	}else if ($fltbuscar==6){
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
	}else if ($fltbuscar==7){
		$tmpl->setVariable('displaynombreseccion', 'd-none');
		$tmpl->setVariable('tamanoimagen'	, 'Se recomienda 1062 x 916px en formato .png'	);
	}else if ($fltbuscar==8){
		$tmpl->setVariable('displaynombreseccion', 'd-none');
		$tmpl->setVariable('displaytitulosuperior', 'd-none');
		$tmpl->setVariable('displaytitulo', 'd-none');
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
	}else if ($fltbuscar==9){
		$tmpl->setVariable('displaynombreseccion', 'd-none');
		$tmpl->setVariable('displaytitulosuperior', 'd-none');
		$tmpl->setVariable('displaytitulo', 'd-none');
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
		$tmpl->setVariable('displayopcional1', '');
		$tmpl->setVariable('tituloopcional1', 'Link Google Maps');
	}else if ($fltbuscar==10){
		$tmpl->setVariable('displaytitulosuperior', 'd-none');
		$tmpl->setVariable('displaytitulo', 'd-none');
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
		$tmpl->setVariable('displayopcional1', '');
	$tmpl->setVariable('tituloopcional1', 'Dirección');
	$tmpl->setVariable('displayopcional2', '');
	$tmpl->setVariable('tituloopcional2', 'Email');
	$tmpl->setVariable('displayopcional3', '');
	$tmpl->setVariable('tituloopcional3', 'Teléfono');
	}else if ($fltbuscar==11){
		$tmpl->setVariable('displaynombreseccion', 'd-none');
		$tmpl->setVariable('displaytitulosuperior', 'd-none');
		$tmpl->setVariable('displaytitulo', 'd-none');
		$tmpl->setVariable('displaydescri', 'd-none');
		$tmpl->setVariable('displayimagen', 'd-none');
		$tmpl->setVariable('displayopcional1', '');
	$tmpl->setVariable('tituloopcional1', 'Facebook');
	$tmpl->setVariable('displayopcional2', '');
	$tmpl->setVariable('tituloopcional2', 'Twitter');
	$tmpl->setVariable('displayopcional3', '');
	$tmpl->setVariable('tituloopcional3', 'Instagram');
	$tmpl->setVariable('displayopcional4', '');
	$tmpl->setVariable('tituloopcional4', 'LinkedIn');
	}

	$query = "	SELECT ID_SECCION,NOM_SEC, TIT_SUP, TITULO, DESCRI, OPCIONAL1, OPCIONAL2,OPCIONAL3,OPCIONAL4,OPCIONAL5,OPCIONAL6,IMAGEN,ESTCODIGO
					FROM TBL_LANDING
					WHERE ID_SECCION=$fltbuscar";

	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		$row = $Table->Rows[0];
		$id_seccion 	= trim($row['ID_SECCION']);
		$nom_sec 	= trim($row['NOM_SEC']);
		$tit_sup 	= trim($row['TIT_SUP']);
		$titulo 	= trim($row['TITULO']);
		$descri  	= trim($row['DESCRI']);
		$opcional1 	= trim($row['OPCIONAL1']);
		$opcional2 	= trim($row['OPCIONAL2']);
		$opcional3 	= trim($row['OPCIONAL3']);
		$opcional4 	= trim($row['OPCIONAL4']);
		$opcional5 	= trim($row['OPCIONAL5']);
		$opcional6 	= trim($row['OPCIONAL6']);
		$imagen 	= trim($row['IMAGEN']);
		$estcodigo 	= trim($row['ESTCODIGO']);


		$tmpl->setVariable('id_seccion', $id_seccion);
		$tmpl->setVariable('nom_sec', $nom_sec);
		$tmpl->setVariable('tit_sup', $tit_sup);
		$tmpl->setVariable('titulo', $titulo);
		$tmpl->setVariable('descri', $descri);
		$tmpl->setVariable('opcional1', $opcional1);
		$tmpl->setVariable('opcional2', $opcional2);
		$tmpl->setVariable('opcional3', $opcional3);
		$tmpl->setVariable('opcional4', $opcional4);
		$tmpl->setVariable('opcional5', $opcional5);
		$tmpl->setVariable('opcional6', $opcional6);
		$tmpl->setVariable('imagen', $imagen);
		$tmpl->setVariable('estcodigo', $estcodigo);

		if ($estcodigo == 1) {
			$tmpl->setVariable('visiblesel', 'selected');
		}else{
			$tmpl->setVariable('ocultosel', 'selected');
		}
	}


//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
