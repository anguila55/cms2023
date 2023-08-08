<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC.'/constants.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('contact.html');

//--------------------------------------------------------------------------------------------------------------



$conn = sql_conectar(); //Apertura de Conexion
$peradmin  = (isset($_SESSION[GLBAPPPORT . 'PERADMIN'])) ? trim($_SESSION[GLBAPPPORT . 'PERADMIN']) : '';
$tmpl->setVariable('SisNombreEvento', NAME_TITLE );
if ($peradmin!=1){
	$tmpl->setVariable('displayadminlanding', 'd-none' );
}
//variables utiles

$pathimagenes = '../expimg/';

$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'SisCorreoLinkAppStore'";
$Table2 = sql_query($query2, $conn);
for ($i = 0; $i < $Table2->Rows_Count; $i++) {
	$row = $Table2->Rows[$i];
	$evefch = date('F j, Y', strtotime(trim($row['ZDESCRI'])));
	$tmpl->setVariable('evefchinicio', $evefch);
	
}
$query = "	SELECT BANNERS
			FROM ADM_IMG WHERE BANID='10'";

$Table = sql_query($query,$conn);

for($i=0; $i<$Table->Rows_Count; $i++){
	$row = $Table->Rows[$i];
	$file 	= trim($row['BANNERS']);
	$tmpl->setVariable('file', $file);

}
$query = "	SELECT ID_SECCION,NOM_SEC, TIT_SUP, TITULO, DESCRI, OPCIONAL1, OPCIONAL2,OPCIONAL3,OPCIONAL4,IMAGEN,ESTCODIGO
					FROM TBL_LANDING";

	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		for ($i = 0; $i < $Table->Rows_Count; $i++) {
			$row = $Table->Rows[$i];
			$id_seccion 	= trim($row['ID_SECCION']);
			$nom_sec 	= trim($row['NOM_SEC']);
			$tit_sup 	= trim($row['TIT_SUP']);
			$titulo 	= trim($row['TITULO']);
			$descri  	= trim($row['DESCRI']);
			$opcional1 	= trim($row['OPCIONAL1']);
			$opcional2 	= trim($row['OPCIONAL2']);
			$opcional3 	= trim($row['OPCIONAL3']);
			$opcional4 	= trim($row['OPCIONAL4']);
			$imagen 	= trim($row['IMAGEN']);
			$estcodigo 	= trim($row['ESTCODIGO']);


			$tmpl->setVariable('id_seccion_'.$id_seccion, $id_seccion);
			$tmpl->setVariable('nom_sec_'.$id_seccion, $nom_sec);
			$tmpl->setVariable('tit_sup_'.$id_seccion, $tit_sup);
			$tmpl->setVariable('titulo_'.$id_seccion, $titulo);
			$tmpl->setVariable('descri_'.$id_seccion, $descri);
			$tmpl->setVariable('opcional1_'.$id_seccion, $opcional1);
			$tmpl->setVariable('opcional2_'.$id_seccion, $opcional2);
			$tmpl->setVariable('opcional3_'.$id_seccion, $opcional3);
			$tmpl->setVariable('opcional4_'.$id_seccion, $opcional4);
			$tmpl->setVariable('imagen_'.$id_seccion, '../../landing/landingimg/'.$id_seccion.'/'.$imagen);
			$tmpl->setVariable('estcodigo_'.$id_seccion, $estcodigo);
			$tmpl->setVariable('displayseccion_'.$id_seccion, '');
			if ($id_seccion==11){
				$tmpl->setVariable('displayopcional1', '');
				$tmpl->setVariable('displayopcional2', '');
				$tmpl->setVariable('displayopcional3', '');
				$tmpl->setVariable('displayopcional4', '');
				if ($opcional1==''){
					$tmpl->setVariable('displayopcional1', 'd-none');
				}
				if ($opcional2==''){
					$tmpl->setVariable('displayopcional2', 'd-none');
				}
				if ($opcional3==''){
					$tmpl->setVariable('displayopcional3', 'd-none');
				}
				if ($opcional4==''){
					$tmpl->setVariable('displayopcional4', 'd-none');
				}
			}
			if ($estcodigo == 3) {
				$tmpl->setVariable('displayseccion_'.$id_seccion, 'd-none');
			}
		}
	}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>