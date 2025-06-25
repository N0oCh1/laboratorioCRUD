<?PHP
class SanitizarEntrada {
    // Sanitiza una cadena eliminando espacios y etiquetas HTML
    public static function limpiarCadena($cadena) {
        return trim(strip_tags($cadena));
    }

    public static function ValidarEntero($variableEntera) {
       $variableEntera = trim($variableEntera);
        //ctype_digit: consiste completamente de dígitos. sin puntos o letras; ni entre comillas
        //is_numeric: puede estar entre comillas y puntos
        if (is_numeric($variableEntera) and ($variableEntera > 0))
                $variableNumerica = $variableEntera;
                    else
                        $variableNumerica = 0;
                        
        
        return($variableNumerica);
    }
  

}//SanitizarEntrada

//$nombre = "<b>Juan</b> ";
//$nombreLimpio = SanitizarEntrada::limpiarCadena($nombre);  
//echo "la salida es: ".$nombre."<br>";
?>