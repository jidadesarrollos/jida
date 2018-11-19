<?PHP
$data =& $this->data;

?>

<?PHP if (isset($data->formLoggin)) { ?>

    <div class="col-md-6 col-md-offset-3 top-60">
        <div class="panel panel-default panel-login">
            <section class="panel-heading" role="title">
                <h1>
                    JIDA Framework <br/>
                    <small>Desarrollo de aplicaciones</small>
                </h1>
            </section>
            <section class="panel-body">

                <?= $data->formLoggin ?>
            </section>
        </div>
    </div>
    <?PHP

}
?>