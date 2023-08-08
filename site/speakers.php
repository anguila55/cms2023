<?php 

if (!isset($_SESSION))  session_start();
// include($_SERVER["DOCUMENT_ROOT"] . '/congresoaapresid/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"] . '/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC . '/sigma.php';
require_once GLBRutaFUNC . '/zdatabase.php';
require_once GLBRutaFUNC . '/zfvarias.php';

$tmpl = new HTML_Template_Sigma();
$tmpl->loadTemplateFile('speakers.html');

//--------------------------------------------------------------------------------------------------------------
$percodigo = (isset($_SESSION[GLBAPPPORT . 'PERCODIGO'])) ? trim($_SESSION[GLBAPPPORT . 'PERCODIGO']) : '';
$pernombre = (isset($_SESSION[GLBAPPPORT . 'PERNOMBRE'])) ? trim($_SESSION[GLBAPPPORT . 'PERNOMBRE']) : '';

// $fltdescri = (isset($_POST['fltdescri']))? trim($_POST['fltdescri']):'';
// //Filtro de busqueda por titulo
// $where = '';
// if($fltdescri!=''){
// 	$where .= " AND AVITITULO CONTAINING '$fltdescri' ";
// }

$conn = sql_conectar(); //Apertura de Conexion

$query = "SELECT *
				FROM SPK_MAEST
				WHERE ESTCODIGO<>3
				ORDER BY SPKPOS ASC";

