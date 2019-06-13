<?php
/* Smarty version 3.1.33, created on 2019-06-13 15:12:52
  from 'C:\xampp\htdocs\JIDA\core-app\jida\Core\Consola\plantillas\codigosPHP\clase-BD.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d0267f4c857f5_19548201',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9506069a67ffb8f3b8434ef1f14d98aee962a988' => 
    array (
      0 => 'C:\\xampp\\htdocs\\JIDA\\core-app\\jida\\Core\\Consola\\plantillas\\codigosPHP\\clase-BD.jida',
      1 => 1560186815,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d0267f4c857f5_19548201 (Smarty_Internal_Template $_smarty_tpl) {
echo '<?php
';?>/**
 * Creado por Jida Framework
 * <?php echo date('Y-m-d H:i:s');?>

 */
namespace App\Config;


class BD{
    var $manejador = '<?php ob_start();
echo $_smarty_tpl->tpl_vars['manejador']->value;
$_prefixVariable1 = ob_get_clean();
echo $_prefixVariable1;?>
';
   
    var $default   = [
        'puerto' => '<?php ob_start();
echo $_smarty_tpl->tpl_vars['puerto']->value;
$_prefixVariable2 = ob_get_clean();
echo $_prefixVariable2;?>
',
        'usuario' => '<?php ob_start();
echo $_smarty_tpl->tpl_vars['usuario']->value;
$_prefixVariable3 = ob_get_clean();
echo $_prefixVariable3;?>
',
        'clave' => '<?php ob_start();
echo $_smarty_tpl->tpl_vars['clave']->value;
$_prefixVariable4 = ob_get_clean();
echo $_prefixVariable4;?>
',
        'bd' => '<?php ob_start();
echo $_smarty_tpl->tpl_vars['bd']->value;
$_prefixVariable5 = ob_get_clean();
echo $_prefixVariable5;?>
',
        'servidor' => '<?php ob_start();
echo $_smarty_tpl->tpl_vars['servidor']->value;
$_prefixVariable6 = ob_get_clean();
echo $_prefixVariable6;?>
'
    ];
}
 

<?php }
}
