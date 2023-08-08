<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
	require_once GLBRutaFUNC.'/idioma.php';//Idioma	
			
	//--------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------
	$errcod = 0;
	$errmsg = '';
	$err 	= 'SQLACCEPT';
	//--------------------------------------------------------------------------------------------------------------
	$conn= sql_conectar();//Apertura de Conexion
	$trans	= sql_begin_trans($conn);
	
	//Control de Datos
	$expreg 		= (isset($_POST['expreg']))? trim($_POST['expreg']) : 0;
	$expcupreg 		= (isset($_POST['expcupreg']))? trim($_POST['expcupreg']) : 0;
	$expcuptit 		= (isset($_POST['expcuptit']))? trim($_POST['expcuptit']) : '';
	$expcupdes      = (isset($_POST['expcupdes']))? trim($_POST['expcupdes']):'';
	$expcupval      = (isset($_POST['expcupval']))? trim($_POST['expcupval']):'';
	$expcupzon      = (isset($_POST['expcupzon']))? trim($_POST['expcupzon']):'';
	$expcupcar1      = (isset($_POST['expcupcar1']))? trim($_POST['expcupcar1']):'';
	$expcupcar2      = (isset($_POST['expcupcar2']))? trim($_POST['expcupcar2']):'';
	$expcupcar3      = (isset($_POST['expcupcar3']))? trim($_POST['expcupcar3']):'';
	$expcupcar4      = (isset($_POST['expcupcar4']))? trim($_POST['expcupcar4']):'';
	$expcupcar5      = (isset($_POST['expcupcar5']))? trim($_POST['expcupcar5']):'';
	$expcupcar6      = (isset($_POST['expcupcar6']))? trim($_POST['expcupcar6']):'';
	$expcupcar7      = (isset($_POST['expcupcar7']))? trim($_POST['expcupcar7']):'';
	$expcupcar8      = (isset($_POST['expcupcar8']))? trim($_POST['expcupcar8']):'';
	$expcupcar9      = (isset($_POST['expcupcar9']))? trim($_POST['expcupcar9']):'';
	$expcupcar10      = (isset($_POST['expcupcar10']))? trim($_POST['expcupcar10']):'';
	$expcupcar11      = (isset($_POST['expcupcar11']))? trim($_POST['expcupcar11']):'';
	$estcodigo 		= 1;
	

	if($expcupreg==''){
		$expcupreg=0;
	}

	
	
	//--------------------------------------------------------------------------------------------------------------
	$expreg			= VarNullBD($expreg			, 'N');
	$expcupreg		= VarNullBD($expcupreg		, 'N');
	$expcuptit		= VarNullBD($expcuptit		, 'S');
	$expcupdes		= VarNullBD($expcupdes		, 'S');
	$expcupval		= VarNullBD($expcupval		, 'S');
	$expcupzon		= VarNullBD($expcupzon		, 'S');
	$expcupcar1		= VarNullBD($expcupcar1		, 'N');
	$expcupcar2		= VarNullBD($expcupcar2		, 'N');
	$expcupcar3		= VarNullBD($expcupcar3		, 'N');
	$expcupcar4		= VarNullBD($expcupcar4		, 'N');
	$expcupcar5		= VarNullBD($expcupcar5		, 'N');
	$expcupcar6		= VarNullBD($expcupcar6		, 'N');
	$expcupcar7		= VarNullBD($expcupcar7		, 'N');
	$expcupcar8		= VarNullBD($expcupcar8		, 'N');
	$expcupcar9		= VarNullBD($expcupcar9		, 'N');
	$expcupcar10		= VarNullBD($expcupcar10		, 'N');
	$expcupcar11		= VarNullBD($expcupcar11		, 'N');
	

	if($expcupreg==0){
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_EXPCUPONES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query,$conn,$trans);
		$RowId		= $TblId->Rows[0];			
		$expcupreg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		$query = " 	INSERT INTO EXP_CUPO (EXPREG,EXPCUPREG,EXPCUPTIT,EXPCUPDES,EXPCUPVAL,ESTCODIGO,EXPCUPZON,EXPCUPFCH,EXPCUPCAR1,EXPCUPCAR2,EXPCUPCAR3,EXPCUPCAR4,EXPCUPCAR5,EXPCUPCAR6,EXPCUPCAR7,EXPCUPCAR8,EXPCUPCAR9,EXPCUPCAR10,EXPCUPCAR11)
					VALUES($expreg,$expcupreg,$expcuptit,$expcupdes,$expcupval,$estcodigo,$expcupzon,CURRENT_TIMESTAMP,$expcupcar1,$expcupcar2,$expcupcar3,$expcupcar4,$expcupcar5,$expcupcar6,$expcupcar7,$expcupcar8,$expcupcar9,$expcupcar10,$expcupcar11) ";
	}else{
		$query = " 	UPDATE EXP_CUPO  SET 
					EXPCUPTIT=$expcuptit,EXPCUPDES=$expcupdes,EXPCUPVAL=$expcupval,ESTCODIGO=$estcodigo,EXPCUPZON=$expcupzon,EXPCUPCAR1=$expcupcar1,EXPCUPCAR2=$expcupcar2,EXPCUPCAR3=$expcupcar3,EXPCUPCAR4=$expcupcar4,EXPCUPCAR5=$expcupcar5,EXPCUPCAR6=$expcupcar6,EXPCUPCAR7=$expcupcar7,EXPCUPCAR8=$expcupcar8,EXPCUPCAR9=$expcupcar9,EXPCUPCAR10=$expcupcar10,EXPCUPCAR11=$expcupcar11
					WHERE EXPREG=$expreg AND EXPCUPREG=$expcupreg ";
	}
	$err = sql_execute($query,$conn,$trans);	

	
	//--------------------------------------------------------------------------------------------------------------
	if($err == 'SQLACCEPT' && $errcod==0){
		sql_commit_trans($trans);
		$errcod = 0;
		$errmsg = TrMessage('Guardado correctamente!');      
	}else{            
		sql_rollback_trans($trans);
		$errcod = 2;
		$errmsg = ($errmsg=='')? TrMessage('Guardado correctamente!') : $errmsg;
	}	
	//--------------------------------------------------------------------------------------------------------------
	sql_close($conn);	
	echo '{"errcod":"'.$errcod.'","errmsg":"'.$errmsg.'"}';
	
?>	
