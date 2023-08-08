<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma	


$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('modaloferta.html');
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

if ($pertipolog != 65){
	$tmpl->setVariable('postularme'	, '');	
}else{
	$tmpl->setVariable('postularme'	, 'd-none');	
}

$pathimagenes = '../expimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';

$expreg 		= (isset($_POST['expreg']))? trim($_POST['expreg']) : '';
$expcupregfinal 		= (isset($_POST['expcupreg']))? trim($_POST['expcupreg']) : '';
$expcategofinal 		= (isset($_POST['expcatego']))? trim($_POST['expcatego']) : 0;
$medida 		= (isset($_POST['medida']))? trim($_POST['medida']) : 0;


$tmpl->setVariable('anchocupones'	, '');
$tmpl->setVariable('alturacupones'	, 'height:300px;');


$conn = sql_conectar(); //Apertura de Conexion
$where = '';
$where1 = '';


$query = "	SELECT EC.EXPREG, EM.EXPREG,EM.EXPAVATAR,EM.EXPNOMBRE, EM.EXPCATEGO ,EC.EXPCUPREG, EC.EXPCUPTIT, EC.EXPCUPDES, EC.EXPCUPVAL,EC.EXPCUPZON,EC.EXPCUPCAR1,EC.EXPCUPCAR2,EC.EXPCUPCAR3,EC.EXPCUPCAR4,EC.EXPCUPCAR5,EC.EXPCUPCAR6,EC.EXPCUPCAR7,EC.EXPCUPCAR8,EC.EXPCUPCAR9,EC.EXPCUPCAR10,EC.EXPCUPCAR11,EC.EXPCUPVID
						FROM EXP_MAEST EM
						LEFT OUTER JOIN EXP_CUPO EC ON EC.EXPREG=EM.EXPREG
						LEFT OUTER JOIN PER_MAEST PM ON PM.PERCODIGO=$percodlog
						WHERE EM.ESTCODIGO=1 AND EM.EXPREG=$expreg AND EC.EXPCUPREG=$expcupregfinal 
						ORDER BY EM.EXPPOS ASC,EM.EXPNOMBRE ";
		
		$Table = sql_query($query,$conn);
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			// logerror($encreg);
			
			$expcuptit 	= trim($row['EXPCUPTIT']);
			$expcupdes 	= trim($row['EXPCUPDES']);
			// logerror($encpretip);
			$expnombre 	= trim($row['EXPNOMBRE']);
			$expcupval 	= trim($row['EXPCUPVAL']);
			$expavatar 	= trim($row['EXPAVATAR']);
			$expcupzon 	= trim($row['EXPCUPZON']);
			$expcupfch 	= trim($row['EXPCUPFCH']);
			$expcupcar1 	= trim($row['EXPCUPCAR1']);
			$expcupcar2 	= trim($row['EXPCUPCAR2']);
			$expcupcar3 	= trim($row['EXPCUPCAR3']);
			$expcupcar4 	= trim($row['EXPCUPCAR4']);
			$expcupcar5 	= trim($row['EXPCUPCAR5']);
			$expcupcar6 	= trim($row['EXPCUPCAR6']);
			$expcupcar7 	= trim($row['EXPCUPCAR7']);
			$expcupcar8 	= trim($row['EXPCUPCAR8']);
			$expcupcar9 	= trim($row['EXPCUPCAR9']);
			$expcupcar10 	= trim($row['EXPCUPCAR10']);
			$expcupcar11 = trim($row['EXPCUPCAR11']);
			$expcupvid 	= trim($row['EXPCUPVID']);

			if ($expcupvid == '') {
				$tmpl->setVariable('vervideo'	, 'd-none');
			} else {
				$tmpl->setVariable('vervideo'	, '');
			}

			if ($expavatar == '') {
				$expavatar = $imgAvatarNull;
			} else {
				$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
			}

			$date1 = new DateTime('now');
			$date2 = new DateTime($expcupfch);
			$diff = $date1->diff($date2);
			// will output 2 days
			
			if ($diff->days > 0){
				$hacetanto = 'Publicado hace '.  $diff->days . ' días ';
			}else{
				$hacetanto = 'Publicado hoy';
			}

			if ($expcupval == 'Destacado')
			{
				$tmpl->setVariable('expcupval'	, '');
			}else{
				$tmpl->setVariable('expcupval'	, 'd-none');
			}
			if ($expcupcar1 == 0){
				$tmpl->setVariable('checkedcar1'	, 'd-none');
			}
			if ($expcupcar2 == 0){
				$tmpl->setVariable('checkedcar2'	, 'd-none');
			}
			if ($expcupcar3 == 0){
				$tmpl->setVariable('checkedcar3'	, 'd-none');
			}
			if ($expcupcar4 == 0){
				$tmpl->setVariable('checkedcar4'	, 'd-none');
			}
			if ($expcupcar5 == 0){
				$tmpl->setVariable('checkedcar5'	, 'd-none');
			}
			if ($expcupcar6 == 0){
				$tmpl->setVariable('checkedcar6'	, 'd-none');
			}
			if ($expcupcar7 == 0){
				$tmpl->setVariable('checkedcar7'	, 'd-none');
			}
			if ($expcupcar8 == 0){
				$tmpl->setVariable('checkedcar8'	, 'd-none');
			}
			if ($expcupcar9 == 0){
				$tmpl->setVariable('checkedcar9'	, 'd-none');
			}
			if ($expcupcar10 == 0){
				$tmpl->setVariable('checkedcar10'	, 'd-none');
			}
			if ($expcupcar11 == 0){
				$tmpl->setVariable('checkedcar11'	, 'd-none');
			}
					
			$tmpl->setVariable('expreg'	, $expcupregfinal);
			
			$tmpl->setVariable('expavatar', $expavatar);
			$tmpl->setVariable('expnombre', $expnombre);
			$tmpl->setVariable('expcupzon', $expcupzon);
			$tmpl->setVariable('expcuptit'	, $expcuptit);
			$tmpl->setVariable('expcupdes'	, $expcupdes);
			$tmpl->setVariable('expcupvid'	, $expcupvid);
			$tmpl->setVariable('expcupfch'	, $hacetanto);
		}


 $query2 = "	SELECT PERCODIGO, EXPCUPREG
 FROM CUP_PER 
	WHERE PERCODIGO = $percodlog AND EXPCUPREG = $expcupregfinal";
	
	$Table2 = sql_query($query2,$conn);
	
	if ($Table2->Rows_Count > 0){
		$tmpl->setVariable('postularme', 'd-none');
	}

		

sql_close($conn);
$tmpl->show();

?>	
