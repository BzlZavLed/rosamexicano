<?php
include '../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../../../');
$dotenv->load();

 $conn =  mysqli_connect($_ENV['DB_LOCAL_HOST'], $_ENV['DB_LOCAL_USER'],$_ENV['DB_LOCAL_PWD'],$_ENV['DB_LOCAL_DB']);
if (!$conn) {
     die('No pudo conectarse: ' . mysqli_error($err));
} 

session_start();
$emailcliente = $_POST['email'];
$datostable = $_POST['bigarray'];
$fecha = $_POST['todayDate'];
$nombre = $_POST['nombre'];
$totalVenta = $_POST['totalVenta'];
$plantilla = $_POST['plantilla'];



$body = "";
$totalventacalc = 0;
for($i = 0; $i <= count($datostable)-1; $i++){
  $producto = $datostable[$i][1];
  $descripcion = $datostable[$i][2];
  $proveedor = $datostable[$i][3];
  $cantidad = $datostable[$i][5];
  $total = $datostable[$i][7];
  $body .= "{
              \"producto\" : \"$producto\",
              \"descripcion\" : \"$descripcion\",
              \"proveedor\" : \"$proveedor\",
              \"cantidad\" : \"$cantidad\",
              \"total\" : \"$total\"
            },\n";
  $totalventacalc += (int)$total;
}
$body = substr_replace($body ,"", -2);
$emailbody = "
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
            \"email\" : \"$emailcliente\"
          }
        ],
        \"cc\": [
            {
                \"email\":\"rosamexicanopdc@gmail.com\"
                
            }
        ],
    ],
        \"dynamic_template_data\": {  
          \"items\" : [
            $body
          ]
          
        }
      }
    ]
}";

$curl = curl_init();
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
  CURLOPT_POSTFIELDS => "
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
              \"email\" : \"$emailcliente\"
            }
          ],
          \"cc\": [
            {
                \"email\":\"rosamexicanopdc@gmail.com\"
                
            }
        ],
          \"dynamic_template_data\": {
            \"items\" : [
              $body
            ],
            \"nombreCliente\" : \"$nombre\",
            \"totalVenta\" : \"$totalVenta\",
          }
        }
      ],
      \"template_id\":\"$plantilla\"
  }",
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
   echo json_encode(array('message' => 'Email enviado con exito ','type' => 'success'));
 }else{
   echo json_encode(array('message' => 'Error al enviar email','type' => 'warning'));
 }

?>

