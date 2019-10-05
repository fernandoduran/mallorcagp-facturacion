<?php
	/*-------------------------
	Autor: Fernando Duran Ruiz
	Mail: fduranruiz@gmail.com
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	
	$active_facturas_anti="active";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";	
	$title="Facturas Clientes | Mallorca GP";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php 
		include("head.php");
		require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	?>

  </head>
  <body>
	<?php
	include("navbar.php");
	?>  
    <div class="container">
		<div class="panel panel-info">
		<div class="panel-heading">
			<h4><i class='glyphicon glyphicon-search'></i> Facturas clientes </h4>
		</div>
		
		<div class="panel-body">
			<form class="form-horizontal" role="form" id="datos_cotizacion" method="post">				
				<div class="form-group row">
					<label for="p" class="col-md-1 control-label">Cliente</label>
					<div class="col-md-3">
						<input type="text" class="form-control" name="p" id="p" placeholder="Nombre del cliente" onkeyup='load(1);'>
					</div>
					<label for="m" class="col-md-1 control-label">Mes</label>
					<div class="col-md-2">
						<select class="form-control" name="m" id="m">
							<option value="00">Seleccione un mes</option>
							<option value="01">Enero</option>
							<option value="02">Febrero</option>
							<option value="03">Marzo</option>
							<option value="04">Abril</option>
							<option value="05">Mayo</option>
							<option value="06">Junio</option>
							<option value="07">Julio</option>
							<option value="08">Agosto</option>
							<option value="09">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</div>		
					
					<label for="a" class="col-md-1 control-label">Año</label>
					<div class="col-md-2">
						<select class="form-control" name="a" id="a">
							<option value="0">Seleccione un año</option>
							<?php

								for ($i=2010; $i<=date('Y'); $i++) {
								   echo "<option value='$i'>$i</option>";
								} 
							?>
						</select>
					</div>			
					<div class="col-md-2">
						<button type="submit" class="btn btn-default" name="busca">
							<span class="glyphicon glyphicon-search" ></span> Buscar</button>
							<span id="loader"></span>
					</div>		
				</div>
			</form>

<?php

	if(isset($_POST['busca'])){

		$total_iva = 0;
		$total_total = 0;	
		
		if($_POST['p'] == "" && $_POST['m'] == "00" && $_POST['a'] == "0"){

			//Todo vacío
			?>
			<div class="alert alert-danger" role="alert">
			  Introduce algun dato de búsqueda
			</div>
			<?

		} elseif ($_POST['p'] == "" && $_POST['m'] != "00" && $_POST['a'] == "0") {
			
			//busca por mes
			$sql = "SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id 
								AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['tot_fact'];
			}


		} elseif ($_POST['p'] == "" && $_POST['m'] == "00" && $_POST['a'] != "0") {
			
			//busca por año
			$sql = "SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id
								AND YEAR(facturas.fecha_factura) = '".$_POST['a']."'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND YEAR(facturas.fecha_factura) = '".$_POST['a']."'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['tot_fact'];
			}


		} elseif ($_POST['p'] != "" && $_POST['m'] == "00" && $_POST['a'] == "0") {
			
			//busca por proveedor
			$sql="SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id 
								AND clientes.nombre_cliente LIKE '%".$_POST['p']."%'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND clientes.nombre_cliente LIKE '%".$_POST['p']."%'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['tot_fact'];
			}
			

		} elseif ($_POST['p'] != "" && $_POST['m'] != "00" && $_POST['a'] == "0") {
			
			//Busca por proveedor y mes
			$sql="SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id 
								AND clientes.nombre_cliente LIKE '%".$_POST['p']."%' AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND clientes.nombre_cliente LIKE '%".$_POST['p']."%' AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['tot_fact'];
			}

		} elseif ($_POST['p'] != "" && $_POST['m'] == "00" && $_POST['a'] != "0") {
			
			//Busca por proveedor y año
			$sql="SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id 
								AND clientes.nombre_cliente LIKE '%".$_POST['p']."%' AND YEAR(facturas.fecha_factura) = '".$_POST['a']."'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND clientes.nombre_cliente LIKE '%".$_POST['p']."%' AND YEAR(facturas.fecha_factura) = '".$_POST['a']."'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['tot_fact'];
			}
			
		} elseif ($_POST['p'] == "" && $_POST['m'] != "00" && $_POST['a'] != "0") {
			
			//Busca por mes y año
			$sql="SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id 
								AND YEAR(facturas.fecha_factura) = '".$_POST['a']."' AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND YEAR(facturas.fecha_factura) = '".$_POST['a']."' AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['tot_fact'];
			}
			
		} else {

			$sql="SELECT facturas.*, users.firstname, users.lastname, clientes.nombre_cliente FROM facturas, users, clientes 
								WHERE facturas.id_cliente = clientes.id_cliente AND facturas.id_vendedor =  users.user_id 
								AND YEAR(facturas_prov.fecha_factura) = '".$_POST['a']."' AND MONTH(facturas.fecha_factura) = '".$_POST['m']."' AND clientes.nombre_cliente LIKE '%".$_POST['p']."%'";

			$query2 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas, clientes
								WHERE facturas.id_cliente = clientes.id_cliente AND clientes.nombre_cliente LIKE '%".$_POST['p']."%' AND YEAR(facturas.fecha_factura) = '".$_POST['a']."' AND MONTH(facturas.fecha_factura) = '".$_POST['m']."'"); 
			
			while($row2 = mysqli_fetch_array($query2)){
				$total_facturado_neto = $row2['neto'];
			}
			
		}
		?>
		<div class="table-responsive">
		  	<table class="table">
				<tr  class="info">
					<th>#</th>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Vendedor</th>
					<th>Estado</th>
					<th class='text-right'>SUBTOTAL</th>
					<th class='text-right'>IVA</th>
					<th class='text-right'>Total</th>
					<th class='text-right'>Acciones</th>
				</tr>
		<?php

		$sql_query = mysqli_query($con, $sql);

		$count = mysqli_num_rows($sql_query);

		if($count > 0){

			while($rw_fact = mysqli_fetch_array($sql_query)){
				$id_factura = $rw_fact['id_factura'];
				$num_fact = $rw_fact['numero_factura'];
				$fecha_factura=date("d/m/Y", strtotime($rw_fact['fecha_factura']));
				$nombre_cli = $rw_fact['firstname']." ".$rw_fact['lastname'];
				$nombre_prov = $rw_fact['nombre_cliente'];
				$cond = $rw_fact['condiciones'];
				$total = $rw_fact['total_venta'];
				$estado = $rw_fact['estado_factura'];

				if ($estado == 1){
					$text_estado="Pagada";
					$label_class='label-success';
				
				} else{
					$text_estado="Pendiente";
					$label_class='label-warning';
				}
				
				$neto = $rw_fact['total_venta'];
				$bruto = ($rw_fact['total_venta']/1.21);
				$iva = $rw_fact['total_venta']-($rw_fact['total_venta']/1.21);
				$total_iva += $iva;
				$total_total += $bruto;

				?>
				<tr>
					<td><?php echo $num_fact; ?></td>
					<td><?php echo $fecha_factura; ?></td>
					<td><?php echo $nombre_cli; ?></td>
					<td><?php echo $nombre_prov; ?></td>
					<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
					<td class='text-right' class="sub" value="<?php number_format ($bruto,2);?>"><?php echo number_format ($bruto,2) ?></td>
					<td class='text-right' class="iva" value="<?php number_format ($iva,2);?>"><?php echo number_format ($iva,2) ?></td>	
					<td class='text-right'><?php echo number_format ($neto,2); ?></td>	
					<td class="text-right">
						<a href="editar_factura.php?id_factura=<?php echo $id_factura;?>" class='btn btn-default btn-sm' title='Editar factura' ><i class="glyphicon glyphicon-edit"></i></a> 
						<a href="editar_fecha_fact.php?id_factura=<?php echo $id_factura;?>" class='btn btn-default btn-sm' title='Editar fecha factura' ><i class="glyphicon glyphicon-calendar"></i></a> 
						<a href="#" class='btn btn-default btn-sm' title='Descargar factura' onclick="imprimir_factura('<?php echo $id_factura;?>');"><i class="glyphicon glyphicon-download"></i></a> 
						<a href="#" class='btn btn-danger btn-sm' title='Borrar factura' onclick="eliminar('<?php echo $numero_factura; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
					</td>

					
				</tr>
				<?php
					}
					?>
				<tr>
					<td>
						<?php
							echo "<span class='label label-default' style='font-size: 11pt;'>Total sin IVA</span>"." <br>"."<span style='font-size: 11pt;'> ".number_format($total_total,2)."€</span>";
						?>
					</td>
					<td>
						<?php
							echo "<span class='label label-primary' style='font-size: 11pt;'>Total IVA</span>"." <br>"."<span style='font-size: 11pt;'> ".number_format($total_iva,2)."€</span>";
						?>
					</td>
					<td>
						<?php
							echo "<span class='label label-success' style='font-size: 11pt;'>Total con IVA</span> "." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado_neto,2)."€</span>";
						?>
					</td>
				</tr>
				</table>
		</div>
					<?php
				} else {

					?>
					<div class="alert alert-danger" role="alert">
					  No hay datos almacenados con los criterios de búsqueda seleccionados.
					</div>
					<?

				}

	}
?>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/facturas.js"></script>
  </body>
</html>