//logerror($query);
$Table = sql_query($query, $conn);
for ($i = 0; $i < $Table->Rows_Count; $i++) {
	$row = $Table->Rows[$i];
	$spkreg 	= trim($row['SPKREG']);
	$spktitulo 	= trim($row['SPKNOMBRE']);
	$spkdescri  = trim($row['SPKDESCRI']);
	$spkpos     = trim($row['SPKPOS']);
	$spkempres     = trim($row['SPKEMPRES']);
	$spkimg     = trim($row['SPKIMG']);
	$spkcargo     = trim($row['SPKCARGO']);
	$spklinked     = trim($row['SPKLINKED']);
	//$aviimagen  = trim($row['AVIIMAGEN']);
	if ($spklinked != '') {
		$tmpl->setVariable('cssactive', 'color:#292ECE');
	}else{
		
		$tmpl->setVariable('cssactive', 'color:silver');
		$tmpl->setVariable('inactivo', 'inactivo');
	}

	$tmpl->setCurrentBlock('speakers');
	$tmpl->setVariable('spkreg', $spkreg);
	$tmpl->setVariable('spktitulo', $spktitulo);
	$tmpl->setVariable('spkdescri', $spkdescri);
	$tmpl->setVariable('spkempres', $spkempres);
	$tmpl->setVariable('spkcargo', $spkcargo);
	$tmpl->setVariable('spkimg', '../spkimg/' . $spkreg . '/' . $spkimg);
	$tmpl->setvariable('spkpos', $spkpos);
    $tmpl->setvariable('spklinked', $spklinked);
    
/* ---------------------------- Inicio activades ---------------------------- */

    $queryspk = "SELECT  AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG
    FROM AGE_MAEST
    WHERE   ESTCODIGO <> 3
    ORDER BY AGEHORINI";
    
        $Tablespk = sql_query($queryspk, $conn);

        for ($l = 0; $l < $Tablespk->Rows_Count; $l++) {
        $row = $Tablespk->Rows[$l];
        $ageregspk 	= trim($row['AGEREG']);
        $agetitulospk 	= trim($row['AGETITULO']);
        $agedescrispk 	= trim($row['AGEDESCRI']);
        $agelugarspk   = trim($row['AGELUGAR']);
        $spkregspk   	= trim($row['SPKREG']);
        $agefchspk   = BDConvFch($row['AGEFCH']);
        $agehorinispk  = substr(trim($row['AGEHORINI']), 0, 5);
        $agehorfinspk  = substr(trim($row['AGEHORFIN']), 0, 5);


        $agefchspk	= substr($agefchspk, 6, 4) . '-' . substr($agefchspk, 3, 2) . '-' . substr($agefchspk, 0, 2); //Formato calendario (yyyy-mm-dd)
        $agehorinispk = ($agehorinispk != '') ?  $agehorinispk : '';
        $agehorfinspk = ($agehorfinspk != '') ?  $agehorfinspk : '';

        $spekArrayspk  =  explode(',',$spkregspk);

            if (in_array($spkreg, $spekArrayspk)) {
                $spekArrayspk  =  implode(',',$spekArrayspk);
              
                $queryspk2 = "SELECT  AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG
                FROM AGE_MAEST
                WHERE   ESTCODIGO <> 3 AND SPKREG = '$spekArrayspk'
                ORDER BY AGEHORINI";
                
                $Tablespk2 = sql_query($queryspk2, $conn);

                for ($k = 0; $k < $Tablespk2->Rows_Count; $k++) {
                    $row = $Tablespk2->Rows[$k];
                    $ageregspk2 	= trim($row['AGEREG']);
                    $agetitulospk2 	= trim($row['AGETITULO']);
                    $agedescrispk2 	= trim($row['AGEDESCRI']);
                    $agelugarspk2   = trim($row['AGELUGAR']);
                    $spkregspk2   	= trim($row['SPKREG']);
                    $agefchspk2     = BDConvFch($row['AGEFCH']);
                    $agehorinispk2  = substr(trim($row['AGEHORINI']), 0, 5);
                    $agehorfinspk2  = substr(trim($row['AGEHORFIN']), 0, 5);


                    $tmpl->setCurrentBlock('actividadesfirst');
                    // logerror('existe dentro del array');
                    $tmpl->setVariable('ageregspk2', $ageregspk2);
                    $tmpl->setVariable('titulocharlaspk2', $agetitulospk2);
                    $tmpl->setVariable('agedescrispk2', $agedescrispk2);
                    $tmpl->setvariable('agelugarspk2', $agelugarspk2);
                    $tmpl->setVariable('agehorinispk2', $agehorinispk2);
                    $tmpl->setVariable('agehorfinspk2', $agehorfinspk2);
                    // $tmpl->setVariable('color', $coloReunionAge);
                    $tmpl->setVariable('agefchspk2', $agefchspk2);
                    $tmpl->parse('actividadesfirst');
                }

            }

}

/* ----------------------------- END actividades ---------------------------- */
//$tmpl->setvariable('aviimagen',$aviimagen);
$tmpl->parse('speakers');
}



