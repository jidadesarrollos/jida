<?PHP 

echo "<h1>$dataArray[titulo]</h1><br>";
if(isset($dataArray['subtitulo']))
    echo "<h3>$dataArray[subtitulo]</h3>";
echo $dataArray['formOpcion'];

?>