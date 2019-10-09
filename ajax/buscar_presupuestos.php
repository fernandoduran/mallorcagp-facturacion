<?php

	/*-------------------------
	Autor: Fernando Duran Ruiz
	Mail: fduranruiz@gmail.com
	---------------------------*/
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$numero_factura=intval($_GET['id']);
		$del1="delete from presupuestos where numero_factura='".$numero_factura."'";
		$del2="delete from detalle_presupuesto where numero_factura='".$numero_factura."'";
		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se puedo eliminar los datos
			</div>
			<?php
			
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$sTable = "presupuestos, clientes, users";
		$sWhere = "";
		$sWhere2 = "";

		$sWhere.=" WHERE presupuestos.id_cliente=clientes.id_cliente and presupuestos.id_vendedor=users.user_id";
		$sWhere2.=" WHERE presupuestos.id_cliente=clientes.id_cliente and presupuestos.id_vendedor=users.user_id";

		if ( $_GET['q'] != "" )
		{
			$sWhere.= " and  (clientes.nombre_cliente like '%$q%' or presupuestos.numero_factura like '%$q%')";
			$sWhere2.= " and  (clientes.nombre_cliente like '%$q%' or presupuestos.numero_factura like '%$q%')";
			
		}

		$sWhere.=" order by presupuestos.id_factura desc";

		$sWhere2.=" and MONTH(presupuestos.fecha_factura) = MONTH(CURRENT_DATE()) and YEAR(presupuestos.fecha_factura) = YEAR(CURRENT_DATE()) order by presupuestos.id_factura desc";
		
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './presupuestos.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere2 LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			echo mysqli_error($con);
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="info">
					<th>#</th>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Estado</th>
					<th class='text-right'>Subtotal</th>
					<th class='text-right'>IVA</th>
					<th class='text-right'>Total</th>
					<th class='text-center'>Acciones</th>
					
				</tr>
				<?php
				$total_iva = 0;
				while ($row=mysqli_fetch_array($query)){
						$id_factura=$row['id_factura'];
						$numero_factura=$row['numero_factura'];
						$fecha=date("d/m/Y", strtotime($row['fecha_factura']));
						$nombre_cliente=$row['nombre_cliente'];
						$telefono_cliente=$row['telefono_cliente'];
						$email_cliente=$row['email_cliente'];
						$nombre_vendedor=$row['firstname']." ".$row['lastname'];
						$estado_factura=$row['estado_factura'];
						
						if ($estado_factura==1){
							$text_estado="Aceptado";
							$label_class='label-success';
							$disabled = "disabled";
						} else{
							$text_estado="Pendiente";
							$label_class='label-warning';
							$disabled = "";
							
						}
						$iva = $row['total_venta'] - ($row['total_venta']/1.21);
						$total_venta=$row['total_venta'];
						$sub = $total_venta - $iva;
					?>
					<tr>
						<td><?php echo $numero_factura; ?></td>
						<td><?php echo $fecha; ?></td>
						<td><a href="#" data-toggle="tooltip" data-placement="top" title="<i class='glyphicon glyphicon-phone'></i> <?php echo $telefono_cliente;?><br><i class='glyphicon glyphicon-envelope'></i>  <?php echo $email_cliente;?>" ><?php echo $nombre_cliente;?></a></td>
						<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
						<td class='text-right' class="sub" value="<?php number_format ($sub,2);?>"><?php echo number_format ($sub,2) ?></td>
						<td class='text-right' class="iva" value="<?php number_format ($iva,2);?>"><?php echo number_format ($iva,2) ?></td>	
						<td class='text-right'><?php echo number_format ($total_venta,2); ?></td>					
					<td class="text-right">
						<a href="pasar_a_factura.php?id_factura=<?php echo $id_factura?>" class="btn btn-success btn-sm" title="Pasar a factura" <?php echo $disabled?>><i class="glyphicon glyphicon-check"></i></a>
						<a href="editar_presupuesto.php?id_factura=<?php echo $id_factura;?>" class='btn btn-default btn-sm' title='Editar presupuesto'><i class="glyphicon glyphicon-edit"></i></a> 
						<a href="editar_fecha_presu.php?id_factura=<?php echo $id_factura;?>" class='btn btn-default btn-sm' title='Editar fecha presupuesto' ><i class="glyphicon glyphicon-calendar"></i></a>
						<a href="#" class='btn btn-default btn-sm' title='Descargar presupuesto' onclick="imprimir_factura('<?php echo $id_factura;?>');"><i class="glyphicon glyphicon-download"></i></a> 
						<a href="#" class='btn btn-danger btn-sm' title='Borrar presupuesto' onclick="eliminar('<?php echo $numero_factura; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
					</td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>

			<?php
		}
	}
?>