/* -------------------------------------------------------------------------- */
/*                                   MODALES                                   */
/* -------------------------------------------------------------------------- */

        $queryM2 = "	SELECT *
                        FROM SPK_MAEST
                        WHERE ESTCODIGO<>3
                        ORDER BY SPKPOS DESC";


        $Tablem2 = sql_query($queryM2, $conn);
        for ($j = 0; $j < $Tablem2->Rows_Count; $j++) {
            $row = $Tablem2->Rows[$j];
            $spkregm2	= trim($row['SPKREG']);
            $spktitulom2	= trim($row['SPKNOMBRE']);
            $spkdescrim2 = trim($row['SPKDESCRI']);
            $spkposm2    = trim($row['SPKPOS']);
            $spkempresm2    = trim($row['SPKEMPRES']);
            $spkimgm2    = trim($row['SPKIMG']);
            $spkcargom2    = trim($row['SPKCARGO']);
            $spklinkedm2    = trim($row['SPKLINKED']);
            //$aviimagen  = trim($row['AVIIMAGEN']);
            if ($spklinkedm2 != '') {
                $tmpl->setVariable('cssactivem2', 'color:#292ECE');
            }else{
                
                $tmpl->setVariable('cssactivem2', 'color:silver');
                $tmpl->setVariable('inactivom2', 'inactivo');
            }

            $tmpl->setCurrentBlock('modalspeakers');
            $tmpl->setVariable('spkregm2', $spkregm2);
            $tmpl->setVariable('spktitulom2', $spktitulom2);
        
            $tmpl->setVariable('spkdescrim2', $spkdescrim2);
            $tmpl->setVariable('spkempresm2', $spkempresm2);
            $tmpl->setVariable('spkcargom2', $spkcargom2);
            $tmpl->setVariable('spkimgm2', 'spkimg/' . $spkregm2 . '/' . $spkimgm2);
            $tmpl->setvariable('spkposm2', $spkposm2);
            $tmpl->setvariable('spklinkedm2', $spklinkedm2);
            

            $queryspkm2= "SELECT  AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG
            FROM AGE_MAEST
            WHERE   ESTCODIGO <> 3
            ORDER BY AGEHORINI";
            
                $Tablespkm2 = sql_query($queryspkm2, $conn);

                for ($l = 0; $l < $Tablespkm2->Rows_Count; $l++) {
                $row = $Tablespkm2->Rows[$l];
                $ageregspkm2 	= trim($row['AGEREG']);
                $agetitulospkm2 	= trim($row['AGETITULO']);
                $agedescrispkm2 	= trim($row['AGEDESCRI']);
                $agelugarspkm2   = trim($row['AGELUGAR']);
                $spkregspkm2   	= trim($row['SPKREG']);
                $agefchspkm2     = BDConvFch($row['AGEFCH']);
                $agehorinispkm2  = substr(trim($row['AGEHORINI']), 0, 5);
                $agehorfinspkm2  = substr(trim($row['AGEHORFIN']), 0, 5);


                $agefchspkm2	= substr($agefchspkm2, 6, 4) . '-' . substr($agefchspkm2, 3, 2) . '-' . substr($agefchspkm2, 0, 2); //Formato calendario (yyyy-mm-dd)
                $agehorinispkm2 = ($agehorinispkm2 != '') ?  $agehorinispkm2 : '';
                $agehorfinspkm2 = ($agehorfinspkm2 != '') ?  $agehorfinspkm2 : '';




                $spekArrayspkm2  =  explode(',',$spkregspkm2);


                    if (in_array($spkregm2, $spekArrayspkm2)) {
                        $spekArrayspkm2  =  implode(',',$spekArrayspkm2);
                       
                        $querySbk = "SELECT  AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG
                        FROM AGE_MAEST
                        WHERE   ESTCODIGO <> 3 AND SPKREG = '$spekArrayspkm2'
                        ORDER BY AGEHORINI";
                        
                        $TableSbk = sql_query($querySbk, $conn);

                        for ($k = 0; $k < $TableSbk->Rows_Count; $k++) {
                            $row = $TableSbk->Rows[$k];
                            $ageregSbk 	= trim($row['AGEREG']);
                            $agetituloSbk 	= trim($row['AGETITULO']);
                            $agedescriSbk 	= trim($row['AGEDESCRI']);
                            $agelugarSbk   = trim($row['AGELUGAR']);
                            $spkregSbk   	= trim($row['SPKREG']);
                            $agefchSbk     = BDConvFch($row['AGEFCH']);
                            $agehoriniSbk  = substr(trim($row['AGEHORINI']), 0, 5);
                            $agehorfinSbk  = substr(trim($row['AGEHORFIN']), 0, 5);


                            $tmpl->setCurrentBlock('actividades');
                            // logerror('existe dentro del array');
                            $tmpl->setVariable('ageregSbk', $ageregSbk);
                            $tmpl->setVariable('titulocharlaSbk', $agetituloSbk);
                            $tmpl->setVariable('agedescriSbk', $agedescriSbk);
                            $tmpl->setvariable('agelugarSbk', $agelugarSbk);
                            $tmpl->setVariable('agehoriniAgeSbk', $agehoriniSbk);
                            $tmpl->setVariable('agehorfinAgeSbk', $agehorfinSbk);
                            // $tmpl->setVariable('color', $coloReunionAge);
                            $tmpl->setVariable('agefchSbk', $agefchSbk);
                            $tmpl->parse('actividades');
                        }

                    }


        }
        
            //$tmpl->setvariable('aviimagen',$aviimagen);
            $tmpl->parse('modalspeakers');
        }
        


