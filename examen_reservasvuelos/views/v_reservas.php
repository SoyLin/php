<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>RESERVAS VUELOS</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
 </head>
   
 <body>
   

    <div class="container ">
        <!--Aplicacion-->
		<div class="card border-success mb-3" style="max-width: 30rem;">
		<div class="card-header">Reservar Vuelos</div>
		<div class="card-body">
	  	  

	<!-- INICIO DEL FORMULARIO -->
	<form action="" method="post">
	
		<B>Email Cliente:</B><?php echo $_SESSION['email']; ?>    <BR>
		<B>Nombre Cliente:</B><?php echo $_SESSION['usuario']; ?>    <BR>
		<B>Fecha:</B> <?php echo $_SESSION['fecha']; ?>    <BR><BR>
		
		<B>Vuelos</B><select name="vuelos" class="form-control">
		<?php  foreach($todos_vuelos as $aray) { 
     
	 	echo "<option value=" . "'" . $aray["id_vuelo"] . "'>" . $aray["id_vuelo"] . "." .  $aray["origen"] . "-" .  $aray["destino"] . "</option>";
	 	}
	 	?> 
				
		</select>	
		<BR> 
		<B>Número Asientos</B><input type="number" name="asientos" size="3" min="1" max="100" value="1" class="form-control">
		<BR><BR><BR><BR><BR>
		<div>
			<input type="submit" value="Agregar a Cesta" name="agregar" class="btn btn-warning disabled">
			<input type="submit" value="Comprar" name="comprar" class="btn btn-warning disabled">
			<input type="submit" value="Vaciar Cesta" name="vaciar" class="btn btn-warning disabled">
			<input type="submit" value="Volver" name="volver" class="btn btn-warning disabled">
		</div>		
	</form>
	
	<!-- FIN DEL FORMULARIO -->
    <a href = "./controllers/con_logout.php">Cerrar Sesion</a>
	<?php
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";

	
	if (!empty($error)) {
		echo "<pre>";
		print_r($error);
		echo "</pre>";	
	}
	?>
  </body>
   
</html>

