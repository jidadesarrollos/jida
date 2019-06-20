<?php
/* Smarty version 3.1.33, created on 2019-06-07 13:31:26
  from 'C:\xampp\htdocs\JIDA\app\vendor\jida\jida\Core\Consola\plantillas\codigosPHP\vista.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5cfa672e308256_57163988',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '876353421658491282b9ec0286c4f13f344d0c37' => 
    array (
      0 => 'C:\\xampp\\htdocs\\JIDA\\app\\vendor\\jida\\jida\\Core\\Consola\\plantillas\\codigosPHP\\vista.jida',
      1 => 1559864610,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5cfa672e308256_57163988 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Creado por Jida Framework <?php echo date('Y-m-d H:i:s');?>
 -->
<div>
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
