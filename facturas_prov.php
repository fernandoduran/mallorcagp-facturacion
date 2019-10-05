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
	
	$active_facturas_prov="active";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";	
	$title="Facturas Proveedores | Mallorca GP";
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
		    <div class="btn-group pull-right">
				<a  href="nueva_factura_prov.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Factura</a>
			</div>
			<h4><i class='glyphicon glyphicon-search'></i> Buscar Facturas</h4>
		</div>
		
		<div class="panel-body">
			<form class="form-horizontal" role="form" id="datos_cotizacion" method="post">				
				<div class="form-group row">
					<label for="q" class="col-md-2 control-label">Proveedor o # de factura</label>
					<div class="col-md-5">
						<input type="text" class="form-control" name="q" id="q" placeholder="Nombre del proveedor o # de factura" onkeyup='load(1);'>
					</div>			
							
					<div class="col-md-3">
						<button type="submit" class="btn btn-default" name="busca">
							<span class="glyphicon glyphicon-search" ></span> Buscar</button>
							<span id="loader"></span>
					</div>		
				</div>
			</form>
				<!-- <div id="resultados"></div>Carga los datos ajax -->
				<!-- <div class='outer_div'></div>Carga los datos ajax -->
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
						<th>Acciones</th>
					</tr>
				<?php

					if(isset($_POST['busca'])){

						$total_neto = 0;
						$total_iva = 0;
						$total_total = 0;

						$sql = "SELECT facturas_prov.*, users.firstname, users.lastname, proveedores.nombre_cliente FROM facturas_prov, users, proveedores 
								WHERE facturas_prov.id_cliente = users.user_id AND facturas_prov.id_vendedor = proveedores.id_cliente
								AND MONTH(facturas_prov.fecha_factura) = MONTH(CURRENT_DATE()) AND YEAR(facturas_prov.fecha_factura) = YEAR(CURRENT_DATE()) 
								AND (proveedores.nombre_cliente LIKE '%".$_POST['q']."%' OR facturas_prov.numero_factura LIKE '%".$_POST['q']."%') 
								ORDER BY facturas_prov.fecha_factura ASC";
						$sql_query = mysqli_query($con, $sql);

						$count = mysqli_num_rows($sql_query);

					if($count > 0){

						while($rw_fact = mysqli_fetch_array($sql_query)){

							$num_fact = $rw_fact['numero_factura'];
							$fecha_factura=date("d/m/Y", strtotime($rw_fact['fecha_factura']));
							$nombre_cli = $rw_fact['firstname']." ".$rw_fact['lastname'];
							$nombre_prov = $rw_fact['nombre_cliente'];
							$cond = $rw_fact['condiciones'];
							$neto = $rw_fact['neto'];
							$iva = $rw_fact['iva'];
							$total = $rw_fact['total_venta'];
							$estado = $rw_fact['estado_factura'];

							if ($estado == 1){
								$text_estado="Pagada";
								$label_class='label-success';
							
							} else{
								$text_estado="Pendiente";
								$label_class='label-warning';
							}
							
							$total_neto += $neto;
							$total_iva += $iva;
							$total_total += $total;

							?>
					<tr>
						<td><?php echo $num_fact; ?></td>
						<td><?php echo $fecha_factura; ?></td>
						<td><?php echo $nombre_cli; ?></td>
						<td><?php echo $nombre_prov; ?></td>
						<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
						<td class='text-right' class="sub" value="<?php number_format ($neto,2);?>"><?php echo number_format ($neto,2) ?></td>
						<td class='text-right' class="iva" value="<?php number_format ($iva,2);?>"><?php echo number_format ($iva,2) ?></td>	
						<td class='text-right'><?php echo number_format ($total,2); ?></td>	
						<td>
							<form action="" method="post">
								
							<button type="submit" name="borra" class='btn btn-default'>
								<i class="glyphicon glyphicon-trash">
									<input type="hidden" name="factura" value="<?php echo $num_fact;?>">
								</i>
							</button>
							</form>
						</td>			

						
					</tr>
					<?php
						}
						?>
					<tr>
						<td>
							<?php
								$query2 = mysqli_query($con, "SELECT SUM(neto) AS 'neto' FROM facturas_prov, proveedores
									WHERE facturas_prov.id_vendedor = proveedores.id_cliente AND (proveedores.nombre_cliente LIKE '%".$_POST['q']."%' OR facturas_prov.numero_factura LIKE '%".$_POST['q']."%')" ); 
								while($row2 = mysqli_fetch_array($query2)){
									$total_facturado_neto = $row2['neto'];
								}
								echo "<span class='label label-default' style='font-size: 11pt;'>Total sin IVA</span>"." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado_neto,2)."€</span>";
							?>
						</td>
						<td>
							<?php
								$query3 = mysqli_query($con, "SELECT SUM(iva) AS 'iva' FROM facturas_prov, proveedores
									WHERE facturas_prov.id_vendedor = proveedores.id_cliente AND (proveedores.nombre_cliente LIKE '%".$_POST['q']."%' OR facturas_prov.numero_factura LIKE '%".$_POST['q']."%')" ); 
								while($row3 = mysqli_fetch_array($query3)){
									$total_facturado_iva = $row3['iva'];
								}
								echo "<span class='label label-primary' style='font-size: 11pt;'>Total IVA</span>"." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado_iva,2)."€</span>";
							?>
						</td>
						<td>
							<?php
								$query4 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas_prov, proveedores
									WHERE facturas_prov.id_vendedor = proveedores.id_cliente AND (proveedores.nombre_cliente LIKE '%".$_POST['q']."%' OR facturas_prov.numero_factura LIKE '%".$_POST['q']."%')" ); 
								while($row4 = mysqli_fetch_array($query4)){
									$total_facturado = $row4['tot_fact'];
								}
								echo "<span class='label label-success' style='font-size: 11pt;'>Total con IVA</span> "." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado,2)."€</span>";
							?>
						</td>
					</tr>
 				</table>
			</div>
						<?php
					}


					} else {
						$sql = "SELECT facturas_prov.*, users.firstname, users.lastname, proveedores.nombre_cliente FROM facturas_prov, users, proveedores 
								WHERE facturas_prov.id_cliente = users.user_id AND facturas_prov.id_vendedor = proveedores.id_cliente
								AND MONTH(facturas_prov.fecha_factura) = MONTH(CURRENT_DATE()) AND YEAR(facturas_prov.fecha_factura) = YEAR(CURRENT_DATE()) ORDER BY facturas_prov.fecha_factura ASC";

						$total_neto = 0;
						$total_iva = 0;
						$total_total = 0;		
						
						$sql_query = mysqli_query($con, $sql);

						$count = mysqli_num_rows($sql_query);

						if($count > 0){

							while($rw_fact = mysqli_fetch_array($sql_query)){

								$num_fact = $rw_fact['numero_factura'];
								$fecha_factura=date("d/m/Y", strtotime($rw_fact['fecha_factura']));
								$nombre_cli = $rw_fact['firstname']." ".$rw_fact['lastname'];
								$nombre_prov = $rw_fact['nombre_cliente'];
								$cond = $rw_fact['condiciones'];
								$neto = $rw_fact['neto'];
								$iva = $rw_fact['iva'];
								$total = $rw_fact['total_venta'];
								$estado = $rw_fact['estado_factura'];

								if ($estado == 1){
									$text_estado="Pagada";
									$label_class='label-success';
							
								} else{
									$text_estado="Pendiente";
									$label_class='label-warning';
								}

								if ($cond == 1){
								
									$text_cond = "Efectivo";
							
								} elseif($cond == 2){
								
									$text_cond = "Cheque";
								
								} elseif ($cond == 3) {
									
									$text_cond = "Transferencia Bancaria";
								} else {

									$text_cond = "Crédito";
								}

								$total_neto += $neto;
								$total_iva += $iva;
								$total_total += $total;
							?>
					<tr>
						<td><?php echo $num_fact; ?></td>
						<td><?php echo $fecha_factura; ?></td>
						<td><?php echo $nombre_cli; ?></td>
						<td><?php echo $nombre_prov; ?></td>
						<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
						<td class='text-right' class="sub" value="<?php number_format ($neto,2);?>"><?php echo number_format ($neto,2) ?></td>
						<td class='text-right' class="iva" value="<?php number_format ($iva,2);?>"><?php echo number_format ($iva,2) ?></td>	
						<td class='text-right'><?php echo number_format ($total,2); ?></td>	
						<td>
							<form action="" method="post">
								
							<button type="submit" name="borra" class='btn btn-default'>
								<i class="glyphicon glyphicon-trash">
									<input type="hidden" name="factura" value="<?php echo $num_fact;?>">
								</i>
							</button>
							</form>
						</td>				

						
					</tr>
					<?php
						}
						?>

					<tr>
						<td>
							<?php
								$query2 = mysqli_query($con, "SELECT SUM(neto) AS 'neto' FROM facturas_prov WHERE MONTH(facturas_prov.fecha_factura) = MONTH(CURRENT_DATE()) AND YEAR(facturas_prov.fecha_factura) = YEAR(CURRENT_DATE())"); 
								while($row2 = mysqli_fetch_array($query2)){
									$total_facturado_neto = $row2['neto'];
								}
								echo "<span class='label label-default' style='font-size: 11pt;'>Total sin IVA</span>"." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado_neto,2)."€</span>";
							?>
						</td>
						<td>
							<?php
								$query3 = mysqli_query($con, "SELECT SUM(iva) AS 'iva' FROM facturas_prov WHERE MONTH(facturas_prov.fecha_factura) = MONTH(CURRENT_DATE()) AND YEAR(facturas_prov.fecha_factura) = YEAR(CURRENT_DATE())"); 
								while($row3 = mysqli_fetch_array($query3)){
									$total_facturado_iva = $row3['iva'];
								}
								echo "<span class='label label-primary' style='font-size: 11pt;'>Total IVA</span>"." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado_iva,2)."€</span>";
							?>
						</td>
						<td>
							<?php
								$query4 = mysqli_query($con, "SELECT SUM(total_venta) AS 'tot_fact' FROM facturas_prov WHERE MONTH(facturas_prov.fecha_factura) = MONTH(CURRENT_DATE()) AND YEAR(facturas_prov.fecha_factura) = YEAR(CURRENT_DATE())"); 
								while($row4 = mysqli_fetch_array($query4)){
									$total_facturado = $row4['tot_fact'];
								}
								echo "<span class='label label-success' style='font-size: 11pt;'>Total con IVA</span> "." <br>"."<span style='font-size: 11pt;'> ".number_format($total_facturado,2)."€</span>";
							?>
						</td>
					</tr>
 				</table>
			</div>
						<?php
					}
					}
				

						
				?>
			</div>
		</div>	
		
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
  </body>
</html>
<?php
	
	if (isset($_POST['borra'])) {
		
		$sql = "DELETE FROM facturas_prov WHERE numero_factura = '".$_POST['factura']."'";
		$sql_del = mysqli_query($con, $sql);
		echo"<script language='javascript'>window.location='facturas_prov.php'</script>";
	}
?>