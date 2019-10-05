<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
.midnight-blue{
	background:#2c5046;
	padding: 4px 4px 4px;
	color:white;
	font-weight:bold;
	font-size:12px;
}
.silver{
	background:white;
	padding: 3px 4px 3px;
}
.clouds{
	background:#ecf0f1;
	padding: 3px 4px 3px;
}
.border-top{
	border-top: solid 1px #bdc3c7;
	
}
.border-left{
	border-left: solid 1px #bdc3c7;
}
.border-right{
	border-right: solid 1px #bdc3c7;
}
.border-bottom{
	border-bottom: solid 1px #bdc3c7;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
}
-->
</style>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        <page_footer>
        <table class="page_footer">
            <tr>

                <td style="width: 50%; text-align: left">
                    P&aacute;gina [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 50%; text-align: right">
                    &copy; <?php echo "Mallorca GP "; echo  $anio=date('Y'); ?>
                </td>
            </tr>
        </table>
    </page_footer>
	<?php include("encabezado_presupuesto.php");?>
    <br>
    

	
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
           <td class='midnight-blue'>PRESUPUESTAR A</td>
        </tr>

		<tr>
           <td style="width:50%;" >
			<?php 
				$sql_cliente=mysqli_query($con,"select * from clientes where id_cliente='$id_cliente'");
				$rw_cliente=mysqli_fetch_array($sql_cliente);

				echo $rw_cliente['nombre_cliente']." - ".$rw_cliente['dni'];
				echo "<br>";
				echo $rw_cliente['direccion_cliente'];
				echo "<br>";
				echo $rw_cliente['telefono_cliente']." - ".$rw_cliente['email_cliente'];
				echo "<br>";
				echo "<br><strong> Marca:</strong> ";
				echo $rw_cliente['marca'];
				echo "<br><strong> Matricula:</strong> ";
				echo $rw_cliente['matricula'];
				echo "<br><strong> Km:</strong> ";
				echo $rw_cliente['kilometros'];
				echo "<br><strong> Bastidor:</strong> ";
				echo $rw_cliente['bastidor'];
			?>
			
		   </td>
        </tr>
        
   
    </table>
    
       <br>
		<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
           <td style="width:35%;" class='midnight-blue'>VENDEDOR</td>
		  <td style="width:25%;" class='midnight-blue'>FECHA</td>
        </tr>
		<tr>
           <td style="width:35%;">
			<?php 
				$sql_user=mysqli_query($con,"select * from users where user_id='$id_vendedor'");
				$rw_user=mysqli_fetch_array($sql_user);
				echo $rw_user['firstname']." ".$rw_user['lastname'];
			?>
		   </td>
		  <td style="width:25%;"><?php echo date("d/m/Y", strtotime($fecha_factura));?></td>
        </tr>
		
        
   
    </table>
	<br>
  
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 9pt;">
        <tr>
        	<th style="width: 25%;text-align:center" class='midnight-blue'>REF.</th>
            <th style="width: 10%;text-align:center" class='midnight-blue'>CANT.</th>
            <th style="width: 35%" class='midnight-blue'>DESCRIPCION</th>
            <th style="width: 15%;text-align: right" class='midnight-blue'>PRECIO UNIT.</th>
            <th style="width: 15%;text-align: right" class='midnight-blue'>PRECIO TOTAL</th>
            
        </tr>

<?php
$nums=1;
$sumador_total=0;
$sql=mysqli_query($con, "select * from products, detalle_presupuesto, presupuestos where products.id_producto=detalle_presupuesto.id_producto and detalle_presupuesto.numero_factura=presupuestos.numero_factura and presupuestos.id_factura='".$id_factura."'");

while ($row=mysqli_fetch_array($sql))
	{
	$id_producto=$row["id_producto"];
	$codigo_producto=$row['codigo_producto'];
	$cantidad=$row['cantidad'];
	$nombre_producto=$row['nombre_producto'];
	
	$precio_venta=$row['precio_venta'];
	$precio_venta_f=number_format($precio_venta,2);//Formateo variables
	$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
	$precio_total=$precio_venta_r*$cantidad;
	$precio_total_f=number_format($precio_total,2);//Precio total formateado
	$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
	$sumador_total+=$precio_total_r;//Sumador
	if ($nums%2==0){
		$clase="clouds";
	} else {
		$clase="silver";
	}
	?>

        <tr>
        	<td class='<?php echo $clase;?>' style="width: 25%; text-align: center"><?php echo $codigo_producto; ?></td>
            <td class='<?php echo $clase;?>' style="width: 10%; text-align: center"><?php echo $cantidad; ?></td>
            <td class='<?php echo $clase;?>' style="width: 35%; text-align: left"><?php echo $nombre_producto;?></td>
            <td class='<?php echo $clase;?>' style="width: 15%; text-align: right"><?php echo $precio_venta_f;?></td>
            <td class='<?php echo $clase;?>' style="width: 15%; text-align: right"><?php echo $precio_total_f;?></td>
            
        </tr>

	<?php 

	
	$nums++;
	}
	$impuesto=get_row('perfil','impuesto', 'id_perfil', 1);
	$subtotal=number_format($sumador_total,2,'.','');
	$total_iva=($subtotal * $impuesto )/100;
	$total_iva=number_format($total_iva,2,'.','');
	$total_factura=$subtotal+$total_iva;
?>
	 </table>

	 <div style="font-size:8pt;text-align:center;font-weight:bold">
	 	<p><?php echo $condiciones;?></p>
	 </div>
	 
	 <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt; position: absolute; top: 88%;"> 
        <tr>
            <td colspan="1" style="widtd: 5%; text-align: right;" class='midnight-blue'>SUBTOTAL  </td>
            <td colspan="1" style="widtd: 5%; text-align: center;" class='midnight-blue'>IVA (<?php echo $impuesto;?>)%  </td>
            <td colspan="2" style="widtd: 15%; text-align: left;" class='midnight-blue'>TOTAL </td>
        </tr>
		<tr>
            <td style="width: 60%; text-align: right;"> <?php echo number_format($subtotal,2);?><?php echo $simbolo_moneda;?></td>
            <td style="width: 20%; text-align: center;"> <?php echo number_format($total_iva,2);?> <?php echo $simbolo_moneda;?></td>
            <td style="width: 20%; text-align: left;"> <?php echo number_format($total_factura,2);?> <?php echo $simbolo_moneda;?></td>
        </tr><tr>
        </tr>
    </table>
	
	
	
	<br>
	<div style="font-size:3pt;text-align:center;font-weight:bold">
		<p>Los presupuestos tienen una validez de 12 dias naturales ,de no aceptarlo tendra que abonarse el 50% de la mano de obra</p>								
		<p>Los presupuestos realizados de palabra,desmontanto parcialmente o sin desmontar  el vehiculo no seran cerrados, se avisara al propietario 								
			si el coste de la averia encontrada fuera superior a 50€ del precio indicado en el presupuesto inicial	</p>							
		<p>Las reparaciones reflejadas en esta factura incluidos los gastos de mano de obra contaran con una garantia de 3 meses o 2000 km recorridos  a partir de la fecha								de entrega del véhiculo.La garantia que afecta a las piezas sustituidas en la reparacion reflejada en la presente factura ,sera dada por el fabricante o garante de dichas piezas 			Si las piezas sustituidas, ya bien sean nuevas o de segunda mano son aportadas por el propietario del vehiculo, la reparacion no tendra ningun tipo de garantia por este taller 			este taller no se hace responsable de los bienes personales que se dejen depositados dentro del vehiculo</p>								

		<p>Sus datos de carácter personal han sido recogidos de acuerdo con lo dispuesto en la ley orgánica 15/1999, del 13 de diciembre de protección de datos personal, y se encuentran almacenados en un fichero propiedad de silvia bonet Giménez, con domicilio en c7 general Riera 77 Local 3 07010-Palma de mallorca Baleares. De acuerdo con la ley anteriortiene derecho a ejercer los derechos de acceso,rectificación,cancelación y oposicion de los datos en la direccion facilitada en este párrafo</p>
	</div>
	
	
	  

</page>

