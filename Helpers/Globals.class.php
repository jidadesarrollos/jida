<?php
/**
 * Helper para manejo de variables globales
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category Helpers
 * @version 1 -12-02-2014
 */
 
 
 class Globals{
     
     
     
     static function obtPost($name){
         $post =& $_POST;
         
         if(isset($post[$name])){
             return $post[$name];
         }else{
             return false;
         }
         
     }
     
     static function obtGet($name){
         $get =& $_GET;
         
         if(isset($get[$name])){
             return $get[$name];
         }else{
             return false;
         }
         
     }
     
     static function setPost($clave,$valor){
         $_POST[$clave]=$valor;         
     }
     
	 
	 static function getGlobal($clave){
	 	if(isset($GLOBALS[$clave])){
	 		return $GLOBALS[$clave];
	 	}else{
	 		return false;
	 	}
	 }
 }
?>