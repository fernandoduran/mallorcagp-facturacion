<!--<?php
//	session_start();
//	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
 //       header("location: login.php");
//		exit;
//        }
//	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
//	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	

//	$id_factura = $_SESSION['id_factura'];
//	$sql = "SELECT fecha_factura FROM facturas WHERE id_factura = '".$id_factura."'";

//	$query = mysqli_query($con, $sql);

//	while ($row = mysqli_fetch_array($query)) {
		
//		$fecha_fact = $row['fecha_factura'];
//	}
?>-->
<!--<!DOCTYPE html>
<html lang="en">
  <head>
    <?php //include("head.php");?>
  </head>
  <body>
	<?php
	//include("navbar.php");
	?>  
<form method="post">
	
	<input type="date" name="fecha" id="fecha" value="<?php //echo $fecha_fact;?>">
	<input type="hidden" id="factu" value="<?php //echo $id_factura?>"
	<input type="submit" id="actualiza">

</form>-->
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
	$active_facturas="active";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";	
	$title="Editar Fecha Factura | Mallorca GP";
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	if (isset($_GET['id_factura']))
	{
		$id_factura=intval($_GET['id_factura']);
		$campos="clientes.id_cliente, clientes.nombre_cliente, clientes.telefono_cliente, clientes.email_cliente, facturas.id_vendedor, facturas.fecha_factura, facturas.condiciones, facturas.estado_factura, facturas.numero_factura";
		$sql_factura=mysqli_query($con,"select $campos from facturas, clientes where facturas.id_cliente=clientes.id_cliente and id_factura='".$id_factura."'");
		$count=mysqli_num_rows($sql_factura);
		if ($count==1)
		{
				$rw_factura=mysqli_fetch_array($sql_factura);
				$id_cliente=$rw_factura['id_cliente'];
				$nombre_cliente=$rw_factura['nombre_cliente'];
				$telefono_cliente=$rw_factura['telefono_cliente'];
				$email_cliente=$rw_factura['email_cliente'];
				$id_vendedor_db=$rw_factura['id_vendedor'];
				$fecha_factura2=date("d/m/Y", strtotime($rw_factura['fecha_factura']));
				$fecha_factura=$rw_factura['fecha_factura'];
				$condiciones=$rw_factura['condiciones'];
				$estado_factura=$rw_factura['estado_factura'];
				$numero_factura=$rw_factura['numero_factura'];
				$_SESSION['id_factura']=$id_factura;
				$_SESSION['numero_factura']=$numero_factura;
		}	
		else
		{
			header("location: facturas.php");
			exit;	
		}
	} 
	else 
	{
		header("location: facturas.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
	<?php
	include("navbar.php");
	?>  
    <div class="container">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h4><i class='glyphicon glyphicon-edit'></i> Editar fecha factura</h4>
		</div>
		<div class="panel-body">
			<form action="#" class="form-horizontal" role="form" id="datos_factura" method="post">
				<input type="hidden" name="idfact" value="<?php echo $id_factura;?>">
				<div class="form-group row">
				  <label for="nombre_cliente" class="col-md-1 control-label">Cliente</label>
				  <div class="col-md-3">
					  <input type="text" class="form-control input-sm" id="nombre_cliente" placeholder="Selecciona un cliente" required value="<?php echo $nombre_cliente;?>" disabled>
					  <input id="id_cliente" name="id_cliente" type='hidden' value="<?php echo $id_cliente;?>">	
				  </div>
				  <label for="tel1" class="col-md-1 control-label">Teléfono</label>
							<div class="col-md-2">
								<input type="text" class="form-control input-sm" id="tel1" placeholder="Teléfono" value="<?php echo $telefono_cliente;?>" disabled>
							</div>
					<label for="mail" class="col-md-1 control-label">Email</label>
							<div class="col-md-3">
								<input type="text" class="form-control input-sm" id="mail" placeholder="Email"  value="<?php echo $email_cliente;?>" disabled>
							</div>
				 </div>
						<div class="form-group row">
							<label for="empresa" class="col-md-1 control-label">Vendedor</label>
							<div class="col-md-3">
								<select class="form-control input-sm" id="id_vendedor" name="id_vendedor"  disabled>
									<?php
										$sql_vendedor=mysqli_query($con,"select * from users order by lastname");
										while ($rw=mysqli_fetch_array($sql_vendedor)){
											$id_vendedor=$rw["user_id"];
											$nombre_vendedor=$rw["firstname"]." ".$rw["lastname"];
											if ($id_vendedor==$id_vendedor_db){
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
								<input type="date" class="form-control input-sm" id="fecha" name="fecha" value="">
							</div>
							<label for="estado_factura" class="col-md-1 control-label">Estado</label>
								<div class="col-md-2">
								<select class='form-control input-sm ' id="estado_factura" name="estado_factura" disabled>
									<option value="1" <?php if ($estado_factura==1){echo "selected";}?>>Pagado</option>
									<option value="2" <?php if ($estado_factura==2){echo "selected";}?>>Pendiente</option>
								</select>
							</div>

						</div>
						<div class="form-group row">
							<label for="condiciones" class="col-md-1 control-label">Obs.</label>
							<div class="col-md-2">
								<textarea class='form-control input-sm' id="condiciones" rows="4" cols="5" disabled>
									<?php echo $condiciones;?>
								</textarea>
							</div>
						</div>
				
				
				<div class="col-md-12">
					<div class="pull-right">

						<input type="submit" class="btn btn-default" id="actu" name="actu" value="Actualizar fecha">
						<button class="btn btn-success"><a href="facturas.php" style="color: white;">Volver</a></button>  

					</div>	
				</div>
			</form>	
			Fecha actual de la factura: <?php echo $fecha_factura2;?>
			<div class="clearfix"></div>
				<div class="editar_factura" class='col-md-12' style="margin-top:10px"></div><!-- Carga los datos ajax -->	
			
		<div id="resultados" class='col-md-12' style="margin-top:10px"></div><!-- Carga los datos ajax -->			
			
		</div>
	</div>		
		 
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<?php

		if(isset($_POST['fecha'])){

			$fecha_actu = date('Y-m-d', strtotime($_POST['fecha']));
		}
				if(isset($_POST['actu'])){


					$sql = "UPDATE facturas SET fecha_factura ='".$fecha_actu."' WHERE id_factura = '".$id_factura."'";
					
					$query_update = mysqli_query($con,$sql);
					
					if ($query_update){
						
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