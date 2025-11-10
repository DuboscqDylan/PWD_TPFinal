<?php 

    /**
     * Función para cargar automáticamente las clases cuando se utilizan en lugar de requerirlas explícitamente con include o require.
     * @return
     */
spl_autoload_register(function ($class_name) {
    //echo "class ".$class_name ;
    $directories = array(
        $_SERVER['ROOT'] . '/Modelo/',
        $_SERVER['ROOT'] . '/Util/',
        $_SERVER['ROOT'] . '/Control/',
        $_SERVER['ROOT'] . '/Vista/Recursos/',
        //  $GLOBALS['ROOT'].'util/class/',
    );
    //print_object($directories) ;

    $index = 0;
    $found = false; // Variable para controlar si se encontró la clase

    // Iteración del arreglo $directories
    while ($index < count($directories) && !$found) {

        $directory = $directories[$index];
        // Armado de ruta para el archivo de la clase
        $file = $directory . $class_name . '.php';

        // Verifica si la ruta existe antes de incluirla
        if (file_exists($file)) {
            require_once($file);
            $found = true; // Bandera que detiene el loop si encuentra la clase
        }

        // Procede al siguiente índice del arreglo
        $index++;
    }
    return;
});
