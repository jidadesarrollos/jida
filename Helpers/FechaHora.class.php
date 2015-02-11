<?php
/**
 * Helpers Formatos para Fecha y Horas
 * 
 * Definición  de las funciones formatos de fechas y horas a utlilizar.
 * 
 * @category	framework
 * @package		Helpers
 * 
 * @author      Daniel Suniaga <dsuniaga@sundecop.gob.ve>
 * @license     http://www.gnu.org/copyleft/gpl.html    GNU General Public License
 * @version     0.1 - 09/09/2013
 */
class FechaHora {
	
	/**
	 * Función que retorna el día de hoy.
	 *
	 * @param string $texto        	
	 * @return string
	 * @example Imprime Lunes
	 */
	static function diaSemana() {
		$diaHoy = date ( 'l' );
		$diasSemanas = array (
				'Sonday' => 'Domingo',
				'Monday' => 'Lunes',
				'Tuesday' => 'Martes',
				'Wednesday' => 'Miércoles',
				'Thursday' => 'Jueves',
				'Friday' => 'Viernes',
				'Saturday' => 'Sábado' 
		);
		return $diasSemanas [$diaHoy];
	}
	
	/**
	 * Función que retorna el mes en el que estamos.
	 * 
	 * @param string $texto        	
	 * @return string
	 * @example Imprime Febrero
	 */
	static function mes($fecha = '') {
		if ($fecha == '') {
			$mesActual = date ( 'F' );
			$mesesAnio = array (
					'January' => 'Enero',
					'February' => 'Febrero',
					'March' => 'Marzo',
					'April' => 'Abril',
					'May' => 'Mayo',
					'June' => 'Junio',
					'July' => 'Julio',
					'August' => 'Agosto',
					'September' => 'Septiembre',
					'October' => 'Octubre',
					'November' => 'Noviembre',
					'December' => 'Diciembre' 
			);
			return $mesesAnio [$mesActual];
		} else {
			$mesesAnio = array (
					'01' => 'Enero',
					'02' => 'Febrero',
					'03' => 'Marzo',
					'04' => 'Abril',
					'05' => 'Mayo',
					'06' => 'Junio',
					'07' => 'Julio',
					'08' => 'Agosto',
					'09' => 'Septiembre',
					'10' => 'Octubre',
					'11' => 'Noviembre',
					'12' => 'Diciembre' 
			);
			return $mesesAnio [$fecha];
		}
	}
	
	/**
	 * Función que retorna el dia de hoy en número.
	 * 
	 * @return string
	 */
	static function diaSemanaNumero() {
		return date ( 'j' );
	}
	
	/**
	 * Función que retorna el mes en el que estamos en número.
	 * 
	 * @return string
	 */
	static function mesNumero() {
		return date ( 'n' );
	}
	
	/**
	 * Función que retorna el año en dos digitos.
	 * 
	 * @return string
	 */
	static function anioDosDigitos() {
		return date ( 'y' );
	}
	
	/**
	 * Función que retorna el año en cuatro digitos.
	 * 
	 * @return string
	 */
	static function anioCuatroDigitos() {
		return date ( 'Y' );
	}
	
	/**
	 * Función que retorna la hora en formato 24 de dos digitos.
	 * 
	 * @return string
	 */
	static function horaFormato24() {
		return date ( 'h' );
	}
	
	/**
	 * Función que retorna la hora en formato 12 de dos digitos.
	 * 
	 * @return string
	 */
	static function horaFormato12() {
		return date ( 'g' );
	}
	/**
	 * Función que retorna los minutos.
	 * 
	 * @return string
	 */
	static function minutos() {
		self::mesNumero ();
		return date ( 'i' );
	}
	
	/**
	 * Función que retorna un arreglo con los dias en letra.
	 * 
	 * @return array
	 */
	static function formatoDias() {
		return array (
				'01' => 'Primer',
				'02' => 'Segundo',
				'03' => 'Tercer',
				'04' => 'Cuatro',
				'05' => 'Quinto',
				'06' => 'Sexto',
				'07' => 'Septimo',
				'08' => 'Octavo',
				'09' => 'Noveno',
				'10' => 'Diez',
				'11' => 'Once',
				'12' => 'Doce',
				'13' => 'Trece',
				'14' => 'Catorce',
				'15' => 'Quince',
				'16' => 'Dieciseis',
				'17' => 'Diecisiete',
				'18' => 'Dieciocho',
				'19' => 'Diecinueve',
				'20' => 'Veinte',
				'21' => 'Veintiún',
				'22' => 'Veintidos',
				'23' => 'Veintitres',
				'24' => 'Veinticuatro',
				'25' => 'Veinticinco',
				'26' => 'Veintiseis',
				'27' => 'Veintisiete',
				'28' => 'Veintiocho',
				'29' => 'Veintinueve',
				'30' => 'Treinta',
				'31' => 'Treinta y un' 
		);
	}
	
