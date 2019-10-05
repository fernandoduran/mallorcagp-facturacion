	<?php
		if (isset($con))
		{
	?>

	<!-- Modal -->
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar cliente</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="editar_cliente" name="editar_cliente">
			<div id="resultados_ajax2"></div>
			  <div class="form-group">
				<label for="mod_nombre" class="col-sm-3 control-label">Nombre</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_nombre" name="mod_nombre"  readonly>
					<input type="hidden" name="mod_id" id="mod_id">
				</div>
			  </div>
			   <div class="form-group">
				<label for="mod_telefono" class="col-sm-3 control-label">Teléfono</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_telefono" name="mod_telefono">
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="mod_email" class="col-sm-3 control-label">Email</label>
				<div class="col-sm-8">
				 <input type="email" class="form-control" id="mod_email" name="mod_email">
				</div>
			  </div>

			  <!--<div class="form-group">
				<label for="mod_dni" class="col-sm-3 control-label">DNI</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_dni" name="mod_dni">
				</div>
			  </div>

			  <div class="form-group">
				<label for="mod_matricula" class="col-sm-3 control-label">Matricula</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_matricula" name="mod_matricula">
				</div>
			  </div>-->

			  <div class="form-group">
				<label for="mod_km" class="col-sm-3 control-label">KM</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_km" name="mod_km">
				</div>
			  </div>

			  <!--<div class="form-group">
				<label for="mod_marca" class="col-sm-3 control-label">Marca</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_marca" name="mod_marca">
				</div>
			  </div>

			  <div class="form-group">
				<label for="mod_bastidor" class="col-sm-3 control-label">Bastidor</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_bastidor" name="mod_bastidor">
				</div>
			  </div>-->

			  <div class="form-group">
				<label for="mod_direccion" class="col-sm-3 control-label">Dirección</label>
				<div class="col-sm-8">
				  <textarea class="form-control" id="mod_direccion" name="mod_direccion" ></textarea>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="mod_estado" class="col-sm-3 control-label">Estado</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_estado" name="mod_estado" required>
					<option value="">-- Selecciona estado --</option>
					<option value="1" selected>Activo</option>
					<option value="0">Inactivo</option>
				  </select>
				</div>
			  </div>
			 
			 
			 
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="actualizar_datos">Actualizar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
	<?php
		}
	?>