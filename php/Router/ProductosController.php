<?PHP
require_once "Modelo/conexion.php";
require_once "Modelo/Productos.php";
require_once "Modelo/SanitizarEntrada.php";


class ProductoController
{

    private $db;
    private $conn;



    private $codigo;
    private $producto;
    private $precio;
    private $cantidad;

    private $myProducto;

    public function __construct()
    {

        $this->db = new mod_db();
        $this->conn = $this->db->getConexion();
        $this->myProducto = new ObjProductos($this->db);
    }
    public function crearProducto($codigo, $producto, $precio, $cantidad)
    {
        if ($codigo && $producto && $precio && $cantidad) {
            $this->codigo = SanitizarEntrada::limpiarCadena($codigo);
            $this->producto = SanitizarEntrada::limpiarCadena($producto);
            $this->precio = SanitizarEntrada::ValidarEntero($precio);
            $this->cantidad = SanitizarEntrada::ValidarEntero($cantidad);

            $datosProductos = array(
                "codigo" => $this->codigo,
                "producto" => $this->producto,
                "precio" => $this->precio,
                "cantidad" => $this->cantidad
            );

            $this->myProducto->DatosRequeridos($datosProductos);

            try {
                if ($this->myProducto->registrarProductos()) {
                    http_response_code(201);
                    echo json_encode(["message" => "Producto creado exitosamente"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Error al crear producto en la base de datos"]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["message" => "Error de base de datos", "error" => $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos JSON inválidos o incompletos"]);
        }
    }

    public function leerProductos()
    {
        try {
            $datos = $this->myProducto->leerDatos();
            echo json_encode($datos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error al leer productos", "error" => $e->getMessage()]);
        }
    }

    public function leerUnProducto($id)
    {
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID inválido"]);
            return;
        }
        try {
            $data = $this->myProducto->unProducto($id);
            if ($data && count($data) > 0) {
                echo json_encode($data);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error al leer producto", "error" => $e->getMessage()]);
        }
    }

    public function ActualizarProducto($id, $codigo, $producto, $precio, $cantidad)
    {
        $this->codigo = SanitizarEntrada::limpiarCadena($codigo);
        $this->producto = SanitizarEntrada::limpiarCadena($producto);
        $this->precio = SanitizarEntrada::ValidarEntero($precio);
        $this->cantidad = SanitizarEntrada::ValidarEntero($cantidad);

        $datosProductos = array(
            "id" => $id,
            "codigo" => $this->codigo,
            "producto" => $this->producto,
            "precio" => $this->precio,
            "cantidad" => $this->cantidad
        );

        if (!$id || !$this->codigo || !$this->producto || $this->precio === false || $this->cantidad === false) {
            http_response_code(400);
            echo json_encode(["message" => "Datos inválidos o incompletos"]);
            return;
        }

        $this->myProducto->DatosRequeridos($datosProductos);
        try {
            if ($this->myProducto->actualizarProducto()) {
                http_response_code(200);
                echo json_encode(["message" => "Producto actualizado"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al actualizar en la base de datos"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error de base de datos", "error" => $e->getMessage()]);
        }
    }

    public function EliminarProducto($id)
    {
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID inválido"]);
            return;
        }
        try {
            if ($this->myProducto->eliminarDato($id)) {
                http_response_code(200);
                echo json_encode(["message" => "Producto eliminado"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al eliminar en la base de datos"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error de base de datos", "error" => $e->getMessage()]);
        }
    }
}
