<?php
require '../includes/app.php';
estaAutenticado();

use App\Propiedad;
use App\Vendedor;

// Implementar metodo para obtener todas las propiedades
$propiedades = Propiedad::all();
$vendedores = Vendedor::all();

// Mensaje condicional
$result = $_GET['result'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {
        $tipo = $_POST['tipo'];
        if (validarTipoContenido($tipo)) {
            if ($tipo === 'propiedad') {
                $propiedad = Propiedad::find($id);
                $propiedad->eliminar($id);
            } else if ($tipo === 'vendedor') {
                $vendedor = Vendedor::find($id);
                $vendedor->eliminar($id);
            }
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador</h1>
    <?php
    $mensaje = mostrarNotificacion(intval($result));
    if ($mensaje) { ?>
        <p class="alerta exito">
            <?php echo s($mensaje); ?>
        </p>
    <?php } ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
    <a href="/admin/vendedores/crear.php" class="boton boton-amarillo">Nuevo Vendedor</a>

    <h2>Propiedades</h2>
    <table class="propiedades">
        <thead>
            <tr>
                <th>Id</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($propiedades as $row) : ?>
                <tr>
                    <td><?php echo $row->id; ?></td>
                    <td><?php echo $row->titulo; ?></td>
                    <td> <img src="/imagenes/<?php echo $row->imagen; ?>" class="imagen-tabla"> </td>
                    <td>$ <?php echo $row->precio; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                            <input type="hidden" name="tipo" value="propiedad">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $row->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Vendedores</h2>
    <table class="propiedades">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vendedores as $row) : ?>
                <tr>
                    <td><?php echo $row->id; ?></td>
                    <td><?php echo $row->nombre . " " . $row->apellido; ?></td>
                    <td><?php echo $row->telefono; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $row->id; ?>">
                            <input type="hidden" name="tipo" value="vendedor">
                            <input type="submit" value="Eliminar" class="boton-rojo-block">
                        </form>
                        <a href="/admin/vendedores/actualizar.php?id=<?php echo $row->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
incluirTemplate('footer');
?>