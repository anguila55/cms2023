<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
    $percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
    $pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
    $perapelli = (isset($_SESSION[GLBAPPPORT.'PERAPELLI']))? trim($_SESSION[GLBAPPPORT.'PERAPELLI']) : '';
    $percompan = (isset($_SESSION[GLBAPPPORT.'PERCOMPAN']))? trim($_SESSION[GLBAPPPORT.'PERCOMPAN']) : '';
           
    $JSTable	= '{"notificaciones": [ '; // nombre de la variable json
    $conn= sql_conectar();
	
	//Busco las notificaciones leidas, solo 20
	$query = "SELECT FIRST 10 N.NOTREG, N.NOTTITULO, N.REUREG, P.PERNOMBRE, P.PERAPELLI, P.PERCOMPAN, P.PERCARGO,N.NOTCODIGO, N.PERCODDST
				FROM NOT_CABE N
				LEFT OUTER JOIN REU_CABE R ON R.REUREG = N.REUREG 
				LEFT OUTER JOIN PER_MAEST P ON P.PERCODIGO = N.PERCODORI
				WHERE (N.PERCODDST=$percodigo OR N.PERCODDST=181777 OR N.PERCODDST=181778)
				ORDER BY N.NOTFCHREG DESC ";
	
	$Table = sql_query($query,$conn);
	for($i=0; $i < $Table->Rows_Count; $i++){
		$row= $Table->Rows[$i];
		$notreg    = trim($row['NOTREG']); 
		$nottitulo	= trim($row['NOTTITULO']);
		
		$pernombre = trim($row['PERNOMBRE']);
		$perapelli = trim($row ['PERAPELLI']);
		$percompan = trim($row ['PERCOMPAN']);
		$notcodigo = trim($row ['NOTCODIGO']);
		$percoddst = trim($row ['PERCODDST']);
		$percargo = trim($row ['PERCARGO']);
		if ($IdiomView == 'ING'){
			$de = 'from';
		}else{
			$de = 'de';
		}
		$reureg = trim($row ['REUREG']);
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
				WHERE (PERCODIGO=$percodigo $where)
				ORDER BY PERCODIGO DESC ";
			$Tabledes = sql_query($querydes, $conn);
			if(isset($Tabledes->Rows[0])){ 
			$rowdes = $Tabledes->Rows[0];
			$guardomasivo = 1 ;
			}else{

				$guardomasivo = 0 ;
			}
		}
		if ($guardomasivo==1){
	
		$JSTable .= '{	"notreg":"'.$notreg.'",
						"nottitulo":"'.$nottitulo.'",
						"pernombre":"'.$pernombre.'",
						"perapelli":"'.$perapelli.'",
						"percompan":"'.$percompan.'",
						"notcodigo":"'.$notcodigo.'",
						"notestado":"2",
						"percargo":"'.$percargo.'",
						"de":"'.$de.'"
						},';
		}
	}
	
	$JSTable	= substr($JSTable, 0, strlen($JSTable)-1);
	        
    $JSTable.=']}';
    echo $JSTable;
	
    sql_close($conn);
        
?>	
