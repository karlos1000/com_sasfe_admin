ALTER TABLE `#__sasfe_datos_clientes` ADD `email` varchar(150) NULL;

INSERT INTO `#__sasfe_ctr_permisos_campos` (`campo`, `dirV`, `dirE`, `gVtaV`, `gVtaE`, `mCtrV`, `mCtrE`, `titV`, `titE`, `nominaV`, `nominaE`, `postVtaV`, `postVtaE`, `readV`, `readE`) VALUES
('dg_email', '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' );


ALTER TABLE `#__sasfe_departamentos` ADD `fechaDTU` DATE NULL DEFAULT NULL AFTER `idPrototipo`;
ALTER TABLE `#__sasfe_departamentos` ADD KEY (fraccionamientoId);
ALTER TABLE `#__sasfe_departamentos` ADD `ocupado` INT NULL DEFAULT '0' AFTER `fechaDTU`;

#Remover
SELECT a.departamentoId, c.idFraccionamiento, c.nombre, a.fechaDTU
FROM adr9x_sasfe_datos_generales AS a
LEFT JOIN adr9x_sasfe_departamentos AS b ON b.idDepartamento=a.departamentoId
LEFT JOIN adr9x_sasfe_fraccionamientos AS c ON c.idFraccionamiento=b.fraccionamientoId
WHERE a.fechaDTU!="" AND a.fechaDTU!="0000-00-00"
#a.fechaDTU!="" AND a.fechaDTU!="0000-00-00"
#a.idEstatus!="" AND a.idEstatus IN (90, )


CREATE TABLE IF NOT EXISTS `#__sasfe_enlaces_digitales` (
  `idEnlace` int(11) NOT NULL AUTO_INCREMENT,
  `datoProspectoId` int(11) DEFAULT NULL COMMENT 'Identificador del prospecto',
  `datoGeneralId` int(11) DEFAULT NULL COMMENT 'Identificador de datos general CRM',
  `linkGeneral` text,
  `linkContrato` text,
  `linkEscrituras` text,
  `linkEntregas` text,

   PRIMARY KEY  (`idEnlace`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


ALTER TABLE `adr9x_sasfe_datos_prospectos` ADD KEY (departamentoId);
ALTER TABLE `adr9x_sasfe_datos_prospectos` ADD KEY (gteProspeccionId);
ALTER TABLE `adr9x_sasfe_datos_prospectos` ADD KEY (gteVentasId);
ALTER TABLE `adr9x_sasfe_datos_prospectos` ADD KEY (prospectadorId);
ALTER TABLE `adr9x_sasfe_datos_prospectos` ADD KEY (agtVentasId);
ALTER TABLE `adr9x_sasfe_datos_clientes` ADD KEY (datoGeneralId);

#ALTER TABLE adr9x_sasfe_datos_prospectos DROP KEY idDatoProspecto;
#ALTER TABLE adr9x_sasfe_datos_prospectos DROP INDEX (idDatoProspecto);
#DROP INDEX idDatoProspecto FROM TABLE adr9x_sasfe_datos_prospectos;

