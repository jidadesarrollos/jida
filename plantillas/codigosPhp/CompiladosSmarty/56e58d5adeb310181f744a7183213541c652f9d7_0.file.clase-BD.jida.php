<?php
/* Smarty version 3.1.34-dev-7, created on 2019-03-06 18:56:52
  from 'E:\Programacion\php\jida\core-app\jida\plantillas\codigosPHP\clase-BD.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5c8009e4ea5388_20468732',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '56e58d5adeb310181f744a7183213541c652f9d7' => 
    array (
      0 => 'E:\\Programacion\\php\\jida\\core-app\\jida\\plantillas\\codigosPHP\\clase-BD.jida',
      1 => 1551893556,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c8009e4ea5388_20468732 (Smarty_Internal_Template $_smarty_tpl) {
echo '<?php
';?>
/**
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
