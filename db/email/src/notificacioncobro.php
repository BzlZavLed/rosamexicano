<?php
session_start();

include '../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../../../');
$dotenv->load();

 $conn =  mysqli_connect($_ENV['DB_LOCAL_HOST'], $_ENV['DB_LOCAL_USER'],$_ENV['DB_LOCAL_PWD'],$_ENV['DB_LOCAL_DB']);
if (!$conn) {
     die('No pudo conectarse: ' . mysqli_error($err));
} 

$idmarca = $_POST['idmarca'];
$nombre = $_POST['nombre'];
$mes = $_POST['mes'];
$fecha = $_POST['fecha'];
$importe = $_POST['importe'];
$plantilla = $_POST['plantilla'];


      $query = "SELECT email FROM proveedores WHERE ident = ".$idmarca;
      $exec = mysqli_query($conn,$query);
      $row = mysqli_fetch_array($exec);
      $emailproveedor = $row['email'];

$curl = curl_init();
$body = "
  { 
    \"from\": 
      {
        \"email\": \"rosamexicanopdc@gmail.com\"
      }, 
    \"personalizations\": 
      [
        {
          \"to\": [
            { 
              \"email\" : \"$emailproveedor\"
            }
          ],
          \"cc\": [
            {
                \"email\":\"rosamexicanopdc@gmail.com\"
                
            }
        ],
          \"dynamic_template_data\": {
            \"nombreProveedor\" : \"$nombre\",
            \"mesCobro\" : \"$mes\",
            \"importe\" : \"$importe\",
            \"diaCobro\" : \"$fecha\",
          }
        }
      ],
      \"template_id\":\"$plantilla\"
  }";
 curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLINFO_HEADER_OUT=> true,
  CURLOPT_HEADER=> true,
  CURLOPT_POSTFIELDS => $body,
 
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer ".$_ENV['TWILIO_API_KEY'],
    "cache-control: no-cache",
    "content-type: application/json"
  ),
));


$response = curl_exec($curl);
$info = curl_getinfo($curl);


$err = curl_error($curl);

curl_close($curl); 

$query = "INSERT INTO mailer (email_to,name,subject,fecha,data) VALUES ('".$emailcliente."','".$nombre."', 'Nota de venta' ,'".date("Y-m-d")."','-')";
$exec = mysqli_query($conn,$query);

 if($response){
   echo json_encode(array('message' => 'Email enviado con exito a '.$emailproveedor,'type' => 'success'));
 }else{
   echo json_encode(array('message' => 'Email enviado con exito','type' => 'warning'));
 }

?>