	/**
	 * Función que retorna la fecha que se utiliza en los certificados.
	 * 
	 * @param string $fecha        	
	 * @return string
	 * @example Caracas, 02 de Enero de 2013
	 */
	static function FechaCompleta($fecha = '') {
		if ($fecha == '') {
			$diaHoy = self::diaSemanaNumero ();
			$stringMes = 'Dias del mes de';
			$diaHoy = self::diaSemanaNumero ();
			$mesActual = self::mes ();
			$anioActual = self::anioCuatroDigitos ();
			$arrayDias = self::formatoDias ();
			
			return $arrayDias [$diaHoy] . '(' . $diaHoy . ') ' . $stringMes . ' ' . $mesActual . ' de ' . $anioActual . '<br>';
		} else {
			
			$fechaBaseDatos = date ( 'd/m/Y', $fecha );
			
			$fechaSeparacion = explode ( '/', $fechaBaseDatos );
			
			if ($fechaSeparacion [0] >= 10) {
				$stringCiudad = 'Caracas, a los';
				$stringMes = 'Dias del mes de';
			} else {
				$stringCiudad = 'Caracas, al';
				$stringMes = 'Dia del mes de';
			}
			$diaHoy = self::diaSemanaNumero ();
			$mesActual = self::mes ( $fechaSeparacion [1] );
			$anioActual = self::anioCuatroDigitos ();
			
			$arrayDias = self::formatoDias ();
			
			return $stringCiudad . ' ' . $arrayDias [$fechaSeparacion [0]] . '(' . $fechaSeparacion [0] . ') ' . $stringMes . ' ' . $mesActual . ' de ' . $fechaSeparacion [2] . '<br>';
		}
	}
	
	/**
	 * Timestamp Unix
	 *
	 * Funcion que genera el Timestamp de Unix tomando en cuenta
	 * la zona horaria de -4:30 GMT. Esta fecha/hora se inserta tomando
	 * en cuenta la hora de Venezuela y no la hora del meridiano 0 como
	 * generalmente lo hace la funcion el Timestamp de Unix.
	 *
	 * @return int $caracasDateTime Timestamp de Unix de la Hora de Caracas.
	 */
	public static function timestampUnix($fecha='') {
		
		if($fecha==''){
			
			$dateTimeZoneCaracas = new DateTimeZone ( "America/Caracas" );
			$dateTimeCaracas = new DateTime ( "now", $dateTimeZoneCaracas );
			$caracasOffset = $dateTimeZoneCaracas->getOffset ( $dateTimeCaracas );
			$caracasDateTime = strtotime ( date ( "Y-m-d H:i:s", time () + $caracasOffset ) );
			
		}else{
			
			$caracasDateTime = strtotime($fecha);
			
		}	
		
		return $caracasDateTime;
	}
	
	/**
	 * Conviente Timestamp Unix a Date con la hora
	 *
	 * Recibe una fecha Timestamp de Unix y la convierte
	 * a una fecha tipo Date con el siguiente formato
	 * (d-m-Y H:i:s)
	 *
	 * @param int $epoch
	 *        	Timestamp Unix
	 */
	public static function convertirUnixADateYHora($epoch) {
		$dt = new DateTime ( "@$epoch" );
		return $dt->format ( 'd-m-Y H:i:s' );
	}
	/**
	 * Convierte a DATE una fecha timestamp de unix y la devuelve
	 * en formato d-m-Y
	 */
	
	public static function convertirUnixADate($epoch) {
		$dt = new DateTime ( "@$epoch" );
		return $dt->format ( 'd-m-Y' );
	}
	
	/**
	 * Función que convierte la fecha con formato '/' o '-'
	 * 
	 * @param string $fecha        	
	 * @param string $parametro
	 *        	si no se envia un segundo valor,esta variable tiene predeterminado un guion '-'.
	 * @return string
	 */
	static function fechaTipoFormato($fecha = '', $parametro = '-') {
		if ($fecha == '') {
			return date ( 'd' . $parametro . 'm' . $parametro . 'Y' );
		} else {
			// echo "'".$parametro."'".'<hr>';exit;
			if ($fecha [4] == '-' || $fecha [2] == '-') {
				$fechaCortada = explode ( '-', $fecha );
				return $fechaCortada [0] . $parametro . $fechaCortada [1] . $parametro . $fechaCortada [2];
				// return date('d'.$parametro.'m'.$parametro.'Y',strtotime($fecha));
			} elseif ($fecha [4] == '/' || $fecha [2] == '/') {
				
				$fechaCortada = explode ( '/', $fecha );
				return $fechaCortada [0] . $parametro . $fechaCortada [1] . $parametro . $fechaCortada [2];
			}
		}
	}
	/**
	 * Función que invierte el formato de la fecha
	 * 
	 * @param string $fecha.        	
	 * @param string $parametro.
	 *        	si no se envia un segundo valor,esta variable tiene predeterminado un guion '-'.
	 * @return string
	 */
	static function fechaInvertida($fecha = '', $parametro = '-') {
		if ($fecha == '') {
			return date ( 'Y' . $parametro . 'm' . $parametro . 'd', $fecha );
		} else {
			$fechaTipoFormato = self::fechaTipoFormato ( $fecha, $parametro );
			
			if ($fechaTipoFormato [4] == '-' || $fechaTipoFormato [4] == '/') {
				
				return date ( 'd' . $fechaTipoFormato [4] . 'm' . $fechaTipoFormato [4] . 'Y', strtotime ( $fechaTipoFormato ) );
			} elseif ($fechaTipoFormato [2] == '-' || $fechaTipoFormato [2] == '/') {
				
				return date ( 'Y' . $fechaTipoFormato [2] . 'm' . $fechaTipoFormato [2] . 'd', strtotime ( $fechaTipoFormato ) );
			}
		}
	}
    
    static function datetime($fecha=""){
        if(!empty($fecha)){
            return date ( 'Y-m-d H:i:s',$fecha );    
        }else{
            return date ( 'Y-m-d H:i:s' );
        }
        
    }
    static function Fecha(){
        return date('d-m-Y');
    }
    static function fechaToDateTime($fecha){
        $datetime = new DateTime($fecha);
        return $datetime->format('d-m-Y');
    }
}
