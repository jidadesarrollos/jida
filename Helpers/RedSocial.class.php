<<<<<<< HEAD
<?php
/**
 * Clase Helper para manejo de redes sociales
 * @package Framework
 * @category Helper
 * 
 * @author Julio Rodriguez
 * 
 */

class RedSocial{
  /**
   * Crea las etiquetas opengraph necesarias para comprartir una página en facebook
   * @method fbOpenGraphTags
   * @access static public
   */
    static function fbOpenGraphTags($titulo,$type,$content,$url,$img,$siteName,$descripcion){
        if(!defined('FACEBOOK_APP_ID')){
            throw new Exception("No se encuentra definido el id de aplicación de facebook", 1);
            
        }
        $metaTags = 
        '
        <meta property="og:title" content="'.$titulo.'"/>
        <meta property="og:site_name" content="'.$siteName.'"/>
        <meta property="og:url" content="'.$url.'"/>
        <meta property="og:description" content="'.$descripcion.'"/>
        <meta property="og:image" content="'.$img.'"/>
        <meta property="fb:app_id" content="'.FACEBOOK_APP_ID.'"/>
        <meta property="og:type" content="'.$type.'"/>
        
        ';
        return $metaTags;
    }
}
=======
<?php
/**
 * Clase Helper para manejo de redes sociales
 * @package Framework
 * @category Helper
 * 
 * @author Julio Rodriguez
 * 
 */

class RedSocial{
  /**
   * Crea las etiquetas opengraph necesarias para comprartir una página en facebook
   * @method fbOpenGraphTags
   * @access static public
   */
    static function fbOpenGraphTags($titulo,$type,$content,$url,$img,$siteName,$descripcion){
        if(!defined('FACEBOOK_APP_ID')){
            throw new Exception("No se encuentra definido el id de aplicación de facebook", 1);
            
        }
        $metaTags = 
        '
        <meta property="og:title" content="'.$titulo.'"/>
        <meta property="og:site_name" content="'.$siteName.'"/>
        <meta property="og:url" content="'.$url.'"/>
        <meta property="og:description" content="'.$descripcion.'"/>
        <meta property="og:image" content="'.$img.'"/>
        <meta property="fb:app_id" content="'.FACEBOOK_APP_ID.'"/>
        <meta property="og:type" content="'.$type.'"/>
        
        ';
        return $metaTags;
    }
    
    
    static function fbPost(){
        
    }
}
>>>>>>> 34cd0f7f25eb4beb9ae94a3d183a38bc9fc66fae
?>