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
    }
}

    $conn= sql_conectar();//Apertura de Conexion

    $query="SELECT P.PERCPF, P.PREG_ADC, P.PERCODIGO AS REG,P.PERNOMBRE AS NOMBRE,P.PERAPELLI AS APELLIDO,P.PERCOMPAN AS COMPANIA,P.PERCORREO AS CORREO,P.PERCIUDAD AS CIUDAD,
                P.PERESTADO AS ESTADO, S.PAIDESCRI AS PAIS,P.PERCODPOS AS CODIGOPOSTAL,P.PERTELEFO AS TELEFONO,P.PERDIRECC AS DIRECCION, P.PERCARGO AS CARGO,
                P.PERUSUACC AS USUARIOACCESO,T.PERTIPDESESP AS TIPO, C.PERCLADES AS CLASE,P.PERADMIN AS ESADMIN,
                P.PERURLWEB AS SITIOWEB,P.PEREMPDES AS DESCRIPCIONEMPRESA,
                C.PERUSACHA AS USADISPONIBILIDAD,C.PERUSAREU AS USAREUNIONES,
                P.PERCOMENT AS COMENTARIOREGISTRACION,-- M.MESNUMERO AS MESANUMERO,
                P.PERINGLOG AS INGRESOLOG, P.PERULTLOG AS ULTIMOLOG, P.PERARECOD AS INDUSTRYID,
                M.MESCODIGO, 
                CASE
                    WHEN P.ESTCODIGO=1 THEN 'ACTIVO'
                    WHEN P.ESTCODIGO=9 THEN 'MAIL SIN CONFIRMAR'
                    WHEN P.ESTCODIGO=8 THEN 'SIN LIBERAR'
                    WHEN P.ESTCODIGO=3 THEN 'ELIMINADO'
                END AS PERFILESTADO,
                (   SELECT CAST(LIST(SECT.SECDESCRI)  AS VARCHAR(10000))
                    FROM PER_SECT PS
                    LEFT OUTER JOIN SEC_MAEST SECT ON SECT.SECCODIGO=PS.SECCODIGO
                    WHERE PS.PERCODIGO=P.PERCODIGO AND PS.PERVENCOM='C') AS SECTORESCOMPRA,
                (   SELECT CAST(LIST(SECT.SECDESCRI)  AS VARCHAR(10000))
                    FROM PER_SECT PS
                    LEFT OUTER JOIN SEC_MAEST SECT ON SECT.SECCODIGO=PS.SECCODIGO
                    WHERE PS.PERCODIGO=P.PERCODIGO AND PS.PERVENCOM='V') AS SECTORESVENTA,
                (   SELECT CAST(LIST(SSECT.SECSUBDES)  AS VARCHAR(10000))
                    FROM PER_SSEC PSS
                    LEFT OUTER JOIN SEC_SUB SSECT ON SSECT.SECSUBCOD=PSS.SECSUBCOD
                    WHERE PSS.PERCODIGO=P.PERCODIGO AND PSS.PERVENCOM='C') AS SUBSECTORESCOMPRA,
                (   SELECT CAST(LIST(SSECT.SECSUBDES)  AS VARCHAR(10000))
                    FROM PER_SSEC PSS
                    LEFT OUTER JOIN SEC_SUB SSECT ON SSECT.SECSUBCOD=PSS.SECSUBCOD
                    WHERE PSS.PERCODIGO=P.PERCODIGO AND PSS.PERVENCOM='V') AS SUBSECTORESVENTA,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO=2 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESCONFIRMADAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO=1 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESENVIADAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODDST=P.PERCODIGO) AND R.REUESTADO=1 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESRECIBIDAS,
                (SELECT COUNT(*)
                FROM REU_CABE R
                WHERE (R.PERCODDST=P.PERCODIGO OR R.PERCODSOL=P.PERCODIGO) AND R.REUESTADO<>3 AND COALESCE(R.AGEREG,0)=0) AS REUNIONESTOTALES
            FROM PER_MAEST P
            LEFT OUTER JOIN PER_TIPO T ON T.PERTIPO=P.PERTIPO
            LEFT OUTER JOIN PER_CLASE C ON C.PERCLASE=P.PERCLASE
            LEFT OUTER JOIN TBL_PAIS S ON S.PAICODIGO=P.PAICODIGO
            LEFT OUTER JOIN MES_MAEST M ON M.PERCODIGO=P.PERCODIGO
            ORDER BY P.PERNOMBRE,P.PERAPELLI, P.PERCOMPAN";
    $Table = sql_query($query,$conn);


    $objPHPExcel = new PHPExcel();
