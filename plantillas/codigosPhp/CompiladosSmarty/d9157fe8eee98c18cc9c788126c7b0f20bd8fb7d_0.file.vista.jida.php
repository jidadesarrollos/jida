<?php
/* Smarty version 3.1.34-dev-7, created on 2019-03-06 17:58:19
  from 'E:\Programacion\php\jida\core-app\jida\plantillas\codigosPHP\vista.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5c7ffc2be18988_55145702',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd9157fe8eee98c18cc9c788126c7b0f20bd8fb7d' => 
    array (
      0 => 'E:\\Programacion\\php\\jida\\core-app\\jida\\plantillas\\codigosPHP\\vista.jida',
      1 => 1551891447,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c7ffc2be18988_55145702 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Creado por Jida Framework <?php echo date('Y-m-d H:i:s');?>
 -->
<div class = "jumbotron">
    <h2><?php ob_start();
echo $_smarty_tpl->tpl_vars['cabecera']->value;
$_prefixVariable9 = ob_get_clean();
echo $_prefixVariable9;?>
</h2>
    <p><?php ob_start();
echo $_smarty_tpl->tpl_vars['mensaje']->value;
$_prefixVariable10 = ob_get_clean();
echo $_prefixVariable10;?>
</p>
</div ><?php }
}
