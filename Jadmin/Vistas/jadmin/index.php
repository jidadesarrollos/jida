


<?PHP
 // if(isset($_SESSION['msg'])){
    // echo $_SESSION['msg']."<hr>";
// }
?>



<?PHP if(isset($dataArray['formLoggin'])){?>
<!-- <div><?= $dataArray['testBD'] ?></div> -->
<div class="col-md-6 col-md-offset-3 top-60">
    <div class="panel panel-default">
        <section class="panel-heading" role="title">
            <h1>
                Jida - Framework
                <small>Desarrollo de aplicaciones </small>
            </h1>
        </section>
        <section class="panel-body">
            
            <?=$dataArray['formLoggin']?>
        </section>
    </div>    
</div>
<?PHP

}else{
?>


<a href="/jadmin/jadmin/crear-tablas-jida">Crear Tablas Jida</a>
<?PHP 
}
?>