<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('brw.html');
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$percodigo = (isset($_SESSION[GLBAPPPORT.'PERCODIGO']))? trim($_SESSION[GLBAPPPORT.'PERCODIGO']) : '';
	$pernombre = (isset($_SESSION[GLBAPPPORT.'PERNOMBRE']))? trim($_SESSION[GLBAPPPORT.'PERNOMBRE']) : '';
	
	$fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
	
	$where = '';
	if($fltdescri!=''){
		$where .= " AND NETWORK_TITULO CONTAINING '$fltdescri' ";
	}
	
	$conn= sql_conectar();//Apertura de Conexion
	
	//Seleccionamos los datos que se mostrarar en el brw
	$query = "	SELECT A.NETWORK_REG, SUBSTRING(A.NETWORK_TITULO FROM 1 FOR 20) AS NETWORK_TITULO, A.NETWORK_FCH, 
						A.NETWORK_HORINI, A.NETWORK_HORFIN, A.ESTCODIGO, A.SWITCH
				FROM NETWORK_MAEST A
				WHERE A.ESTCODIGO=1 $where
				ORDER BY A.NETWORK_FCH, A.NETWORK_HORINI";
	$Table = sql_query($query,$conn);
	for($i=0; $i<$Table->Rows_Count; $i++){
		$row = $Table->Rows[$i];
		$networkreg 	= trim($row['NETWORK_REG']);
		$networktitulo 	= trim($row['NETWORK_TITULO']);
		$networkfch     = BDConvFch($row['NETWORK_FCH']);
		$networkhorini  = substr(trim($row['NETWORK_HORINI']),0,5);
		$networkhorfin  = substr(trim($row['NETWORK_HORFIN']),0,5);
		$estado 	= trim($row['SWITCH']);
				
		$tmpl->setCurrentBlock('browser');
		$tmpl->setVariable('networkreg'		, $networkreg	);
		$tmpl->setVariable('networktitulo'	, $networktitulo	);
		$tmpl->setVariable('networkfch'		, $networkfch	);
		$tmpl->setVariable('networkhorini'	, $networkhorini	);
		$tmpl->setVariable('networkhorfin'	, $networkhorfin	);

		if ($estado==1){

			$tmpl->setVariable('estado'	, 'LIVE'	);

		}else if ($estado==2){
			$tmpl->setVariable('estado'	, 'FULL'	);
		}else{
			$tmpl->setVariable('estado'	, 'SOON'	);
		}
		$tmpl->parse('browser');

		
	}
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	$tmpl->show();
	
?>	
