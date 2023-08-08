<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('mstclase.html');

//Diccionario de idiomas
DDIdioma($tmpl);
//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$perclase = (isset($_POST['perclase'])) ? trim($_POST['perclase']) : 0;
$pertipo = (isset($_POST['pertipo'])) ? trim($_POST['pertipo']) : 0;
//$tmpl->setVariable('pertipo', $pertipo);



//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion

		
$pertiposeleccionado='';
if ($perclase != 0) {
	$query = "	SELECT PERCLASE, PERCLADES,PERUSACHA,PERUSAREU,PERBLOQ,PERTIPO
					FROM PER_CLASE
					WHERE PERCLASE =$perclase AND ESTCODIGO=1";




	$Table = sql_query($query, $conn);
	if ($Table->Rows_Count > 0) {
		$row = $Table->Rows[0];
		$perclades = trim($row['PERCLADES']);
		$perclase = trim($row['PERCLASE']);
		$perusacha    = trim($row['PERUSACHA']);
		$perusareu    = trim($row['PERUSAREU']);
		$perbloq    = trim($row['PERBLOQ']);
		$pertiposeleccionado    = trim($row['PERTIPO']);


		$tmpl->setVariable('perclades', $perclades);
		$tmpl->setVariable('perclase', $perclase);
		$tmpl->setVariable('perbloq', $perbloq);

		$tmpl->setVariable('perusareuS', '');
		$tmpl->setVariable('perusareuN', '');
		$tmpl->setVariable('perusachaS', '');
		$tmpl->setVariable('perusachaN', '');
	
		if ($perusacha == 1){
			$tmpl->setVariable('perusachaS', 'selected');
		}else{
			$tmpl->setVariable('perusachaN', 'selected');
		}
		if ($perusareu == 1){
			$tmpl->setVariable('perusareuS', 'selected');
		}else{
			$tmpl->setVariable('perusareuN', 'selected');
		}

	}
}
	$query = "	SELECT PERTIPO, PERTIPDESESP, PERTIPDESING
					FROM PER_TIPO
					WHERE ESTCODIGO=1";

		$Table = sql_query($query, $conn);
		if ($Table->Rows_Count > 0) {
			for($i=0; $i<$Table->Rows_Count; $i++){

				$row = $Table->Rows[$i];


			$pertipo = trim($row['PERTIPO']);
			$pertipodesesp = trim($row['PERTIPDESESP']);
			$pertipodeing = trim($row['PERTIPDESING']);

			$tmpl->setCurrentBlock('browser');
			$tmpl->setVariable('pertipo', $pertipo);
			if ($pertiposeleccionado == $pertipo){
				$tmpl->setVariable('pertiposelected', 'selected');
			}
			$tmpl->setVariable('pertipodesesp', $pertipodesesp);
			$tmpl->setVariable('pertipodeing', $pertipodeing);
			$tmpl->parse('browser');
			}
			
		}

//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
