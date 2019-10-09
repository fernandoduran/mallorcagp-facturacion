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
	$active_presupuestos="active";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";	
	$title="Editar Fecha Factura | Mallorca GP";
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
    
    if(isset($_GET['id_factura'])){

        $id_factura = intval($_GET['id_factura']);

        $last_num_fact = mysqli_query($con, "select LAST_INSERT_ID(numero_factura) as last from facturas order by id_factura desc limit 0,1 ");
        $rw=mysqli_fetch_array($last_num_fact);
        $numero_factura=$rw['last']+1;

        $sql_fact = "INSERT INTO facturas (numero_factura, fecha_factura, id_cliente, id_vendedor, condiciones, total_venta, estado_factura)
                    SELECT $numero_factura, CURRENT_TIMESTAMP, id_cliente, id_vendedor, condiciones, total_venta, 1 FROM presupuestos WHERE id_factura = $id_factura";
        
        $insert_fact = mysqli_query($con, $sql_fact);

        $sql_id_fact = "SELECT numero_factura FROM `facturas` WHERE numero_factura = (SELECT MAX(numero_factura) FROM facturas)";
        $select_id = mysqli_query($con, $sql_id_fact);

        $count = mysqli_num_rows($select_id);

        if ($count == 1){

            $rw_id = mysqli_fetch_array($select_id);
            $num_fact = $rw_id['numero_factura'];

            $sql_detalles_fact = "INSERT INTO detalle_factura (numero_factura, id_producto, cantidad, precio_venta)
                                SELECT $num_fact, id_producto, cantidad, precio_venta FROM detalle_presupuesto WHERE numero_factura = (SELECT numero_factura FROM presupuestos WHERE id_factura = $id_factura)";

            $inser_detalles = mysqli_query($con, $sql_detalles_fact);
        }
        $update_presupuesto = mysqli_query($con, "UPDATE presupuestos SET estado_factura = 1 WHERE id_factura = $id_factura");

        if($insert_fact && $inser_detalles){

            $messages[] = "Presupuesto convertido en factura";
        
        } else {

            $errors []= "Lo siento algo ha salido mal intenta nuevamente. ".mysqli_error($con);
        }

        if (isset($errors)){
			
            foreach ($errors as $error) {
                echo '<script language="javascript">
                            alert("'.$error.'");
                            window.location.href="presupuestos.php";
                    </script>';
                }
                
            }
            
			if (isset($messages)){
				foreach ($messages as $message) {
                    echo  '<script language="javascript">
                                alert("'.$message.'");
                                window.location.href="facturas.php";
                            </script>';
                    }
            }
        }
?>