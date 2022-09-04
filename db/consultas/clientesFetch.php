<?php
session_start();
include("../conexiones/conexion.php");
include("tools.php");
include("funcionesConsulta.php");
$column = array("id", "nombre", "email");

$query = "SELECT * FROM clientes ";

if (isset($_POST["search"]["value"])) {
    $query .= '
 WHERE nombre LIKE "%' . $_POST["search"]["value"] . '%" 
 OR email LIKE "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY id DESC ';
}
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $conn->prepare($query);
$statement->execute();
$statement->store_result();
$number_filter_row = $statement->num_rows;

//$statement = $conn->prepare($query . $query1);
//$statement->execute();
//$result = $statement->get_result();
//$result = $result->fetch_all(MYSQLI_ASSOC);



    $data = array();
    $stmt = mysqli_query($conn,$query);
    if($result = mysqli_fetch_all_alt($stmt))
    {
        foreach($result as $res)
        {
            $temp =[];
            foreach($res as $key=>$val)
            {
                $temp[] =$val;
                
            }
            $temp[] ="<button id = 'deleteRow' class = 'btn btn-default'>Borrar</button>";
            array_push($data, $temp);
        }
     }


function count_all_data($connect)
{
    $query = "SELECT * FROM clientes";
    $statement = $connect->prepare($query);
    $statement->execute();
    $statement->store_result();
    $number_filter_row = $statement->num_rows;
    
    return $number_filter_row;
}

$output = array(
    'draw'   => intval($_POST['draw']),
    'recordsTotal' => count_all_data($conn),
    'recordsFiltered' => $number_filter_row,
    'data'   => $data
);

echo json_encode($output);

?>
