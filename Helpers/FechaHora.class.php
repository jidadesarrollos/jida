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
class FechaHora{
    
    static $diasSemana =    [
            
            'en'=>[
                 1=>'Monday',
                 2=>'Tuesday',
                 3=>'Wednesday',
                 4=>'thursday',
                 5=>'Friday',
                 6=>'Saturday',
                 7=>'Sunday'
            ],
            'es'=>[
                1=>'Lunes',
                2=>'Martes',
                3=>'Miercoles',
                4=>'Jueves',
                5=>'Viernes',
                6=>'Sábado',
                7=>'Domingo',    
            ],
            'po'=>[
                1=>'Segunda-feira',
                2=>'Terça-feira',
                3=>'Quarta-feira',
                4=>'Quinta-feira',
                5=>'Quinta-feira',
                6=>'Sexta-feira',
                7=>'Sábado',    
            ]
             
          ]; 

    static $meses = [
        'es'=>[
	        1=>['abr' => 'ene', 'mes'=>"enero"],
	        2=>['abr' => 'feb', 'mes'=>"Febrero"],
	        3=>['abr' => 'mar', 'mes'=>"Marzo"],
	        4=>['abr' => 'abr', 'mes'=>"Abril"],
	        5=>['abr' => 'may', 'mes'=>"Mayo"],
	        6=>['abr' => 'jun', 'mes'=>"Junio"],
	        7=>['abr' => 'jul', 'mes'=>"Julio"],
	        8=>['abr' => 'ago', 'mes'=>"Agosto"],
	        9=>['abr' => 'sep', 'mes'=>"Septiembre"],
	        10=>['abr' => 'oct', 'mes'=>"Octubre"],
	        11=>['abr' => 'nov', 'mes'=>"Noviembre"],
	        12=>['abr' => 'dic', 'mes'=>"Diciembre"],
        
        ],
        'po'=>[
	        1=>['abr' => 'ene', 'mes'=>"Janeiro ¿"],
	        2=>['abr' => 'feb', 'mes'=>"Fevereiro"],
	        3=>['abr' => 'mar', 'mes'=>"Março"],
	        4=>['abr' => 'abr', 'mes'=>"Abril"],
	        5=>['abr' => 'may', 'mes'=>"Maio"],
	        6=>['abr' => 'jun', 'mes'=>"Junho"],
	        7=>['abr' => 'jul', 'mes'=>"Julho"],
	        8=>['abr' => 'ago', 'mes'=>"Agosto"],
	        9=>['abr' => 'sep', 'mes'=>"Setembro"],
	        10=>['abr' => 'oct', 'mes'=>"Outubro"],
	        11=>['abr' => 'nov', 'mes'=>"Outubro"],
	        12=>['abr' => 'dic', 'mes'=>"Dezembro"],
        
        ]
    ];
    /**
     * Retorna el nombre del día solicitado
     * @method obtenerDia
     * @param $dia int 
     */
    static function nombreDia($dia="",$lang="es"){
        if($dia!=0 and empty($dia))  $dia = date('w');
 
        return self::$diasSemana[$lang][$dia];
    }
	
    static function diasSemana($lang='es'){
        
        return self::$diasSemana[$lang];
    }

	function LTaDatetime($fecha){
	    
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
	static function hora24($hora="") {
		if(empty($hora)) $hora=date ( 'H' );
		else{
			$date = new DateTime($hora);
			$hora = $date->format('H');	
		}
		return $hora;
	}

    static function horaFormato12($hora=""){
        
        $periodo =(date($hora)>11)?"pm":"am";
        $horaConFormato =date('h:i',strtotime($hora));
        
        return $horaConFormato." ".$periodo;
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
    /**
     * Retorna una fecha dada en Formato datetime
     * 
     * @method datetime
     */
    
    static function datetime($fecha=""){
        if(!empty($fecha)){
            return date ( 'Y-m-d H:i:s',$fecha );    
        }else{
            return date ( 'Y-m-d H:i:s' );
        }
        
    }
    /**
     * @method fecha
     * @return date
     */
    static function fecha($fecha=""){
        $fecha = new DateTime($fecha);
        
        return $fecha->format('d-m-Y');
    }
    /**
     * Cambia una fecha a formato datetime
     * @method fecha
     * @param date $fecha
     */
    static function fechaToDateTime($fecha){
        $datetime = new DateTime($fecha);
        return $datetime->format('d-m-Y');
    }
    
    /**
     * Retorna el número del día del mes de una fecha dada
     * @method numeroDia
     */
    static function numeroDia($f=""){
        
        $f = new DateTime($f);
        return $f->format('d');
    }
    /**
     * Retorna el anio de una fecha dada
     * @method anio
     */
    static function anio($f=""){
        $f = new DateTime($f);
        return $f->format('Y');
    }
    static function mesAbr($f){
        $f = new DateTime($f);
        return self::$meses['es'][$f->format('n')]['abr'];        
    }
	/**
	 * Retorna el mes
	 * @method nombreMes
	 * @param int Mes Fecha en formato (n)
	 * @param string Lang es
	 */
	static function nombreMes($mes,$lang='es'){
		return self::$meses[$lang][$mes]['mes'];
	}

    static function formato($fecha,$formato){
        $date = new DateTime($fecha);
        return $date->format($formato);
    }
	/**
	 * Retorna si una fecha es valida o no
	 * @method validarFecha
	 * @param string fecha a validar
	 * @param string formato formato en que se recibe la fecha
	 * @return boolean
	 */
	static function validarFecha($fecha, $formato = 'Y-m-d H:i:s'){
	    $date = DateTime::createFromFormat($formato, $fecha);
	    return $date && $date->format($formato) == $fecha;
	}
	
}
