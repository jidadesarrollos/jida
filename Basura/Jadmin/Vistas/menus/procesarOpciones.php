<?PHP
$data =& $this->data;
echo "<h1>$data->titulo</h1><br>";
if (isset($data->subtitulo))
    echo "<h3>$data->subtitulo</h3>";
echo $data->formOpcion;

?>