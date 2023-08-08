<?
if(!isset($_SESSION))  session_start();
//include($_SERVER["DOCUMENT_ROOT"].'/webcoordinador/func/zglobals.php'); //DEV
include($_SERVER["DOCUMENT_ROOT"].'/func/zglobals.php'); //PRD
//--------------------------------------------------------------------------------------------------------------
require_once GLBRutaFUNC.'/sigma.php';	
require_once GLBRutaFUNC.'/zdatabase.php';
require_once GLBRutaFUNC.'/zfvarias.php';
require_once GLBRutaFUNC.'/class.phpmailer.php';
require_once GLBRutaFUNC.'/class.smtp.php';
require_once GLBRutaAPI.'/mailchimp.php';
require_once GLBRutaFUNC.'/constants.php';

$percodigo = (isset($_GET['ID']))? trim($_GET['ID']):0; 
//nombre evento
// $tmpl->setVariable('SisNombreEvento', $_SESSION['PARAMETROS']['SisNombreEvento']);
// $tmpl->setVariable('SisNombreEvento', NAME_TITLE );

#buscar la bd con la base de datos
#query
#usuario de PassAcceso
#cod =1
#clave new
#contraseña de nueva y el usuario lo pasas por el md5 la contraseña
#rowcount esta en 0 esta correao errado

$conn = sql_conectar();//Apertura de Conexion

$errcod = 0;
$errmsg = '';

$query = "SELECT PERCODIGO,PERNOMBRE,PERAPELLI,PERCLASE,PERCPF
			FROM PER_MAEST 
			WHERE PERCODIGO=$percodigo";
		$Table = sql_query($query,$conn);
		$row = $Table->Rows[0];
		$pernombre 	= trim($row['PERNOMBRE']);
		$perapelli	= trim($row['PERAPELLI']);
        $perclasecod	= trim($row['PERCLASE']);
        $perdni	= trim($row['PERCPF']);

$query = "SELECT PERCLASE,PERCLADES
            FROM PER_CLASE	
            WHERE PERCLASE=$perclasecod ";
    $Table = sql_query($query,$conn);
    $row = $Table->Rows[0];
    $perclasedes	= trim($row['PERCLADES']);

$query = " SELECT PERCODIGO,PERTIKREG,TIKCODIGO,TIKTOKEN ,TIKPAYMET,TIKCUOTAS,TIKUSERID,TIKESTCOD,TIKFCHREG,TIKTARJETA,TIKORDEN,TIKMONEDA,TIKDATE,TIKTIPO
            FROM PER_TICK
            WHERE PERCODIGO=$percodigo";

$Table = sql_query($query, $conn);

for ($i = 0; $i < $Table->Rows_Count; $i++) {
    $row = $Table->Rows[$i];
    $valorticket = trim($row['TIKCODIGO']);
    $codigoautorizacion = trim($row['TIKTOKEN']);
    $cuotas = trim($row['TIKCUOTAS']);
    $tarjeta = trim($row['TIKTARJETA']);
    $ordencompra = trim($row['TIKORDEN']);
    $tipomoneda = trim($row['TIKMONEDA']);
    $fechatrans = trim($row['TIKDATE']);
    $plataforma = trim($row['TIKTIPO']);
    $sitio='';
    $comercio='';
    $correo='';
    if ($plataforma==1){
        $sitio=' https://arquisur2021.org ';
        $comercio=' Facultad de Arquitectura y Urbanismo - Universidad de Chile ';
        $correo=' tesoreria_fau@uchilefau.cl ';
    }else{
        $sitio=' https://arquisur2021.org  ';
        $comercio=' Facultad de arquitectura y urbanismo universidad de Tucumán ';
        $correo=' tesoreríafau@gmail.com ';
    }


    $body = '<!DOCTYPE html>
    <html lang="en" class="loading">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        </head>

    <body>
    <div dir="ltr">
        
        <br>
        <br>
        
        <font color="#212121" style="font-family:arial,sans-serif;font-size:18px;">
            <div style="text-align:center">Pago Realizado<br>
            La transacción se ha completado con éxito.<br>
            Datos de compra:N° Orden:'.$ordencompra.'<br>
            Comercio:'.$comercio.'<br>
            Nombre del Comprador:'.$pernombre.' '.$perapelli.'<br>
            Dni/Rut del Comprador:'.$perdni.'<br>
            Servicio:'.$perclasedes.'<br>
            Tipo de Moneda:'.$tipomoneda.'<br>  
            Pago:CréditoMonto (Peso):'.$valorticket.'<br>
            Sitio :'.$sitio.'<br>
            Datos Transacción:<br>
            Código de Autorización:'.$codigoautorizacion.'<br>
            Fecha de Transacción:'.$fechatrans.'<br>
            Tarjeta:'.$tarjeta.'<br>
            Tipo de Transacción:Venta<br>
            Tipo de cuotas:Venta Normal<br>
            Número de Cuotas: '.$cuotas.'<br>
            
            En caso de requerir devoluciones o reembolsos favor de contactar al Teléfono +56 02 29783079 or Correo '.$correo.'</div>
        </font>

    </div>
</body>
</html>';
    //var_dump(SEND_MAIL_USUARIO,MAIL_NAME_APP,$percorreo,$body); die;
    if ($plataforma==1){

        $mail = [
            "from_email" => SEND_MAIL_USUARIO,
        "from_name" => 'Arquisur',
        "subject" => 'Datos de Transaccion',
        "html" => $body,
            "to" => [
                [
                    "email" => 'recaudaciones@uchilefau.cl',
                    "type" => "to"
                ]
            ]
        ];

    }else{

        $mail = [
            "from_email" => SEND_MAIL_USUARIO,
        "from_name" => 'Arquisur',
        "subject" => 'Datos de Transaccion',
        "html" => $body,
            "to" => [
                [
                    "email" => 'tesoreriafau@gmail.com',
                    "type" => "to"
                ]
            ]
        ];


    }
        

    
        
        sendMail($mail);
       // var_dump(sendMail($mail)); die;
    
}

if ($Table->Rows_Count > 0) {
    echo 'OK';
} else {
    echo 'ERROR';
}

echo "<script>top.window.location = 'comptickres'</script>";
?>	