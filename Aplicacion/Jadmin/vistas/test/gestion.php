<form enctype="multipart/form-data"
      action="<?= $this->url ?>"
      method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="30000"/>
    Enviar este fichero:
    <input name="cargaArchivo" type="file"/>
    <hr/>
    <input type="submit" name="cargaArchivos" CLASS="btn" value="Enviar fichero"/>
</form>