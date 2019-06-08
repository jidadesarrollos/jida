<?php
/* Smarty version 3.1.34-dev-7, created on 2019-03-22 22:21:55
  from 'D:\workspace\jida\framework\core-app\jida\plantillas\codigosPHP\clase.jida' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5c9551f3cf8d08_91982020',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a87b2348faed8080a3d6daf645f7a04a431deca0' => 
    array (
      0 => 'D:\\workspace\\jida\\framework\\core-app\\jida\\plantillas\\codigosPHP\\clase.jida',
      1 => 1553289251,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c9551f3cf8d08_91982020 (Smarty_Internal_Template $_smarty_tpl) {
echo '<?php
';?>
/**
 * Creado por Jida Framework
 * <?php echo date('Y-m-d H:i:s');?>

 */
<?php ob_start();
echo $_smarty_tpl->tpl_vars['preNamespace']->value;
$_prefixVariable1 = ob_get_clean();
echo $_prefixVariable1;?>

namespace <?php ob_start();
echo $_smarty_tpl->tpl_vars['namespace']->value;
$_prefixVariable2 = ob_get_clean();
echo $_prefixVariable2;?>
;
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['uses']->value, 'use');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['use']->value) {
?>  
use <?php ob_start();
echo $_smarty_tpl->tpl_vars['use']->value;
$_prefixVariable3 = ob_get_clean();
echo $_prefixVariable3;?>
;
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
ob_start();
echo $_smarty_tpl->tpl_vars['postNamespace']->value;
$_prefixVariable4 = ob_get_clean();
echo $_prefixVariable4;?>

class <?php ob_start();
echo $_smarty_tpl->tpl_vars['class']->value;
$_prefixVariable5 = ob_get_clean();
echo $_prefixVariable5;?>
 extends <?php ob_start();
echo $_smarty_tpl->tpl_vars['extends']->value;
$_prefixVariable6 = ob_get_clean();
echo $_prefixVariable6;?>
{
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['metodos']->value, 'code', false, 'method');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['method']->value => $_smarty_tpl->tpl_vars['code']->value) {
?>  
    public function <?php ob_start();
echo $_smarty_tpl->tpl_vars['method']->value;
$_prefixVariable7 = ob_get_clean();
echo $_prefixVariable7;?>
 (){
    
        <?php ob_start();
echo $_smarty_tpl->tpl_vars['code']->value;
$_prefixVariable8 = ob_get_clean();
echo $_prefixVariable8;?>

    }
    <?php
}
} else {
?>
    // tu codigo aqui
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

}
<?php }
}
