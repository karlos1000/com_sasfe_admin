ALTER TABLE `#__sasfe_datos_credito` ADD `seguros` DOUBLE(11,2) NULL DEFAULT NULL ;
INSERT INTO `#__sasfe_ctr_permisos_campos` (`campo`, `dirV`, `dirE`, `gVtaV`, `gVtaE`, `mCtrV`, `mCtrE`, `titV`, `titE`, `nominaV`, `nominaE`, `postVtaV`, `postVtaE`, `readV`, `readE`) VALUES ('dc_seguros', '1', '1', '0', '0', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0');
