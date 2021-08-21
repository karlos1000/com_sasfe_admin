ALTER TABLE `#__sasfe_datos_postventa` ADD `areaIdPV` INT NULL;

INSERT INTO `#__sasfe_catalogos` (`idCatalogo`, `nombre`) VALUES (12, 'Areas Post Venta');

INSERT INTO `#__sasfe_datos_catalogos` (`catalogoId`, `nombre`, `pordefault`, `activo`) VALUES 
(12, 'PLOMER&Iacute;A Y GAS', '0', '1'),
(12, 'EL&Eacute;CTRICO', '0', '1'),
(12, 'ALBA&Ntilde;ILER&Iacute;A', '0', '1'),
(12, 'HERRER&Iacute;A', '0', '1'),
(12, 'CRISTALER&Iacute;A', '0', '1'),
(12, 'CARPINTER&Iacute;A (PUERTAS)', '0', '1'),
(12, 'ACABADOS (PAREDES, TECHOS E IMPERMEABILIZACI&Oacute;N)', '0', '1'),
(12, 'ACABADOS (LOSETAS)', '0', '1'),
(12, 'ACABADOS (CLOSET)', '0', '1'),
(12, 'ACABADOS (COCINA)', '0', '1');


