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

ALTER TABLE `#__sasfe_nominas` ADD `pctIdAses` INT NULL ;
ALTER TABLE `#__sasfe_nominas` ADD `pctIdProsp` INT NULL ;