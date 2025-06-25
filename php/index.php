<?PHP  

require("Router/ProductosController.php");

$method = $_SERVER['REQUEST_METHOD'];

$MyProductoController = new ProductoController();

switch ($method){

	case 'POST':
	//Crear un Producto
	$MyProductoController->crearProducto(
		$_POST["codigo"],
		$_POST["producto"],
		$_POST["precio"],
		$_POST["cantidad"]
	);
		
	break;
	
	case 'GET':
		if(isset($_GET["id"])){
			$MyProductoController->leerUnProducto($_GET["id"]);
		}
		else{
			$MyProductoController->leerProductos();
		}
	break;

	case 'PUT':
		$input = file_get_contents("php://input");
    // Decodifica el JSON recibido (si tu JS envía JSON)
    $data = json_decode($input, true);
		$MyProductoController->ActualizarProducto(
			$data["id"],
			$data["codigo"],
			$data["producto"],
			$data["precio"],
			$data["cantidad"]
		);
	break;
	case 'DELETE':
		$input = file_get_contents("php://input");
    // Decodifica el JSON recibido (si tu JS envía JSON)
    $data = json_decode($input, true);
		$MyProductoController->EliminarProducto(
			$data["id"]
		);
	break;

	default:
	http_response_code(405);
	echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

/*
break;



*/
?>
