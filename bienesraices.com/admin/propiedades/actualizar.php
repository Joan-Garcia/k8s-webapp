<?php

use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

require '../../includes/app.php';
estaAutenticado();

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin/index.php');
}

// Consulta para obtener datos de propiedades y vendedores
$propiedad = Propiedad::find($id);
$vendedores = Vendedor::all();

// Arreglo con mensajes de error
$errores = Propiedad::getErrores();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $args = $_POST['propiedad'];

    $propiedad->sincronizar($args);

    $errores = $propiedad->validar();

    // Generar nombre unicos
    $nombreImagen = md5(uniqid(rand(), true))  . ".jpg";

    // Realiza un resize a la imagen
    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    if (empty($errores)) {
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            // Almacenar imagen
            if ($image) {
                $image->save(CARPETA_IMAGENES . $nombreImagen);
            }
        }
        $propiedad->guardar();
    }
}


incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>

    <a href="/admin/index.php" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include '../../includes/templates/formulario_propiedades.php' ?>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>