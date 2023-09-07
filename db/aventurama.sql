-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 07-09-2023 a las 23:51:35
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aventurama`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_act`
--

CREATE TABLE `categorias_act` (
  `CD_CATEGORIA` varchar(10) NOT NULL COMMENT 'Código único de la categoría. Ejemplo: CAMPVERANO (Campamento de verano),  ANIMACIONES, CAMPURBANO',
  `DS_CATEGORIA` varchar(30) NOT NULL COMMENT 'Descripción de la categoría (el nombre completo)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Categorias tabuladas para su futura ampliación o renombramiento';

--
-- Volcado de datos para la tabla `categorias_act`
--

INSERT INTO `categorias_act` (`CD_CATEGORIA`, `DS_CATEGORIA`) VALUES
('ANIMACION', 'Animaciones'),
('CAMPURBAN', 'Campamentos Urbanos'),
('CAMPVERAN', 'Campamentos de Verano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `CD_ESTADO` char(1) NOT NULL,
  `DS_ESTADO` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`CD_ESTADO`, `DS_ESTADO`) VALUES
('0', 'NO VALIDADO'),
('1', 'VALIDADO'),
('2', 'PAGADO'),
('3', 'CANCELADO'),
('4', 'REEMBOLSADO'),
('5', 'FINALIZADO'),
('6', 'EN CURSO'),
('7', 'ANTERIOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_sanitaria`
--

CREATE TABLE `info_sanitaria` (
  `ID_NINO` bigint(20) NOT NULL,
  `ALERGIA_MED` varchar(100) DEFAULT NULL COMMENT '¿Presenta alergia y/o intolerancia a algún medicamento? En caso afirmativo, ¿de cuál se trata?',
  `ALERGIA_ALI` varchar(100) DEFAULT NULL COMMENT '¿Tiene alergia y/o intolerancia a algún alimento? En caso afirmativo, ¿de cuál se trata?',
  `LESION` varchar(100) DEFAULT NULL COMMENT '¿Ha sufrido alguna lesión recientemente? En caso afirmativo, señalar la fecha de producción y la zona afectada:',
  `MED_ACTUAL` varchar(100) DEFAULT NULL COMMENT '¿Se le está suministrando alguna medicación actualmente? En caso afirmativo, ¿de qué medicamento se trata?',
  `MOTIVO_MED` text DEFAULT NULL COMMENT '¿por qué se le administra medicación?',
  `DISCAPACIDAD` varchar(100) DEFAULT NULL COMMENT '¿Tiene alguna minusvalía? En caso afirmativo, ¿de qué se trata?',
  `REAC_ALERGICA` char(2) NOT NULL DEFAULT 'NO' COMMENT '¿Ha padecido alguna reacción alérgica? (si o no)',
  `VACUNADO` char(2) NOT NULL DEFAULT 'NO' COMMENT '¿Tiene todas las vacunas correspondientes a su edad?',
  `ANTITETANICA` varchar(100) DEFAULT 'NO' COMMENT '¿Ha sido tratado con la antitetánica? En caso afirmativo, indique la fecha',
  `NATACION` char(2) NOT NULL DEFAULT 'NO' COMMENT '¿Sabe nadar?',
  `AFICIONES` text DEFAULT NULL COMMENT '¿Cuáles son sus aficiones favoritas?',
  `OBSERVACIONES` text DEFAULT NULL COMMENT 'Anote todo lo que considere que debamos saber para un mejor conocimiento del participante:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Aquí guardaremos la información sanitaria de cada niño, de forma tabulada y referenciada';

--
-- Volcado de datos para la tabla `info_sanitaria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ninos`
--

CREATE TABLE `ninos` (
  `ID_NINO` bigint(20) NOT NULL COMMENT 'ID autoincremental para referencias',
  `NOMBRE` varchar(50) NOT NULL,
  `APELLIDOS` varchar(50) NOT NULL,
  `DNI` varchar(9) DEFAULT NULL,
  `FH_NACIMIENTO` date NOT NULL COMMENT 'Fecha de nacimiento del niño',
  `CENTRO_ESTUDIOS` varchar(30) NOT NULL COMMENT 'Centro de estudios asociado',
  `ID_PADRE` bigint(20) NOT NULL,
  `OBSERVACIONES` text DEFAULT NULL COMMENT 'Campo libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='La tabla de niños "clientes" que guarda los datos de los niños asociados a los usuarios';

--
-- Volcado de datos para la tabla `ninos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plazas_act`
--

CREATE TABLE `plazas_act` (
  `CD_ACTIVIDAD` varchar(10) NOT NULL COMMENT 'el codigo de actividad',
  `CD_TURNO` varchar(3) NOT NULL COMMENT 'el turno asociado',
  `NUM_PLAZAS` int(11) NOT NULL DEFAULT 0 COMMENT 'el numero de plazas',
  `PLAZAS_OCUP` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plazas_act`
--

INSERT INTO `plazas_act` (`CD_ACTIVIDAD`, `CD_TURNO`, `NUM_PLAZAS`, `PLAZAS_OCUP`) VALUES
('ARTISTIC', 'TU ', 0, 0),
('AVENTURAMA', 'TU ', 0, 0),
('BABYSHOW', 'TU ', 0, 0),
('BODBAUCOM', 'TU ', 0, 0),
('CHAMPSLG', 'TU ', 0, 0),
('COLEGIO', 'TU ', 0, 0),
('DANMINSTR', 'TU ', 0, 0),
('FASHMODAY', 'TU ', 0, 0),
('FROZEN', 'TU ', 0, 0),
('HOTELTRA', 'TU ', 0, 0),
('INSIDOUT', 'TU ', 0, 0),
('JUEGOLIMP', 'TU ', 0, 0),
('LOWCOST', 'TU ', 0, 0),
('MAGICSHOW', 'TU ', 0, 0),
('MINICHEF', 'TU', 0, 0),
('MINIONS', 'TU ', 0, 0),
('MULTIAVE', 'TU', 0, 0),
('SUPERHERO', 'TU ', 0, 0),
('URBANIZ', 'TU ', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `ID_RESERVA` bigint(20) NOT NULL COMMENT 'Autoincremental de la reserva para llevar un histórico del numero de reservas realizadas',
  `ID_USUARIO` bigint(20) NOT NULL COMMENT 'identificador del usuario que hace la reserva (no es lo mismo que responsable)',
  `ID_NINO` bigint(20) NOT NULL COMMENT 'Identificador del niño',
  `CD_ACTIVIDAD` varchar(10) NOT NULL COMMENT 'Actividad',
  `TURNO` char(3) DEFAULT NULL COMMENT 'Turno asociado',
  `FH_RESERVA` date NOT NULL DEFAULT current_timestamp() COMMENT 'La fecha en la que se efectúa la reserva, no la fercha del campamento en sí',
  `FH_LIMITE` date DEFAULT NULL,
  `CONOCIDO` text DEFAULT NULL COMMENT '¿Cómo nos has conocido? (Abierto)',
  `ESTADO` char(1) NOT NULL DEFAULT '0' COMMENT '0 NO VALIDADO, 1 VALIDADO, 2 PAGADO, 3 CANCELADO, 4 REEMBOLSADO, 5 FINALIZADO, 6 EN CURSO, 7 ANTIGUO',
  `PRECIO` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'EL PRECIO PAGADO DE LA RESERVA DE FORMA INDIVIDUAL POR CADA NIÑO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Histórico de reservas realizadas asociadas al usuario, niño, actividad...';

--
-- Volcado de datos para la tabla `reservas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas_sanitarias`
--

CREATE TABLE `tarjetas_sanitarias` (
  `ID_NINO` bigint(20) NOT NULL,
  `TARJETA` longblob NOT NULL,
  `NM_ARCHIVO` varchar(200) NOT NULL,
  `TIPO_ARCHIVO` varchar(30) NOT NULL,
  `SIZE_ARCHIVO` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_act`
--

CREATE TABLE `tipos_act` (
  `CD_ACTIVIDAD` varchar(10) NOT NULL COMMENT 'Código único de la actividad. Ejemplo: MULTIAVEN, ARTISTIC, LOWCOST, AVENTURA, MINICHEF, FROZEN, MINIONS, INSIDEOUT...',
  `NM_ACTIVIDAD` varchar(50) NOT NULL COMMENT 'Nombre completo de la actividad',
  `DS_ACTIVIDAD` text NOT NULL COMMENT 'descripcion del elemento que se mostrará en las tarjetas de la app bajo el nombre',
  `CT_ACTIVIDAD` varchar(10) NOT NULL COMMENT 'categoria de actividad que se basa en la tabla de categorias',
  `URL_IMAGEN` text DEFAULT NULL,
  `TURNOS` varchar(50) DEFAULT NULL,
  `DESACTIVADO` int(1) NOT NULL DEFAULT 0 COMMENT '0 ACTIVO 1 INACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='La tabla que contiene las distintas actividades que existen';

--
-- Volcado de datos para la tabla `tipos_act`
--

INSERT INTO `tipos_act` (`CD_ACTIVIDAD`, `NM_ACTIVIDAD`, `DS_ACTIVIDAD`, `CT_ACTIVIDAD`, `URL_IMAGEN`, `TURNOS`, `DESACTIVADO`) VALUES
('ARTISTIC', 'Campamento Artístico', 'Hay niños que tienen un interés específico o facilidad para otras actividades que no son el deporte. En AVENTURAMA realizamos campamentos de verano que se enfocan en desarrollar las aptitudes creativas de nuestros niños, manteniendo siempre una constante relación con la naturaleza.', 'CAMPVERAN', 'https://aventurama.es/wp-content/uploads/campamento%20artistico.jpg', 'TU', 0),
('AVENTURAMA', 'Aventurama', 'Nuestra especialidad. Los elementos que más se demandan, en una animación que podrás tematizar a tu gusto.', 'ANIMACION', '', 'TU', 0),
('BABYSHOW', 'Baby Shower', 'La primera fiesta del futuro bebe. Un evento destinado a que el embarazo sea compartido con los más allegados.', 'ANIMACION', '', 'TU', 0),
('BODBAUCOM', 'Bodas, bautizos y comuniones', 'En estos días, nos ocuparemos de lo necesario para entretener a los más pequeños de la forma más divertida.', 'ANIMACION', '', 'TU', 0),
('CHAMPSLG', 'Champions League', '¿Apasionados del fútbol? Prepara tu equipo y competir en las pruebas que os harán lograr la Copa de Europa!!', 'ANIMACION', '', 'TU', 0),
('COLEGIO', 'Campamento en colégios', 'Es una propuesta de actividades lúdico-formativas, dirigidas a niños y niñas de Educación Infantil y Primaria', 'CAMPURBAN', 'https://aventurama.es/wp-content/uploads/campamento%20multiaventura.jpg', 'TU', 0),
('DANMINSTR', 'Dance Mini Start', 'Supera el casting de La Voz, resuelve juegos musicales y realiza la coreografía final que será inmortalizada en vídeo.', 'ANIMACION', '', 'TU', 0),
('FASHMODAY', 'Fashion Model Day', 'Prepararemos todo lo necesario para ser una gran modelo y realizaremos una sesión fotográfica en la gran pasarela.', 'ANIMACION', '', 'TU', 0),
('FROZEN', 'Frozen', 'Ayuda a Elsa, Ana y sus amigos a salvar el reino de Arendelle.', 'ANIMACION', '', 'TU', 0),
('HOTELTRA', 'Hotel Transylvania', 'Dennis será un buen monstruo superando los retos que os propondrán Drácula, Franki y sus amigos.', 'ANIMACION', '', 'TU', 0),
('INSIDOUT', 'Inside Out', 'Los sentimientos están preparados para que llevéis a cabo divertidas pruebas inspiradas en la famosa película.', 'ANIMACION', '', 'TU', 0),
('JUEGOLIMP', 'Juegos Olímpicos', 'Los deportes de siempre y los más novedosos para que compitáis de forma divertida y logréis la medalla de oro.', 'ANIMACION', '', 'TU', 0),
('LOWCOST', 'Low Cost', 'Una opción al alcance de todos los bolsillos. La animación más barata, pero completa.', 'ANIMACION', '', 'TU', 0),
('MAGICSHOW', 'Magic Show', 'Animación y magia juntos en una misma opción. Combina un espectáculo de ilusión con las juegos más divertidos!!', 'ANIMACION', '', 'TU', 0),
('MINICHEF', 'Mini Chef', 'Preparaos para convertiros en «grandes» cocineros y realizar vuestros dulces preferidos.', 'ANIMACION', '', 'TU', 0),
('MINIONS', 'Los Minions', 'Estos simpáticos personajes os necesitan!!! Sus misiones que harán vuestra fiesta mucho más divertida!!!', 'ANIMACION', '', 'TU', 0),
('MULTIAVE', 'Multiaventura', 'Los campamentos multiaventura son nuestra esencia. Somos especialistas en multiaventura y multiactividad. Cada campamento es una aventura rodeada de diversión con la que disfrutan y aprenden nuestros acampados.', 'CAMPVERAN', 'https://aventurama.es/wp-content/uploads/campamento%20verano.jpg', 'TU', 0),
('SUPERHERO', 'Superhéroes', 'Vuestros superhéroes preferidos estarán con vosotros para realizar las misiones más arriesgadas!', 'ANIMACION', '', 'TU', 0),
('URBANIZ', 'Campamento en urbanizaciónes', 'Los campamentos de verano a domicilio son los que realizamos en su propia urbanización o comunidad de vecinos…', 'CAMPURBAN', 'https://aventurama.es/wp-content/uploads/campamento%20urbano.jpg', 'TU', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos_act`
--

CREATE TABLE `turnos_act` (
  `CD_TURNO` char(3) NOT NULL COMMENT 'Código compuesto por una letra y numero correspondiente al turno. Ejemplo: S1 (Semana 1), Q1 (Quincena 1)... Si tiene una ''I'' es un turno con inglés',
  `DS_TURNO` varchar(20) NOT NULL COMMENT 'descripción del turno'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Turnos tabulados posibles para una actividad';

--
-- Volcado de datos para la tabla `turnos_act`
--

INSERT INTO `turnos_act` (`CD_TURNO`, `DS_TURNO`) VALUES
('IQ1', '1st Fortnight'),
('IQ2', '2nd Fortnight'),
('IS1', '1st Week'),
('IS2', '2nd Week'),
('IS3', '3rd Week'),
('IS4', '4th Week'),
('Q1', 'Primera Quincena'),
('Q2', 'Segunda Quincena'),
('S1', 'Primera Semana'),
('S2', 'Segunda Semana'),
('S3', 'Tercera Semana'),
('S4', 'Cuarta Semana'),
('TU', 'Turno único');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutores`
--

CREATE TABLE `tutores` (
  `ID_TUTOR` bigint(20) NOT NULL COMMENT 'ID autoincremental para referencias',
  `NOMBRE` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `APELLIDOS` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `EMAIL` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `NUM_TLFN` varchar(9) NOT NULL COMMENT 'nombre de tutor',
  `DNI` varchar(10) NOT NULL COMMENT 'nombre de tutor',
  `DIRECCION` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `DIRECCION2` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `CP` varchar(5) NOT NULL COMMENT 'nombre de tutor',
  `LOCALIDAD` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `PROVINCIA` varchar(50) NOT NULL COMMENT 'nombre de tutor',
  `ID_PRINCIPAL` bigint(20) NOT NULL COMMENT 'nombre de tutor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tutores`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_USUARIO` bigint(20) NOT NULL COMMENT 'ID autoincremental para asignar una Id a los usuarios que permita la referencia',
  `ROL` varchar(10) NOT NULL DEFAULT 'user',
  `NOMBRE` varchar(50) NOT NULL COMMENT 'Nombre de usuario',
  `APELLIDOS` varchar(50) NOT NULL COMMENT 'Apellidos del usuario',
  `NUM_TLFN` char(9) NOT NULL COMMENT 'Se asumen todos los números formato de España',
  `DNI` char(9) DEFAULT NULL,
  `DIRECCION` varchar(50) DEFAULT NULL COMMENT 'PRIMERA LINEA DE DIRECCION DONDE SE INCLUYE LA CALLE Y NUMERO...',
  `DIRECCION2` varchar(50) DEFAULT NULL COMMENT 'COMPONENTES OPCIONALES DE LA DIRECCION',
  `CP` varchar(6) DEFAULT NULL COMMENT 'CODIGO POSTAL',
  `LOCALIDAD` varchar(50) DEFAULT NULL COMMENT 'LOCALIDAD',
  `PROVINCIA` varchar(30) DEFAULT NULL COMMENT 'PROVINCIA',
  `EMAIL` varchar(50) NOT NULL COMMENT 'email de registro',
  `PASSWORD` varchar(64) NOT NULL COMMENT 'Este campo será un hash para asegurar la integridad de la contraseña',
  `ACTIVADO` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla que identifica a los usuarios solamente como registro.';

--
-- Volcado de datos para la tabla `usuarios`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_act`
--
ALTER TABLE `categorias_act`
  ADD PRIMARY KEY (`CD_CATEGORIA`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`CD_ESTADO`);

--
-- Indices de la tabla `info_sanitaria`
--
ALTER TABLE `info_sanitaria`
  ADD PRIMARY KEY (`ID_NINO`);

--
-- Indices de la tabla `ninos`
--
ALTER TABLE `ninos`
  ADD PRIMARY KEY (`ID_NINO`),
  ADD KEY `FK_PADRE` (`ID_PADRE`);

--
-- Indices de la tabla `plazas_act`
--
ALTER TABLE `plazas_act`
  ADD PRIMARY KEY (`CD_ACTIVIDAD`,`CD_TURNO`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`ID_RESERVA`),
  ADD KEY `FK_ESTADO_RESERVA` (`ESTADO`),
  ADD KEY `reservas_ibfk_1` (`ID_USUARIO`),
  ADD KEY `reservas_ibfk_2` (`ID_NINO`),
  ADD KEY `reservas_ibfk_3` (`CD_ACTIVIDAD`),
  ADD KEY `reservas_ibfk_4` (`TURNO`);

--
-- Indices de la tabla `tarjetas_sanitarias`
--
ALTER TABLE `tarjetas_sanitarias`
  ADD PRIMARY KEY (`ID_NINO`);

--
-- Indices de la tabla `tipos_act`
--
ALTER TABLE `tipos_act`
  ADD PRIMARY KEY (`CD_ACTIVIDAD`),
  ADD KEY `FK_CATEGORIA` (`CT_ACTIVIDAD`);

--
-- Indices de la tabla `turnos_act`
--
ALTER TABLE `turnos_act`
  ADD PRIMARY KEY (`CD_TURNO`);

--
-- Indices de la tabla `tutores`
--
ALTER TABLE `tutores`
  ADD PRIMARY KEY (`ID_TUTOR`),
  ADD KEY `FK_PRINCIPAL` (`ID_PRINCIPAL`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`),
  ADD UNIQUE KEY `DNI` (`DNI`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ninos`
--
ALTER TABLE `ninos`
  MODIFY `ID_NINO` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID autoincremental para referencias', AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `ID_RESERVA` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Autoincremental de la reserva para llevar un histórico del numero de reservas realizadas', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tutores`
--
ALTER TABLE `tutores`
  MODIFY `ID_TUTOR` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID autoincremental para referencias', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_USUARIO` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID autoincremental para asignar una Id a los usuarios que permita la referencia', AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `info_sanitaria`
--
ALTER TABLE `info_sanitaria`
  ADD CONSTRAINT `Info_sanitaria_Ninos` FOREIGN KEY (`ID_NINO`) REFERENCES `ninos` (`ID_NINO`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ninos`
--
ALTER TABLE `ninos`
  ADD CONSTRAINT `FK_PADRE` FOREIGN KEY (`ID_PADRE`) REFERENCES `usuarios` (`ID_USUARIO`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `plazas_act`
--
ALTER TABLE `plazas_act`
  ADD CONSTRAINT `FK_act` FOREIGN KEY (`CD_ACTIVIDAD`) REFERENCES `tipos_act` (`CD_ACTIVIDAD`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_turnos` FOREIGN KEY (`CD_TURNO`) REFERENCES `turnos_act` (`CD_TURNO`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `FK_ESTADO_RESERVA` FOREIGN KEY (`ESTADO`) REFERENCES `estados_reserva` (`CD_ESTADO`),
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuarios` (`ID_USUARIO`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`ID_NINO`) REFERENCES `ninos` (`ID_NINO`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`CD_ACTIVIDAD`) REFERENCES `tipos_act` (`CD_ACTIVIDAD`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_4` FOREIGN KEY (`TURNO`) REFERENCES `turnos_act` (`CD_TURNO`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tarjetas_sanitarias`
--
ALTER TABLE `tarjetas_sanitarias`
  ADD CONSTRAINT `FK_NINO` FOREIGN KEY (`ID_NINO`) REFERENCES `ninos` (`ID_NINO`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipos_act`
--
ALTER TABLE `tipos_act`
  ADD CONSTRAINT `FK_CATEGORIA` FOREIGN KEY (`CT_ACTIVIDAD`) REFERENCES `categorias_act` (`CD_CATEGORIA`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tutores`
--
ALTER TABLE `tutores`
  ADD CONSTRAINT `FK_PRINCIPAL` FOREIGN KEY (`ID_PRINCIPAL`) REFERENCES `usuarios` (`ID_USUARIO`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `cancelar_automatica` ON SCHEDULE EVERY 1 HOUR STARTS '2023-05-03 00:00:00' ON COMPLETION PRESERVE ENABLE DO UPDATE reservas
set ESTADO = '3'
WHERE FH_LIMITE <= CURRENT_TIMESTAMP()
AND ESTADO = '1'$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
