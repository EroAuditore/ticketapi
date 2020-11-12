CREATE TABLE `movimiento`
(
  `_id` int NOT NULL AUTO_INCREMENT,
  `Agente` varchar
(45) DEFAULT NULL,
  `Cliente` varchar
(45) DEFAULT NULL,
  `estatusFactura` varchar
(45) DEFAULT NULL,
  `estatusRetorno` varchar
(45) DEFAULT NULL,
  `estatusDeposito` varchar
(45) DEFAULT NULL,
  `cantidadTotal` decimal
(10,2) DEFAULT NULL,
  `totalDepositos` decimal
(10,2) DEFAULT NULL,
  `totalRetornos` decimal
(10,2) DEFAULT NULL,
  `totalComisiones` decimal
(10,2) DEFAULT NULL,
  `id_sol_factura` int unsigned DEFAULT NULL,
  `comisionAgente` decimal
(10,2) DEFAULT NULL,
  `comisionOficina` decimal
(10,2) DEFAULT NULL,
  PRIMARY KEY
(`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `retornos`
(
  `_id` int NOT NULL AUTO_INCREMENT,
  `Factura` varchar
(45) DEFAULT NULL,
  `Nombre` varchar
(45) DEFAULT NULL,
  `Cuenta_clabe` varchar
(45) DEFAULT NULL,
  `Banco` varchar
(45) DEFAULT NULL,
  `Monto` varchar
(45) DEFAULT NULL,
  `idMovimiento` int DEFAULT NULL,
  `Validado` tinyint DEFAULT NULL,
  `Evidencia` mediumblob,
  PRIMARY KEY
(`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `depositos`
(
  `_id` int NOT NULL AUTO_INCREMENT,
  `monto` varchar
(45) DEFAULT NULL,
  `banco` varchar
(45) DEFAULT NULL,
  `fecha` varchar
(45) DEFAULT NULL,
  `idMovimiento` int DEFAULT NULL,
  `Validado` tinyint DEFAULT NULL,
  `Evidencia` mediumblob,
  `Estatus` varchar
(45) DEFAULT NULL,
  PRIMARY KEY
(`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `comision`
(
  `_id` int NOT NULL AUTO_INCREMENT,
  `Tipo` varchar
(45) DEFAULT NULL,
  `Monto` text,
  `Comentarios` text,
  `idMovimiento` varchar
(45) DEFAULT NULL,
  PRIMARY KEY
(`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
