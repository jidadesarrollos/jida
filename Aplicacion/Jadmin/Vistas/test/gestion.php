<form enctype="multipart/form-data"
      action="<?= $this->url ?>"
      method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="300000"/>
    Enviar este fichero:
    <input name="cargaArchivo[]" type="file" multiple/>
    <hr/>
    <input type="submit" name="cargaArchivos" CLASS="btn" value="Enviar fichero"/>
</form>