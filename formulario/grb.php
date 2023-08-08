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
$variableselegidas = json_decode((isset($_POST['variableselegidas'])) ? trim($_POST['variableselegidas']) : '', true);
$variablesrequeridas = json_decode((isset($_POST['variablesrequeridas'])) ? trim($_POST['variablesrequeridas']) : '', true);
$dataNombreEsp	= (isset($_POST['dataNombreEsp']))? trim($_POST['dataNombreEsp']) : '';
$dataNombreIng	= (isset($_POST['dataNombreIng']))? trim($_POST['dataNombreIng']) : '';
$dataNombrePor	= (isset($_POST['dataNombrePor']))? trim($_POST['dataNombrePor']) : '';
$dataNombrePor	= (isset($_POST['dataNombrePor']))? trim($_POST['dataNombrePor']) : '';
$usuarioregistro	= (isset($_POST['usuarioregistro']))? trim($_POST['usuarioregistro']) : 0;

$where = '';

$usuarioregistro		= VarNullBD($usuarioregistro		, 'N');
// REVISO SI ESTAN LAS VARIABLES DE LA CLASE DE PERFIL CREADAS, SINO LAS CREO
   
    $where .= "AND USUARIO = $usuarioregistro"; 
       
    //- - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    foreach($variableselegidas as $variable)
{
    $query = "UPDATE VAR_MAEST SET VARMOST = '$variable[elegido]'
     WHERE VARTITULO= '$variable[nombre]' $where ";

   $err = sql_execute($query, $conn, $trans);
}



foreach($variablesrequeridas as $variable)
{
    $query = "UPDATE VAR_MAEST SET VARREQ = '$variable[requerida]'
     WHERE VARTITULO= '$variable[nombre]' $where";
    $err = sql_execute($query, $conn, $trans);
}



if($err == 'SQLACCEPT' && $errcod==0){
		
    if($dataNombreEsp!=''){
    
        
        $dataNombreEsp = json_decode($dataNombreEsp);
        foreach($dataNombreEsp as $ind => $data){
            $perm 	= $data->perm;
            $tipori 	= $data->tipori;

            
            $pertipo			= VarNullBD($tipori        , 'S');
            $pertipoperm		= VarNullBD($perm    , 'N');
            


            $query = " 	UPDATE VAR_MAEST SET 
                            VARDESCRI=$pertipo
                            WHERE VARREG=$pertipoperm $where";
               
            $err = sql_execute($query, $conn, $trans);

            
            
        }
    }
}



if($err == 'SQLACCEPT' && $errcod==0){
    
    if($dataNombreIng!=''){

        
        
        $dataNombreIng = json_decode($dataNombreIng);
        foreach($dataNombreIng as $ind => $data){
            $perm 	= $data->perm;
            $tipori 	= $data->tipori;

            
            $pertipo			= VarNullBD($tipori        , 'S');
            $pertipoperm		= VarNullBD($perm    , 'N');
            


            $query = " 	UPDATE VAR_MAEST SET 
                            VARDESCRIING=$pertipo
                            WHERE VARREG=$pertipoperm $where";

            
            $err = sql_execute($query, $conn, $trans);

            
        }
    }
}


if($err == 'SQLACCEPT' && $errcod==0){


    
    if($dataNombrePor!=''){
        
        $dataNombrePor = json_decode($dataNombrePor);
        foreach($dataNombrePor as $ind => $data){
            $perm 	= $data->perm;
            $tipori 	= $data->tipori;

            
            $pertipo			= VarNullBD($tipori        , 'S');
            $pertipoperm		= VarNullBD($perm    , 'N');
            


            $query = " 	UPDATE VAR_MAEST SET 
                            VARDESCRIPOR=$pertipo
                            WHERE VARREG=$pertipoperm $where";
            
            $err = sql_execute($query, $conn, $trans);

    
            
            
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
