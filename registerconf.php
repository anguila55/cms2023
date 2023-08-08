<?
	if(!isset($_SESSION))  session_start();
	// include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
	include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/class.phpmailer.php';
	require_once GLBRutaFUNC.'/class.smtp.php';
			
	$tmpl= new HTML_Template_Sigma();	
	$tmpl->loadTemplateFile('register.html');
	
	$param 	= (isset($_GET['N']))? trim($_GET['N']) : '';
	
	if($param!=''){
		$vaux = explode('::',$param);
		if(count($vaux)==2){
			$percodigo = trim($vaux[1]);
			
			if($percodigo==''){
				header('Location: login');
			}else{
				//Actualizo el estado de 9 a 8, para indicar que fue confirmado 
				$conn= sql_conectar();//Apertura de Conexion
				$estcodigo=8;
				$registerwait='registerwait';
				$ingresoevento='true';
				$query2 = " SELECT ZDESCRI FROM ZZZ_CONF WHERE ZPARAM = 'IngresoEvento'";
				$Table2 = sql_query($query2, $conn);
				for ($i = 0; $i < $Table2->Rows_Count; $i++) {
					$row = $Table2->Rows[$i];
					$ingresoevento = trim($row['ZDESCRI']);	
				}
				if($ingresoevento=='false'){
					$estcodigo=1;
					$registerwait='login';
				}else{
					$estcodigo=8;
					$registerwait='registerwait';
				}
				$query = " UPDATE PER_MAEST SET ESTCODIGO=$estcodigo WHERE PERCODIGO=$percodigo AND ESTCODIGO=9 ";
				$err = sql_execute($query,$conn);
				sql_close($conn);
				header('Location: '.$registerwait);
			}
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	$tmpl->show();
	
?>	
