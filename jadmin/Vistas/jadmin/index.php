


<?PHP
 // if(isset($_SESSION['msg'])){
    // echo $_SESSION['msg']."<hr>";
// }
?>
<h1>Desarrollo de aplicaciones con Jida - Framework</h1>



<div><?= $dataArray['testBD'] ?></div>
<?PHP
if(isset($dataArray['formLoggin'])){
    echo $dataArray['formLoggin'];
}    
else{
?>


<a href="/jadmin/jadmin/crear-tablas-jida">Crear Tablas Jida</a>
<?PHP 
}
?>