// Agregamos las columnas con sus nombres respectivos

    $objPHPExcel->getActiveSheet()
                ->setCellValue('A1', 'REG')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'APELLIDO')
                ->setCellValue('D1', 'COMPANIA')
                ->setCellValue('E1', 'CORREO')
                ->setCellValue('F1', 'CIUDAD')
                ->setCellValue('G1', 'ESTADO')
                ->setCellValue('H1', 'PAIS')
                ->setCellValue('I1', 'CODIGOPOSTAL')
                ->setCellValue('J1', 'TELEFONO')
                ->setCellValue('K1', 'DIRECCION')
                ->setCellValue('L1', 'CARGO')
                ->setCellValue('M1', 'USUARIOACCESO')
                ->setCellValue('N1', 'TIPO')
                ->setCellValue('O1', 'CLASE')
                ->setCellValue('P1', 'ESADMIN')
                ->setCellValue('Q1', 'SITIOWEB')
                ->setCellValue('R1', 'DESCRIPCIONEMPRESA')
                ->setCellValue('S1', 'USACHAT')
                ->setCellValue('T1', 'USAREUNIONES')
                ->setCellValue('U1', 'COMENTARIOREGISTRACION')
                ->setCellValue('V1', 'MESNUMERO')
                ->setCellValue('W1', 'PERFILESTADO')
                ->setCellValue('X1', 'SECTORES DEMANDA')
                ->setCellValue('Y1', 'SECTORES OFERTA')
                ->setCellValue('Z1', 'SUBSECTORES DEMANDA')
                ->setCellValue('AA1', 'SUBSECTORES OFERTA')
                ->setCellValue('AB1', 'REUNIONESCONFIRMADAS')
                ->setCellValue('AC1', 'REUNIONESENVIADAS')
                ->setCellValue('AD1', 'REUNIONESRECIBIDAS')
                ->setCellValue('AE1', 'REUNIONESTOTALES')
				->setCellValue('AF1', 'INGRESOLOG')
                ->setCellValue('AG1', 'ULTIMOLOG')
                ->setCellValue('AH1', 'INDUSTRYID')
                ->setCellValue('AI1', 'PERCPF/CUIT')
                ->setCellValue('AJ1', 'PREGUNTAS ADICIONALES');
            
            //Titulo de la tabla en negrita
            $objPHPExcel->getActiveSheet()->getStyle('A1:AJ1')->getFont()->setBold(true);

            //Recorremos los datos           
            for($i=0; $i<$Table->Rows_Count; $i++){
                    $row = $Table->Rows[$i];
                    $ii = $i+2;
                    $reg 	= trim($row['REG']);
                    $nombre	= trim($row['NOMBRE']);
                    $apellido	= trim($row['APELLIDO']);
                    $compania = trim($row['COMPANIA']);
                    $correo = trim($row['CORREO']);
                    $ciudad = trim($row['CIUDAD']);
                    $estado = trim($row['ESTADO']);
                    $pais = trim($row['PAIS']);
                    $codigopostal = trim($row['CODIGOPOSTAL']);
                    $telefono = trim($row['TELEFONO']);
                    $direccion = trim($row['DIRECCION']);
                    $cargo = trim($row['CARGO']);
                    $usuarioacceso = trim($row['USUARIOACCESO']);
                    $tipo = trim($row['TIPO']);
                    $clase = trim($row['CLASE']);
                    $esadmin = trim($row['ESADMIN']);
                    $sitioweb = trim($row['SITIOWEB']);
                    $descripcionempresa = trim($row['DESCRIPCIONEMPRESA']);
                    $usadisponibilidad = trim($row['USADISPONIBILIDAD']);
                    $usareuniones = trim($row['USAREUNIONES']);
                    $comentarioregistracion = trim($row['COMENTARIOREGISTRACION']);
                    
                    $mesnumero = trim($row['MESCODIGO']);
                    $perfilestado = trim($row['PERFILESTADO']);
                    $sectores = trim($row['SECTORESCOMPRA']);
                    $subsectores = trim($row['SECTORESVENTA']);
                    $categorias = trim($row['SUBSECTORESCOMPRA']);
                    $subcategorias = trim($row['SUBSECTORESVENTA']);
                    $reunionesconfirmadas = trim($row['REUNIONESCONFIRMADAS']);
                    $reunionesenviadas = trim($row['REUNIONESENVIADAS']);
                    $reunionesrecibidas = trim($row['REUNIONESRECIBIDAS']);
                    $reunionestotales = trim($row['REUNIONESTOTALES']);
                    $ingresolog = trim($row['INGRESOLOG']);
                    $ultimolog = trim($row['ULTIMOLOG']);
                    $perarecod = trim($row['INDUSTRYID']);
                    $percpf = trim($row['PERCPF']);
                    $stringpreguntas = trim($row['PREG_ADC']);
                    $preguntasadicionales = str_replace("&quot;","",$stringpreguntas);

                   
                    //Asignamos a cada celda un valor
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, $reg);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $nombre);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $apellido);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $compania);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $correo);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $ciudad);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $estado);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $pais);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$ii, $codigopostal);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$ii, $telefono);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$ii, $direccion);
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$ii, $cargo);
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$ii, $usuarioacceso);
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$ii, $tipo);
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$ii, $clase);
                    $objPHPExcel->getActiveSheet()->setCellValue('P'.$ii, $esadmin);

                    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$ii, $sitioweb);
                    $objPHPExcel->getActiveSheet()->setCellValue('R'.$ii, $descripcionempresa);
                    $objPHPExcel->getActiveSheet()->setCellValue('S'.$ii, $usadisponibilidad);
                    $objPHPExcel->getActiveSheet()->setCellValue('T'.$ii, $usareuniones);
                    $objPHPExcel->getActiveSheet()->setCellValue('U'.$ii, $comentarioregistracion);
                    $objPHPExcel->getActiveSheet()->setCellValue('V'.$ii, $mesnumero);
                    $objPHPExcel->getActiveSheet()->setCellValue('W'.$ii, $perfilestado);
                    $objPHPExcel->getActiveSheet()->setCellValue('X'.$ii, $sectores);
                    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$ii, $subsectores);
                    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$ii, $categorias);
                    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$ii, $subcategorias);
                    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$ii, $reunionesconfirmadas);
                    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$ii, $reunionesenviadas);
                    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$ii, $reunionesrecibidas);
                    $objPHPExcel->getActiveSheet()->setCellValue('AE'.$ii, $reunionestotales);
                    $objPHPExcel->getActiveSheet()->setCellValue('AF'.$ii, $ingresolog);
                    $objPHPExcel->getActiveSheet()->setCellValue('AG'.$ii, $ultimolog);
                    $objPHPExcel->getActiveSheet()->setCellValue('AH'.$ii, $perarecod);
                    $objPHPExcel->getActiveSheet()->setCellValue('AI'.$ii, $percpf);
                    $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$ii, $preguntasadicionales);
        
            }


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ExportarPerfiles.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//Descarga de archivo
$objWriter->save('php://output');


sql_close($conn);
?>