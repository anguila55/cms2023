<?php
include $_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'; //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';
require_once GLBRutaAPI  . '/timezone.php';


$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';

$hoyfecha = date('Y-m-d');

if(!isset($_SESSION[GLBAPPPORT.'FECHATIMEZONE']) || $_SESSION[GLBAPPPORT.'FECHATIMEZONE']< $hoyfecha){
	
	
	$conn = sql_conectar(); //Apertura de Conexion
	if ($percodigo!=''){
		$_SESSION[GLBAPPPORT.'FECHATIMEZONE']= $hoyfecha;
		$query = " 	SELECT P.TIMREG2,P.TIMOFFSET,P.PERCODIGO
						FROM PER_MAEST P
						WHERE P.PERCODIGO=$percodigo AND P.ESTCODIGO!=3 ";

        $Table = sql_query($query, $conn);
        if ($Table->Rows_Count > 0) {
            $row = $Table->Rows[0];
			$timreg2 = trim($row['TIMREG2']);
            $timoffset2 = trim($row['TIMOFFSET']);
			$percodigo2 = trim($row['PERCODIGO']);

			///////////////// Obetengo Timeoffset de la api//////////
			$timoffset=strval(getTimeZone($timreg2));
			/////////////////////////////////////////////////////////	
			
			if ($timoffset != $timoffset2){
				$query = " 	UPDATE PER_MAEST SET TIMOFFSET=$timoffset
				WHERE PERCODIGO=$percodigo2 ";
				$err = sql_execute($query,$conn);
		
				//Establezco la zona horaria
				if($percodigo2 == $_SESSION[GLBAPPPORT.'PERCODIGO']){ //Si el usuario es el logueado
					$_SESSION[GLBAPPPORT.'TIMOFFSET'] 	= $timoffset;
				}
			}
		}
	}
	
}
sql_close($conn);
?>
