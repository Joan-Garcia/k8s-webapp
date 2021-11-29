<?php

namespace App;

class ActiveRecord
{
    // DB
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    protected static $errores = [];

    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function guardar()
    {
        if (!is_null($this->id)) {
            // actualizar
            $this->actualizar();
        } else {
            // crear
            $this->crear();
        }
    }

    public function crear()
    {
        //Sanitizar datos
        $atributos = $this->sanitizarDatos();


        // Insertar en DB
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ');";

        $result = self::$db->query($query);

        if ($result) {
            // Redireccionar al usuario
            header("Location: /admin?result=1");
        }
    }

    public function actualizar()
    {
        //Sanitizar datos
        $atributos = $this->sanitizarDatos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key} = '{$value}'";
        }

        $query = "UPDATE " . static::$tabla . " SET " . join(", ", $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' LIMIT 1;";

        $result = self::$db->query($query);

        if ($result) {
            // Redireccionar al usuario
            header("Location: /admin/index.php?result=2");
        }
    }

    public function eliminar()
    {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id);
        $query .= " LIMIT 1;";
        $result = self::$db->query($query);

        if ($result) {
            $this->borrarImagen();
            header("Location: /admin?result=3");
        }
    }

    // Idenficar y unir los atributos de la db
    public function datos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function setImagen($imagen)
    {
        // Eliminar la anterior
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }
        // Asignar nombre
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    public function borrarImagen()
    {
        if (file_exists(CARPETA_IMAGENES . $this->imagen)) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    public function sanitizarDatos()
    {
        $atributos = $this->datos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    public static function getErrores()
    {
        return static::$errores;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla . ";";

        $result = self::consultarSQL($query);

        return $result;
    }

    public static function get($n)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $n;

        $result = self::consultarSQL($query);

        return $result;
    }

    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
        $result = self::consultarSQL($query);
        return array_shift($result);
    }

    public static function consultarSQL($query)
    {
        // Consultar
        $result = self::$db->query($query);

        // Iterar
        $array = [];
        while ($row = $result->fetch_assoc()) {
            $array[] = static::crearObjeto($row);
        }

        // Liberar memoria
        $result->free();

        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
