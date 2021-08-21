ALTER TABLE `#__sasfe_datos_clientes` ADD `email` varchar(150) NULL;

INSERT INTO `#__sasfe_ctr_permisos_campos` (`campo`, `dirV`, `dirE`, `gVtaV`, `gVtaE`, `mCtrV`, `mCtrE`, `titV`, `titE`, `nominaV`, `nominaE`, `postVtaV`, `postVtaE`, `readV`, `readE`) VALUES
('dg_email', '1', '1', '1', '0', '1', '1', '1', '0', '1', '0', '1', '0', '1', '0' );