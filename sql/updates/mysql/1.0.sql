DROP TABLE IF EXISTS `#__sasfe`;
DROP TABLE IF EXISTS `#__sasfe_fraccionamientos`;
DROP TABLE IF EXISTS `#__sasfe_departamentos`;
DROP TABLE IF EXISTS `#__sasfe_datos_generales`;
DROP TABLE IF EXISTS `#__sasfe_datos_clientes`;
DROP TABLE IF EXISTS `#__sasfe_telefonos`;
DROP TABLE IF EXISTS `#__sasfe_referencias`;
DROP TABLE IF EXISTS `#__sasfe_datos_credito`;
DROP TABLE IF EXISTS `#__sasfe_depositos`;
DROP TABLE IF EXISTS `#__sasfe_pagares`;
DROP TABLE IF EXISTS `#__sasfe_nominas`;
DROP TABLE IF EXISTS `#__sasfe_datos_costoextra`;
DROP TABLE IF EXISTS `#__sasfe_datos_postventa`;
DROP TABLE IF EXISTS `#__sasfe_catalogos`;
DROP TABLE IF EXISTS `#__sasfe_catalogo_costoextra`;
DROP TABLE IF EXISTS `#__sasfe_datos_catalogos`;
DROP TABLE IF EXISTS `#__sasfe_ctr_permisos_campos`;
DROP TABLE IF EXISTS `#__sasfe_servicios`;
DROP TABLE IF EXISTS `#__sasfe_acabados`;
DROP TABLE IF EXISTS `#__sasfe_estados`;
DROP TABLE IF EXISTS `#__sasfe_tipo_telefonos`;
DROP TABLE IF EXISTS `#__sasfe_estatus`;
DROP TABLE IF EXISTS `#__sasfe_estatus_acabados`;
DROP TABLE IF EXISTS `#__sasfe_log_accesos`;
DROP TABLE IF EXISTS `#__sasfe_porcentajes`;
DROP TABLE IF EXISTS `#__sasfe_porcentajes_esp`;


CREATE TABLE `#__sasfe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `greeting` varchar(25) NOT NULL,  
   PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;	
 
INSERT INTO `#__sasfe` (`greeting`) VALUES('Saludos');        


