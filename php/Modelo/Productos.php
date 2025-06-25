<?PHP
 class ObjProductos{ 
    
    private  $controlError=array();

	 Private $id;
    Private $Codigo;
    Private $Producto;
    Private $Precio;
    Private $Cantidad;

    Private $pdo;
    Private $idp;
    Private $Accion;
	//Private $pdo;
	

	Public function __construct($pdo){ 
	
		$this->pdo = $pdo;
		
	} //introduceDatos

	public function DatosRequeridos($datos){
			$this->Codigo = $datos["codigo"];
    	$this->Producto = $datos["producto"];
    	$this->Precio = $datos["precio"];
    	$this->Cantidad = $datos["cantidad"];
			if($datos["id"]){
				$this->id = $datos["id"];
			}

	}

	public function getCodigo(){
		return $this->Codigo;
	}
    public function getCantidad(){
		return $this->Cantidad;
	}
    public function getPrecio(){
		return $this->Precio;
	}
     public function getProducto(){
		return $this->Producto;
	}
	

	
	public function registrarProductos(){ 
    		
			$data = array(
			"codigo" => $this->Codigo,
			"producto" => $this->Producto,
			"precio" => $this->Precio,
    	"cantidad" =>$this->Cantidad);
			error_log("Datos a insertar: " . print_r($data, true)); // Log para depuración
			$resultado = $this->pdo->insertSeguro("productos",$data);

			return $resultado;
	}

	public function unProducto($id){
		return $this->pdo->Arreglos("SELECT * FROM productos WHERE id = '$id'");
	}

	public function actualizarProducto(){
			
			// Verifica si existe el producto con ese ID
    $existe = $this->pdo->Arreglos("SELECT id FROM productos WHERE id = '$this->id'");
    if (!$existe || count($existe) === 0) {
        // No existe el producto con ese ID
        return false;
    }

    $set = "codigo = '$this->Codigo', producto = '$this->Producto', precio = '$this->Precio', cantidad = '$this->Cantidad'";
    $condicion = "id = '$this->id'";
    if ($this->pdo->update("productos", $set, $condicion)){
        return true;
    } else {
        return false;
    }
   	
	}
	public function leerDatos () {
		return $this->pdo->Arreglos("SELECT * FROM productos");
	}

	public function eliminarDato($id){
		if(!$id){
			return false;
		}
		if($this->pdo->del("productos", "id = '$id'")){
			return true;
		}else{
			return false;
		}

	}
	
	//fin de Modificar Productos

	    //Cerrar la conexión
		// $stmt = null;
		// $pdo = null;

} //fin ValidacionLogin

	/* foreach($result as $key => $value) {
	$resp[$key]=$value;
	}//fin del foreach
	*/


?>		