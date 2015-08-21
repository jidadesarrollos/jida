<?PHP 
$data =& $this->data;

?>

<?PHP if(isset($data->formLoggin)){?>

<div class="col-md-6 col-md-offset-3 top-60">
    <div class="panel panel-default">
        <section class="panel-heading" role="title">
            <h1>
                Jida - Framework
                <small>Desarrollo de aplicaciones </small>
            </h1>
        </section>
        <section class="panel-body">
            
            <?=$data->formLoggin?>
        </section>
    </div>    
</div>
<?PHP

}
?>