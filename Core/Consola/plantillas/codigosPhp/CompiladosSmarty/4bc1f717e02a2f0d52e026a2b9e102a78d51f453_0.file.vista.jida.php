<?php
/* Smarty version 3.1.33, created on 2019-06-06 12:07:41
  from 'D:\workspace\jida\framework\core-app\jida\plantillas\codigosPHP\vista.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5cf8e5ed1563f6_32973680',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4bc1f717e02a2f0d52e026a2b9e102a78d51f453' => 
    array (
      0 => 'D:\\workspace\\jida\\framework\\core-app\\jida\\plantillas\\codigosPHP\\vista.jida',
      1 => 1558579615,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5cf8e5ed1563f6_32973680 (Smarty_Internal_Template $_smarty_tpl) {
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
