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
	$title="Nueva Factura Proveedor | Simple Invoice";
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
  	<script type="text/javascript">
  		function calcular() {
  			
  			var neto = eval(document.getElementById('neto').value);
  			var iva = (neto * 1.21) - neto;
  			var total = neto + iva;

  			document.getElementById('iva').value = iva.toFixed(2);
  			document.getElementById('total').value = total.toFixed(2);

  		}
  	</script>
	<?php
	include("navbar.php");
	?>  
    <div class="container">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4><i class='glyphicon glyphicon-edit'></i> Nueva Factura Proveedor</h4>
		</div>
		<div class="panel-body">
		<?php 
			include("modal/buscar_productos.php");
			include("modal/registro_proveedores.php");
			include("modal/registro_productos.php");
		?>
			<form class="form-horizontal" role="form" id="datos_factura" method="post">
				<div class="form-group row">
				  <label for="nombre_cliente" class="col-md-1 control-label">Proveedor</label>
				  <div class="col-md-3">
					  <input type="text" class="form-control input-sm" id="nombre_cliente" placeholder="Selecciona un proveedor" required>
					  <input id="id_cliente" type='hidden' name="prov">	
				  </div>
				  <label for="tel1" class="col-md-1 control-label">Teléfono</label>
							<div class="col-md-2">
								<input type="text" class="form-control input-sm" id="tel1" placeholder="Teléfono" readonly>
							</div>
					<label for="mail" class="col-md-1 control-label">Email</label>
							<div class="col-md-3">
								<input type="text" class="form-control input-sm" id="mail" placeholder="Email" readonly>
							</div>

				 </div>

						<div class="form-group row">
							<label for="empresa" class="col-md-1 control-label">Cliente</label>
							<div class="col-md-2">
								<select class="form-control input-sm" id="id_vendedor" name="cli">
									<?php
										$sql_vendedor=mysqli_query($con,"select * from users order by lastname");
										while ($rw=mysqli_fetch_array($sql_vendedor)){
											$id_vendedor=$rw["user_id"];
											$nombre_vendedor=$rw["firstname"]." ".$rw["lastname"];
											if ($id_vendedor==$_SESSION['user_id']){
												$selected="selected";
											} else {
												$selected="";
											}
											?>
											<option value="<?php echo $id_vendedor?>" <?php echo $selected;?>><?php echo $nombre_vendedor?></option>
											<?php
										}
									?>
								</select>
							</div>
							<label for="tel2" class="col-md-1 control-label">Fecha</label>
							<div class="col-md-2">
								<input type="date" class="form-control input-sm" id="fecha" name="fecha" required>
							</div>
							<label for="email" class="col-md-1 control-label">Pago</label>
							<div class="col-md-2">
								<select class='form-control input-sm' id="condiciones" name="condiciones">
									<option value="1">Efectivo</option>
									<option value="2">Cheque</option>
									<option value="3">Transferencia bancaria</option>
									<option value="4">Crédito</option>
								</select>
							
							</div>
							<label for="num_fact" class="col-md-1 control-label">Nº Fact.</label>
							<div class="col-md-2">
								<input type="text" class="form-control input-sm" name="num_fact" id="num_fact" placeholder="Nº Factura" required>
							</div>
					</div>
				
				<div class="form-group row">
				  <label for="neto" class="col-md-1 control-label">Neto</label>
				  <div class="col-md-3">
					  <input type="number" step=".01" class="form-control input-sm" id="neto" name="neto" placeholder="Precio sin iva" required onkeyup="calcular()">
				  </div>
				  <label for="iva" class="col-md-1 control-label">IVA</label>
							<div class="col-md-1">
								<input type="text" class="form-control input-sm" id="iva" name="iva" placeholder="IVA" disabled="disabled" required>
							</div>
					<label for="total" class="col-md-1 control-label">Total</label>
							<div class="col-md-2">
								<input type="text" class="form-control input-sm" id="total" name="total" placeholder="Total + IVA" disabled="disabled" required>
							</div>
					<label for="estado" class="col-md-1 control-label">Estado</label>
						<div class="col-md-2">
								<select class='form-control input-sm ' id="estado" name="estado" required>
									<option value="1">Pagado</option>
									<option value="2">Pendiente</option>
								</select>
							</div>

				 </div>
				
				
				<div class="col-md-12">
					<div class="pull-right">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#nuevoProveedor">
						 <span class="glyphicon glyphicon-user"></span> Nuevo proveedor
						</button>
						<button type="submit" class="btn btn-success" name="guarda">
						 <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
						</button>
					</div>	
				</div>
			</form>	
			<?php
				if(isset($_POST['guarda'])){
					
					$neto = $_POST['neto'];
					$iva = ($neto * 1.21) - $neto;
					$total = $neto + $iva;
					
					
					$sql = "INSERT INTO facturas_prov (numero_factura, fecha_factura, id_cliente, id_vendedor, condiciones, neto, iva, total_venta, estado_factura)
									VALUES ('".$_POST['num_fact']."','".$_POST['fecha']."','".$_POST['cli']."','".$_POST['prov']."','".$_POST['condiciones']."',
									'".$neto."','".$iva."','".$total."','".$_POST['estado']."') ";

					$query_insert = mysqli_query($con, $sql);

					if ($query_insert){
						
						$messages[] = "Cliente ha sido actualizado satisfactoriamente.";
					} else{
						$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
					}
				}

				if (isset($errors)){
			
				?>
					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Error!</strong> 
						<?php
							foreach ($errors as $error) {
								echo $error;
							}
						?>
					</div>
				<?php
				}
			
				if (isset($messages)){

				?>
				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>¡Bien hecho!</strong>
					<?php
						foreach ($messages as $message) {
							echo $message;
						}
					?>
				</div>
				<?php
				}
			?>

			</form>	
			
		<div id="resultados" class='col-md-12' style="margin-top:10px"></div><!-- Carga los datos ajax -->			
		</div>
	</div>		
		  <div class="row-fluid">
			<div class="col-md-12">
			
	

			
			</div>	
		 </div>
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		$(function() {
						$("#nombre_cliente").autocomplete({
							source: "./ajax/autocomplete/proveedores.php",
							minLength: 2,
							select: function(event, ui) {
								event.preventDefault();
								$('#id_cliente').val(ui.item.id_cliente);
								$('#nombre_cliente').val(ui.item.nombre_cliente);
								$('#tel1').val(ui.item.telefono_cliente);
								$('#mail').val(ui.item.email_cliente);
								$('#dni').val(ui.item.dni);
																
								
							 }
						});
						 
						
					});
					
	$("#nombre_cliente" ).on( "keydown", function( event ) {
						if (event.keyCode== $.ui.keyCode.LEFT || event.keyCode== $.ui.keyCode.RIGHT || event.keyCode== $.ui.keyCode.UP || event.keyCode== $.ui.keyCode.DOWN || event.keyCode== $.ui.keyCode.DELETE || event.keyCode== $.ui.keyCode.BACKSPACE )
						{
							$("#id_cliente" ).val("");
							$("#tel1" ).val("");
							$("#mail" ).val("");
											
						}
						if (event.keyCode==$.ui.keyCode.DELETE){
							$("#nombre_cliente" ).val("");
							$("#id_cliente" ).val("");
							$("#tel1" ).val("");
							$("#mail" ).val("");
						}
			});	
	</script>

  </body>
</html>