CREATE TABLE `#__sasfe_fraccionamientos` (
  `idFraccionamiento` int(11) NOT NULL AUTO_INCREMENT,  
  `nombre` varchar(150) NULL,    
  `imagen` varchar(200) NULL, 
  `activo` CHAR( 1 ) NOT NULL DEFAULT  '0',
  
   PRIMARY KEY  (`idFraccionamiento`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;			


CREATE TABLE `#__sasfe_departamentos` (
  `idDepartamento` int(11) NOT NULL AUTO_INCREMENT,  
  `fraccionamientoId` int(11) NOT NULL,
  `numero` varchar(100) NULL,   
  `precio` DOUBLE (11,2) NULL,
  `idNivel` int(11) NULL,
  `idPrototipo` int(11) NULL,
  
   PRIMARY KEY  (`idDepartamento`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_datos_generales` (
  `idDatoGeneral` int(11) NOT NULL AUTO_INCREMENT,  
  `departamentoId` int(11) NOT NULL,  
  `DTU` CHAR( 1 ) NULL DEFAULT  '0',
  `fechaApartado` date NULL,  
  `fechaInscripcion` date NULL,  
  `fechaCierre` date NULL,
  `idGerenteVentas` int(11) NULL,  
  `idTitulacion` int(11) NULL,  
  `idAsesor` int(11) NULL,
  `idPropectador` int(11) NULL,
  `idEstatus` int(11) NULL,
  `fechaEstatus` date NULL,  
  `idCancelacion` int(11) NULL,
  `observaciones` varchar(500) NULL,    
  `referencia` varchar(250) NULL,    
  `promocion` varchar(250) NULL,    
  `fechaEntrega` date NULL,  
  `reprogramacion` date NULL,  
  `esHistorico` CHAR( 1 ) NOT NULL DEFAULT  '0',
  `esReasignado` CHAR( 1 ) NOT NULL DEFAULT  '0',
  `fechaDTU` date NULL,  
  `obsoleto` CHAR( 1 ) NOT NULL DEFAULT  '0',  
  
   PRIMARY KEY  (`idDatoGeneral`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_datos_clientes` (
  `idDatoCliente` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `aPaterno` varchar(100) NULL,   
  `aManterno` varchar(150) NULL,   
  `nombre` varchar(100) NULL, 
  `NSS` varchar(16) NULL, 
  `tipoCreditoId` int(11) NULL,
  `calle` varchar(200) NULL, 
  `numero` varchar(50) NULL, 
  `colonia` varchar(100) NULL,
  `municipio` varchar(100) NULL,
  `estadoId` int(11) NULL,  
  `cp` varchar(100) NULL,
  `empresa` varchar(100) NULL,
  `fechaNac` date NULL,
  `genero` CHAR( 1 ) NOT NULL DEFAULT  '0',
  `email` varchar(150) NULL,
  
   PRIMARY KEY  (`idDatoCliente`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_telefonos` (
  `idTelefono` int(11) NOT NULL AUTO_INCREMENT,  
  `datoClienteId` int(11) NOT NULL,  
  `numero` varchar(100) NULL,   
  `tipoId` int(11) NOT NULL,

  PRIMARY KEY  (`idTelefono`)      
)  ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_referencias` (
  `idReferencias` int(11) NOT NULL AUTO_INCREMENT,  
  `datoClienteId` int(11) NOT NULL,  
  `nombreReferencia` varchar(100) NULL,   
  `telefono` varchar(150) NULL,
  `comentarios` varchar(350) NULL,

  PRIMARY KEY  (`idReferencias`)        
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_datos_credito` (
  `idDatoCredito` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `numeroCredito` varchar(100) NULL,   
  `valorVivienda` DOUBLE (11,2) NULL,  
  `cInfonavit` DOUBLE (11,2) NULL,   
  `sFederal` DOUBLE (11,2) NULL,   
  `gEscrituracion` DOUBLE (11,2) NULL,   
  `ahorroVol` DOUBLE (11,2) NULL,   
  `seguros` DOUBLE (11,2) NULL,  
  `seguros_resta` DOUBLE (11,2) NULL,  
  
   PRIMARY KEY  (`idDatoCredito`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_depositos` (
  `idDeposito` int(11) NOT NULL AUTO_INCREMENT,  
  `datoCreditoId` int(11) NOT NULL,
  `deposito` DOUBLE (11,2) NULL, 
  `fecha` date NULL,  
  `comentarios` varchar(350) NULL,   
  
   PRIMARY KEY  (`idDeposito`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_pagares` (
  `idPagare` int(11) NOT NULL AUTO_INCREMENT,  
  `datoCreditoId` int(11) NOT NULL,
  `cantidad` DOUBLE (11,2) NULL,   
  `fechaVenc` date NULL,  
  `estatuPagareId` int(11) NOT NULL,
  `anotaciones` varchar(350) NULL,   
  
   PRIMARY KEY  (`idPagare`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_nominas` (
  `idNomina` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `comision` DOUBLE (11,2) NULL,   
  `esPreventa` CHAR( 1 ) NOT NULL DEFAULT  '0',
  `fechaPagApartado` date NULL,  
  `fechaDescomicion` date NULL,  
  `fechaPagEscritura` date NULL,  
  `fechaPagLiquidacion` date NULL,    
  `esAsesor` CHAR( 1 ) NOT NULL DEFAULT  '0',
  `esReferido` CHAR( 1 ) NOT NULL DEFAULT  '0',  
  `nombreReferido` varchar(100) NULL,
  `comisionPros` DOUBLE (11,2) NULL,     
  `esPreventaPros` CHAR( 1 ) NOT NULL DEFAULT  '0',
  `fPagoApartadoPros` date NULL,   
  `fPagoDescomisionPros` date NULL,   
  `fPagoEscrituraPros` date NULL,   
  `pctIdAses` int(11) NULL,
  `pctIdProsp` int(11) NULL,

   PRIMARY KEY  (`idNomina`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_datos_costoextra` (
  `idDatoCostoExtra` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `datoCEId` int(11) NOT NULL,  
  `costo` DOUBLE (11,2) NULL,   
  `esPromo` CHAR( 1 ) NOT NULL DEFAULT  '0',
  
   PRIMARY KEY  (`idDatoCostoExtra`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_datos_postventa` (
  `idDatoPV` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `dato` varchar(100) NULL,       
  `fecha` date NULL,  
  `fechaAt` date NULL,  
  `detRealizado` varchar(100) NULL,
  `areaIdPV` INT NULL,  
  
   PRIMARY KEY  (`idDatoPV`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_catalogos` (
  `idCatalogo` int(11) NOT NULL AUTO_INCREMENT,    
  `nombre` varchar(150) NULL,         
  
   PRIMARY KEY  (`idCatalogo`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sasfe_catalogos` (`idCatalogo`, `nombre`) VALUES
(1, 'Gerentes de Venta'),
(2, 'Titulaci&oacute;n'),
(3, 'Asesores'),
(4, 'Prospectadores'),
(5, 'Estatus Vivienda'),
(6, 'Motivos de Cancelaci&oacute;n'),
(7, 'Tipos Cr&eacute;dito'),
(8, 'Acabados'),
(9, 'Servicios'),
(10, 'Niveles'),
(11, 'Prototipo'),
(12, 'Areas Post Venta')
;


CREATE TABLE `#__sasfe_catalogo_costoextra` (
  `idDatoCE` int(11) NOT NULL AUTO_INCREMENT,    
  `catalogoId` int(11) NOT NULL,
  `fraccionamientoId` int(11) NOT NULL,
  `nombre` varchar(150) NULL,   
  `costo` DOUBLE (11,2) NULL,     
  `activo` CHAR( 1 ) NOT NULL DEFAULT  '0',  
  
   PRIMARY KEY  (`idDatoCE`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_datos_catalogos` (
  `idDato` int(11) NOT NULL AUTO_INCREMENT,    
  `catalogoId` int(11) NOT NULL,  
  `nombre` varchar(150) NULL,   
  `pordefault` CHAR( 1 ) NOT NULL DEFAULT  '0',  
  `activo` CHAR( 1 ) NOT NULL DEFAULT  '0',  
  
   PRIMARY KEY  (`idDato`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


INSERT INTO `#__sasfe_datos_catalogos` (`catalogoId`, `nombre`, `pordefault`, `activo` ) VALUES
(1, 'Guillermo Mondragon Jarquin', '0', '1'),
(1, 'Leticia Mondragon Jarquin', '0', '1'),
(1, 'Juan Jose Hernandez Solis', '0', '1'),
(1, 'Mario Lima', '0', '0'),

(2, 'Albertina Andrade Bonilla', '0', '1'),
(2, 'Silvia Mendez Reyes', '0', '1'),
(2, 'Sandra Castillo de Pedro', '0', '1'),
(2, 'Dennys Hernandez Xicotencatl', '0', '1'),
(2, 'Saul Fernando Sanchez', '0', '1'),

(3, 'ROBERTO SALAZAR MU?OZ', '0', '0'),
(3, 'MARIA DEL ROSARIO FLORES RODRIGUEZ', '0', '0'),
(3, 'ADRIANA BEATRIZ CERQUEDA JUAREZ', '0', '0'),
(3, 'ALFREDO HERNANDEZ HERNANDEZ', '0', '0'),
(3, 'ANGELES IBARRA (EXTERNO)', '0', '0'),
(3, 'ANGELICA MONTERD', '0', '0'),
(3, 'NANCY ARELI GUERRERO CASTILLO', '0', '0'),
(3, 'MARIA DEL CARMEN CHILACA HERNANDEZ', '0', '0'),
(3, 'CLAUDIA ESCAMILLA HERN?NDEZ', '0', '0'),
(3, 'DIANA ZEPEDA CARRILLO', '0', '0'),
(3, 'JOSE ELIAS MU?OZ SALAS', '0', '0'),
(3, 'ERNESTO RUIZ', '0', '0'),
(3, 'GILBERTO TOXQUI LOEZA', '0', '0'),
(3, 'GLORIA MONDRAGON JARQUIN', '0', '0'),
(3, 'JAIME ARTURO OBIL VEIRA', '0', '0'),
(3, 'ISMAEL AHUATZIN HERRERA', '0', '0'),
(3, 'JAIME GONZALEZ CULEBRO', '0', '0'),
(3, 'JORGE LUIS ANDRADE FRANCO', '0', '0'),
(3, 'JUAN (EXTERNO)', '0', '0'),
(3, 'JUAN JOS? PEL?EZ SALINAS', '0', '0'),
(3, 'LUIS ANTONIO CRUZ FAJARDO', '0', '0'),
(3, 'JULIANA LOEZA TONIS', '0', '0'),
(3, 'MANUEL GIL TOXQUI', '0', '0'),
(3, 'MODESTO GONZALEZ VIEYRA', '0', '0'),
(3, 'MIRIAM BRAVO', '0', '0'),
(3, 'NEFI SAUL MORENO PINEDA', '0', '0'),
(3, 'JOSÉ RAÚL CASTELÓN ENRIQUEZ', '0', '0'),
(3, 'NAYELI', '0', '0'),
(3, 'BLANCA LILIA ALONSO HERRERA', '0', '1'),
(3, 'GUILLERMINA ROSALES CASTILLO', '0', '1'),
(3, 'MARCOS SOLIS ALVARADO', '0', '1'),
(3, 'ARACELI HUERTA CRUZ', '0', '1'),
(3, 'MARIA ISABEL MARTINEZ LOPEZ', '0', '1'),
(3, 'JOSE RICARDO HERNANDEZ AGUILAR', '0', '1'),
(3, 'VERONICA RAMIREZ COVARRUBIAS', '0', '1'),
(3, 'MARIA DE JESUS BRAVO CARMONA', '0', '1'),
(3, 'MARIA DE LOURDES CASTILLO FLORES', '0', '1'),
(3, 'MARIA LETICIA GONZALEZ FLORES', '0', '1'),
(3, 'ARTURO SASTRE GONZALEZ', '0', '1'),
(3, 'JANETT BETANCOURT REYES', '0', '1'),
(3, 'CARMINA SAUCEDO CASTILLO', '0', '1'),
(3, 'ROGELIO MARTINEZ MARTINEZ', '0', '1'),
(3, 'CASANDRA CUEVAS TEQUIANES', '0', '1'),
(3, 'ABIGAIL MORALES RAMIREZ', '0', '1'),
(3, 'MA.DEL CARMEN GONZALEZ CASTELLANOS', '0', '1'),
(3, 'GUILLERMINA CARRASCO PEREGRINA', '0', '1'),

(4, 'YIJAN GUADALUPE GAONA CUANAL', '0', '0'),
(4, 'TOMAS EULISES CH?VEZ SOLER', '0', '0'),
(4, 'ROSA CANDIDA MART?NEZ CANJURA', '0', '0'),
(4, 'OLGA PEREZ REYES', '0', '0'),
(4, 'ROC?O MORANCHEL TORRES', '0', '0'),
(4, 'MARIA DEL ROCIO GUTIERREZ REYES', '0', '0'),
(4, 'NERI RAM?REZ NIEVES', '0', '0'),
(4, 'NANCY ARELI GUERRERO CASTILLO', '0', '0'),
(4, 'MICHEL SHELEM RAM?REZ ORT?Z', '0', '0'),
(4, 'MARIBEL HERNANDEZ RAMIREZ', '0', '0'),
(4, 'MARIA MACOTO TORRES', '0', '0'),
(4, 'JOSEFINA ILIANA LOPEZ FERTO', '0', '0'),
(4, 'MARIA FIRELY SANCHEZ ESPINOSA', '0', '0'),
(4, 'ABEL DALI SANCHEZ GARCIA', '0', '0'),
(4, 'ABIGAIL MORALES RAMIREZ', '0', '0'),
(4, 'ALECC', '0', '0'),
(4, 'ANA LAURA TOCHIMANI COYOMANI', '0', '0'),
(4, 'ANGELICA CALDERON LOEZA', '0', '0'),
(4, 'BETZABE PEREZ MEJORADO', '0', '0'),
(4, 'MARIA DEL CARMEN CHILACA HERNANDEZ', '0', '0'),
(4, 'JUANITA ELIZABETH REYES HIPOLITO', '0', '0'),
(4, 'SUSANA HERNANDEZ HERNANDEZ', '0', '1'),
(4, 'FERNANDO FUENTES CRUZ', '0', '1'),
(4, 'MARIA ELENA PILAR MU?OZ JUAREZ', '0', '1'),
(4, 'GUADALUPE SILVA MU?OZ', '0', '1'),
(4, 'ANTONIO SILVA MU?OZ', '0', '1'),
(4, 'GUADALUPE ROJAS TERAN', '0', '1'),
(4, 'KARLA ISABEL MENA MARTINEZ', '0', '1'),
(4, 'JUAN SOSA MARTINEZ', '0', '1'),
(4, 'REFERIDOS', '0', '1'),

(5, 'Disponible', '1', '1'),
(5, 'Escriturado', '0', '1'),
(5, 'Cancelado', '0', '1'),
(5, 'Taller', '0', '1'),
(5, 'En validaci?n', '1', '1'),
(5, 'Incompleto', '0', '1'),
(5, 'Diferencia', '0', '1'),
(5, 'Inscrito', '0', '1'),
(5, 'Aviso de retenci?n', '0', '1'),
(5, 'Con problema', '0', '1'),

(6, 'DISCREPANCIA DE DATOS', '0', '1'),
(6, 'FALTA DE DOCUMENTOS OFICIALES', '0', '1'),
(6, 'DIFERENCIA', '0', '1'),
(6, 'PERDIDA O MODIFICACION DE RELACION LABORAL', '0', '1'),
(6, 'MODIFICACION DE SALARIO, MONTO DE CREDITO O PUNTOS', '0', '1'),
(6, 'INCUMPLIMIENTO DE LA UBICACI?N ', '0', '1'),
(6, 'CONDICIONES DE LA VIVIENDA', '0', '1'),
(6, 'NO SE LOCALIZA', '0', '1'),
(6, 'OTRA OPCION DE VIVIENDA', '0', '1'),
(6, 'POR OTROS MOTIVOS', '0', '1'),

(7, 'Tradicional Infonavit', '0', '1'),
(7, 'Subsidio 2.6 Infonavit', '0', '1'),
(7, 'Subsidio 5 Infonavit', '0', '1'),
(7, 'Segundo cr?dito Infonavit', '0', '1'),
(7, 'Conyugal Tradicional Infonavit', '0', '1'),
(7, 'Conyugal Subsidio Infonavit', '0', '1'),
(7, 'ISSTEP', '0', '1'),
(7, 'Hipotecario', '0', '1'),
(7, 'Fovissste pensionados', '0', '1'),
(7, 'Tradicional Fovissste', '0', '1'),
(7, 'Subsidio Fovissste', '0', '1'),
(7, 'Mancomunado Fovissste', '0', '1'),
(7, 'Conyugal Info Fovissste', '0', '1'),
(7, 'Banjercito', '0', '1'),
(7, 'Info Total', '0', '1'),
(7, 'IMSS', '0', '1'),

(10, 'PLANTA BAJA', '0', '1'),
(10, 'PRIMER NIVEL', '0', '1'),
(10, 'SEGUNDO NIVEL', '0', '1'),

(11, '54 MTS', '0', '1'),
(11, '47 MTS', '0', '1')
;        


CREATE TABLE `#__sasfe_ctr_permisos_campos` (
 `campo` varchar(100) NULL,   
 `dirV` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `dirE` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `gVtaV` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `gVtaE` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `mCtrV` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `mCtrE` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `titV` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `titE` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `nominaV` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `nominaE` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `postVtaV` CHAR( 1 ) NOT NULL DEFAULT  '0',  
 `postVtaE` CHAR( 1 ) NOT NULL DEFAULT  '0',
 `readV` CHAR( 1 ) NOT NULL DEFAULT  '1',  
 `readE` CHAR( 1 ) NOT NULL DEFAULT  '0'

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `#__sasfe_ctr_permisos_campos` (`campo`, `dirV`, `dirE`, `gVtaV`, `gVtaE`, `mCtrV`, `mCtrE`, `titV`, `titE`, `nominaV`, `nominaE`, `postVtaV`, `postVtaE`, `readV`, `readE`) VALUES
('dg_dtu',          '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_fApart',       '1', '1', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('dg_fInsc',        '1', '1', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dg_fCierre',      '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_gteVtas',      '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_titulacion',   '1', '1', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dg_asesor',       '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_prospec',      '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_estatus',      '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dg_fEstatus',     '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dg_motCancel',    '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_observ',       '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dg_aPaternoC',    '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_aMaternoC',    '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_nombreC',      '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_nssC',         '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_ref',          '1', '1', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('dg_prom',         '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_fEntrega',     '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0' ),
('dg_reprog',       '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0' ),

('cl_calle',        '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_col',          '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_estado',       '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_empresa',      '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_no',           '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_mpioLodad',    '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_cp',           '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_telefonos',    '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),
('cl_ref',          '1', '1', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0', '1', '0' ),

('dc_numcdto',          '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_valorvivienda',    '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_acabados',         '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_servicios',        '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_totalViv',         '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_cInfonavit',       '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_subFed',           '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_cdtoSub',          '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_diferencia',       '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_gEscrituracion',   '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_ahorroVol',        '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_difTotal',         '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dc_totalDep',         '1', '1', '0', '0', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0' ),
('dc_difPend',          '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),

('pag_difPend',          '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('pag_totalPagado',      '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('pag_suma',             '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('pag_porPagar',         '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),

('nom_ases_asesor',         '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_apartado',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_fPagoApar',      '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_fDescomision',   '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_escritura',      '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_fPagoEsc',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_liquidacion',    '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_fPagoLiq',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_total',          '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_ases_comision',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),

('nom_pros_prospectador',   '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_apartado',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_fPagoApar',      '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_fDescomision',   '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_escritura',      '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_fPagoEsc',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_total',          '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_comision',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
        
('dg_tipo_credito',         '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_nombre_ref',           '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '0', '0', '1', '0' ),
('grid_depositos',          '1', '1', '0', '0', '1', '0', '1', '1', '1', '1', '1', '0', '1', '0' ),
('grid_pagares',            '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_asesor_prevta',       '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('nom_pros_prevta',         '1', '1', '0', '0', '1', '0', '1', '0', '1', '1', '1', '0', '1', '0' ),
('total_acabados',          '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('grid_acabados',           '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('total_servicios',         '1', '1', '0', '0', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0' ),
('grid_servicios',          '1', '1', '0', '0', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0' ),
('grid_postventa',          '1', '1', '0', '0', '1', '0', '1', '0', '1', '0', '1', '1', '1', '0' ),
('dg_fdtu',                 '1', '1', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0', '1', '0' ),

('dg_fNac',                 '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dg_genero',               '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' ),
('dc_seguros',              '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0' ),
('dg_email',                '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' )
;


CREATE TABLE `#__sasfe_servicios` (
  `idServicio` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `nombre` varchar(100) NULL,       
  `monto` DOUBLE (11,2) NULL,
  `aplica` CHAR( 1 ) NOT NULL DEFAULT  '0',     
  `comentarios` varchar(350) NULL,
  
  PRIMARY KEY  (`idServicio`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_acabados` (
  `idAcabados` int(11) NOT NULL AUTO_INCREMENT,  
  `datoGeneralId` int(11) NOT NULL,
  `nombre` varchar(100) NULL,       
  `precio` DOUBLE (11,2) NULL,
  `estatus` int(11) NOT NULL,  
  
  PRIMARY KEY  (`idAcabados`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_estados` (
  `idEstado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(25) NOT NULL,

  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
  
INSERT INTO `#__sasfe_estados` (estado) VALUES 
('Aguascalientes'),
('Baja California Norte'),
('Baja California Sur'),
('Campeche'),
('Chiapas'),
('Chihuahua'),
('Coahuila'),
('Colima'),
('Distrito Federal'),
('Durango'),
('Estado de M&eacute;xico'),
('Guanajuato'),
('Guerrero'),
('Hidalgo'),
('Jalisco'),
('Michoac&aacute;n'),
('Morelos'),
('Nayarit'),
('Nuevo Le&oacute;n'),
('Oaxaca'),
('Puebla'),
('Quer&eacute;taro'),
('Quintana Roo'),
('San Luis Potos&iacute;'),
('Sinaloa'),
('Sonora'),
('Tabasco'),
('Tamaulipas'),
('Tlaxcala'),
('Veracruz'),
('Yucat&aacute;n'),
('Zacatecas');


CREATE TABLE `#__sasfe_tipo_telefonos` (
  `idTipoTel` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,

  PRIMARY KEY (`idTipoTel`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sasfe_tipo_telefonos` (nombre) VALUES 
('Casa'),
('Celular'),
('Oficina');


CREATE TABLE `#__sasfe_estatus` (
  `idEstatusCat` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,

  PRIMARY KEY (`idEstatusCat`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sasfe_estatus` (nombre) VALUES 
('Pendiente'),
('Abono parcial'),
('Pagado'),
('Vencido');


CREATE TABLE `#__sasfe_estatus_acabados` (
  `idEstatusAcabados` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,

  PRIMARY KEY (`idEstatusAcabados`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sasfe_estatus_acabados` (idEstatusAcabados, nombre) VALUES 
(1, 'SI'),
(2, 'Promoción'),
(3, 'Base');


CREATE TABLE `#__sasfe_log_accesos` (
  `idLog` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idDpt` int(11) NOT NULL,
  `idFracc` int(11) NOT NULL,  
  `fechaHora` timestamp NULL,

  PRIMARY KEY (`idLog`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `#__sasfe_porcentajes` (
  `idPorcentaje` int(11) NOT NULL AUTO_INCREMENT,  
  `nombre` varchar(25) NOT NULL,
  `pctUno` DECIMAL(5,2) NULL, 
  `pctDos` DECIMAL(5,2) NULL, 
  `asesProsp` CHAR(1) NULL,
  `esPreventa` CHAR(1) NULL,
  
   PRIMARY KEY  (`idPorcentaje`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sasfe_porcentajes` (idPorcentaje, nombre, pctUno, pctDos, asesProsp, esPreventa) VALUES 
(1, 'Apartado', 0.3, 0.92, '1', '0'),
(2, 'Escritura', 0.5, 0.92, '1', '0'),
(3, 'Liquidaci&oacute;n', 0.2, 0.92, '1', '0'),
(4, 'Apartado', 0.2, 0.92, '1', '1'),
(5, 'Escritura', 0.6, 0.92, '1', '1'),
(6, 'Liquidaci&oacute;n', 0.2, 0.92, '1', '1'),
(7, 'Apartado', 0.40, 0.92, '2', '0'),
(8, 'Escritura', 0.60, 0.92, '2', '0'),
(9, 'Apartado', 0.30, 0.92, '2', '1'),
(10, 'Escritura', 0.70, 0.92, '2', '1');


CREATE TABLE `#__sasfe_porcentajes_esp` (
  `idPct` int(11) NOT NULL AUTO_INCREMENT,  
  `titulo` varchar(150) NULL,
  `apartado` DECIMAL(5,2) NULL, 
  `escritura` DECIMAL(5,2) NULL,   
  `liquidacion` DECIMAL(5,2) NULL,     
  `mult` DECIMAL(5,2) NULL,       
  `esAsesPros` CHAR(1) NULL,

   PRIMARY KEY  (`idPct`)           
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


INSERT INTO `#__sasfe_porcentajes_esp` (idPct, titulo, apartado, escritura, liquidacion, mult, esAsesPros) VALUES 
(1, 'Asesor 1 pct: (0.30-0.50-0.20)', 0.3, 0.5, 0.20, 0.92, '1'),
(2, 'Asesor 2 pct: (0.20-0.60-0.20)', 0.20, 0.60, 0.20, 0.92, '1')
;

INSERT INTO `#__sasfe_porcentajes_esp` (idPct, titulo, apartado, escritura, mult, esAsesPros) VALUES 
(3, 'Prospectador 1 pct: (0.40-0.60)', 0.40, 0.60, 0.92, '2'),
(4, 'Prospectador 2 pct: (0.30-0.70)', 0.30, 0.70, 0.92, '2')
;