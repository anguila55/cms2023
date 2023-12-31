<?php include('../val/valuser.php'); ?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$errcod 	= 0;
$errmsg 	= '';
$err 		= 'SQLACCEPT';
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans	= sql_begin_trans($conn);

//Control de Datos
$percodlog = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$workreg = (isset($_POST['workreg'])) ? trim($_POST['workreg']) : 0;
//--------------------------------------------------------------------------------------------------------------
$workreg = VarNullBD($workreg, 'N');



if ($workreg != 0) {
	$checkFav =  "SELECT * FROM REU_CABE WHERE PERCODSOL = $percodlog AND PERCODDST = $percodlog AND WORK_REG = $workreg";


	$TableCheckFav = sql_query($checkFav, $conn);

	if ($TableCheckFav->Rows_Count == -1) {


		//Busco datos del evento
		$query = "SELECT WORK_REG,WORK_FCH,WORK_TITULO,WORK_DESCRI,WORK_HORINI,WORK_HORFIN,ESTCODIGO
				FROM WORK_MAEST
				WHERE WORK_REG=$workreg ";

		$Table = sql_query($query, $conn);
		$row = $Table->Rows[0];
		$workreg 	= trim($row['WORK_REG']);
		$workfch 	= trim($row['WORK_FCH']);
		$workhorini 	= trim($row['WORK_HORINI']);

		//Agendo el evneto
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 		
		//Genero un ID 
		$query 		= 'SELECT GEN_ID(G_REUNIONES,1) AS ID FROM RDB$DATABASE';
		$TblId		= sql_query($query, $conn, $trans);
		$RowId		= $TblId->Rows[0];
		$reureg 	= trim($RowId['ID']);
		//- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


		$query = "	INSERT INTO REU_CABE (REUREG, REUFCHREG, PERCODSOL, PERCODDST, REUESTADO, REUFECHA, REUHORA, REUFCHCAN,WORK_REG) 
					VALUES ($reureg, CURRENT_TIMESTAMP, $percodlog, $percodlog, 2, '$workfch', '$workhorini', NULL, $workreg);";
		$err = sql_execute($query, $conn, $trans);

		$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN+4
					WHERE PERCODIGO=$percodlog ";

			$err = sql_execute($query,$conn,$trans);
		
	} else {
		$deleteFav =  "DELETE FROM REU_CABE WHERE PERCODSOL = $percodlog AND PERCODDST = $percodlog AND WORK_REG = $workreg ";
		$err = sql_execute($deleteFav, $conn);

		$query = " 	UPDATE PER_MAEST SET 
					PERQRPUN=PERQRPUN-4
					WHERE PERCODIGO=$percodlog ";

			$err = sql_execute($query,$conn,$trans);
		
	}
}

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
	sql_commit_trans($trans);
	$errcod = 0;
	
		$errmsg = 'Agenda agregada!';	
	
	
} else {
	sql_rollback_trans($trans);
	$errcod = 2;
	$errmsg = ($errmsg == '') ? 'Error al agendar!' : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';

?>	
