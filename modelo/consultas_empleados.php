<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
?>

<?php
    require 'conexcion.php';

    session_start();

    if(isset($_SESSION['username']))
    {
        $nombre_usuario = $_SESSION['username'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Empleados</title>
</head>
<body>
    <h1>Consulta Empleados</h1>
    <?php
        echo 'Usuario: '.$nombre_usuario;
    ?>
    
    <!-- Formulario de búsqueda -->
    <form method="POST" action="">
        <label for="id_departamento">ID del Departamento:</label>
        <input type="number" name="id_departamento" id="id_departamento" min="1">
        <input type="submit" name="buscar_departamento" value="Buscar por Departamento">
        
        <br><br>
        
        <label for="id_empleado">ID del Empleado:</label>
        <input type="number" name="id_empleado" id="id_empleado" min="1">
        <input type="submit" name="buscar_empleado" value="Buscar por Empleado">
        
        <br><br>
        
        <input type="submit" name="mostrar_todos" value="Mostrar Todos">
    </form>
    
    <!--Mostrar consulta-->
    <?php
        if(isset($_SESSION['username']))
        {
            // Verificar si se hizo una búsqueda específica por departamento
            if(isset($_POST['buscar_departamento']) && !empty($_POST['id_departamento']))
            {
                $id_departamento = intval($_POST['id_departamento']);
                $query = "SELECT empleado.id_empleado, empleado.nombre_empleado, empleado.apellidos_empleados, departamento.nombre_departamento 
                FROM empleado JOIN departamento ON empleado.id_departamento = departamento.id_departamento 
                WHERE empleado.id_departamento = $id_departamento";
            }
            // Verificar si se hizo una búsqueda específica por empleado
            elseif(isset($_POST['buscar_empleado']) && !empty($_POST['id_empleado']))
            {
                $id_empleado = intval($_POST['id_empleado']);
                $query = "SELECT empleado.id_empleado, empleado.nombre_empleado, empleado.apellidos_empleados, departamento.nombre_departamento 
                FROM empleado JOIN departamento ON empleado.id_departamento = departamento.id_departamento 
                WHERE empleado.id_empleado = $id_empleado";
            }
            else
            {
                // Mostrar todos los empleados (consulta original)
                $query = "SELECT empleado.id_empleado, empleado.nombre_empleado, empleado.apellidos_empleados, departamento.nombre_departamento 
                FROM empleado JOIN departamento ON empleado.id_departamento = departamento.id_departamento";
            }

            $resultado = mysqli_query($conexion, $query) or trigger_error("Error en la consulta: " . mysqli_error($conexion));

            // Verificar si hay resultados
            if(mysqli_num_rows($resultado) > 0)
            {
                //Encabezado de la tabla de resultados
                echo "<table border='1' align='center'>";
                echo "<tr>";
                    echo "<th>Id</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Apellidos</th>";
                    echo "<th>Departamento</th>";
                echo "</tr>";
                
                // Filas de la tabla, traidos de la consulta a la BD
                while($fila = mysqli_fetch_array($resultado))
                {
                    echo "<tr>";
                        echo "<td>";
                            echo $fila['id_empleado'];
                        echo "</td>";
                        echo "<td>";
                            echo $fila['nombre_empleado'];
                        echo "</td>";
                        echo "<td>";
                            echo $fila['apellidos_empleados'];
                        echo "</td>";
                        echo "<td>";
                            echo $fila['nombre_departamento'];
                        echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else
            {
                echo "<p align='center'>No se encontraron empleados.</p>";
            }
        }
        else
        {
            header('location: ../index.php');
        }
    ?>
</body>
</html>