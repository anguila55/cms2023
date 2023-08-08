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

$fltbuscar 		= (isset($_POST['fltbuscar']))? trim($_POST['fltbuscar']) : '';
$fltanuncio 		= (isset($_POST['fltanuncio']))? trim($_POST['fltanuncio']) : '';
$empresa 		= (isset($_POST['empresa']))? trim($_POST['empresa']) : '';
$medida 		= (isset($_POST['medida']))? trim($_POST['medida']) : 0;

$tmpl->setVariable('avatarresponsive'	, 'avatar-sponsor');
$tmpl->setVariable('alturacupon'	, 'height:200px');

if ($pertipolog == 65){
	$tmpl->setVariable('displayanuncios'	, '');	
}else{
	$tmpl->setVariable('displayanuncios'	, 'd-none');	
}



$conn = sql_conectar(); //Apertura de Conexion
$where = '';
$where1 = '';
$tmpl->setVariable('display-anuncios'	, 'display:;');
$tmpl->setVariable('display-todos'	, 'display:none;');
if ($fltbuscar!='' || $empresa!='' || $fltanuncio!=''){

	if($fltanuncio!=''){
		$where .= " AND (EM.EXPREG=$fltanuncio) ";
		$tmpl->setVariable('display-anuncios'	, 'display:none;');
		$tmpl->setVariable('display-todos'	, 'display:;');

	}
	if($empresa!=''){
		$where .= " AND EM.EXPNOMBRE CONTAINING '$empresa' ";
	}

}


$query = "	SELECT EM.EXPREG,EM.EXPAVATAR,EM.EXPNOMBRE, EM.EXPCATEGO ,EC.EXPCUPREG, EC.EXPCUPTIT, EC.EXPCUPDES, EC.EXPCUPVAL,EC.EXPCUPZON,EC.EXPCUPFCH,EM.PERCODIGO
						FROM EXP_MAEST EM
						LEFT OUTER JOIN EXP_CUPO EC ON EC.EXPREG=EM.EXPREG
						WHERE EM.ESTCODIGO=1 AND EC.ESTCODIGO<>3 $where
						ORDER BY EC.EXPCUPFCH DESC, EM.EXPPOS ASC,EM.EXPNOMBRE ";
		
		$Table = sql_query($query,$conn);
	
		for($i=0; $i<$Table->Rows_Count; $i++){
			$row = $Table->Rows[$i];
			$expreg 	= trim($row['EXPREG']);
			$expcatego 	= trim($row['EXPCATEGO']);
			// logerror($encreg);
			$expcupreg 	= trim($row['EXPCUPREG']);
			$expcuptit 	= trim($row['EXPCUPTIT']);
			$expcupdes 	= trim($row['EXPCUPDES']);
			// logerror($encpretip);
			$expnombre 	= trim($row['EXPNOMBRE']);
			$expcupval 	= trim($row['EXPCUPVAL']);
			$expavatar 	= trim($row['EXPAVATAR']);
			$expcupfch 	= trim($row['EXPCUPFCH']);
			$expcupzon 	= trim($row['EXPCUPZON']);
		

			$representantesponsor = trim($row['PERCODIGO']);

			if ($expavatar == '') {
				$expavatar = $imgAvatarNull;
			} else {
				$expavatar = $pathimagenes . $expreg . '/' . $expavatar;
			}
			
			$tmpl->setCurrentBlock('cupones');
			if ($medida == 100){
				$tmpl->setVariable('avatarresponsive'	, 'avatar-sponsor1');
				$tmpl->setVariable('alturacupon'	, 'height:300px');
				
	
			}
			$querySponsor =  "SELECT EXPREG FROM EXP_PER WHERE PERCODIGO = $percodlog";
			$TablSponsor = sql_query($querySponsor,$conn);

			$tmpl->setVariable('visiblepublicar'		, 'd-none');

			if ($TablSponsor->Rows_Count != -1) {
				
				$rowsponsor= $TablSponsor->Rows[0];
				$expregsponsor 	= trim($rowsponsor['EXPREG']);
				$tmpl->setVariable('visiblepublicar'		, '');	
				$tmpl->setVariable('expregsponsor'	, $expregsponsor);
				$tmpl->setVariable('expregsponsor2'	, $expregsponsor);
			}
		
			$tmpl->setVariable('expreg'	, $expreg);
		



			if ($expcupval == 'Destacado')
			{
				$tmpl->setVariable('expcupval'	, '');
			}else{
				$tmpl->setVariable('expcupval'	, 'd-none');
			}

			$date1 = new DateTime('now');
			$date2 = new DateTime($expcupfch);
			$diff = $date1->diff($date2);
			// will output 2 days
			
			if ($diff->days > 0){

				if ($IdiomView == 'ING'){
					$hacetanto = 'Published '.  $diff->days . ' days ago';
				}else{
					$hacetanto = 'Publicado hace '.  $diff->days . ' días ';
				}

				
			}else{

				if ($IdiomView == 'ING'){
					$hacetanto = 'Publish today';
				}else{
					$hacetanto = 'Publicado hoy';
				}

			
			}

			


			$tmpl->setVariable('expcupzon', $expcupzon);
			$tmpl->setVariable('expavatar', $expavatar);
			$tmpl->setVariable('expnombre', $expnombre);
			$tmpl->setVariable('expcupreg'	, $expcupreg);
			$tmpl->setVariable('expcuptit'	, $expcuptit);
			$tmpl->setVariable('expcupdes'	, $expcupdes);
			$tmpl->setVariable('expcatego'	, $expcatego);
			$tmpl->setVariable('expcupfch'	, $hacetanto);
			$tmpl->setVariable('percodigo'	, $percodlog);
			$tmpl->setVariable('representantesponsor'	, $representantesponsor);


			$tmpl->parse('cupones');
		}
		

sql_close($conn);
$tmpl->show();

?>	




