<?php
require('../func/constants.php');
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
require_once GLBRutaFUNC . '/zdatabase.php';


function updateUser( $payload, $conn ) {
	if (filter_var($payload->email, FILTER_VALIDATE_EMAIL) && $payload->first_name && $payload->last_name){
		$estcodigo1=1;
		$otro = 37;
		$otro1 = '';
		$email      = $payload->email;
		$first_name = $payload->first_name;
		$last_name     = $payload->last_name;
		$last_name = str_replace("'", "", $last_name);
		
		$company  = $payload->company;
		$company = str_replace("'", "", $company);
		$position  = $payload->position;
		$position = str_replace("'", "", $position);
		$phone   = $payload->phone;
		$paisbase = 77;

		$country = is_null($payload->country) ? $paisbase : $payload->country;

		$subindustria_id = is_null($payload->subindustria_id) ? $otro : $payload->subindustria_id;
		$language_id     = $payload->language_id;

		$linkedin     = is_null($payload->linkedin) ? $otro1 : $payload->linkedin;
		$profile_picture  = is_null($payload->profile_picture) ? $otro1 : $payload->profile_picture;
		$status  = $payload->status;
		if ($status=='deleted'){

			$estcodigo1=3;
		}
		$hash   = is_null($payload->hash) ? $otro1 : $payload->hash;
		

		if ($status=='confirmed' || $status=='deleted'){

			// BUSCAMOS EL TIME REG
			$querytime= "SELECT TIMEREG FROM TBL_PAIS WHERE PAICODIGO=$country";
			
			$Tabletime = sql_query($querytime,$conn);
			if($Tabletime->Rows_Count>0){
				$rowtime= $Tabletime->Rows[0];
				$timereg 	= trim($rowtime['TIMEREG']);
			}
			$querysec= "SELECT SECCODIGO FROM SEC_SUB WHERE SECSUBCOD=$subindustria_id";
			
			$Tablesec = sql_query($querysec,$conn);
			if($Tablesec->Rows_Count>0){
				$rowsec= $Tablesec->Rows[0];
				$industria_id 	= trim($rowsec['SECCODIGO']);
			}

			$query = "SELECT PERCODIGO,PERCORREO FROM PER_MAEST WHERE PERCORREO='$email'";

			$Table = sql_query( $query, $conn );
			if ( $Table->Rows_Count > 0 ) {
				$row= $Table->Rows[0];
				$percodigo 	= trim($row['PERCODIGO']);
				
				$query = "UPDATE PER_MAEST SET 
				PERNOMBRE='$first_name',PERAPELLI='$last_name',PERCARGO='$position',PERCOMPAN='$company',PERTELEFO = '$phone', ESTCODIGO=$estcodigo1, PERUSUACC='$email', PERIDIOMA='$language_id', PERIMPEVE=1, PERURLWEB='$linkedin', PERAVATAR='$profile_picture', PAICODIGO = $country,PAICODIGO2 = $country, PERPARNOM2='$hash', TIMREG = $timereg
							WHERE PERCORREO='$email'";
				$err   = sql_execute( $query, $conn );

				/////elimino sectores y subsectores

				$querysecdel=" DELETE FROM PER_SECT WHERE PERCODIGO=$percodigo ";
				$err = sql_execute($querysecdel,$conn);
				$querysubsecdel=" DELETE FROM PER_SSEC WHERE PERCODIGO=$percodigo ";
				$err = sql_execute($querysubsecdel,$conn);

				/////inserto actualizado
				///// guardo sector/////////
				$querysectores = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
							VALUES($percodigo,$industria_id,'V')";
				$err = sql_execute($querysectores,$conn);

				///// guardo subsector
				$querysubsectores = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
								VALUES($percodigo,$subindustria_id,'V')";
					$err = sql_execute($querysubsectores,$conn);

				echo json_encode(['status' => 200, 'message' => 'Usuarios actualizados correctamente.']);
			} else {
				$pass      = password_hash( rand( 8, 10 ), PASSWORD_DEFAULT );
				$query     = "SELECT FIRST 1 PERCODIGO FROM PER_MAEST ORDER BY PERCODIGO DESC ";
				$last      = sql_query( $query, $conn );
				$percodigo = $last->Rows[0]['PERCODIGO'] + 1;
				$query     = " 	INSERT INTO PER_MAEST(PERCODIGO, PERNOMBRE, PERAPELLI, PERCARGO, PERCOMPAN, PERCORREO, PERTELEFO,ESTCODIGO,PERUSUACC,PERTIPO,PERCLASE,PERIMPEVE,PERIDIOMA,PERURLWEB,PERAVATAR,PAICODIGO,PAICODIGO2,PERPARNOM2,PERPOP,TIMREG)
				VALUES($percodigo, '$first_name', '$last_name', '$position', '$company' ,'$email' ,'$phone',$estcodigo1,'$email',66,53, 1,'$language_id','$linkedin','$profile_picture',$country,$country,'$hash',0,$timereg) ";
				$err       = sql_execute( $query, $conn );

				///// guardo sector/////////
				$querysectores = "	INSERT INTO PER_SECT(PERCODIGO,SECCODIGO,PERVENCOM)
							VALUES($percodigo,$industria_id,'V')";
				$err = sql_execute($querysectores,$conn);

				///// guardo subsector
				$querysubsectores = "	INSERT INTO PER_SSEC(PERCODIGO,SECSUBCOD,PERVENCOM)
								VALUES($percodigo,$subindustria_id,'V')";
					$err = sql_execute($querysubsectores,$conn);
				echo json_encode(['status' => 200, 'message' => 'Usuario creado correctamente.']);
			}
		}
		
	}else{
		echo json_encode(['status' => 400 , 'message' => 'usuario encontrado pero campo(s) incorrectos']);
	}
}

function updateUsers()
{
    
    try {
        if ( isset($_SERVER['PHP_AUTH_PW']) == CMS_PASS && isset($_SERVER['PHP_AUTH_USER']) == CMS_USER ) {
            $payload = json_decode( file_get_contents( 'php://input' ) );
            $conn = sql_conectar();
            updateUser( $payload, $conn);
        }else{
			echo json_encode(['status' => 403 , 'message' => 'Credenciales erroneas']);
		}
    } catch (Exception $e) {
        echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
updateUsers();

?>