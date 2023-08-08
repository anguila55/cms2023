<?php include '../val/valuser.php';?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma

//--------------------------------------------------------------------------------------------------------------

$errcod = 0;
$errmsg = '';
$err = 'SQLACCEPT';

//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans = sql_begin_trans($conn);

//Control de Datos

$queryBase = "	SELECT *
FROM VAR_MAEST WHERE USUARIO=0";

$TableBase = sql_query($queryBase, $conn);



    for ($i = 0; $i < $TableBase->Rows_Count; $i++) {
    $row = $TableBase->Rows[$i];
    $varreg 	= trim($row['VARREG']);
    $vartitulo 	= trim($row['VARTITULO']);
    $vardescri 	= trim($row['VARDESCRI']);
    $vardescriing 	= trim($row['VARDESCRIING']);
    $vardescripor 	= trim($row['VARDESCRIPOR']);
    $mostrar	= trim($row['VARMOST']);
    $requerida	= trim($row['VARREQ']);
    $vartipo	= trim($row['VARTIPO']);
    $varopc	= trim($row['VAROPC']);

    //Clase de Perfiles
		
    $query = "	SELECT PERCLASE,PERCLADES
                FROM PER_CLASE
                WHERE PERTIPO=66 AND ESTCODIGO<>3
                ORDER BY PERCLASE ";
    $Table = sql_query($query,$conn);
    for($j=0; $j<$Table->Rows_Count; $j++){
        $rowClase = $Table->Rows[$j];
        $usuarioregistro 	= trim($rowClase['PERCLASE']);

    $queryBusco = "	SELECT VARTITULO
    FROM VAR_MAEST WHERE USUARIO=$usuarioregistro AND VARREG = $varreg";

    $TableBusco = sql_query($queryBusco, $conn);

        if($TableBusco->Rows_Count<0){

        if (!$vartipo){
            $vartipo = 0;
        }
    	
        $queryInserto = " 	INSERT INTO VAR_MAEST(VARREG,VARDESCRI,VARDESCRIING,VARDESCRIPOR,VARMOST,VARREQ,VARTITULO,VARTIPO,VAROPC,USUARIO)
						VALUES($varreg,'$vardescri','$vardescriing','$vardescripor','$mostrar','$requerida','$vartitulo',$vartipo,'$varopc',$usuarioregistro) ";


		$err = sql_execute($queryInserto,$conn,$trans);
    
        }

        }

    }




if ($err == 'SQLACCEPT' && $errcod == 0) {
    sql_commit_trans($trans);
    $errcod = 0;
    $errmsg = TrMessage('Guardado correctamente!');
} else {
    sql_rollback_trans($trans);
    $errcod = 2;
    $errmsg = ($errmsg == '') ? TrMessage('Error al guardar!') : $errmsg;
}

sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
