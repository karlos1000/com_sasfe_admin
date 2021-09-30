<?php
/**
 * fecha: 19-11-13
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.model');

class SasfeModelDepartamento extends JModelLegacy{

        public function insDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $fdtu){
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

           $query = "INSERT INTO $tbl_sasfe_datos_generales (departamentoId, DTU, fechaApartado, fechaInscripcion, fechaCierre, idGerenteVentas, idTitulacion, idAsesor,
                                                            idPropectador, idEstatus, fechaEstatus, idCancelacion, observaciones, referencia,
                                                            promocion, fechaEntrega, reprogramacion, esHistorico, fechaDTU)
                                                        VALUES ($id_Dpt, '$dtu_dg', $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                                            $prospectador_dg, $estatus_dg, '$fEstatus', $motivo_cancel_dg, '$motivo_texto_dg', '$ref_dg',
                                                            '$prom_dg', $fEntrega, $fReprog, '$historico', $fdtu )";
           // echo $query;
           // exit();
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           //echo $query;
           return $id;
       }

       public function upDatoGral($id_Dpt, $dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $fdtu, $id_dato){
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

           $query = "UPDATE $tbl_sasfe_datos_generales SET DTU='$dtu_dg', fechaApartado=$fApartado, fechaInscripcion=$fInsc, fechaCierre=$fCierre, idGerenteVentas=$gte_vtas_dg,
                     idTitulacion=$titulacion_dg, idAsesor=$asesor_dg, idPropectador=$prospectador_dg, idEstatus=$estatus_dg, fechaEstatus='$fEstatus', idCancelacion=$motivo_cancel_dg,
                     observaciones='$motivo_texto_dg', referencia='$ref_dg', promocion='$prom_dg', fechaEntrega=$fEntrega, reprogramacion=$fReprog, esHistorico='$historico', fechaDTU=$fdtu
                     WHERE idDatoGeneral = $id_dato ";
           $db->setQuery($query);
           $db->query();

           //echo $query;
       }

       /***
        * Insertar datos cliente despues de que se inserto correctamente los datos generales
        */
       public function insDatoCliente($id_datoGral, $aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                                   $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $fechaNac, $genero, $emailC_dg){
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';

           $query = "INSERT INTO $tbl_sasfe_datos_clientes (datoGeneralId, aPaterno, aManterno, nombre, NSS, tipoCreditoId,
                                                            calle, numero, colonia, municipio, estadoId, cp, empresa, fechaNac, genero, email)
                                                        VALUES ($id_datoGral, '$aPaternoC_dg', '$aMaternoC_dg', '$nombreC_dg', '$nssC_dg', $tipoCto_dg,
                                                                '$calle_cl', '$no_cl', '$col_cl', '$mpioLodad_cl', $estado_cl, '$cp_cl', '$empresa_cl', $fechaNac, '$genero', '$emailC_dg' )";
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           //echo $query;

           return $id;
       }

        /***
        * Actualizar datos del cliente
        */
       public function upDatoCliente($aPaternoC_dg, $aMaternoC_dg, $nombreC_dg, $nssC_dg, $tipoCto_dg,
                                               $calle_cl, $no_cl, $col_cl, $mpioLodad_cl, $estado_cl, $cp_cl, $empresa_cl, $fechaNac, $genero, $emailC_dg, $id_datoGral)
       {
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';

           $query = "UPDATE $tbl_sasfe_datos_clientes SET aPaterno='$aPaternoC_dg', aManterno='$aMaternoC_dg', nombre='$nombreC_dg', NSS='$nssC_dg', tipoCreditoId=$tipoCto_dg,
                                                       calle='$calle_cl', numero='$no_cl', colonia='$col_cl', municipio='$mpioLodad_cl', estadoId=$estado_cl, cp='$cp_cl', empresa='$empresa_cl', fechaNac=$fechaNac, genero='$genero', email='$emailC_dg'
                     WHERE datoGeneralId = $id_datoGral ";
           $db->setQuery($query);
           $db->query();

          //echo $query;
       }

       /***
        * Insertar datos del credito
       */
       public function insDatoCdto($id_datoGral, $numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta)
       {
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_credito = $db->getPrefix().'sasfe_datos_credito';

           $query = "INSERT INTO $tbl_sasfe_datos_credito (datoGeneralId, numeroCredito, valorVivienda, cInfonavit,
                                                            sFederal, gEscrituracion, ahorroVol, seguros, seguros_resta)
                                                        VALUES ($id_datoGral, '$numcdto_dc', $valorv_dc, $cInfonavit_dc,
                                                                $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta)";
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           return $id;
       }

        /***
        * Actualizar datos credito
        */
       public function upDatoCdto($numcdto_dc, $valorv_dc, $cInfonavit_dc, $subFed_dc, $gEscrituracion_dc, $ahorroVol, $seguros, $seguros_resta, $id_datoGral)
       {
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_credito = $db->getPrefix().'sasfe_datos_credito';

           $query = "UPDATE $tbl_sasfe_datos_credito SET numeroCredito='$numcdto_dc', valorVivienda=$valorv_dc, cInfonavit=$cInfonavit_dc,
                                                       sFederal=$subFed_dc, gEscrituracion=$gEscrituracion_dc, ahorroVol=$ahorroVol, seguros=$seguros, seguros_resta=$seguros_resta
                     WHERE datoGeneralId = $id_datoGral ";
           $db->setQuery($query);
           $db->query();

           echo $query;
       }

       /***
        * Insertar datos de las nominas
       */
       public function insDatoNomina($id_datoGral, $ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                    $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador)
       {
           $db =& JFactory::getDBO();
           $tbl_sasfe_nominas = $db->getPrefix().'sasfe_nominas';

           $query = "INSERT INTO $tbl_sasfe_nominas (datoGeneralId, comision, esPreventa, fechaPagApartado,
                                                            fechaDescomicion, fechaPagEscritura, fechaPagLiquidacion,
                                                            esAsesor, esReferido, nombreReferido, comisionPros, esPreventaPros, fPagoApartadoPros,
                                                            fPagoDescomisionPros, fPagoEscrituraPros, pctIdAses, pctIdProsp )
                                                        VALUES ($id_datoGral, $ases_comision_nom, '$preventa_nom', $fPagoApar,
                                                                $fDescomision, $fPagoEsc, $fPagoLiq,
                                                                '$esAsesor', '$esReferido', '$nombreReferido', $pros_comision_nom, '$pros_preventa_nom',$pros_fPagoApar_nom,
                                                                $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador )";
           // echo $query;
           // exit();
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           return $id;
       }

       /***
        * Actualizar datos nominas
        */
       public function upDatoNomina($ases_comision_nom, $preventa_nom, $fPagoApar, $fDescomision, $fPagoEsc, $fPagoLiq, $esAsesor, $esReferido, $nombreReferido,
                                    $pros_comision_nom, $pros_preventa_nom, $pros_fPagoApar_nom, $pros_fDesc_nom, $pros_fPagoEsc_nom, $idPctAsesor, $idPctProspectador, $id_datoGral)
       {
           $db =& JFactory::getDBO();
           $tbl_sasfe_nominas = $db->getPrefix().'sasfe_nominas';

           $query = "UPDATE $tbl_sasfe_nominas SET comision=$ases_comision_nom, esPreventa='$preventa_nom', fechaPagApartado=$fPagoApar,
                                                   fechaDescomicion=$fDescomision, fechaPagEscritura=$fPagoEsc, fechaPagLiquidacion=$fPagoLiq,
                                                   esAsesor='$esAsesor', esReferido='$esReferido', nombreReferido='$nombreReferido',
                                                   comisionPros=$pros_comision_nom, esPreventaPros='$pros_preventa_nom', fPagoApartadoPros=$pros_fPagoApar_nom,
                                                   fPagoDescomisionPros=$pros_fDesc_nom, fPagoEscrituraPros=$pros_fPagoEsc_nom, pctIdAses=$idPctAsesor, pctIdProsp=$idPctProspectador
                     WHERE datoGeneralId = $id_datoGral ";
           $db->setQuery($query);
           $db->query();

           echo $query;
       }


       public function obtDatosDpt($idDato){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_datos_catalogos
                        WHERE idDato =$idDato
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtiene todos los datos generales por idDato
       */
       public function obtDatosByDpt($idDato){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_datos_generales
                        WHERE idDatoGeneral = $idDato
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtiene todos los datos del cliente por idDato
       */
       public function obtDatosClientePorIdDatoGral($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_datos_clientes
                        WHERE datoGeneralId = $idDatoGral
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtiene todos los datos credito por idDatoGeneral
       */
       public function obtDatosCdtoPorIdDatoGral($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_credito = $db->getPrefix().'sasfe_datos_credito';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_datos_credito
                        WHERE datoGeneralId = $idDatoGral
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtiene todos los datos de nomina
       */
       public function obtDatosNominaPorIdDatoGral($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_nominas = $db->getPrefix().'sasfe_nominas';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_nominas
                        WHERE datoGeneralId = $idDatoGral
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener telefonos de cliente por su id de cliente
       */
       public function obtTelefonosPorIdCliente($idCliente){
            $db = JFactory::getDbo();
            $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_telefonos
                        WHERE datoClienteId = $idCliente
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener referencias de cliente por su id de cliente
       */
       public function obtReferenciasPorIdCliente($idCliente){
            $db = JFactory::getDbo();
            $tbl_sasfe_referencias = $db->getPrefix().'sasfe_referencias';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_referencias
                        WHERE datoClienteId = $idCliente
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener depositos por id credito
       */
       public function obtDepositosPorIdCto($idCto){
            $db = JFactory::getDbo();
            $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_depositos
                        WHERE datoCreditoId = $idCto
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener pagares por su id credito
       */
       public function obtPagaresPorIdCto($idCto){
            $db = JFactory::getDbo();
            $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_pagares
                        WHERE datoCreditoId = $idCto
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener acabados por id dato genereal
       */
       public function obtAcabadosPorIdDatoGral($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_acabados
                        WHERE datoGeneralId = $idDatoGral
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener servicios por id dato genereal
       */
       public function obtServiciosPorIdDatoGral($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_servicios
                        WHERE datoGeneralId = $idDatoGral
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Obtener post venta por id dato genereal
       */
       public function obtPostVentaPorIdDatoGral($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_postventa = $db->getPrefix().'sasfe_datos_postventa';

            $query = "
                        SELECT *
                        FROM $tbl_sasfe_datos_postventa
                        WHERE datoGeneralId = $idDatoGral
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();

          return $rows;
       }

       /**
        * Insertar los telefonos
       */
       public function insTelefonos($idCliente, $datosTelefonos){
            $db = JFactory::getDbo();
            $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';

            foreach($datosTelefonos as $item){
                $numero = $item->numero;
                $tipoId = $item->tipoId;

                 $query = "INSERT INTO $tbl_sasfe_telefonos (datoClienteId, numero, tipoId)
                                                        VALUES ($idCliente, '$numero', $tipoId )";
                $db->setQuery($query);
                $db->query();

            }
       }

       /**
        * Insertar los referencias
       */
       public function insReferencias($idCliente, $datosReferencias){
            $db = JFactory::getDbo();
            $tbl_sasfe_referencias = $db->getPrefix().'sasfe_referencias';

            foreach($datosReferencias as $item){
                $nombreRef = $item->nombreReferencia;
                $tel = $item->telefono;
                $coment = $item->comentarios;

                $query = "INSERT INTO $tbl_sasfe_referencias (datoClienteId, nombreReferencia, telefono, comentarios)
                                                        VALUES ($idCliente, '$nombreRef', '$tel', '$coment' )";
                $db->setQuery($query);
                $db->query();

            }
       }

       /**
        * Insertar los depositos
       */
       public function insDepositos($idCto, $datosDeposito){
            $db = JFactory::getDbo();
            $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

            foreach($datosDeposito as $item){
                $deposito = $item->deposito;
                $fecha = $item->fecha;
                $coment = $item->comentarios;

                $query = "INSERT INTO $tbl_sasfe_depositos (datoCreditoId, deposito, fecha, comentarios)
                                                        VALUES ($idCto, $deposito, '$fecha', '$coment' )";
                $db->setQuery($query);
                $db->query();

            }
       }

       /**
        * Insertar los pagares
       */
       public function insPagares($idCto, $datosPagares){
            $db = JFactory::getDbo();
            $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

            foreach($datosPagares as $item){
                $cantidad = $item->cantidad;
                $fechaVenc = $item->fechaVenc;
                $estatuPagareId = $item->estatuPagareId;
                $anotaciones = $item->anotaciones;

                $query = "INSERT INTO $tbl_sasfe_pagares (datoCreditoId, cantidad, fechaVenc, estatuPagareId, anotaciones)
                                                  VALUES ($idCto, $cantidad, '$fechaVenc', $estatuPagareId, '$anotaciones' )";
                $db->setQuery($query);
                $db->query();

            }
       }

       /**
        * Insertar acabados
       */
       public function insAcabados($idDatoGral, $datosAcabado){
            $db = JFactory::getDbo();
            $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';

            foreach($datosAcabado as $item){
                $nombre = $item->nombre;
                $precio = $item->precio;
                $estatus = $item->estatus;

                $query = "INSERT INTO $tbl_sasfe_acabados (datoGeneralId, nombre, precio, estatus)
                                                   VALUES ($idDatoGral, '$nombre', $precio, $estatus )";
                $db->setQuery($query);
                $db->query();
            }
       }

       /**
        * Insertar servicios
       */
       public function insServicios($idDatoGral, $datosServicios){
            $db = JFactory::getDbo();
            $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';

            foreach($datosServicios as $item){
                $nombre = $item->nombre;
                $monto = $item->monto;
                $aplica = $item->aplica;
                $coment = $item->comentarios;

                $query = "INSERT INTO $tbl_sasfe_servicios (datoGeneralId, nombre, monto, aplica, comentarios )
                                                   VALUES ($idDatoGral, '$nombre', $monto, '$aplica', '$coment' )";
                $db->setQuery($query);
                $db->query();

            }
       }

       /**
        * Insertar post venta
       */
       public function insPostVenta($idDatoGral, $datosPostVenta){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_postventa = $db->getPrefix().'sasfe_datos_postventa';

            foreach($datosPostVenta as $item){
                $dato = $item->dato;
                $fecha = $item->fecha;
                $atencionFecha = $item->atencionFecha;
                $detRea = $item->detRealizado;

                $query = "INSERT INTO $tbl_sasfe_datos_postventa (datoGeneralId, dato, fecha, atencionFecha, detRealizado )
                                                          VALUES ($idDatoGral, '$dato', '$fecha', '$atencionFecha', '$detRea' )";
                $db->setQuery($query);
                $db->query();

            }
       }

       /*
        * Comprobar si existe el idDato general en la tabla datos credito
        */
       public function obtDatoGralTblCredito($idDato){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_credito = $db->getPrefix().'sasfe_datos_credito';

            $query = "
                        SELECT datoGeneralId FROM $tbl_sasfe_datos_credito WHERE datoGeneralId = $idDato
                      ";
            $db->setQuery($query);
            $db->query();
            $row = $db->loadResult();

            $result = ($row!=null) ? 1 : 0;

          return $result;
       }

/*
 * METODOS PARA LA REASIGNACION
 */

       /**
        * Obtener id de dato general por id del departamento
       */
       public function obtIdDatoGralPorIdDpt($idDpt){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

            $query = "
                        SELECT idDatoGeneral
                        FROM $tbl_sasfe_datos_generales
                        WHERE departamentoId = $idDpt
                        ORDER BY idDatoGeneral DESC LIMIT 0,1
                      ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadResult();

          return $rows;
       }

       /*
        * Actualizar datos general
       */
       public function upDatoGralReasig($dtu_dg, $fApartado, $fInsc, $fCierre, $gte_vtas_dg, $titulacion_dg, $asesor_dg,
                                       $prospectador_dg, $estatus_dg, $fEstatus, $motivo_cancel_dg, $motivo_texto_dg, $ref_dg,
                                       $prom_dg, $fEntrega, $fReprog, $historico, $esReasignado, $fdtu, $id_dato){
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

           $query = "UPDATE $tbl_sasfe_datos_generales SET DTU='$dtu_dg', fechaApartado=$fApartado, fechaInscripcion=$fInsc, fechaCierre=$fCierre, idGerenteVentas=$gte_vtas_dg,
                     idTitulacion=$titulacion_dg, idAsesor=$asesor_dg, idPropectador=$prospectador_dg, idEstatus=$estatus_dg, fechaEstatus='$fEstatus', idCancelacion=$motivo_cancel_dg,
                     observaciones='$motivo_texto_dg', referencia='$ref_dg', promocion='$prom_dg', fechaEntrega=$fEntrega, reprogramacion=$fReprog, esHistorico='$historico', esReasignado='$esReasignado', fechaDTU=$fdtu
                     WHERE idDatoGeneral = $id_dato ";
           $db->setQuery($query);
           $db->query();

           //echo $query;
       }


       /**
        * Poner esHistorico=1, esReasignado=1, obsoleto
       */
       public function updHistReasigObsoleto($idDpt){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

            $query = "
                     UPDATE $tbl_sasfe_datos_generales SET esHistorico='1', esReasignado='1', obsoleto='1'
                     WHERE departamentoId IN ($idDpt) ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadResult();

          return $rows;
       }
       /***
        * Actualizar el id del prospecto, esto solo sucede cuando se aplica desde la pantalla prospecto
       */
       public function updIdDatoProspecto($idDatoGeneral, $datoProspectoId)
       {
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
           $query = "UPDATE $tbl_sasfe_datos_generales SET datoProspectoId=$datoProspectoId
                     WHERE idDatoGeneral=$idDatoGeneral ";
           $db->setQuery($query);
           $db->query();
          //echo $query;
       }
       /**
        * Obtiene datos del departamento por su ID
       */
       public function obtDatosDptoPorId($idDepartamento){
          $db = JFactory::getDbo();
          $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
          $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
          $query = "  SELECT a.*, b.nombre AS nombrefracc
                      FROM $tbl_sasfe_departamentos AS a
                      LEFT JOIN $tbl_sasfe_fraccionamientos AS b ON b.idFraccionamiento=a.fraccionamientoId
                      WHERE idDepartamento=$idDepartamento
                    ";
          $db->setQuery($query);
          $db->query();
          $rows = $db->loadObjectList();
          return $rows;
       }
       /**
        * Poner esHistorico=1, obsoleto=1 cuando seleccionaron cancelado =88
       */
       public function updCancelCustomer($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
            $query = "
                     UPDATE $tbl_sasfe_datos_generales SET esHistorico='1', obsoleto='1'
                     WHERE idDatoGeneral=$idDatoGral ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadResult();
          return $rows;
       }

}

?>
