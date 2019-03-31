<?php
/* Smarty version 3.1.34-dev-7, created on 2019-03-22 21:51:41
  from 'D:\workspace\jida\framework\core-app\jida\plantillas\codigosPHP\vista.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5c954add207d42_45506761',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4bc1f717e02a2f0d52e026a2b9e102a78d51f453' => 
    array (
      0 => 'D:\\workspace\\jida\\framework\\core-app\\jida\\plantillas\\codigosPHP\\vista.jida',
      1 => 1553286955,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c954add207d42_45506761 (Smarty_Internal_Template $_smarty_tpl) {
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
