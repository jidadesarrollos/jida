<?PHP
/**
 * Helper para Numeros
 *
 * @author   Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package  Framework
 * @category Helpers
 * @version  0.1
 */

namespace Jida\Medios;

class Numeros {

    /**
     * @property string $moneda refleja nombre de moneda
     */
    static $moneda = 'Bs.';
    /**
     * @property string $separadorMiles
     */
    static $separadorMiles = ".";
    /**
     * @property string separadorDecimales
     */
    static $separadorDecimales = ",";

    static $monedaAntes = false;

    /**
     * Devuelve un número en formato de moneda
     * @method moneda
     *
     * @param mixed $valor Numero a formatear
     * @param int $decimales
     *
     * @return string $numero Numero resultante
     * @access public
     * @since  0.1
     */
    public static function moneda ($valor, $decimales = 2, $moneda = false) {

        $numero = number_format($valor, $decimales, self::$separadorDecimales, self::$separadorMiles);
        if ($moneda) {
            if (self::$monedaAntes)
                return self::$moneda . " " . $numero;

            return $numero . " " . self::$moneda;
        }

        return $numero;
    }

    /**
     * @internal valida si un nomero es entero de lo contrario retorma false
     * @method validarInt
     * @return int
     * @access   public
     * @since    0.1
     */
    public static function validarInt ($valor) {

        return filter_var($valor, FILTER_VALIDATE_INT);
    }

    /**
     * @deprecated 0.5
     */
    public static function _moneda ($numero, $decimales = 2, $type = "bolivar") {

        switch ($type) {
            case 'dolar':
                $numero = ($numero == "") ? "" : number_format($numero, $decimales, ".", ",");

                return $numero;
                break;

            default:
                $numero = ($numero == "") ? "" : number_format($numero, $decimales, ",", ".");

                return $numero;
                break;
        }
    }

    /**
     * @internal   Convierte un numero en formato de moneda en el formato correcto para guardarlo en base de datos
     * @method floatBD
     * @return string
     * @access     public
     * @since      0.1
     * @deprecated 0.5
     */
    public static function floatBD ($valor) {

        self::aFloat($valor);
    }

    public static function aFloat ($valor) {

        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);

        return $valor;
    }
}