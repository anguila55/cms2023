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


$pathimagenes = '../assets-nuevodisenio/img/';
//Control de Datos
$accreg = (isset($_POST['accreg'])) ? trim($_POST['accreg']) : '';
$acctitulo = (isset($_POST['acctitulo'])) ? trim($_POST['acctitulo']) : '';
$accmostrar = (isset($_POST['accmostrar'])) ? trim($_POST['accmostrar']) : '';
$acchref = (isset($_POST['acchref'])) ? trim($_POST['acchref']) : '';

$accesos = json_decode((isset($_POST['accesos'])) ? trim($_POST['accesos']) : [], true);
$enviadoDesde = (isset($_POST['enviadoDesde'])) ? trim($_POST['enviadoDesde']) : '';

//--------------------------------------------------------------------------------------------------------------

$acctitulo = VarNullBD($acctitulo, 'S');
$acchref = VarNullBD($acchref, 'S');


if ($accreg == 0) {
    //- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //Genero un ID
    $query = 'SELECT GEN_ID(G_ACCDIREC,1) AS ID FROM RDB$DATABASE';
    $TblId = sql_query($query, $conn, $trans);
    $RowId = $TblId->Rows[0];
    $accreg = trim($RowId['ID']);

    $accreg = VarNullBD($accreg, 'N');
    //- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $query = " 	INSERT INTO ACC_MAEST(ACCREG,ACCTITULO, ACCMOSTRAR, ACCHREF, ESTCODIGO)
					VALUES($accreg,$acctitulo, 'false', $acchref,1) ";

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
                    ACCTITULO=$acctitulo, ACCHREF=$acchref 
                   WHERE ACCREG=$accreg";
    }
}

$err = sql_execute($query, $conn, $trans);

if (isset($_FILES['accicono'])) {

	$ext 	= pathinfo($_FILES['accicono']['name'], PATHINFO_EXTENSION);
	//$name 	= 'SPKIMAGEN'.date(mktime(0, 0, 0, 7, 1, 2000)).'.'.$ext;
	$name 	= 'ACCICONO' . date('His') . rand(100, 200) . '.' . $ext;  //$file['name'];

	
	move_uploaded_file($_FILES['accicono']['tmp_name'], $pathimagenes.$name);

	//$_SESSION[GLBAPPPORT . 'ACCICONO'] =  $pathimagenes . $spkreg . '/' . $name; //Actualizo la variable de Session del AVATAR

	$query = "	UPDATE ACC_MAEST SET ACCICONO='$name' WHERE ACCREG=$accreg ";

	//-------------Redimension de imagen----------------------------------//
	$imagen_optimizada = redimensionar_imagen($name, $pathimagenes.$name, 200, 200);

	//Guardado de imagen
	imagepng($imagen_optimizada, $pathimagenes.$name);

	$err = sql_execute($query, $conn, $trans);
}




//--------------------------------------------------------------------------------------------------------------
if ($err == 'SQLACCEPT' && $errcod == 0) {
    sql_commit_trans($trans);
    $errcod = 0;
    $errmsg = TrMessage('Guardado correctamente!');
} else {
    sql_rollback_trans($trans);
    $errcod = 2;
    $errmsg = ($errmsg == '') ? TrMessage('No se ha podido guardar!') : $errmsg;
}
//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
echo '{"errcod":"' . $errcod . '","errmsg":"' . $errmsg . '"}';
