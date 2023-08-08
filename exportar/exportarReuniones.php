<?php include('../val/valuser.php'); ?>
<?
	//--------------------------------------------------------------------------------------------------------------
	require_once GLBRutaFUNC.'/sigma.php';	
	require_once GLBRutaFUNC.'/zdatabase.php';
	require_once GLBRutaFUNC.'/zfvarias.php';
    require_once 'Classes/PHPExcel.php';
   
	$peradmin = (isset($_SESSION[GLBAPPPORT.'PERADMIN']))? trim($_SESSION[GLBAPPPORT.'PERADMIN']) : '';
    //verificamos is es administrador
    if($peradmin!=1){
		header('Location: ../index');
    }

         //fechas
         $fechas = (isset($_GET['ID']))? trim($_GET['ID']):''; //Nota desde el home acceso directo

         if($fechas!=''){
             $vaux = explode('||',$fechas);
             if(count($vaux)>1){
                 $desde = $vaux[0];
                 $hasta = $vaux[1];
 
                 if($desde != ''){
                    $desde	= substr($desde, 8, 2) . '.' . substr($desde, 5, 2) . '.' . substr($desde, 0, 4); 
                }
                if($hasta != ''){
                    $hasta	= substr($hasta, 8, 2) . '.' . substr($hasta, 5, 2) . '.' . substr($hasta, 0, 4);
                }
 
             }
         }
         if(!$desde && !$hasta){
             $and = "";
         }
         elseif($desde && !$hasta){
             $and = "AND R.REUFECHA >= '$desde'";
         }elseif(!$desde && $hasta){
             $and = "AND R.REUFECHA <= '$hasta'";
         }else{
             $and = "AND (R.REUFECHA >= '$desde' AND R.REUFECHA <= '$hasta')";
         }

    
    $conn= sql_conectar();//Apertura de Conexion
    
    $query="SELECT R.REUREG, R.REUFCHREG, 
    R.PERCODSOL,R.PERCODDST,
    R.REUESTADO,R.REUFECHA,R.REUHORA,R.REUFCHCAN,
    R.AGEREG,Z.REUHORA AS HORAALT, Z.REUFECHA AS REUFECHAALT,
    PS.PERCODIGO AS PERCODSOL,PS.PERCOMPAN AS SOLEMPRESA,PS.PERNOMBRE AS SOLNOMBRE,PS.PERAPELLI AS SOLAPELLI,PS.PERCORREO AS SOLCORREO,
    PD.PERCODIGO AS PERCODDST,PD.PERCOMPAN AS DSTEMPRESA,PD.PERNOMBRE AS DSTNOMBRE,PD.PERAPELLI AS DSTAPELLI,PD.PERCORREO AS DSTCORREO,
    M.MESNUMERO AS MESA,R.REUTIPO, Z.REUHORA AS HORARIO2, Z.REUFECHA AS FECHA2
FROM REU_CABE R
LEFT OUTER JOIN REU_SOLI Z ON Z.REUREG=R.REUREG
LEFT OUTER JOIN PER_MAEST PS ON R.PERCODSOL=PS.PERCODIGO
LEFT OUTER JOIN PER_MAEST PD ON R.PERCODDST=PD.PERCODIGO
LEFT OUTER JOIN MES_MAEST M ON M.MESCODIGO=R.MESCODIGO
WHERE R.REUESTADO!=5 $and
ORDER BY R.REUFCHREG 
            ";
    $Table = sql_query($query,$conn);
    //logerror($query);
    $objPHPExcel = new PHPExcel();
// Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'Reg.')
                ->setCellValue('B1', 'Fecha Registro')
                ->setCellValue('C1', 'Empresa Solicitante')
				->setCellValue('D1', 'Nombre Soliticante')
				->setCellValue('E1', 'Apellido Soliticante')
				->setCellValue('F1', 'Correo Soliticante')
                ->setCellValue('G1', 'Empresa Destino')
				->setCellValue('H1', 'Nombre Destino')
				->setCellValue('I1', 'Apellido Destino')
				->setCellValue('J1', 'Correo Destino')
                ->setCellValue('K1', 'Estado')
                ->setCellValue('L1', 'Fecha')
                ->setCellValue('M1', 'Hora')
                ->setCellValue('N1', 'Fecha Cancelacion')
                ->setCellValue('O1', 'Mesa')
                ->setCellValue('P1', 'Conectado Solicitante')
                ->setCellValue('Q1', 'Conectado Destino')
                ->setCellValue('R1', 'Tipo de Reunion')
               ;
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    $reureg 	= trim($row['REUREG']);
                    $reufchreg	= trim($row['REUFCHREG']);
                    $solempresa	= trim($row['SOLEMPRESA']);
					$solnombre	= trim($row['SOLNOMBRE']);
					$solapelli	= trim($row['SOLAPELLI']);
					$solcorreo	= trim($row['SOLCORREO']);
                    $dstempresa	= trim($row['DSTEMPRESA']);
					$dstnombre	= trim($row['DSTNOMBRE']);
					$dstapelli	= trim($row['DSTAPELLI']);
					$dstcorreo	= trim($row['DSTCORREO']);
                    $reuestado 	= trim($row['REUESTADO']);
                    $reufecha 	= trim($row['REUFECHA']);
                    $reuhora 	= trim($row['REUHORA']);
                    $reuhora2 	= trim($row['HORARIO2']);
                    $reufecha2 	= trim($row['FECHA2']);
                    $reufchcan 	= trim($row['REUFCHCAN']);
                    $mesa 		= trim($row['MESA']);
                    $percodsol 		= trim($row['PERCODSOL']);
                    $percoddst 		= trim($row['PERCODDST']);
                    $reutipo 		= trim($row['REUTIPO']);
                    $conectadosol='';
                    $conectadodst='';
                    
					switch($reuestado){
						case 1: $reuestado='PENDIENTE'; break;
						case 2: $reuestado='CONFIRMADA'; break;
						case 3: $reuestado='CANCELADA'; break;
                        case 5: $reuestado='ELIMINADA'; break;
					}
                    $query1  = "	SELECT FIRST 1  PERQRITM
                    FROM PER_QR
                    WHERE PERCODIGO=$percoddst AND PERQRPER=$reureg AND PERQRAGE=25 ";
                    $Table1 	= sql_query($query1, $conn);

                    // Do bad things to the votes array
                    if(isset($Table1->Rows[0])){ 
                        $row1 = $Table1->Rows[0];
                    
                        $conectadodst='Conectado';
                    } else{

                        $conectadodst='No Conectado';

                    }

                    $query2  = "	SELECT FIRST 1 PERQRITM
                    FROM PER_QR
                    WHERE PERCODIGO=$percodsol AND PERQRPER=$reureg AND PERQRAGE=25 ";
                    $Table2 	= sql_query($query2, $conn);

                    // Do bad things to the votes array
                    if(isset($Table2->Rows[0])){ 
                        $row2 = $Table2->Rows[0];
                    
                        $conectadosol='Conectado';
                    } else{

                        $conectadosol='No Conectado';

                    }	
                    
                    if ( ($reuestado == 'PENDIENTE' ) || ($reuestado == 'CANCELADA') ){
                        $reuhora = $reuhora2;
                        $reufecha = $reufecha2;
                    } 
                    
                  
                    
                    $haux = date('H:i', strtotime('+10800 seconds', strtotime($reuhora))); //Pongo la hora en Huso horario 0
                    $haux = date('H:i', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux))); //Pongo la hora, segun el Huso horario establecido por el perfil
                    $reuhora = $haux;


                 

                    if ($reufchcan){
                        $haux2 = date('d-m-Y H:i:s', strtotime('+10800 seconds', strtotime($reufchcan))); //Pongo la hora en Huso horario 0
                        $haux2 = date('d-m-Y H:i:s', strtotime($_SESSION[GLBAPPPORT.'TIMOFFSET'].' seconds', strtotime($haux2))); //Pongo la hora, segun el Huso horario establecido por el perfil
                        $reufchcan = $haux2;
                    }
					if ($reutipo == 0){
                        $reutiponombre = 'Virtual';
                       
                    }else{
                        $reutiponombre = 'Presencial';
                    }
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $reureg);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $reufchreg);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $solempresa);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $solnombre);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $solapelli);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $solcorreo);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $dstempresa);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $dstnombre);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$ii, $dstapelli);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$ii, $dstcorreo);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$ii, $reuestado);
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$ii, $reufecha);
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$ii, $reuhora);
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$ii, $reufchcan);
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$ii, $mesa);
                    $objPHPExcel->getActiveSheet()->setCellValue('P'.$ii, $conectadosol);
                    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$ii, $conectadodst);
                    $objPHPExcel->getActiveSheet()->setCellValue('R'.$ii, $reutiponombre);
                    
            }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ExportarReuniones.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //Descarga de archivo
    $objWriter->save('php://output');


    sql_close($conn);
    ?>