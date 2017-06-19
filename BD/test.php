<?php
include_once 'ConexionBD.class.php';
include_once 'DBContainer.class.php';
    echo "<h3>Hola mundo</h3>";
	$cn  = new DBContainer();
	$cn->bd->comenzarTransaccion();
	
	//print_r($cn->bd->obtenerDataCompleta($q));
	
	
	$query2="insert into prueba values (2,'hola mundillo geek','como estas tu');";
	$cn->bd->ejecutarQuery($query2);
	$query3="insert into prueba values (2,'insert otro','de prueba');";
	$cn->bd->ejecutarQuery($query3);
	$cn->bd->establecerPuntoControl("PUNTO");
	$query="delete from prueba where id_prueba=30;";
	$cn->bd->ejecutarQuery($query);
	$query4="insert into prueba values (1,'Despues del delete','de prueba');";
	
	$cn->bd->ejecutarQuery($query4);
	$cn->bd->finalizarTransaccion("PUNTO");
	
	
	echo "<hr>$cn->totalRegistros<hr>";
	
	 
?>