$coloReunionact	= '#967ADC';
$colorBloqueadoact	= '#FFAD8F';
$whereact 	= '';

$queryact = "SELECT  AGEREG, AGEFCH, AGETITULO, AGEDESCRI, AGEHORINI, AGEHORFIN, AGELUGAR , SPKREG
			FROM AGE_MAEST
			WHERE   ESTCODIGO <> 3
			ORDER BY AGEHORINI";

$Tableact = sql_query($queryact, $conn);

for ($e = 0; $e < $Tableact->Rows_Count; $e++) {
	$row = $Tableact->Rows[$e];
	$ageregact 	= trim($row['AGEREG']);
	$agetituloact 	= trim($row['AGETITULO']);
	$agedescriact 	= trim($row['AGEDESCRI']);
	$agelugaract   = trim($row['AGELUGAR']);
	$spkregact   	= trim($row['SPKREG']);
	$agefchact     = BDConvFch($row['AGEFCH']);
	$agehoriniact  = substr(trim($row['AGEHORINI']), 0, 5);
	$agehorfinact  = substr(trim($row['AGEHORFIN']), 0, 5);


	$agefchact	= substr($agefchact, 6, 4) . '-' . substr($agefchact, 3, 2) . '-' . substr($agefchact, 0, 2); //Formato calendario (yyyy-mm-dd)
	$agehoriniact = ($agehoriniact != '') ?  $agehoriniact : '';
	$agehorfinact = ($agehorfinact != '') ?  $agehorfinact : '';


	$tmpl->setCurrentBlock('actividadesmodal');

	
	$spekArrayact  =  explode(',',$spkregact);
		
	foreach ($spekArrayact as $key => $value) {

		if($value != 0){
			$queryspk1 = "	SELECT SPKREG, SPKNOMBRE, SPKDESCRI, SPKIMG, SPKPOS, ESTCODIGO,SPKEMPRES,SPKCARGO
				FROM SPK_MAEST
				WHERE SPKREG=$value";
				
			$Tablespk1 = sql_query($queryspk1, $conn);
			if ($Tablespk1->Rows_Count > 0) {

				$rowspk = $Tablespk1->Rows[0];
				$spkimg1  	= trim($rowspk['SPKIMG']);
				$spkregnew1  	= trim($rowspk['SPKREG']);
				$spknombrenw  	= trim($rowspk['SPKNOMBRE']);
				$tmpl->setCurrentBlock('spkimg12');
				$tmpl->setVariable('spkimg1', $spkimg1);
				$tmpl->setVariable('spkreg1', $spkregnew1);
				$tmpl->setVariable('spknombrenw', $spknombrenw);
				$tmpl->parse('spkimg12');

			}
		}
    }
    

    
 //Cambiamos el formato de fecha a d/m/y
    $agefchactm = date("d/m/Y", strtotime($agefchact));

	$tmpl->setVariable('ageregact', $ageregact);
	$tmpl->setVariable('titulocharlaact', $agetituloact);
	$tmpl->setVariable('agedescriact', $agedescriact);
	$tmpl->setvariable('agelugaract', $agelugaract);
	$tmpl->setVariable('agehoriniact', $agehoriniact);
	$tmpl->setVariable('agehorfinact', $agehorfinact);
	$tmpl->setVariable('coloract', $coloReunionact);
	$tmpl->setVariable('agefchact', $agefchactm);
	$tmpl->parse('actividadesmodal');
}








//--------------------------------------------------------------------------------------------------------------
sql_close($conn);
$tmpl->show();

?>	
