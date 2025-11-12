-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2024 a las 22:24:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";

-- Base de datos: `bdcarritocompras`
CREATE DATABASE `bdcarritocompras`;
-- --------------------------------------------------------

-- Selecciona la base de datos a usar:
USE `bdcarritocompras`;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuario`
CREATE TABLE `usuario` (
  `idusuario` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usnombre` varchar(50) NOT NULL,
  `uspass` varchar(32) NOT NULL,
  `usmail` varchar(256) NOT NULL,
  `usdeshabilitado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `producto`
CREATE TABLE `producto` (
  `idproducto` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `pronombre` varchar(50) NOT NULL,
  `prodetalle` varchar(1024) NOT NULL,
  `procantstock` int(11) NOT NULL,
  `proprecio` DECIMAL(10, 2) NOT NULL,
  `prodeshabilitado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `rol`
CREATE TABLE `rol` (
  `idrol` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rodescripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `compraestadotipo`
CREATE TABLE `compraestadotipo` (
  `idcompraestadotipo` int(11) NOT NULL AUTO_INCREMENT,
  `cetdescripcion` varchar(50) NOT NULL,
  `cetdetalle` varchar(256) NOT NULL,
  PRIMARY KEY (`idcompraestadotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `compra`
CREATE TABLE `compra` (
  `idcompra` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cofecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idusuario` bigint UNSIGNED NOT NULL,
  PRIMARY KEY(`idcompra`),
  FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `compraestado`
CREATE TABLE `compraestado` (
  `idcompraestado` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idcompra` bigint UNSIGNED NOT NULL,
  `idcompraestadotipo` int(11) NOT NULL,
  `cefechaini` timestamp NOT NULL DEFAULT current_timestamp(),
  `cefechafin` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcompraestado`),
  FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `compraitem`
CREATE TABLE `compraitem` (
  `idcompraitem` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `idproducto` bigint UNSIGNED NOT NULL,
  `idcompra` bigint UNSIGNED NOT NULL,
  `cicantidad` int(11) NOT NULL,
  PRIMARY KEY (`idcompraitem`),
  FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `menu`
CREATE TABLE `menu` (
  `idmenu` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menombre` varchar(50) NOT NULL COMMENT 'Nombre del item del menu',
  `medescripcion` varchar(128) NOT NULL COMMENT 'Descripcion mas detallada del item del menu',
  `meurl` varchar(2000) NOT NULL DEFAULT '#' COMMENT 'Ubicacion donde redirecciona item del menu (Desde BASE_URL)',
  `idpadre` bigint UNSIGNED DEFAULT NULL COMMENT 'Referencia al id del menu que es subitem',
  `medeshabilitado` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que el menu fue deshabilitado por ultima vez',
  PRIMARY KEY (`idmenu`),
  FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `menurol`
CREATE TABLE `menurol` (
  `idmenu` bigint UNSIGNED NOT NULL,
  `idrol` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`idmenu`,`idrol`),
  FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuariorol`
CREATE TABLE `usuariorol` (
  `idusuario` bigint UNSIGNED NOT NULL,
  `idrol` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`idusuario`,`idrol`),
  FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE,
  FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

-- Volcado de datos para la tabla `menu`
INSERT INTO `menu` (`idmenu`, `menombre`, `medescripcion`, `meurl`, `idpadre`) VALUES
(1, 'Administrador', '#', '#', NULL),
(2, 'Deposito', '#', '#', NULL),
(3, 'Cliente', '#', '#', NULL),
(4, 'Administrar Usuarios', 'AdministrarUsuarios', '/Vista/Paginas/Usuarios/Usuarios.php', 1),
(5, 'Administrar Productos', 'AdministrarProductos', '/Vista/Paginas/Producto/Producto.php', 2),
(6, 'Administrar Compras', 'AdministrarCompras', '/Vista/Paginas/Compras/Compras.php', 2),
(7, 'Carrito', 'Carrito', '/Vista/Paginas/Carrito/Carrito.php', 3),
(8, 'Mis Compras', 'MisCompras', '/Vista/Paginas/MisCompras/MisCompras.php', 3);
-- --------------------------------------------------------

-- Volcado de datos para la tabla `usuario`
INSERT INTO `usuario` (`idusuario`, `usnombre`, `uspass`, `usmail`, `usdeshabilitado`) VALUES
(1, 'admin', MD5('password'), 'admin@example.com', NULL),
(2, 'deposito', MD5('password'), 'deposito1@example.com', NULL),
(3, 'cliente', MD5('password'), 'cliente1@example.com', NULL);
-- --------------------------------------------------------

-- Volcado de datos para la tabla `rol`
INSERT INTO `rol` (`idrol`, `rodescripcion`) VALUES
(1, 'Administrador'),
(2, 'Deposito'),
(3, 'Cliente');
-- --------------------------------------------------------

-- Volcado de datos para la tabla `usuariorol`
INSERT INTO `usuariorol` (`idusuario`, `idrol`) VALUES
(1, 1), -- Admin
(2, 2), -- Deposito
(3, 3); -- Cliente
-- --------------------------------------------------------

-- Volcado de datos para la tabla `menurol`
INSERT INTO `menurol` (`idmenu`, `idrol`) VALUES
(1, 1), -- Administrador
(2, 2), -- Depósito
(3, 3); -- Cliente
-- --------------------------------------------------------

-- Volcado de datos para la tabla `producto` (solo bicicletas)
INSERT INTO `producto` (`idproducto`, `pronombre`, `prodetalle`, `procantstock`, `proprecio`, `prodeshabilitado`) VALUES
(1, 'Bicicleta de Montaña XTR 29"', 'La XTR 29" es una bicicleta de montaña con cuadro de aluminio liviano, suspensión delantera, transmisión Shimano de 21 velocidades y frenos a disco hidráulicos. Ideal para terrenos exigentes y aventuras al aire libre.', 12, 950.00, NULL),
(2, 'Bicicleta de Ruta AeroSpeed 105', 'La AeroSpeed 105 combina rendimiento y aerodinámica. Equipada con cuadro de carbono, grupo Shimano 105 de 22 velocidades y ruedas de perfil alto. Diseñada para ciclistas que buscan velocidad y precisión.', 8, 2750.00, NULL),
(3, 'Bicicleta Urbana EcoRide City', 'La EcoRide City es una bicicleta urbana con cuadro de acero reforzado, canasto delantero, portaequipaje trasero y frenos a disco. Cómoda, resistente y perfecta para desplazamientos diarios en la ciudad.', 15, 680.00, NULL),
(4, 'Bicicleta Eléctrica VoltX 500', 'La VoltX 500 es una bicicleta eléctrica equipada con motor de 350W y batería de litio de 36V con autonomía de hasta 60 km. Posee frenos hidráulicos, pantalla LCD y asistencia al pedaleo con 5 modos.', 6, 1680.00, NULL),
(5, 'Bicicleta Gravel Terra GX', 'La Terra GX combina velocidad de ruta con la resistencia del ciclismo off-road. Cuadro de aluminio, transmisión SRAM Apex 1x11 y neumáticos de 40 mm listos para cualquier superficie.', 10, 1850.00, NULL),
(6, 'Bicicleta Infantil Rocket 16"', 'La Rocket 16" es perfecta para los más pequeños. Cuenta con cuadro de acero, ruedas estabilizadoras desmontables, frenos V-Brake y diseño colorido. Recomendada para niños de 5 a 8 años.', 14, 320.00, NULL),
(7, 'Bicicleta Plegable UrbanFold', 'La UrbanFold es una bicicleta plegable ligera y práctica con cuadro de aluminio, ruedas de 20", cambios Shimano de 6 velocidades y diseño compacto ideal para transporte urbano o guardar en espacios reducidos.', 9, 740.00, NULL),
(8, 'Bicicleta BMX StreetRider', 'La StreetRider es una BMX diseñada para trucos y saltos. Cuadro de acero cromoly, llantas de 20", piñón libre y frenos U-Brake traseros. Ideal para skateparks o calle.', 11, 590.00, NULL),
(9, 'Bicicleta de Montaña TrailMaster 27.5"', 'La TrailMaster 27.5" es una bicicleta todo terreno con suspensión delantera, cuadro de aluminio, transmisión Shimano Altus 3x9 y frenos hidráulicos. Ideal para ciclistas de nivel intermedio.', 10, 870.00, NULL),
(10, 'Bicicleta de Ruta CarbonSpeed Ultegra', 'La CarbonSpeed Ultegra ofrece un cuadro 100% carbono con grupo Shimano Ultegra R8000 de 22 velocidades. Ligera, aerodinámica y lista para la competencia.', 4, 3450.00, NULL),
(11, 'Bicicleta de Descenso Downhill Fury', 'La Downhill Fury está diseñada para dominar descensos extremos. Cuenta con cuadro de aluminio reforzado, doble suspensión RockShox y transmisión SRAM GX Eagle 12V.', 3, 3100.00, NULL),
(12, 'Bicicleta Touring Adventure 700', 'La Adventure 700 es una bicicleta de cicloturismo con cuadro de acero, parrillas delantera y trasera, frenos a disco mecánicos y transmisión Shimano Alivio 3x9. Perfecta para viajes largos.', 7, 1250.00, NULL),
(13, 'Bicicleta Gravel Carbon GTR', 'La GTR Carbon está pensada para rutas mixtas. Cuadro de carbono, frenos hidráulicos, grupo Shimano GRX y neumáticos de 38 mm para máximo confort y versatilidad.', 5, 2150.00, NULL),
(14, 'Bicicleta Eléctrica UrbanVolt S', 'La UrbanVolt S es una bicicleta eléctrica urbana con batería integrada de 48V, luces LED y frenos hidráulicos. Autonomía de 70 km por carga.', 6, 1890.00, NULL),
(15, 'Bicicleta de Pista TrackOne', 'La TrackOne es una bicicleta de pista ultraliviana con cuadro de aluminio aero, transmisión fija y componentes de alta precisión para velocidad en velódromo.', 5, 1150.00, NULL),
(16, 'Bicicleta FatBike Arctic Beast', 'La Arctic Beast es una fatbike con neumáticos de 4.8”, cuadro de aluminio y frenos hidráulicos. Diseñada para nieve, arena o cualquier terreno extremo.', 4, 1580.00, NULL);
-- --------------------------------------------------------

-- Volcado de datos para la tabla `compraestadotipo`
INSERT INTO `compraestadotipo` (`idcompraestadotipo`, `cetdescripcion`, `cetdetalle`) VALUES
(1, 'Iniciada', 'Cliente inicia la compra de uno o mas productos del carrito'),
(2, 'Aceptada', 'Deposito da ingreso a una de las compras en estado = 1'),
(3, 'Enviada', 'Deposito envia a uno de las compras en estado = 2'),
(4, 'Cancelada', 'Deposito podra cancelar una compra en cualquier estado y un usuario cliente solo en estado = 1');
-- --------------------------------------------------------
