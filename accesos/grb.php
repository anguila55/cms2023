<?php include '../val/valuser.php';?>
<?
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaFUNC . '/idioma.php'; //Idioma

//--------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------
$errcod = 0;
$errmsg = '';
$err = 'SQLACCEPT';
//--------------------------------------------------------------------------------------------------------------
$conn = sql_conectar(); //Apertura de Conexion
$trans = sql_begin_trans($conn);

//Control de Datos
$accreg = (isset($_POST['accreg'])) ? trim($_POST['accreg']) : '';
$acctitulo = (isset($_POST['acctitulo'])) ? trim($_POST['acctitulo']) : '';
$accmostrar = (isset($_POST['accmostrar'])) ? trim($_POST['accmostrar']) : '';
$acchref = (isset($_POST['acchref'])) ? trim($_POST['acchref']) : '';
$accicono = (isset($_POST['accicono'])) ?   trim($_POST['accicono']) : '';

$accesos = json_decode((isset($_POST['accesos'])) ? trim($_POST['accesos']) : [], true);
$enviadoDesde = (isset($_POST['enviadoDesde'])) ? trim($_POST['enviadoDesde']) : '';

//--------------------------------------------------------------------------------------------------------------
$accreg = VarNullBD($accreg, 'N');
$acctitulo = VarNullBD($acctitulo, 'S');
$acchref = VarNullBD($acchref, 'S');
$accicono = VarNullBD($accicono, 'S');

if ($accreg == 0) {
    //- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //Genero un ID
    $query = 'SELECT GEN_ID(G_ACCDIREC,1) AS ID FROM RDB$DATABASE';
    $TblId = sql_query($query, $conn, $trans);
    $RowId = $TblId->Rows[0];
    $accreg = trim($RowId['ID']);
    //- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $query = " 	INSERT INTO ACC_MAEST(ACCREG,ACCTITULO, ACCMOSTRAR, ACCHREF, ACCICONO, ESTCODIGO)
					VALUES($accreg,$acctitulo, 'false', $acchref, $accicono,1) ";
} else {
    if ($enviadoDesde === 'browser') {
        foreach ($accesos as $acceso) {

            $query2 = " UPDATE ACC_MAEST SET
					 ACCMOSTRAR='$acceso[checked]'
					WHERE ACCREG=$acceso[id]";
            sql_query($query2, $conn);
        }
    } else {

        $query = "UPDATE ACC_MAEST SET
                    ACCTITULO=$acctitulo, ACCHREF=$acchref, ACCICONO=$accicono 
                   WHERE ACCREG=$accreg";
    }
}

$err = sql_execute($query, $conn, $trans);

//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
    sql_commit_trans($trans);
    $errcod = 0;
    $errmsg = TrMessage('Guardado correctamente!');
} else {
    sql_rollback_trans($trans);
    $errcod = 2;
    $errmsg = ($errmsg == '') ? TrMessage('Guardado correctamente!') : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
