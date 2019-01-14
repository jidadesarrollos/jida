<form enctype="multipart/form-data" action="<?= $this->url ?>" method="POST">
    <!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000"/>
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    Enviar este fichero: <input name="fichero_usuario" type="file"/>
    <hr/>
    <input type="submit" value="Enviar fichero"/>
</form>