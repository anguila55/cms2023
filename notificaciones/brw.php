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
// DDIdioma($tmpl);


//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$percodlog 	= (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre 	= (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';
$perapelli 	= (isset($_SESSION[GLBAPPPORT . 'PERAPELLI'])) ? trim($_SESSION[GLBAPPPORT . 'PERAPELLI']) : '';
$perusuacc 	= (isset($_SESSION[GLBAPPPORT . 'PERUSUACC'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSUACC']) : '';
$percorreo 	= (isset($_SESSION[GLBAPPPORT . 'PERCORREO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCORREO']) : '';
$perusareu 	= (isset($_SESSION[GLBAPPPORT . 'PERUSAREU'])) ? trim($_SESSION[GLBAPPPORT . 'PERUSAREU']) : '';
$pertipolog = (isset($_SESSION[GLBAPPPORT . 'PERTIPO']))	? trim($_SESSION[GLBAPPPORT . 'PERTIPO'])  : '';
$perclaselog = (isset($_SESSION[GLBAPPPORT . 'PERCLASE'])) ? trim($_SESSION[GLBAPPPORT . 'PERCLASE'])  : '';


$pathimagenes = '../perimg/';
$imgAvatarNull = '../app-assets/img/avatar.png';


$conn = sql_conectar(); //Apertura de Conexion

$urllink='';
$query = "SELECT N.NOTREG, N.NOTTITULO, P.PERAVATAR, P.PERNOMBRE, P.PERAPELLI, P.PERCOMPAN,P.PERCARGO,N.NOTCODIGO,N.REUREG, N.PERCODDST, N.PERCODORI, N.NOTFCHREG
				FROM NOT_CABE N
				LEFT OUTER JOIN REU_CABE R ON R.REUREG = N.REUREG 
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO = N.PERCODORI
				WHERE (N.PERCODDST=$percodlog OR N.PERCODDST=181777 OR N.PERCODDST=181778)
				ORDER BY N.NOTFCHREG DESC ";

	$Table = sql_query($query,$conn);

	if($Table->Rows_Count>0){
		$tmpl->setVariable('notiene', 'd-none');
	}else{
		$tmpl->setVariable('notiene', '');
	}

	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$notreg    = trim($row['NOTREG']); 
		$nottitulo	= trim($row['NOTTITULO']);
		$pernombre = trim($row['PERNOMBRE']);
		$perapelli = trim($row ['PERAPELLI']);
		$percompan = trim($row ['PERCOMPAN']);
		$percargo = trim($row ['PERCARGO']);
		$notcodigo = trim($row ['NOTCODIGO']);
		$percoddst = trim($row ['PERCODDST']);
		$percodori = trim($row ['PERCODORI']);
		$notfchreg = trim($row ['NOTFCHREG']);
		$peravatar = trim($row ['PERAVATAR']);
		$reureg = trim($row ['REUREG']);

		$vaux = explode('##',$nottitulo);
		if(count($vaux)>1){
			$nottitulo = $vaux[0];
			$urllink = $vaux[1];
		}



		switch ($notcodigo) {
			case 1: //Solicitud
				$dir = '../reuniones/bsq?T=2';
				break;
			case 2: //Confirmado
				$dir = '../reuniones/bsq?T=3';
				break;
			case 3: //Cancelado
				$dir = '../reuniones/bsq?T=4';
				break;
			case 4: //Cambio de Horario
				$dir = '../reuniones/bsq?T=3';
				break;
			case 5: //Chat
				$dir = "../chat/bsq?chatid=$percoddst";
				break;
			case 6: //sin Link
				$dir = '';
				break;
			case 7: //Home
				$dir = '../index';
				break;
			case 8: //Reuniones
				$dir = '../reuniones/bsq';
				break;
			case 9: //Mensajes
				$dir = '../chat/bsq';
				break;
			case 10: //Programa
				$dir = '../actividades/bsq';
				break;
			case 11: //Sponsor
				$dir = '../stand/bsq';
				break;
			case 12: //Asistente
				$dir = '../directorio/bsq';
				break;				
			case 13: //Prensa
				$dir = '../prensa/bsq';
				break;
			case 14: //Comunidad
				$dir = '../muro/bsq';
				break;
			case 100: //UrlLink
				$dir = $urllink;
				break;
		}
	
		if ($peravatar != '') {
			$tmpl->setVariable('peravatar', $pathimagenes . $percodori . '/' . $peravatar);
		} else {
			$tmpl->setVariable('peravatar', $imgAvatarNull);
		}
		$guardomasivo = 1 ;
		$where = '';
		if ($percoddst == 181777){
			if ($reureg!=0){

				$where = " AND PERTIPO=$reureg ";
			}
			
		}
		if ($percoddst == 181778){

			$where = " AND PERCLASE=$reureg ";
		}
		if ($where != ''){
			$querydes = "SELECT PERCODIGO
				FROM PER_MAEST
				WHERE (PERCODIGO=$percodlog $where)
				ORDER BY PERCODIGO DESC ";
			$Tabledes = sql_query($querydes, $conn);
			if(isset($Tabledes->Rows[0])){ 
			$rowdes = $Tabledes->Rows[0];
			$guardomasivo = 1 ;
			$percoddst=$percodlog;
			}else{

				$guardomasivo = 0 ;
			}
		}
		if ($guardomasivo==1){
		$tmpl->setCurrentBlock('notificaciones_total');
		$tmpl->setVariable('notreg', $notreg);
		$tmpl->setVariable('nottitulo', $nottitulo);
		$tmpl->setVariable('pernombre', $pernombre);
		$tmpl->setVariable('perapelli', $perapelli);
		$tmpl->setVariable('percompan', $percompan);
		$tmpl->setVariable('percargo', $percargo);

		$tmpl->setVariable('notcodigo', $notcodigo);
		$tmpl->setVariable('percoddst', $percoddst);
		$tmpl->setVariable('notfchreg', $notfchreg);
		$tmpl->setVariable('dir', $dir);
		DDIdioma($tmpl);
		$tmpl->parse('notificaciones_total');
		}
	}


sql_close($conn);

$tmpl->show();

?>	




