<?php 
	if ($con){
?>
   
    <table cellspacing="0" style="width: 100%;">
        <tr>

            <td style="width: 25%; color: #444444;">
                <img style="width: 100%;" src="../../<?php echo get_row('perfil','logo_url', 'id_perfil', 1);?>" alt="Logo"><br>
                
            </td>
			<td style="width: 50%;text-align:center">
                <span style="color: #34495e;font-size:14px;font-weight:bold"><?php echo get_row('perfil','nombre_empresa', 'id_perfil', 1);?></span>
				<br>
				<span style="font-size:10px;"><?php echo get_row('perfil','direccion', 'id_perfil', 1).", ". get_row('perfil','ciudad', 'id_perfil', 1)." ".get_row('perfil','estado', 'id_perfil', 1);?><br> 
				<b>Teléfono:</b> <?php echo get_row('perfil','telefono', 'id_perfil', 1);?> - 
				<b>Email:</b> <?php echo get_row('perfil','email', 'id_perfil', 1);?><br>
				<b>NIF/CIF:</b> <?php echo get_row('perfil','nif_cif', 'id_perfil', 1);?></span>
                
            </td>
			<td style="width: 25%;text-align:right">
			PRESUPUESTO Nº <?php echo $numero_factura;?>
			</td>
			
        </tr>
    </table>
	<?php }?>	