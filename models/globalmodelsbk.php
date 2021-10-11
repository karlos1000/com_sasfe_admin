<?php
/**
 * fecha: 11-08-14
 * company: Framelova
 * @author Carlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.model');

//metodos globales para el componente sasfe
class SasfeModelGlobalmodelsbk extends JModelLegacy{

    public function obtTodosFraccionamientosDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';

        $query = "
                  SELECT * FROM $tbl_sasfe_fraccionamientos
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    public function obtFraccionamientosGerenteDB($gerenteId){
      $db = JFactory::getDbo();
      $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_gerente_fracc_esp';

      $query = "
                SELECT * FROM $tbl_sasfe_fraccionamientos WHERE gteVentasId=".$gerenteId."  ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();

      return $rows;
    }

    public function obtFraccionamientosGerentePorDesarrolloDB($fraccionamientoId){
      $db = JFactory::getDbo();
      $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_gerente_fracc_esp';

      $query = "
                SELECT * FROM $tbl_sasfe_fraccionamientos WHERE fraccionamientosId LIKE '%,".$fraccionamientoId.",%' ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    public function guardarFraccionamientosGerenteDB($gteVentasId, $fraccIds){

     $db = JFactory::getDBO();
     $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_gerente_fracc_esp';
     // $fechaNac = ($fechaNac!="") ?"'".$fechaNac."'" :"NULL";

     $query = "INSERT INTO $tbl_sasfe_fraccionamientos (
                          gteVentasId, fraccionamientosId)
                    VALUES (
                    $gteVentasId, '$fraccIds')";
     //echo $query;
     //exit();
     $db->setQuery($query);
     $db->query();
     $id = $db->insertid();

     return $id;
  }

  public function actualizaFraccionamientosGerenteDB($gteVentasId, $fraccIds){

     $db = JFactory::getDBO();
     $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_gerente_fracc_esp';
     // $fechaNac = ($fechaNac!="") ?"'".$fechaNac."'" :"NULL";

     $query = "UPDATE $tbl_sasfe_fraccionamientos SET
                          fraccionamientosId='$fraccIds'
                    WHERE gteVentasId=$gteVentasId
                    ";
    //  echo $query;
    //  exit();
     $db->setQuery($query);
     $db->query();
     $row = $db->getAffectedRows();
      $result = ($row>0) ? 1 : 0;

     return $result;
  }


    public function obtFraccionamientoByDesarrolloDB($desarrollo){
      $db = JFactory::getDbo();
      $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';

      $query = "
                SELECT * FROM $tbl_sasfe_fraccionamientos WHERE nombre LIKE '".$desarrollo."' LIMIT 1
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();

      return $rows;
    }

    /***
     * Obtiene toda la coleccion de catalogos
     */
    public function obtTodosCatGenericosDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_catalogos = $db->getPrefix().'sasfe_catalogos';

        $query = "
                  SELECT * FROM $tbl_sasfe_catalogos
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }
    /***
     * Obtiene el catalogo por su id
     */
    public function obtTodosCatGenericoPorIdDB($idCatGen){
        $db = JFactory::getDbo();
        $tbl_sasfe_catalogos = $db->getPrefix().'sasfe_catalogos';

        $query = "
                  SELECT * FROM $tbl_sasfe_catalogos WHERE idCatalogo=$idCatGen
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;

    }

    /***
     * Obtener el idDatoGeneral si exite si no regresar 0
     */
    public function obtIdDatoGralPorIdDptDB($idDpt){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

        $query = "
                  SELECT idDatoGeneral FROM $tbl_sasfe_datos_generales WHERE departamentoId=$idDpt  AND esHistorico=0
                 ";
        $db->setQuery($query);
        $db->query();
        $row = $db->loadResult();
        $result = ($row!=null) ? $row : 0;

        return $result;
    }

    /***
     * Obtiene el numero de vivienda por su id
     */
    public function obtNumDptPorIdDB($idDpt){
        $db = JFactory::getDbo();
        $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';

        $query = "
                  SELECT numero FROM $tbl_sasfe_departamentos WHERE idDepartamento=$idDpt
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadResult();

        return $rows;

    }

    /***
     * Imp. 29/09/21, Carlos => Obtiene informacion del departamento pr su id
     */
    public function obtInfoDptPorIdDB($idDpt){
        $db = JFactory::getDbo();
        $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';

        $query = "
                  SELECT * FROM $tbl_sasfe_departamentos WHERE idDepartamento=$idDpt
                  LIMIT 0,1
                 ";
        // echo $query;
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObject();

        return $rows;
    }

    /***
     * Obtener todos los gerentes de ventas que esten activos
     */
    public function obtColElemPorIdCatalogoDB($idCatalogo){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
        //Se aumento  AND usuarioIdJoomla IS NOT NULL el 24/07/18

        if($idCatalogo==1){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=1 AND activo='1' AND nombre !='' AND usuarioIdJoomla IS NOT NULL
                 ";
        }
        if($idCatalogo==2){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=2 AND activo='1' AND nombre !='' AND usuarioIdJoomla IS NOT NULL
                 ";
        }
        if($idCatalogo==3){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=3 AND activo='1' AND nombre !='' AND usuarioIdJoomla IS NOT NULL
                 ";
        }
        if($idCatalogo==4){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=4 AND activo='1' AND nombre !='' AND usuarioIdJoomla IS NOT NULL
                 ";
        }
        if($idCatalogo==5){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=5 AND activo='1' AND nombre !=''
                 ";
        }
        if($idCatalogo==6){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=6 AND activo='1' AND nombre !=''
                 ";
        }
        if($idCatalogo==7){
            $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=7 AND activo='1' AND nombre !=''
                 ";
        }

        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

     /***
     * Obtiene todos los estado de la republica
     */
    public function obtColEstadosRepDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_estados = $db->getPrefix().'sasfe_estados';

        $query = "
                  SELECT * FROM $tbl_sasfe_estados
                 ";
        $db->setQuery($query);
        $db->query();
       $rows = $db->loadObjectList();

        return $rows;

    }

    /***
     * Obtener telefonos por cliente
     */
    public function ObtTelsPorCliente($ds, $idCliente)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_catalogos = $db->getPrefix().'sasfe_catalogos';
           $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';

           $query = "
                        SELECT idTelefono, datoClienteId, numero, tipoId
                        FROM $tbl_sasfe_telefonos
                        WHERE datoClienteId = $idCliente
                    ";

           $queryIns = "
                        INSERT INTO $tbl_sasfe_telefonos
                               (datoClienteId, numero, tipoId) VALUES
                               ($idCliente, '@numero', @tipoId)
                       ";

           $queryUp = "UPDATE $tbl_sasfe_telefonos SET datoClienteId=$idCliente, numero='@numero', tipoId=@tipoId
                      WHERE idTelefono = @idTelefono ";

           $queryDel = "DELETE FROM $tbl_sasfe_telefonos WHERE idTelefono = @idTelefono ";

            //echo $queryIns;

            $ds->SelectCommand = $query;
            if($idCliente>0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       public function ObtRefsPorCliente($ds, $idCliente)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_catalogos = $db->getPrefix().'sasfe_catalogos';
           $tbl_sasfe_referencias = $db->getPrefix().'sasfe_referencias';

           $query = "
                        SELECT idReferencias, datoClienteId, nombreReferencia, telefono, comentarios
                        FROM $tbl_sasfe_referencias WHERE datoClienteId IN ($idCliente)
                    ";
           $queryIns = "
                        INSERT INTO $tbl_sasfe_referencias
                               (datoClienteId, nombreReferencia, telefono, comentarios) VALUES
                               ($idCliente, '@nombreReferencia', '@telefono', '@comentarios')
                       ";
           $queryUp = "UPDATE $tbl_sasfe_referencias SET datoClienteId=$idCliente, nombreReferencia='@nombreReferencia', telefono='@telefono', comentarios='@comentarios'
                       WHERE idReferencias = @idReferencias
                      ";
           $queryDel = "DELETE FROM $tbl_sasfe_referencias WHERE idReferencias = @idReferencias ";

            //echo $queryIns;

            $ds->SelectCommand = $query;

            if($idCliente!=0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       public function ObtDepositosPorDpt($ds, $idDatoCto)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

           $query = "
                        SELECT idDeposito, datoCreditoId, deposito, fecha, comentarios
                        FROM $tbl_sasfe_depositos WHERE datoCreditoId IN ($idDatoCto)
                    ";
           $queryIns = "
                        INSERT INTO $tbl_sasfe_depositos
                               (datoCreditoId, deposito, fecha, comentarios) VALUES
                               ($idDatoCto, @deposito, '@fecha', '@comentarios')
                       ";
           $queryUp = "UPDATE $tbl_sasfe_depositos SET datoCreditoId=$idDatoCto, deposito=@deposito, fecha='@fecha', comentarios='@comentarios'
                       WHERE idDeposito = @idDeposito
                      ";
           $queryDel = "DELETE FROM $tbl_sasfe_depositos WHERE idDeposito = @idDeposito ";

            //echo $query;
            $ds->SelectCommand = $query;

            if($idDatoCto!=0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       public function ObtPagaresPorDpt($ds, $idDatoCto)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

           $query = "
                        SELECT idPagare, datoCreditoId, cantidad, fechaVenc, estatuPagareId, anotaciones
                        FROM $tbl_sasfe_pagares WHERE  datoCreditoId IN ($idDatoCto)
                    ";
           $queryIns = "
                        INSERT INTO $tbl_sasfe_pagares
                               (datoCreditoId, cantidad, fechaVenc, estatuPagareId, anotaciones) VALUES
                               ($idDatoCto, @cantidad, '@fechaVenc', @estatuPagareId, '@anotaciones')
                       ";
           $queryUp = "UPDATE $tbl_sasfe_pagares SET datoCreditoId=$idDatoCto, cantidad=@cantidad, fechaVenc='@fechaVenc', estatuPagareId=@estatuPagareId, anotaciones='@anotaciones'
                       WHERE idPagare = @idPagare
                      ";
            $queryDel = "DELETE FROM $tbl_sasfe_pagares WHERE idPagare = @idPagare ";

            //echo $queryIns;
            $ds->SelectCommand = $query;

            if($idDatoCto!=0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       public function ObtPostVentaPorDpt($ds, $idDatoGral)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_datos_postventa = $db->getPrefix().'sasfe_datos_postventa';

           $query = "
                        SELECT idDatoPV, datoGeneralId, dato, fecha, atencionFecha, detRealizado, areaIdPV
                        FROM $tbl_sasfe_datos_postventa WHERE datoGeneralId = $idDatoGral
                    ";
           $queryIns = "
                        INSERT INTO $tbl_sasfe_datos_postventa
                               (datoGeneralId, dato, fecha, atencionFecha, detRealizado, areaIdPV) VALUES
                               ($idDatoGral, '@dato', '@fecha', '@atencionFecha', '@detRealizado', '@areaIdPV')
                       ";

           $queryUp = "UPDATE $tbl_sasfe_datos_postventa SET dato='@dato', fecha='@fecha', atencionFecha='@atencionFecha', detRealizado='@detRealizado', areaIdPV='@areaIdPV'
                       WHERE idDatoPV = @idDatoPV
                      ";
           $queryDel = "DELETE FROM $tbl_sasfe_datos_postventa WHERE idDatoPV = @idDatoPV ";

            //echo $queryIns;
            $ds->SelectCommand = $query;

            if($idDatoGral>0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       public function ObtServiciosPorDpt($ds, $idDatoGeral)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';

           $query = "
                        SELECT idServicio, datoGeneralId, nombre, monto, aplica, comentarios
                        FROM $tbl_sasfe_servicios WHERE datoGeneralId = $idDatoGeral
                    ";
           $queryIns = "
                        INSERT INTO $tbl_sasfe_servicios
                               (datoGeneralId, nombre, monto, aplica, comentarios) VALUES
                               ($idDatoGeral, '@nombre', @monto, '@aplica', '@comentarios')
                       ";
           $queryUp = "UPDATE $tbl_sasfe_servicios SET nombre='@nombre', monto=@monto, aplica='@aplica', comentarios='@comentarios'
                       WHERE idServicio = @idServicio
                      ";
            $queryDel = "DELETE FROM $tbl_sasfe_servicios WHERE idServicio = @idServicio ";

            //echo $queryIns;

            $ds->SelectCommand = $query;

            if($idDatoGeral>0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       public function ObtAcabadosPorDpt($ds, $idDatoGral)
       {
           $db = JFactory::getDbo();
           $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';

           $query = "
                        SELECT idAcabados, datoGeneralId, nombre, precio, estatus
                        FROM $tbl_sasfe_acabados WHERE datoGeneralId = $idDatoGral
                    ";
           $queryIns = "
                        INSERT INTO $tbl_sasfe_acabados
                               (datoGeneralId, nombre, precio, estatus) VALUES
                               ($idDatoGral, '@nombre', @precio, @estatus)
                       ";
           $queryUp = "UPDATE $tbl_sasfe_acabados SET nombre='@nombre', precio=@precio, estatus=@estatus
                       WHERE idAcabados = @idAcabados
                      ";
           $queryDel = "DELETE FROM $tbl_sasfe_acabados WHERE idAcabados = @idAcabados ";

            //echo $queryIns;
            $ds->SelectCommand = $query;

            if($idDatoGral!=0){
                $ds->InsertCommand = $queryIns;
                $ds->UpdateCommand = $queryUp;
                $ds->DeleteCommand = $queryDel;
            }

           return $ds;
       }

       /**
        * Obtiene la suma total de los depositos por el idCredito y si esta vacio regresa 0
        */
       public function sumaTotalDepositosDB($idCto){
            $db = JFactory::getDbo();
            $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

            $query = "
                      SELECT sum(deposito) as total FROM $tbl_sasfe_depositos WHERE datoCreditoId=$idCto
                     ";
            $db->setQuery($query);
            $db->query();
            $row = $db->loadResult();
            $total = ($row!=null) ? $row : 0;

            return $total;
       }
       /**
        * Obtiene la suma total de los pagares por el idCredito y si esta vacio regresa 0
        */
       public function sumaTotalPagaresDB($idCto){
            $db = JFactory::getDbo();
            $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

            $query = "
                      SELECT sum(cantidad) as total FROM $tbl_sasfe_pagares WHERE datoCreditoId=$idCto
                     ";
            $db->setQuery($query);
            $db->query();
            $row = $db->loadResult();
            $total = ($row!=null) ? $row : 0;

            return $total;
       }
       /**
        * Obtiene la suma total de los acabados por vivienda
        */
       public function sumaTotalAcabadoDB($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';

            $query = "
                      SELECT sum(precio) as total FROM $tbl_sasfe_acabados WHERE datoGeneralId=$idDatoGral AND estatus=1
                     ";
            $db->setQuery($query);
            $db->query();
            $row = $db->loadResult();
            $total = ($row!=null) ? $row : 0;

            return $total;
       }
       /**
        * Obtiene la suma total de los servicios por vivienda
        */
       public function sumaTotalServiciosDB($idDatoGral){
            $db = JFactory::getDbo();
            $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';

            $query = "
                      SELECT sum(monto) as total FROM $tbl_sasfe_servicios WHERE datoGeneralId=$idDatoGral AND aplica = 1
                     ";
            $db->setQuery($query);
            $db->query();
            $row = $db->loadResult();
            $total = ($row!=null) ? $row : 0;

            return $total;
       }

       /**
        * Obtiene el precio de la vivienda por su id
        */
       public function obtPrecioviviendaDB($idDpt){
            $db = JFactory::getDbo();
            $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';

            $query = "
                      SELECT precio FROM $tbl_sasfe_departamentos WHERE idDepartamento=$idDpt
                     ";
            $db->setQuery($query);
            $db->query();
            $row = $db->loadResult();

            return $row;
       }

    /***
    * Obtiene todos los tipos de telefonos existentes
    */
    public function obtTodosTipoTelsDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_tipo_telefonos = $db->getPrefix().'sasfe_tipo_telefonos';

        $query = "
                  SELECT * FROM $tbl_sasfe_tipo_telefonos
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /***
    * Obtiene todos los elementos del catalogo de estatus -Este es propio del sistema y no se modificara-
    */
    public function obtTodosEstatusDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_estatus = $db->getPrefix().'sasfe_estatus';

        $query = "
                  SELECT * FROM $tbl_sasfe_estatus
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /***
    * Obtiene la suma de los depositos por su id credito
    */
    public function getSumaDepositosPorIdCto($idDatoCto){
        $db = JFactory::getDbo();
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

        $query = " SELECT SUM(deposito) as total FROM $tbl_sasfe_depositos WHERE datoCreditoId IN ($idDatoCto) ";

        $db->setQuery($query);
        $db->query();
        $row = $db->loadResult();
        $total = ($row!=null) ? $row : 0;

        return $total;
    }

    /***
    * Obtiene la suma total de los pagares por su id credito
    */
    public function getSumaPagaresPorIdCto($idDatoCto){
        $db = JFactory::getDbo();
        $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

        $query = " SELECT SUM(cantidad) as total FROM $tbl_sasfe_pagares WHERE datoCreditoId IN ($idDatoCto) ";
        $query2 = " SELECT SUM(cantidad) as total FROM $tbl_sasfe_pagares WHERE datoCreditoId IN ($idDatoCto) AND estatuPagareId=3 ";

        $db->setQuery($query);
        $db->query();
        $row = $db->loadResult();
        $total = ($row!=null) ? $row : 0;

        $db->setQuery($query2);
        $db->query();
        $row2 = $db->loadResult();
        $total2 = ($row2!=null) ? $row2 : 0;
        $suma = $total-$total2;

        return $total.'|'.$suma;
    }

    /***
    * Obtiene diferencia entre total pagares pagados - total de todos pagares
    */
    public function porPagarPagaresDB($idDatoCto){
        $db = JFactory::getDbo();
        $db2 = JFactory::getDbo();
        $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

        $querySum1 = " SELECT SUM(cantidad) as total FROM $tbl_sasfe_pagares WHERE datoCreditoId IN ($idDatoCto) ";
        $querySum2 = " SELECT SUM(cantidad) as total FROM $tbl_sasfe_pagares WHERE datoCreditoId IN ($idDatoCto) AND estatuPagareId=3 ";

        $db->setQuery($querySum1);
        $db->query();
        $row = $db->loadResult();
        $total1 = ($row!=null) ? $row : 0;

        $db2->setQuery($querySum2);
        $db2->query();
        $row2 = $db2->loadResult();
        $total2 = ($row2!=null) ? $row2 : 0;

        $suma = $total1-$total2;

        return $suma;
    }

    /***
    * Obtiene todos los elementos del de estatus de acabados -Este es propio del sistema y no se modificara-
    */
    public function obtTodosEstatusAcabadosDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_estatus_acabados = $db->getPrefix().'sasfe_estatus_acabados';

        $query = "
                  SELECT * FROM $tbl_sasfe_estatus_acabados
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    public function obtTodosAcabadosFraccDB($idFracc){
        $db = JFactory::getDbo();
        $tbl_sasfe_acabados_fracc = $db->getPrefix().'sasfe_catalogo_costoextra';

        $query = "
                  SELECT * FROM $tbl_sasfe_acabados_fracc WHERE catalogoId = 8 AND fraccionamientoId = $idFracc
                 ";

        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    public function obtTodosServiciosFraccDB($idFracc){
        $db = JFactory::getDbo();
        $tbl_sasfe_acabados_fracc = $db->getPrefix().'sasfe_catalogo_costoextra';

        $query = "
                  SELECT * FROM $tbl_sasfe_acabados_fracc WHERE catalogoId = 9 AND fraccionamientoId = $idFracc
                 ";

        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

       public function convertDateToMysql($date){
           list($d,$m,$y) = explode('/', $date);
           $date = $y.'-'.$m.'-'.$d;

           return $date;
       }

    public function obtCrtPermisosDB($idGrupo, $ver, $editar){
        $db = JFactory::getDbo();
        $tbl_sasfe_ctr_permisos_campos = $db->getPrefix().'sasfe_ctr_permisos_campos';

        $query = "
                  SELECT campo, $ver, $editar  FROM $tbl_sasfe_ctr_permisos_campos
                 ";
        $db->setQuery($query);
        $db->query();
        $ctrPermisos = $db->loadObjectList();

        if($idGrupo==10){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->dirV, "edit"=>$item->dirE);
            }
        }
        if($idGrupo==11){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->gVtaV, "edit"=>$item->gVtaE);
            }
        }
        if($idGrupo==12){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->mCtrV, "edit"=>$item->mCtrE);
            }
        }
        if($idGrupo==13){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->titV, "edit"=>$item->titE);
            }
        }
        if($idGrupo==14){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->nominaV, "edit"=>$item->nominaE);
            }
        }
        if($idGrupo==15){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->postVtaV, "edit"=>$item->postVtaE);
            }
        }
        if($idGrupo==16){
            foreach($ctrPermisos as $item){
                $array[$item->campo] = array("ver"=>$item->readV, "edit"=>$item->readE);
            }
        }

        return $array;
    }

    /***
     * Cambiar el estatus al departamento por su id general
     */
     public function cambioEstatusDptDB($idEstatus, $id_Dpt, $idGral){
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

           $query = "UPDATE $tbl_sasfe_datos_generales SET idEstatus=$idEstatus, esHistorico='0', idCancelacion=NULL
                     WHERE idDatoGeneral = $idGral ";
           $db->setQuery($query);
           $db->query();

           $row = $db->getAffectedRows();
           $result = ($row>0) ? 1 : 0;

           return $result;
    }

    /*
     * Obtener asesor o prospectador inactivo desde datos catalogo por id
     */
    public function obtSelectInactivoAPDB($idSel){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        $query = "
                  SELECT * FROM $tbl_sasfe_datos_catalogos WHERE idDato=$idSel
                 ";

        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /*
     *  Obtiene todos los departamentos por id de fraccionamiento
     */
    public function obtTodosDepartamentosPorIdFraccDB($idFracc){
        $db = JFactory::getDbo();
        $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos ';
        $arrDeptos = array();
        $arraDatosGrales = array();
        //LIMIT 0,11
        $query = "
                  SELECT * FROM $tbl_sasfe_departamentos WHERE fraccionamientoId IN ($idFracc)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();
        // echo "<pre>";print_r($rows);echo "</pre>";
        // exit();
        if(count($rows)>0){
            foreach($rows as $itemDpt){
                $nivel = ($itemDpt->idNivel!="") ?$this->obtNombreDatoCatalogo($itemDpt->idNivel) :"";
                $prototipo = ($itemDpt->idPrototipo) ?$this->obtNombreDatoCatalogo($itemDpt->idPrototipo) :"";
                $arraDatosGrales = (object)$this->obtDatosGralsPorIdDepartamentosDB($itemDpt->idDepartamento);

                //Obtener el maximo numero de las tablas siguientes


                //echo $itemDpt->idDepartamento .' - '.  $itemDpt->idNivel .' - '. $nivel .' - '. $idPrototipo .'<br/>';

                $arrDeptos[] = (object)array("idDepartamento"=>$itemDpt->idDepartamento,
                                     "fraccionamientoId"=>$itemDpt->fraccionamientoId,
                                     "numero"=>$itemDpt->numero,
                                     "precio"=>$itemDpt->precio,
                                     "idNivel"=>$itemDpt->idNivel,
                                     "nivel"=>$nivel,
                                     "idPrototipo"=>$itemDpt->idPrototipo,
                                     "prototipo"=>$prototipo,
                                     "datosGrales"=>$arraDatosGrales
                                     );
            }
        }
        // echo "<pre>";print_r($arrDeptos);echo "</pre>";
        // exit();
        return $arrDeptos;
    }

    /*
     *  Obtiene todos los departamentos por id de fraccionamiento
     */
    public function obtNombreDatoCatalogo($idNivelPrototipo){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        $query = "
                  SELECT nombre FROM $tbl_sasfe_datos_catalogos WHERE idDato = $idNivelPrototipo
                 ";
        // echo $query.'<br/>';
        $db->setQuery($query);
        $db->query();
        $row = $db->loadResult();

        if($row!=''){
            $nombreDatoCat = $row;
        }else{
            $nombreDatoCat = '';
        }

        return $nombreDatoCat;
    }


    /*
     *  Obtiene todos los datos generales por id departamento
     */
    public function obtDatosGralsPorIdDepartamentosDB($idDpt){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
        $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';
        $tbl_sasfe_datos_credito = $db->getPrefix().'sasfe_datos_credito';
        $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';
        $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';
        $tbl_sasfe_nominas = $db->getPrefix().'sasfe_nominas';
        $tbl_sasfe_referencias = $db->getPrefix().'sasfe_referencias';

        $arrDatosGrales = array();
        $datosClientes = array();
        $datosCredito = array();
        $totalAcabados = '';
        $totalServiciosMun = '';
        $colRerefencias = array(); //Arreglo de todos los telefonos referencias por idCliente

        $query = "
                  SELECT * FROM $tbl_sasfe_datos_generales WHERE departamentoId = $idDpt AND esHistorico!=1 ORDER BY idDatoGeneral DESC LIMIT 0,1
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObject();

        if(count($rows)>0){
            $gerenteVentas = ($rows->idGerenteVentas!="") ?$this->obtNombreDatoCatalogo($rows->idGerenteVentas) :"";
            $titulacion = ($rows->idTitulacion!="") ?$this->obtNombreDatoCatalogo($rows->idTitulacion) :"";
            $asesor = ($rows->idAsesor!="") ?$this->obtNombreDatoCatalogo($rows->idAsesor) :"";
            $prospectador = ($rows->idPropectador!="") ?$this->obtNombreDatoCatalogo($rows->idPropectador) :"";
            $estatus = ($rows->idEstatus!="") ?$this->obtNombreDatoCatalogo($rows->idEstatus) :"";

            //Consulta los datos de los clientes por el id de dato general
            $consultaCliente = "
                               SELECT * FROM $tbl_sasfe_datos_clientes WHERE datoGeneralId = $rows->idDatoGeneral
                              ";
            $db->setQuery($consultaCliente);
            $db->query();
            $colClientes = $db->loadObject();

            //Consulta los datos credito por id dato general
            $consultaDatosCredito = "
                               SELECT * FROM $tbl_sasfe_datos_credito WHERE datoGeneralId = $rows->idDatoGeneral
                              ";
            $db->setQuery($consultaDatosCredito);
            $db->query();
            $colCredito = $db->loadObject();

            if(count($colCredito)>0){
                $datosCredito = $colCredito;
            }else{
                $datosCredito = (object)array("idDatoCredito"=>'',
                                       "datoGeneralId"=>'',
                                       "numeroCredito"=>'',
                                       "valorVivienda"=>'',
                                       "cInfonavit"=>'',
                                       "sFederal"=>'',
                                       "gEscrituracion"=>'',
                                       "ahorroVol"=>''
                                       );
            }

            //Consulta el total de acabados por el id dato General
            $consultaTotalAcabados = "
                               SELECT SUM(precio) FROM $tbl_sasfe_acabados WHERE datoGeneralId IN ($rows->idDatoGeneral) AND estatus=1
                              ";
            $db->setQuery($consultaTotalAcabados);
            $db->query();
            $totalAcabados = $db->loadResult();

            //Consulta el total de servicios por el id dato General
            $consultaTotalServicios = "
                               SELECT SUM(monto) FROM $tbl_sasfe_servicios WHERE datoGeneralId IN ($rows->idDatoGeneral) AND aplica=1
                              ";
            $db->setQuery($consultaTotalServicios);
            $db->query();
            $totalServiciosMun = $db->loadResult();

            //Consulta los datos de las nominas por el id de dato general
            $consultaNominas = "
                               SELECT * FROM $tbl_sasfe_nominas WHERE datoGeneralId = $rows->idDatoGeneral
                              ";
            $db->setQuery($consultaNominas);
            $db->query();
            $colNominas = $db->loadObject();

            if(count($colNominas)>0){

            }else{
                $colNominas = (object)array("idNomina"=>'',
                                       "datoGeneralId"=>'',
                                       "comision"=>'',
                                       "esPreventa"=>'',
                                       "fechaPagApartado"=>'',
                                       "fechaDescomicion"=>'',
                                       "fechaPagEscritura"=>'',
                                       "fechaPagLiquidacion"=>'',
                                       "esAsesor"=>'',
                                       "esReferido"=>'',
                                       "nombreReferido"=>'',
                                       "comisionPros"=>'',
                                       "esPreventaPros"=>'',
                                       "fPagoApartadoPros"=>'',
                                       "fPagoDescomisionPros"=>'',
                                       "fPagoEscrituraPros"=>'',
                                       "pctIdAses"=>'',
                                       "pctIdProsp"=>''
                                       );
            }

            if(count($colClientes)>0){
                $tipoCredito = ($colClientes->tipoCreditoId!="") ?$this->obtNombreDatoCatalogo($colClientes->tipoCreditoId) :"";

                $datosClientes = (object)array("idDatoCliente"=>$colClientes->idDatoCliente,
                                       "datoGeneralId"=>$colClientes->datoGeneralId,
                                       "aPaterno"=>$colClientes->aPaterno,
                                       "aManterno"=>$colClientes->aManterno,
                                       "nombre"=>$colClientes->nombre,
                                       "NSS"=>$colClientes->NSS,
                                       "tipoCreditoId"=>$colClientes->tipoCreditoId,
                                       "calle"=>$colClientes->calle,
                                       "numero"=>$colClientes->numero,
                                       "colonia"=>$colClientes->colonia,
                                       "municipio"=>$colClientes->municipio,
                                       "estadoId"=>$colClientes->estadoId,
                                       "cp"=>$colClientes->cp,
                                       "empresa"=>$colClientes->empresa,
                                       "fechaNac"=>$colClientes->fechaNac,
                                       "genero"=>$colClientes->genero,
                                       "tipoCredito"=>$tipoCredito,
                                       "email"=>$colClientes->email,
                                       );

               //Obtener los telefonos de referencia del cliente
               /*
               $consTelRef = "
                               SELECT * FROM $tbl_sasfe_referencias WHERE datoClienteId = $colClientes->idDatoCliente
                              ";
               $db->setQuery($consTelRef);
               $db->query();
               $colRefTelefonos = $db->loadObjectList();


               if(count($colRefTelefonos)>0){
               }else{
                   $colRefTelefonos = (object)array("idReferencias"=>'',
                                       "datoClienteId"=>'',
                                       "nombreReferencia"=>'',
                                       "telefono"=>'',
                                       "comentarios"=>''
                                       );
               }
                "referencias"=>$colRefTelefonos
               */


            }

            $arrDatosGrales = array("idDatoGeneral"=>$rows->idDatoGeneral,
                                    "departamentoId"=>$rows->departamentoId,
                                    "dtu"=>$rows->DTU,
                                    "fechaApartado"=>$rows->fechaApartado,
                                    "fechaInscripcion"=>$rows->fechaInscripcion,
                                    "fechaCierre"=>$rows->fechaCierre,
                                    "idGerenteVentas"=>$rows->idGerenteVentas,
                                    "idTitulacion"=>$rows->idTitulacion,
                                    "idAsesor"=>$rows->idAsesor,
                                    "idPropectador"=>$rows->idPropectador,
                                    "idEstatus"=>$rows->idEstatus,
                                    "fechaEstatus"=>$rows->fechaEstatus,
                                    "idCancelacion"=>$rows->idCancelacion,
                                    "observaciones"=>$rows->observaciones,
                                    "referencia"=>$rows->referencia,
                                    "promocion"=>$rows->promocion,
                                    "fechaEntrega"=>$rows->fechaEntrega,
                                    "reprogramacion"=>$rows->reprogramacion,
                                    "esHistorico"=>$rows->esHistorico,
                                    "esReasignado"=>$rows->esReasignado,
                                    "fechaDTU"=>$rows->fechaDTU,
                                    "gerenteVentas"=>$gerenteVentas,
                                    "titulacion"=>$titulacion,
                                    "asesor"=>$asesor,
                                    "prospectador"=>$prospectador,
                                    "estatus"=>$estatus,
                                    "datosClientes"=>$datosClientes,
                                    "datosCredito"=>$datosCredito,
                                    "acabados"=>$totalAcabados,
                                    "serviciosMun"=>$totalServiciosMun,
                                    "datosNominas"=>$colNominas
                                    );

        }else{

            $datosClientes = (object)array("idDatoCliente"=>'',
                                       "datoGeneralId"=>'',
                                       "aPaterno"=>'',
                                       "aManterno"=>'',
                                       "nombre"=>'',
                                       "NSS"=>'',
                                       "tipoCreditoId"=>'',
                                       "calle"=>'',
                                       "numero"=>'',
                                       "colonia"=>'',
                                       "municipio"=>'',
                                       "estadoId"=>'',
                                       "cp"=>'',
                                       "empresa"=>'',
                                       "fechaNac"=>'',
                                       "genero"=>'',
                                       "tipoCredito"=>'',
                                       "email"=>'',
                                       );

            $colRefTelefonos = (object)array("idReferencias"=>'',
                                       "datoClienteId"=>'',
                                       "nombreReferencia"=>'',
                                       "telefono"=>'',
                                       "comentarios"=>''
                                       );


            $datosCredito = (object)array("idDatoCredito"=>'',
                                       "datoGeneralId"=>'',
                                       "numeroCredito"=>'',
                                       "valorVivienda"=>'',
                                       "cInfonavit"=>'',
                                       "sFederal"=>'',
                                       "gEscrituracion"=>'',
                                       "ahorroVol"=>''
                                       );

            $colNominas = (object)array("idNomina"=>'',
                                       "datoGeneralId"=>'',
                                       "comision"=>'',
                                       "esPreventa"=>'',
                                       "fechaPagApartado"=>'',
                                       "fechaDescomicion"=>'',
                                       "fechaPagEscritura"=>'',
                                       "fechaPagLiquidacion"=>'',
                                       "esAsesor"=>'',
                                       "esReferido"=>'',
                                       "nombreReferido"=>'',
                                       "comisionPros"=>'',
                                       "esPreventaPros"=>'',
                                       "fPagoApartadoPros"=>'',
                                       "fPagoDescomisionPros"=>'',
                                       "fPagoEscrituraPros"=>'',
                                       "pctIdAses"=>'',
                                       "pctIdProsp"=>''
                                       );

            $arrDatosGrales = array("idDatoGeneral"=>'',
                                    "departamentoId"=>'',
                                    "dtu"=>'',
                                    "fechaApartado"=>'',
                                    "fechaInscripcion"=>'',
                                    "fechaCierre"=>'',
                                    "idGerenteVentas"=>'',
                                    "idTitulacion"=>'',
                                    "idAsesor"=>'',
                                    "idPropectador"=>'',
                                    "idEstatus"=>'',
                                    "fechaEstatus"=>'',
                                    "idCancelacion"=>'',
                                    "observaciones"=>'',
                                    "referencia"=>'',
                                    "promocion"=>'',
                                    "fechaEntrega"=>'',
                                    "reprogramacion"=>'',
                                    "esHistorico"=>'',
                                    "esReasignado"=>'',
                                    "fechaDTU"=>'',
                                    "gerenteVentas"=>'',
                                    "titulacion"=>'',
                                    "asesor"=>'',
                                    "prospectador"=>'',
                                    "estatus"=>'Disponible',
                                    "datosClientes"=>$datosClientes,
                                    "datosCredito"=>$datosCredito,
                                    "acabados"=>'',
                                    "serviciosMun"=>'',
                                    "datosNominas"=>$colNominas
                                    );
        }

       // return $rows;
        return $arrDatosGrales;
    }

    /**
     * Obtener porcentajes de la db por su id
     */
    public function obtDatosPorcentajePorIdDB($id){
        $db = JFactory::getDbo();
        $tbl_sasfe_porcentajes_esp = $db->getPrefix().'sasfe_porcentajes_esp';

        $query = "
                  SELECT * FROM $tbl_sasfe_porcentajes_esp WHERE idPct=$id
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObject();

        return $rows;

    }

    public function obtColReferenciasPorIdClienteDB($idCliente){
        $db = JFactory::getDbo();
        $tbl_sasfe_referencias = $db->getPrefix().'sasfe_referencias';

        $query = "
                  SELECT * FROM $tbl_sasfe_referencias WHERE datoClienteId IN ($idCliente)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;

    }

    public function obtColDepositoPorIdCreditoDB($idCredito){
        $db = JFactory::getDbo();
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

        $query = "
                  SELECT * FROM $tbl_sasfe_depositos WHERE datoCreditoId IN ($idCredito)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;

    }


    /*
     * Obtener fechas todas las fechas dtu de la tabla datos_denerales
     */
    public function ObtTodasFechasDTU($ds, $idFracc)
    {
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
        $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
        $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
        $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        // WHERE c.idFraccionamiento IN ($idFracc) AND esHistorico=0 AND esReasignado=0 AND obsoleto=0
        // ORDER BY b.idDepartamento
        // Imp. 21/09/21, Carlos, Se removio el filtro
        $query = "
                     SELECT a.idDatoGeneral, a.fechaDTU, a.fechaCierre, a.fechaApartado, b.numero, c.nombre as fraccionamiento, CONCAT(d.nombre,' ',d.aPaterno,' ',d.aManterno) as cliente, e.nombre as estatus
                     FROM $tbl_sasfe_datos_generales as a
                     LEFT JOIN $tbl_sasfe_departamentos as b ON b.idDepartamento = a.departamentoId
                     LEFT JOIN $tbl_sasfe_fraccionamientos as c ON c.idFraccionamiento = b.fraccionamientoId
                     LEFT JOIN $tbl_sasfe_datos_clientes as d ON d.datoGeneralId = a.idDatoGeneral
                     LEFT JOIN $tbl_sasfe_datos_catalogos as e ON e.idDato = a.idEstatus
                     WHERE c.idFraccionamiento IN ($idFracc)
                     ORDER BY b.numero ASC
                 ";
        $queryUp = "UPDATE $tbl_sasfe_datos_generales SET fechaDTU='@fechaDTU', DTU='1'
                    WHERE idDatoGeneral = @idDatoGeneral
                   ";
        // echo $query;
         $ds->SelectCommand = $query;
         $ds->UpdateCommand = $queryUp;

        return $ds;
    }

    /*
     * Imp. 27/09/21
     * Obtener fechas todas las fechas dtu de la tabla datos_denerales
     */
    public function ObtTodasFechasDTU2($ds, $idFracc)
    {
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
        $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
        $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
        $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        $query = "
                  SELECT * FROM $tbl_sasfe_departamentos
                  WHERE fraccionamientoId IN ($idFracc)
                  ORDER BY numero ASC
                 ";
        $queryUp = "UPDATE $tbl_sasfe_departamentos SET fechaDTU='@fechaDTU'
                    WHERE idDepartamento=@idDepartamento
                   ";
        // echo $query;
         $ds->SelectCommand = $query;
         $ds->UpdateCommand = $queryUp;

        return $ds;
    }

    /*
     * Imp. 03/09/21, Carlos, Cambio de fechas DTU en batch
     */
    public function ActBatchFechasDTU($fecha, $ids){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

        $query = "UPDATE $tbl_sasfe_datos_generales SET fechaDTU='$fecha', DTU='1'
                    WHERE idDatoGeneral IN ($ids)
                 ";
        // echo $query; exit;
        $db->setQuery($query);
        $db->query();
        $row = $db->getAffectedRows();
        $result = ($row>0) ? 1 : 0;

        return $result;
    }

    /*
     * Imp. 27/09/21, Carlos, Cambio de fechas DTU en batch
     */
    public function ActBatchFechasDTU2($fecha, $ids){
        $db = JFactory::getDbo();
        $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';

        $query = "UPDATE $tbl_sasfe_departamentos SET fechaDTU='$fecha'
                  WHERE idDepartamento IN ($ids)
                 ";
        // echo $query; exit;
        $db->setQuery($query);
        $db->query();
        $row = $db->getAffectedRows();
        $result = ($row>0) ? 1 : 0;

        return $result;
    }


    /***
     * Obtiene el nombre de fraccionamiento por su id
     */
    //public function obtNombreFraccPorIdDB($idFracc){
    public function obtDatosFraccPorIdDB($idFracc){
        $db = JFactory::getDbo();
        $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';

        $query = "
                  SELECT * FROM $tbl_sasfe_fraccionamientos WHERE idFraccionamiento=$idFracc
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObject();

        return $rows;

    }


    /*
     * Obtener los porcentajes
    */
    public function ObtPorcentajesDB($ds, $esAsesPros)
    {
        $db = JFactory::getDbo();
        $tbl_sasfe_porcentajes_esp = $db->getPrefix().'sasfe_porcentajes_esp';

        $query = "
                     SELECT *
                     FROM $tbl_sasfe_porcentajes_esp WHERE esAsesPros = $esAsesPros
                 ";

        if($esAsesPros==1){
            $queryIns = "
                         INSERT INTO $tbl_sasfe_porcentajes_esp
                         (titulo, apartado, escritura, liquidacion, mult, esAsesPros) VALUES
                         ('@titulo', @apartado, @escritura, @liquidacion, @mult, '$esAsesPros')
                        ";
            $queryUp = "UPDATE $tbl_sasfe_porcentajes_esp SET titulo='@titulo', apartado=@apartado, escritura=@escritura, liquidacion=@liquidacion, mult=@mult
                        WHERE idPct = @idPct
                   ";
            $ds->SelectCommand = $query;
            $ds->InsertCommand = $queryIns;
            $ds->UpdateCommand = $queryUp;
        }

        if($esAsesPros==2){
            $queryIns = "
                         INSERT INTO $tbl_sasfe_porcentajes_esp
                         (titulo, apartado, escritura, mult, esAsesPros) VALUES
                         ('@titulo', @apartado, @escritura, @mult, '$esAsesPros')
                        ";
            $queryUp = "UPDATE $tbl_sasfe_porcentajes_esp SET titulo='@titulo', apartado=@apartado, escritura=@escritura, mult=@mult
                        WHERE idPct = @idPct
                   ";

            $ds->SelectCommand = $query;
            $ds->InsertCommand = $queryIns;
            $ds->UpdateCommand = $queryUp;
        }
         //echo $query;


        return $ds;
    }

    /***
     * Obtiene el total de los depositos por el conjunto de ids de depositos
     */
    public function obtTotalDptsPorIdsDptDB($idDpts){
        $db = JFactory::getDbo();
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

        $query = "
                 SELECT sum(deposito) FROM $tbl_sasfe_depositos WHERE idDeposito IN ($idDpts)
                 ";
        $db->setQuery($query);
        $db->query();
        $row = $db->loadResult();
        $total = ($row!=null) ? $row : 0;

        return $total;
    }

    /***
     * Obtiene todas las fechas de los depositos seleccionados
     */
    public function obtTodasFechasDptsPorIdsDptDB($idDpts){
        $db = JFactory::getDbo();
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

        $query = "
                 SELECT fecha FROM $tbl_sasfe_depositos WHERE idDeposito IN ($idDpts) ORDER BY fecha DESC
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadColumn();

        return $rows;
    }

    /***
     * Obtiene el nombre del asesor por su id
     */
    public function obtNombreAsesorPorIdDB($idAsesor){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        $query = "
                 SELECT nombre FROM $tbl_sasfe_datos_catalogos WHERE idDato = $idAsesor
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadResult();

        return $rows;
    }

     /***
     * Obtiene todos los datos de los depositos seleccionados
     */
    public function obtDatosDepositosPorIdsDptDB($idDpts){
        $db = JFactory::getDbo();
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

        $query = "
                 SELECT * FROM $tbl_sasfe_depositos WHERE idDeposito IN ($idDpts) ORDER BY fecha DESC
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

     /***
     * Obtiene el id de credito por id de dato general
     */
    public function obtDatoCtoPorIdDatoCtoDB($idDatoGral){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_credito = $db->getPrefix().'sasfe_datos_credito';

        $query = "
                 SELECT * FROM $tbl_sasfe_datos_credito WHERE datoGeneralId=$idDatoGral
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /***
    * Obtiene todas las areas de postventa desde el catalogo
    */
    public function obtTodasAreasPostVentaDB(){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

        $query = "
                 SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId IN (12)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /***
    * Obtiene todas las areas de postventa desde el catalogo
    */
    public function obtUltimoDptPorIdCreditoDB($idCto){
        $db = JFactory::getDbo();
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';

        $query = "
                 SELECT * FROM $tbl_sasfe_depositos WHERE datoCreditoId = $idCto ORDER BY fecha DESC LIMIT 0,1
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    /*
     * Metodos para los reportes
     */
    public function obtTodosFraccionamientosPorIdDB($idFracc){
        $db = JFactory::getDbo();
        $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';

        $where = ($idFracc!=0) ? " WHERE idFraccionamiento=$idFracc" : "";
        $query = "
                  SELECT * FROM $tbl_sasfe_fraccionamientos $where
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    public function obtFilasTotalesTablasPorIdFraccDB($idFracc){
        $db = JFactory::getDbo();
        $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';
        $tbl_sasfe_depositos = $db->getPrefix().'sasfe_depositos';
        $tbl_sasfe_referencias = $db->getPrefix().'sasfe_referencias';
        $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';
        $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';
        $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';
        $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';
        $tbl_sasfe_datos_postventa = $db->getPrefix().'sasfe_datos_postventa';

        $queryTotalTelefonos = " SELECT count(datoClienteId) as col FROM $tbl_sasfe_telefonos group by datoClienteId order by col DESC LIMIT 0,1 ";
        $queryTotalReferencias = " SELECT count(datoClienteId) as col FROM $tbl_sasfe_referencias group by datoClienteId order by col DESC LIMIT 0,1 ";
        $queryTotalDepositos = " SELECT count(datoCreditoId) as col FROM $tbl_sasfe_depositos group by datoCreditoId order by col DESC LIMIT 0,1 ";
        $queryTotalPagares = " SELECT count(datoCreditoId) as col FROM $tbl_sasfe_pagares group by datoCreditoId order by col DESC LIMIT 0,1 ";
        $queryTotalAcabados = " SELECT count(datoGeneralId) as col FROM $tbl_sasfe_acabados group by datoGeneralId order by col DESC LIMIT 0,1 ";
        $queryTotalServicios = " SELECT count(datoGeneralId) as col FROM $tbl_sasfe_servicios group by datoGeneralId order by col DESC LIMIT 0,1 ";
        $queryTotalPostVenta = " SELECT count(datoGeneralId) as col FROM $tbl_sasfe_datos_postventa group by datoGeneralId order by col DESC LIMIT 0,1 ";

        $db->setQuery($queryTotalTelefonos);
        $db->query();
        $totalTelefonos = $db->loadResult();

        $db->setQuery($queryTotalReferencias);
        $db->query();
        $totalReferencias = $db->loadResult();

        $db->setQuery($queryTotalDepositos);
        $db->query();
        $totalDepositos = $db->loadResult();

        $db->setQuery($queryTotalPagares);
        $db->query();
        $totalPagares = $db->loadResult();

        $db->setQuery($queryTotalAcabados);
        $db->query();
        $totalAcabados = $db->loadResult();

        $db->setQuery($queryTotalServicios);
        $db->query();
        $totalServicios = $db->loadResult();

        $db->setQuery($queryTotalPostVenta);
        $db->query();
        $totalPostVenta = $db->loadResult();

        $arrTotales = (object)array("totalTelefonos"=>($totalTelefonos>0) ? $totalReferencias : 0,
                                    "totalReferencias"=>($totalReferencias>0) ? $totalReferencias : 0,
                                    "totalDepositos"=>($totalDepositos>0) ? $totalDepositos : 0,
                                    "totalPagares"=>($totalPagares>0) ? $totalPagares : 0,
                                    "totalAcabados"=>($totalAcabados>0) ? $totalAcabados : 0,
                                    "totalServicios"=>($totalServicios>0) ? $totalServicios : 0,
                                    "totalPostVenta"=>($totalPostVenta>0) ? $totalPostVenta : 0
                                   );

        return $arrTotales;
    }

    //Obtiene toda la coleccion de telefonos por el idcliente
    public function obtColTelefonosPorIdClienteDB($idCliente){
        $db = JFactory::getDbo();
        $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';

        $query = "
                  SELECT * FROM $tbl_sasfe_telefonos WHERE datoClienteId IN ($idCliente)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    //Obtiene toda la coleccion de pagares por id de credito
    public function obtColPagaresPorIdClienteDB($idCredito){
        $db = JFactory::getDbo();
        $tbl_sasfe_pagares = $db->getPrefix().'sasfe_pagares';

        $query = "
                  SELECT * FROM $tbl_sasfe_pagares WHERE datoCreditoId IN ($idCredito)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    //Obtiene toda la coleccion de acabados por su id
    public function obtColAcabadosPorIdDatoGralDB($idGral){
        $db = JFactory::getDbo();
        $tbl_sasfe_acabados = $db->getPrefix().'sasfe_acabados';
        $tbl_sasfe_catalogo_costoextra = $db->getPrefix().'sasfe_catalogo_costoextra';

        $query = "
                  SELECT a.*, b.nombre as nombreCatalogo FROM $tbl_sasfe_acabados AS a
                  LEFT JOIN $tbl_sasfe_catalogo_costoextra AS b ON a.nombre = b.idDatoCE
                  WHERE a.datoGeneralId IN ($idGral)
                 ";

        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    //Obtiene toda la coleccion de servicios generales por su id
    public function obtColServiciosPorIdDatoGralDB($idGral){
        $db = JFactory::getDbo();
        $tbl_sasfe_servicios = $db->getPrefix().'sasfe_servicios';
        $tbl_sasfe_catalogo_costoextra = $db->getPrefix().'sasfe_catalogo_costoextra';

        $query = "
                  SELECT a.*, b.nombre as nombreCatalogo FROM $tbl_sasfe_servicios AS a
                  LEFT JOIN $tbl_sasfe_catalogo_costoextra AS b ON a.nombre = b.idDatoCE
                  WHERE a.datoGeneralId IN ($idGral)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }

    //Obtiene toda la coleccion de postVenta por  por su id general
    public function obtColPostVentaPorIdDatoGralDB($idGral){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_postventa = $db->getPrefix().'sasfe_datos_postventa';

        $query = "
                  SELECT * FROM $tbl_sasfe_datos_postventa
                  WHERE datoGeneralId IN ($idGral)
                 ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();

        return $rows;
    }


    /***
     * Obtiene todos los tipos de eventos
    */
    public function obtColTipoEventoDB(){
      $db = JFactory::getDbo();
      $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos';

      $query = "
                 SELECT * FROM $tbl_sasfe_tipo_eventos WHERE activo=1
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /***
     * Obtiene la coleccion de los tiempos recordatorioss
    */
    public function obtColTiempoRecordatoriosDB(){
      $db = JFactory::getDbo();
      $tbl_sasfe_tiempo_recordatorios = $db->getPrefix().'sasfe_tiempo_recordatorios';

      $query = "
                 SELECT * FROM $tbl_sasfe_tiempo_recordatorios WHERE activo=1
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }


    /***
     * Implementado 31/08/17
     * Obtiene usuarios de joomla segun el catalogo
        case 1: gerentes_venta = grupo 11
        case 3: asesores(agentes de venta) = grupo 18
        case 4: prospectadores = grupo 17
     */
    public function obtUsuariosJoomlaPorGrupoDB($idGrupo){
      $db = JFactory::getDbo();
      $tbl_users = $db->getPrefix().'users';
      $tbl_user_usergroup_map = $db->getPrefix().'user_usergroup_map';

      $query = "
                 SELECT a.* FROM $tbl_users AS a
                 LEFT JOIN $tbl_user_usergroup_map AS b ON b.user_id=a.id
                 WHERE b.group_id=$idGrupo
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /**
     * Obtener datos del usuario catalogo por id del usuario joomla
    */
    public function obtUsuarioDatosCatalogoPorIdUsrJoomlaDB($idUsrJoomla){
      $db = JFactory::getDbo();
      $tbl_users = $db->getPrefix().'users';
      $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

      $query = "SELECT * FROM $tbl_sasfe_datos_catalogos WHERE usuarioIdJoomla=$idUsrJoomla LIMIT 0,1";
      // echo $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /**
     * Obtener datos del usuario catalogo por id del gerente joomla
    */
    public function obtUsuarioDatosCatalogoPorIdUsrGteJoomlaDB($idGteJoomla){
      $db = JFactory::getDbo();
      $tbl_users = $db->getPrefix().'users';
      $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

      $query = "SELECT * FROM $tbl_sasfe_datos_catalogos WHERE usuarioIdGteJoomla=$idGteJoomla LIMIT 0,1";
      // echo $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /**
     * Obtener ids de usuario joomla por en datos catalogo por id del gerente prospeccion o ventas
    */
    public function obtIdsUsrDatosCatPorUsrIdGteJoomlaDB($usuarioIdGteJoomla){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

      $query = "SELECT GROUP_CONCAT(usuarioIdJoomla) as usuarioIdJoomla
                FROM $tbl_sasfe_datos_catalogos WHERE usuarioIdGteJoomla=$usuarioIdGteJoomla";
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $row = $db->loadResult();

      return $row;
    }

    /**
     *  Revisar si tiene eventos programados para el dia actual corriendo
     *  opcionId=1 corresponde a los eventos
    */
    public function checkEventosHoyDB($idUrsJoomla, $fechaHoy){
      $db = JFactory::getDbo();
      $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos';

      $query = "SELECT a.*, a.comentario as comentarioevcom, b.*, c.tipoEvento
                FROM $tbl_sasfe_movimientosprospecto AS a
                LEFT JOIN $tbl_sasfe_datos_prospectos AS b ON b.idDatoProspecto=a.datoProspectoId
                LEFT JOIN $tbl_sasfe_tipo_eventos AS c ON c.idTipoEvento=a.tipoEventoId
                WHERE a.opcionId=1 AND a.usuarioId=$idUrsJoomla
                      AND ( DATE(a.fechaHora)>='".$fechaHoy."' AND DATE(a.fechaHora)<='".$fechaHoy."' )
                      AND ( a.atendido IS NULL OR a.atendido!=1 )
                ";
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /***
     * Obtener telefonos por cliente
     */
    public function ObtEventosComentariosDB($ds, $idDatoProspecto, $tipoOpc)
    {
       $db = JFactory::getDbo();
       $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
       $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos';
       $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

       $queryOpc = "";
       if($tipoOpc!=""){
         $queryOpc = " AND a.opcionId=$tipoOpc ";
       }

       $query = "
                SELECT a.*, b.tipoEvento, DATE_FORMAT(a.fechaHora, '%d/%m/%Y %H:%i:%s') AS fechaEvCom, CONCAT(c.nombre,' ',c.aPaterno,' ',c.aManterno) as prospecto,
                IF( a.atendido=1 , 'checked', '' ) AS checkAtendido, IF( a.opcionId=1 , 'Evento', 'Comentario' ) AS opcTipo
                FROM $tbl_sasfe_movimientosprospecto AS a
                LEFT JOIN $tbl_sasfe_tipo_eventos AS b ON b.idTipoEvento=a.tipoEventoId
                LEFT JOIN $tbl_sasfe_datos_prospectos AS c ON c.idDatoProspecto=a.datoProspectoId
                WHERE a.datoProspectoId=$idDatoProspecto $queryOpc
                ";
       // echo $query;
       $ds->SelectCommand = $query;

       return $ds;
    }


    public function obtenerDepartamentosDisponiblesDB($idFracc, $idProspectado, $idGteV)
    {
       $db = JFactory::getDbo();
       $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
       $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
       $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
       $tbl_sasfe_gerente_deptos = $db->getPrefix().'sasfe_gerente_deptos';

       //obtener grupo
       $this->userC = JFactory::getUser();
       $this->groups = JAccess::getGroupsByUser($this->userC->id, false);
       $queryDpts = "SELECT idDepartamento
                     FROM $tbl_sasfe_departamentos
                     WHERE fraccionamientoId IN ($idFracc)
                     ";

       $db->setQuery($queryDpts);
       $db->query();
       $rowsDpts = $db->loadColumn();

       $idsAllDpts = implode(',', $rowsDpts);
       // echo $idsAllDpts."<br/><br/><br/>"; exit();
       if($idsAllDpts!=""){
          // $queryInfoGral = "
          //           SELECT DISTINCT departamentoId, esReasignado, obsoleto
          //           FROM $tbl_sasfe_datos_generales
          //           WHERE departamentoId IN ($idsAllDpts) AND esHistorico=0
          //          ";
          // Imp. 14/09/21, Carlos, Solo obtendra aquellos con DTU encendido y excluir aquellos los siguientes 400 (Apartado definitivo), 401 (Apartado provisional), 87 (Escriturado)
          // AND DTU!='' AND DTU>0 AND fechaDTU!=''
          $queryInfoGral = "
                    SELECT DISTINCT departamentoId, esReasignado, obsoleto
                    FROM $tbl_sasfe_datos_generales
                    WHERE departamentoId IN ($idsAllDpts)
                    AND esHistorico=0
                    -- AND idEstatus NOT IN (400, 401, 87)
                   ";
          // echo $queryInfoGral."<br/>"; exit();

          $db->setQuery($queryInfoGral);
          $db->query();
          $rowsInfo_Gral = $db->loadObjectList();
          // echo "<pre>"; print_r($rowsInfo_Gral); echo "</pre>"; exit();

          $arrDptsFound = array();
          //Quitar todos los departamentos que existan en la primera lista
          // y que obsoleto sea 0
          foreach($rowsInfo_Gral as $item){
              if($item->obsoleto==0){
                  $arrDptsFound[] = $item->departamentoId;
              }
          }
          // echo implode(',', $arrDptsFound); exit();

          // Obtener aquellos departamentos del primer arreglo (tabla departamentos) donde sean diferentes a los departamentos del segundo arreglo (tabla datos generales)
          if(count($arrDptsFound)>0){
              foreach($arrDptsFound as $item){
                   foreach($rowsDpts as $itemInt){
                       if($itemInt == $item){
                           $index = array_search($itemInt, $rowsDpts); //Obtiene el indice del arreglo rowsDpts
                           // echo $index.'<br/>';
                           unset($rowsDpts[$index]);
                           break;
                       }
                   }
              }
          }else{
            return array();
          }

          $idsDptCurrent = implode(',', $rowsDpts);
          // echo $idsDptCurrent."<br/><br/><br/>";
          // exit();

          //Comprobar si tiene ids de los departamentos
          if($idsDptCurrent!=""){
            //obtener todos los departamentos asociados al gerente de ventas
            $queryIdsDptosGteVentas = "SELECT * FROM $tbl_sasfe_gerente_deptos WHERE gteVentasId=$idGteV LIMIT 0,1";
            $db->setQuery($queryIdsDptosGteVentas);
            $db->query();
            $idsDtosGteVentas = $db->loadObject(); //Estos ids son los que se van a mostrar para el usuario

            //if(count($arrIdsDtosGteVentas)>0){
            if($idsDtosGteVentas->departamentosId!=""){
                $arrIdsDtosGteVentas = explode(",", $idsDtosGteVentas->departamentosId);
                $arridsDptCurrent = explode(",", $idsDptCurrent);
                $arrayDeptoDisponible = array();

                foreach($arrIdsDtosGteVentas as $idDepto){
                  if(in_array($idDepto, $arridsDptCurrent)){
                     $arrayDeptoDisponible[] = $idDepto;
                  }
                }
                $idsDptCurrent = implode(",", $arrayDeptoDisponible);
                // echo '<pre>'; print_r($arrayDeptoDisponible);  echo '</pre>';
                // exit();

                if(count($arrayDeptoDisponible)>0){
                  //Obtener ids de los prospectos asignados anteriormente
                  $queryIdsDptosPreasig = "SELECT GROUP_CONCAT(deptosPreasignaron) as deptosPreasignaron
                                        FROM $tbl_sasfe_datos_prospectos WHERE idDatoProspecto=$idProspectado";
                  //echo $queryIdsDptosPreasig.'<br/>';
                  $db->setQuery($queryIdsDptosPreasig);
                  $db->query();
                  $idsDptosPreasig = $db->loadResult();
                  $queryIdsDptos = "";
                  if($idsDptosPreasig!=""){
                    //Habilitar para super usuario, direccion y gerentes de venta
                    if(!in_array("8", $this->groups) && !in_array("10", $this->groups) && !in_array("11", $this->groups)){
                    $queryIdsDptos = " AND idDepartamento NOT IN ($idsDptosPreasig) ";
                    }
                  }
                  $query = "SELECT *
                            FROM $tbl_sasfe_departamentos
                            WHERE fraccionamientoId = $idFracc AND idDepartamento IN ($idsDptCurrent) $queryIdsDptos
                            ";
                   //echo $query.'<br/>';
                   //exit();
                  $db->setQuery($query);
                  $db->query();
                  $rows = $db->loadObjectList();
                  // return array(implode(",", $arrayDeptoDisponible));
                }else{
                  $rows = array();
                }
            }else{
              $rows = array();
            }
          }else{
            $rows = array();
          }

          return $rows;
          // echo $idsAllDpts .'<br/>';
          // echo "<pre>";
          // print_r($rowsInfo_Gral);
          // print_r($rowsDpts);
          // print_r($rows);
          // echo "</pre>";
       }
    }

    // Imp. 29/09/21, Carlos Modificación
    public function obtenerDepartamentosDisponiblesDB2($idFracc, $idProspectado, $idGteV)
    {
       $db = JFactory::getDbo();
       $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
       $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
       $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
       $tbl_sasfe_gerente_deptos = $db->getPrefix().'sasfe_gerente_deptos';

       //obtener grupo
       $this->userC = JFactory::getUser();
       $this->groups = JAccess::getGroupsByUser($this->userC->id, false);
       $rows = array();
       // $queryDpts = "
       //              SELECT idDepartamento, numero
       //              FROM $tbl_sasfe_departamentos
       //              WHERE fraccionamientoId IN ($idFracc)
       //              AND ocupado=0 AND fechaDTU!=''
       //            ";
       $queryDpts = "
                    SELECT GROUP_CONCAT(idDepartamento) AS idDepartamento
                    FROM $tbl_sasfe_departamentos
                    WHERE fraccionamientoId IN ($idFracc)
                    AND ocupado=0 AND fechaDTU!=''
                  ";
       $db->setQuery($queryDpts);
       $db->query();
       $rowsDpts = $db->loadObject();
       // print_r($rowsDpts);
       // echo "es: ".$rowsDpts->idDepartamento ."<br/>";

       if($rowsDpts->idDepartamento!= ""){
          //obtener todos los departamentos asociados al gerente de ventas
          $queryIdsDptosGteVentas = "SELECT * FROM $tbl_sasfe_gerente_deptos WHERE gteVentasId=$idGteV LIMIT 0,1";
          $db->setQuery($queryIdsDptosGteVentas);
          $db->query();
          $idsDtosGteVentas = $db->loadObject(); //Estos ids son los que se van a mostrar para el usuario

          if($idsDtosGteVentas->departamentosId!=""){
            $arrIdsDtosGteVentas = explode(",", $idsDtosGteVentas->departamentosId);
            $arridsDptCurrent = explode(",", $rowsDpts->idDepartamento);
            $arrayDeptoDisponible = array();

            //Obt. Departamentos que tiene permiso el gerente de ventas desde el catalogo deptos-gerentes
            foreach($arrIdsDtosGteVentas as $idDepto){
              if(in_array($idDepto, $arridsDptCurrent)){
                 $arrayDeptoDisponible[] = $idDepto;
              }
            }

            if(count($arrayDeptoDisponible)>0){
              $idsDptCurrent = implode(",", $arrayDeptoDisponible); //Son todos los departamentos disponibles

              //Obtener ids de los prospectos asignados anteriormente para ser excluidos
              $queryIdsDptosPreasig = "SELECT GROUP_CONCAT(deptosPreasignaron) as deptosPreasignaron
                                    FROM $tbl_sasfe_datos_prospectos WHERE idDatoProspecto=$idProspectado";
              //echo $queryIdsDptosPreasig.'<br/>';
              $db->setQuery($queryIdsDptosPreasig);
              $db->query();
              $idsDptosPreasig = $db->loadResult();
              // echo "idsDptosPreasig: ". $idsDptosPreasig."<br/>";
              $queryIdsDptos = "";

              if($idsDptosPreasig!=""){
                //Permitir ver para super usuario, direccion y gerentes de venta
                if(!in_array("8", $this->groups) && !in_array("10", $this->groups) && !in_array("11", $this->groups)){
                  $queryIdsDptos = " AND idDepartamento NOT IN ($idsDptosPreasig) ";
                }
                // else{
                //   $queryIdsDptos = " AND idDepartamento NOT IN ($idsDptosPreasig) ";
                // }
              }
              // Obt. informacioon de los departamentos por mostrar en la interfaz
              $query = "SELECT *
                            FROM $tbl_sasfe_departamentos
                            WHERE fraccionamientoId=$idFracc AND idDepartamento IN ($idsDptCurrent) $queryIdsDptos
                            ";
              // echo $query.'<br/>';
              // exit();
              $db->setQuery($query);
              $db->query();
              $rows = $db->loadObjectList();
              // print_r($rows);
              if( count($rows)>0 ){
                return $rows;
              }
            }
          }
       }

       // echo "<pre>";
       // print_r($rowsDpts);
       // print_r($idsDtosGteVentas);
       // print_r($idsDptCurrent);
       // echo "</pre>";
       // exit();
    }

    /***
     * Imp. 29/09/21, Carlos, Setear departamento a ocupado
    */
    public function actDepartamentoOcupadoDB($idDepartamento, $opc){
      $db = JFactory::getDbo();
      $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';

      $query = "UPDATE $tbl_sasfe_departamentos SET ocupado=$opc
                WHERE idDepartamento=$idDepartamento
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /***
     * Imp. 29/09/21, Carlos, Actualizar el estatus de la tabla sasfe_datos_generales
    */
    public function actEstatusPorDeptoProspectoDB($departamentoId, $idDatoProspecto){
      $db = JFactory::getDbo();
      $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
      $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $tbl_sasfe_gerente_deptos = $db->getPrefix().'sasfe_gerente_deptos';

      $query = "
                SELECT GROUP_CONCAT(idDatoGeneral) AS idDatoGeneral FROM $tbl_sasfe_datos_generales
                WHERE departamentoId=$departamentoId AND datoProspectoId=$idDatoProspecto
              ";
      // echo $query."<br/>"; exit();
      $db->setQuery($query);
      $db->query();
      $idDatoGeneral = $db->loadResult();
      // echo "idDatoGeneral: ".$idDatoGeneral."<br/>";
      // exit();

      if($idDatoGeneral!=""){
        $query2 = "UPDATE $tbl_sasfe_datos_generales SET idEstatus='402'
                  WHERE idDatoGeneral IN ($idDatoGeneral)
                ";
        $db->setQuery($query2);
        $db->query();
      }
    }

    /***
     * Obtiene la coleccion de los tipos de captados
    */
    public function obtColTipoCaptadosDB(){
      $db = JFactory::getDbo();
      $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';

      $query = "
                 SELECT * FROM $tbl_sasfe_tipo_captados WHERE activo=1
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /**
     * Salvar el historial para el prospecto a partir de la asignacion de la casa
    */
    public function salvarHistorialProspectoDB($datoProspectoId, $estatusId, $comentario, $fechaCreacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_historialprospecto = $db->getPrefix().'sasfe_historialprospecto';

      $query = "INSERT INTO $tbl_sasfe_historialprospecto
                (datoProspectoId, estatusId, comentario, fechaCreacion) VALUES
                ($datoProspectoId, $estatusId, '$comentario', '$fechaCreacion')
               ";
      $db->setQuery($query);
      $db->query();
      $id = $db->insertid();

      return $id;
    }

    /**
     * Salvar el historial para el prospecto a partir de la asignacion de la casa
    */
    public function obtHistorialProspectoDB($datoProspectoId){
      $db = JFactory::getDbo();
      $tbl_sasfe_historialprospecto = $db->getPrefix().'sasfe_historialprospecto';
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

      $query = "SELECT a.*, b.*
                FROM $tbl_sasfe_historialprospecto AS a
                LEFT JOIN $tbl_sasfe_datos_prospectos AS b ON b.idDatoProspecto=a.datoProspectoId
                WHERE a.datoProspectoId=$datoProspectoId
               ";
      // echo  $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /**
    * Obtener usuarios de jooma
    */
    public function obtInfoUsuariosJoomlaDB($id){
      $db = JFactory::getDbo();
      $tbl_users = $db->getPrefix().'users';

      $query = "SELECT * FROM $tbl_users WHERE id=$id
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();

      return $rows;
    }

    /**
    * Obtener usuarios de joomla por grupo
    */
    public function obtInfoUsuariosJoomlaPorGrupoDB($idGrupo){
      $db = JFactory::getDbo();
      $tbl_users = $db->getPrefix().'users';
      $tbl_user_usergroup_map = $db->getPrefix().'user_usergroup_map';

      $query = "SELECT a.*, b.*
                FROM $tbl_users as a
                LEFT JOIN $tbl_user_usergroup_map as b ON b.user_id=a.id
                WHERE b.group_id NOT IN (1,2,3,4,5,6) AND a.block=0 AND b.group_id=$idGrupo
                ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }


    /**
    * Obtener usuarios de jooma
    */
    public function obtProspectosRelacionadosRepetidosDB($rfc, $idDatoProspecto){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';

      $query = "
                SELECT a.*, b.nombre as tipoCredito
                FROM $tbl_sasfe_datos_prospectos as a
                LEFT JOIN $tbl_sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
                WHERE a.RFC='$rfc' AND a.idDatoProspecto!=$idDatoProspecto
               ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }

    /***
     * Obtiene la coleccion de los tipos de captados
    */
    public function obtTodosDepartamentosArrDB(){
      $db = JFactory::getDbo();
      $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
      $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';

      $query = "
                SELECT a.*, b.nombre as nfraccionamiento
                FROM $tbl_sasfe_departamentos as a
                LEFT JOIN $tbl_sasfe_fraccionamientos as b ON b.idFraccionamiento=a.fraccionamientoId
                ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      return $rows;
    }


    /***
     * Obtener datos para exportar el reporte de prospectos
    */
    public function obtDatosParaReportesProspectosDB($agtVentasId, $fechaDel, $fechaAl, $difDias){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $tbl_sasfe_users = $db->getPrefix().'users';
      $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
      $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos'; //Imp. 08/09/21, Carlos
      $suma = 0; //Sumas la diferencia de dias entre la fecha de asignacion al agente y la fecha de cierre o cuando ya se asigno el departamento definitivamente

      // prospectos adquiridos, prospConvertidos, prospNoprocedido
      $query1 = "
        SELECT agtVentasId, COUNT(agtVentasId) AS prospAdquiridos, COUNT(fechaDptoAsignado) AS prospConvertidos, COUNT(idNoProcesados) AS prospNoprocedido
        FROM $tbl_sasfe_datos_prospectos
        WHERE agtVentasId IS NOT NULL AND agtVentasId=$agtVentasId
          AND ( CAST(fechaAlta AS date)>='".$fechaDel."' AND CAST(fechaAlta AS date)<='".$fechaAl."' )
        GROUP BY agtVentasId ORDER BY agtVentasId
        ";
      // echo $query1.'<br/>'; //exit();
      $db->setQuery($query1);
      $db->query();
      $rows1 = (array)$db->loadObject();
      if(count($rows1)>0){
        $queryUser = "SELECT name FROM $tbl_sasfe_users WHERE id=$agtVentasId";
        $db->setQuery($queryUser);
        $db->query();
        $rowsUser = $db->loadObject();
        if($rowsUser!=""){
          $rows1["nombreAgenteV"]=$rowsUser->name;
        }else{
          $rows1["nombreAgenteV"]="";
          // $rows1["ptcRechazados"]=0;
          // $rows1["ptcConversion"]=0;
        }
        //Porcentajes
        $rows1["ptcRechazados"]=round(($rows1['prospNoprocedido']/$rows1['prospAdquiridos'])*100, 2);
        $rows1["ptcConversion"]=round(($rows1['prospConvertidos']/$rows1['prospAdquiridos'])*100, 2);
        $rows1["prospectosxdia"]=0;
        if($difDias>0){
          // $prospectosxdiaTmp = round($rows1['prospAdquiridos']/$difDias);
          // $rows1["prospectosxdia"]= ($prospectosxdiaTmp>0) ?$prospectosxdiaTmp :ceil($rows1['prospAdquiridos']/$difDias);
          $rows1["prospectosxdia"]= round($rows1['prospAdquiridos']/$difDias, 2);
        }
        //Obtener Velocidad de conversion dias
        $suma = 0;
        if($rows1['prospConvertidos']!="" && $rows1['prospConvertidos']>0){
            $queryConvertidos = "
                           SELECT *, DATEDIFF(fechaDptoAsignado, fechaAsignacionAgt) AS difDias
                           FROM $tbl_sasfe_datos_prospectos
                           WHERE agtVentasId IS NOT NULL AND fechaDptoAsignado IS NOT NULL AND agtVentasId=$agtVentasId
                           ORDER BY agtVentasId
                           ";
            $db->setQuery($queryConvertidos);
            $db->query();
            $rowsConvertidos = $db->loadObjectList();
            if(count($rowsConvertidos)>0){
              foreach ($rowsConvertidos as $elemConv) {
                //Sumas la diferencia de dias entre la fecha de asignacion al agente y la fecha de cierre o cuando ya se asigno el departamento definitivamente
                $suma += $elemConv->difDias;
              }
            }
            if($suma>0){
              // $rows1["velocidadConversionDias"]=round(($suma/$difDias), 2);
              $rows1["velocidadConversionDias"]=round(($difDias/$suma), 2);
            }else{
              $rows1["velocidadConversionDias"]=0;
            }
        }else{
           $rows1["velocidadConversionDias"]=0;
        }
      }

      // Prospectos en proceso
      //AND duplicado=0
      $query2 = "
        SELECT COUNT(*) AS prospEnProceso
        FROM $tbl_sasfe_datos_prospectos
        WHERE agtVentasId IS NOT NULL AND idNoProcesados IS NULL AND fechaDptoAsignado IS NULL AND agtVentasId=$agtVentasId
        AND ( CAST(fechaAlta AS date)>='".$fechaDel."' AND CAST(fechaAlta AS date)<='".$fechaAl."' )
        ";
      // echo $query2.'<br/>'; exit();
      $db->setQuery($query2);
      $db->query();
      $rows2 = $db->loadObject();
      if($rows2!=""){
        $rows1["prospEnProceso"]=$rows2->prospEnProceso;
      }

      // prospectos promedio por dia
      $query3 = "
        SELECT CAST(fechaAlta AS date) AS fechas, COUNT(*) as total
        FROM $tbl_sasfe_datos_prospectos
        WHERE agtVentasId IS NOT NULL AND idNoProcesados IS NULL AND fechaDptoAsignado IS NULL AND duplicado=0 AND agtVentasId=$agtVentasId
        AND ( CAST(fechaAlta AS date)>='".$fechaDel."' AND CAST(fechaAlta AS date)<='".$fechaAl."' )
        GROUP BY fechas
        ";
      $db->setQuery($query3);
      $db->query();
      $rows3 = $db->loadObjectList();
      if($rows3!=""){
        $total = 0;
        foreach($rows3 as $elemRow3) {
          $total += $elemRow3->total;
        }
        if($total>0){
          $prosPrompordia = $total/count($rows3);
          $rows1["prosPrompordia"]=$prosPrompordia;
        }else{
          $rows1["prosPrompordia"]=0;
        }
      }

      //Obtener estadistica de los eventos
      if(count($rows1)>0){
        $query4 = "
            SELECT GROUP_CONCAT(idDatoProspecto) AS idDatoProspecto
            FROM $tbl_sasfe_datos_prospectos
            WHERE agtVentasId IS NOT NULL AND agtVentasId=$agtVentasId
            AND ( CAST(fechaAlta AS date)>='".$fechaDel."' AND CAST(fechaAlta AS date)<='".$fechaAl."' )
            ORDER BY agtVentasId
          ";
        // echo $query4.'<br/>'; //exit();
        $db->setQuery($query4);
        $db->query();
        $rows4 = $db->loadObject();

        $rows1["eventosProgramados"]=0;
        $rows1["eventosCumplidos"]=0;
        $rows1["eventosNoCumplidos"]=0;
        $rows1["ptcEventosCumplidos"]=0;
        $rows1["ptcEventosNoCumplidos"]=0;
        $rows1["eventosxdia"]=0;
        $rows1["idsDatosProspectos"]=''; //Imp. 08/09/21

        if(count((array)$rows4)>0 && $rows4->idDatoProspecto!=""){
          //# de eventos programados, # de eventos cumplidos
          $query5 = "
            SELECT COUNT(*) AS eventosProgramados, COUNT(atendido) AS eventosCumplidos
            FROM $tbl_sasfe_movimientosprospecto
            WHERE opcionId=1 AND datoProspectoId IN ($rows4->idDatoProspecto)
            AND ( CAST(fechaHora AS date)>='".$fechaDel."' AND CAST(fechaHora AS date)<='".$fechaAl."' )
          ";
          // echo $query5.'<br/>'; //exit();
          $db->setQuery($query5);
          $db->query();
          $rows5 = $db->loadObject();

          if(count((array)$rows5)>0){
            $rows1["eventosProgramados"]=$rows5->eventosProgramados;
            $rows1["eventosCumplidos"]=$rows5->eventosCumplidos;
            //Porcentajes
            $rows1["ptcEventosCumplidos"]=@round(($rows5->eventosCumplidos/$rows5->eventosProgramados)*100, 2);
            if($difDias>0){
              $rows1["eventosxdia"]= @round($rows5->eventosProgramados/$difDias, 2);
            }

            //# de eventos no cumplidos
            $query6 = "
              SELECT COUNT(*) AS eventosNoCumplidos
              FROM $tbl_sasfe_movimientosprospecto
              WHERE opcionId=1 AND atendido IS NULL AND datoProspectoId IN ($rows4->idDatoProspecto)
              AND ( CAST(fechaHora AS date)>='".$fechaDel."' AND CAST(fechaHora AS date)<='".$fechaAl."' )
            ";
            // echo $query6.'<br/>';
            $db->setQuery($query6);
            $db->query();
            $rows6 = $db->loadObject();

            if(count((array)$rows6)>0){
              $rows1["eventosNoCumplidos"]=$rows6->eventosNoCumplidos;
              $rows1["ptcEventosNoCumplidos"]=@round(($rows6->eventosNoCumplidos/$rows5->eventosProgramados)*100, 2);
            }
            //Imp. 08/09/21
            $rows1["idsDatosProspectos"]=$rows4->idDatoProspecto;
          }
        }
      }

      // echo "<pre>";
      // print_r($rows1);
      // echo "</pre>";
      // exit();

      return $rows1;
    }

    // Imp. 09/09/21
    public function detalleEventoProspectosDB($tipoEvento=0, $fechaDel, $fechaAl, $idDatoProspecto=0){
      //tipoEvento=1 => Eventos cumplidos=1, //tipoEvento=2 => Eventos no cumplidos
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
      $tbl_sasfe_tipo_eventos = $db->getPrefix().'sasfe_tipo_eventos'; //Imp. 08/09/21, Carlos
      $col = array();

      if($tipoEvento==1){
        $query = "
          SELECT a.*, b.tipoEvento, CONCAT(c.nombre, ' ', c.aPaterno, ' ', c.aManterno) as prospecto, c.RFC
          FROM $tbl_sasfe_movimientosprospecto AS a
          LEFT JOIN $tbl_sasfe_tipo_eventos AS b ON b.idTipoEvento=a.tipoEventoId
          LEFT JOIN $tbl_sasfe_datos_prospectos AS c ON c.idDatoProspecto=a.datoProspectoId
          WHERE a.opcionId=1 AND a.atendido IS NOT NULL AND a.datoProspectoId IN ($idDatoProspecto)
          AND ( CAST(a.fechaHora AS date)>='".$fechaDel."' AND CAST(a.fechaHora AS date)<='".$fechaAl."' )
        ";
        // echo $query.'<br/>'; exit();
        $db->setQuery($query);
        $db->query();
        $col = $db->loadObjectList();
      }

      if($tipoEvento==2){
        $query = "
          SELECT a.*, b.tipoEvento, CONCAT(c.nombre, ' ', c.aPaterno, ' ', c.aManterno) as prospecto, c.RFC
          FROM $tbl_sasfe_movimientosprospecto AS a
          LEFT JOIN $tbl_sasfe_tipo_eventos AS b ON b.idTipoEvento=a.tipoEventoId
          LEFT JOIN $tbl_sasfe_datos_prospectos AS c ON c.idDatoProspecto=a.datoProspectoId
          WHERE a.opcionId=1 AND a.atendido IS NULL AND a.datoProspectoId IN ($idDatoProspecto)
          AND ( CAST(a.fechaHora AS date)>='".$fechaDel."' AND CAST(a.fechaHora AS date)<='".$fechaAl."' )
        ";
        // echo $query.'<br/>'; exit();
        $db->setQuery($query);
        $db->query();
        $col = $db->loadObjectList();
      }

      return $col;
    }

    /***
     * Obtiene la coleccion de todos los asesores o agentes de ventas que pertenecen a un gerente de ventas
     */
    public function obtColAsesoresAgtVentaXIdGteVentasDB($idGteVentas){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
        //Se aumento  AND usuarioIdJoomla IS NOT NULL el 24/07/18
        $query = "
              SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=3 AND activo='1' AND nombre !='' AND usuarioIdJoomla IS NOT NULL  AND usuarioIdGteJoomla=$idGteVentas
             ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();
        return $rows;
    }

    public function obtColAsesoresAgtVentaXIdsDB($idsAsesores){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
      //Se aumento  AND usuarioIdJoomla IS NOT NULL el 24/07/18
      $query = "
            SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=3 AND usuarioIdJoomla IN($idsAsesores)
           ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();
      return $rows;
    }

    public function obtColAsesoresActivosIdsDB(){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
      //Se aumento  AND usuarioIdJoomla IS NOT NULL el 24/07/18
      $query = "
            SELECT usuarioIdJoomla FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=3 AND activo=1
           ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadAssocList();
      return $rows;
    }


    /***
     * Obtiene la coleccion de todos los prospectadores por el id del gerente (prospeccion o ventas)
     */
    public function obtColProspectadoresXIdGteDB($idGte){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
        $query = "
              SELECT * FROM $tbl_sasfe_datos_catalogos WHERE catalogoId=4 AND activo='1' AND nombre !='' AND usuarioIdGteJoomla=$idGte
             ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();
        return $rows;
    }

    /***
     * Obtener informacion de los prospectos por rango de fechas
     */
    public function obtDatosProspectosPorFechasDB($fechaDel, $fechaAl){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
        $tbl_sasfe_datos_catalogos = $db->getPrefix().'sasfe_datos_catalogos';
        $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';
        $query = "
                  SELECT a.*, b.nombre as tipoCredito, c.tipoCaptado
                  FROM $tbl_sasfe_datos_prospectos as a
                  LEFT JOIN $tbl_sasfe_datos_catalogos as b ON b.idDato=a.tipoCreditoId
                  LEFT JOIN $tbl_sasfe_tipo_captados as c ON c.idTipoCaptado=a.idTipoCaptado
                  WHERE ( DATE(a.fechaAlta)>='".$fechaDel."' AND DATE(a.fechaAlta)<='".$fechaAl."' )
                ";
        // echo $query;
        // exit();
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();
        return $rows;
    }
    /***
     * Obtener datos para exportar el reporte de prospectos por FUENTE
    */
    public function obtDatosParaReportesProspectosPorFuenteDB($agtVentasId, $fechaDel, $fechaAl, $difDias){

      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $tbl_sasfe_users = $db->getPrefix().'users';
      $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
      $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';
      $suma = 0; //Sumas la diferencia de dias entre la fecha de asignacion al agente y la fecha de cierre o cuando ya se asigno el departamento definitivamente
      // echo "DifDias: ".$difDias.'<br/>';
      //Bucle por tipo de fuente
      $arrTipoCaptados = $this->obtColTipoCaptadosDB();
      $arrGral = array();
      // prospectos adquiridos, prospConvertidos, prospNoprocedido
      foreach ($arrTipoCaptados as $elemTipoCaptado) {
        $elemTipoCaptado = $elemTipoCaptado->idTipoCaptado;
        $query1 = "
          SELECT a.agtVentasId, COUNT(a.agtVentasId) AS prospAdquiridos, COUNT(a.fechaDptoAsignado) AS prospConvertidos, COUNT(a.idNoProcesados) AS prospNoprocedido, b.tipoCaptado
          FROM $tbl_sasfe_datos_prospectos as a
          LEFT JOIN $tbl_sasfe_tipo_captados as b ON b.idTipoCaptado=a.idTipoCaptado
          WHERE a.agtVentasId IS NOT NULL AND a.idTipoCaptado IS NOT NULL AND a.agtVentasId=$agtVentasId AND a.idTipoCaptado=$elemTipoCaptado
          AND ( CAST(a.fechaAlta AS date)>='".$fechaDel."' AND CAST(a.fechaAlta AS date)<='".$fechaAl."' )
          GROUP BY agtVentasId ORDER BY agtVentasId
          ";
        // echo $query1.'<br/>';
        $db->setQuery($query1);
        $db->query();
        $rows1 = (array)$db->loadObject();
        if(count($rows1)>0){
          $queryUser = "SELECT name FROM $tbl_sasfe_users WHERE id=$agtVentasId";
          $db->setQuery($queryUser);
          $db->query();
          $rowsUser = $db->loadObject();
          //Nombre de agente
          if($rowsUser!=""){
            $rows1["nombreAgenteV"]=$rowsUser->name;
          }else{
            $rows1["nombreAgenteV"]="";
          }
          //Porcentajes
          $rows1["ptcRechazados"]=round(($rows1['prospNoprocedido']/$rows1['prospAdquiridos'])*100, 2);
          $rows1["ptcConversion"]=round(($rows1['prospConvertidos']/$rows1['prospAdquiridos'])*100, 2);
          $rows1["prospectosxdia"]=0;
          if($difDias>0){
            $rows1["prospectosxdia"]= round($rows1['prospAdquiridos']/$difDias, 2);
          }
          //Obtener Velocidad de conversion dias
          $suma = 0;
          if($rows1['prospConvertidos']!="" && $rows1['prospConvertidos']>0){
              $queryConvertidos = "
                            SELECT *, DATEDIFF(a.fechaDptoAsignado, a.fechaAsignacionAgt) AS difDias
                            FROM $tbl_sasfe_datos_prospectos as a
                            LEFT JOIN $tbl_sasfe_tipo_captados as b ON b.idTipoCaptado=a.idTipoCaptado
                            WHERE a.agtVentasId IS NOT NULL AND a.fechaDptoAsignado IS NOT NULL
                            AND a.agtVentasId=$agtVentasId AND a.idTipoCaptado IS NOT NULL AND a.idTipoCaptado=$elemTipoCaptado
                            ORDER BY a.agtVentasId
                            ";
              // echo $queryConvertidos.'<br/>';
              $db->setQuery($queryConvertidos);
              $db->query();
              $rowsConvertidos = $db->loadObjectList();
              if(count($rowsConvertidos)>0){
                foreach ($rowsConvertidos as $elemConv) {
                  //Sumas la diferencia de dias entre la fecha de asignacion al agente y la fecha de cierre o cuando ya se asigno el departamento definitivamente
                  $suma += $elemConv->difDias;
                }
              }
              if($suma>0){
                // $rows1["velocidadConversionDias"]=round(($suma/$difDias), 2);
                $rows1["velocidadConversionDias"]=round(($difDias/$suma), 2);
              }else{
                $rows1["velocidadConversionDias"]=0;
              }
          }else{
             $rows1["velocidadConversionDias"]=0;
          }
          // Prospectos en proceso
          //AND duplicado=0
          $query2 = "
            SELECT COUNT(*) AS prospEnProceso
            FROM $tbl_sasfe_datos_prospectos as a
            LEFT JOIN $tbl_sasfe_tipo_captados as b ON b.idTipoCaptado=a.idTipoCaptado
            WHERE a.agtVentasId IS NOT NULL AND a.idNoProcesados IS NULL AND a.fechaDptoAsignado IS NULL AND a.idTipoCaptado IS NOT NULL
            AND a.agtVentasId=$agtVentasId AND a.idTipoCaptado=$elemTipoCaptado
            AND ( CAST(a.fechaAlta AS date)>='".$fechaDel."' AND CAST(a.fechaAlta AS date)<='".$fechaAl."' )
            ";
          // echo $query2.'<br/>';
          $db->setQuery($query2);
          $db->query();
          $rows2 = $db->loadObject();
          if($rows2!=""){
            $rows1["prospEnProceso"]=$rows2->prospEnProceso;
          }
          // prospectos promedio por dia
          $query3 = "
            SELECT CAST(a.fechaAlta AS date) AS fechas, COUNT(*) as total
            FROM $tbl_sasfe_datos_prospectos as a
            LEFT JOIN $tbl_sasfe_tipo_captados as b ON b.idTipoCaptado=a.idTipoCaptado
            WHERE agtVentasId IS NOT NULL AND idNoProcesados IS NULL AND fechaDptoAsignado IS NULL AND duplicado=0
            AND agtVentasId=$agtVentasId AND a.idTipoCaptado IS NOT NULL AND a.idTipoCaptado=$elemTipoCaptado
            AND ( CAST(a.fechaAlta AS date)>='".$fechaDel."' AND CAST(a.fechaAlta AS date)<='".$fechaAl."' )
            GROUP BY fechas
            ";
          // echo $query3.'<br/>';
          $db->setQuery($query3);
          $db->query();
          $rows3 = $db->loadObjectList();
          if($rows3!=""){
            $total = 0;
            foreach($rows3 as $elemRow3) {
              $total += $elemRow3->total;
            }
            if($total>0){
              $prosPrompordia = $total/count($rows3);
              $rows1["prosPrompordia"]=$prosPrompordia;
            }else{
              $rows1["prosPrompordia"]=0;
            }
          }
          //Obtener estadistica de los eventos
          if(count($rows1)>0){
            $query4 = "
                SELECT GROUP_CONCAT(a.idDatoProspecto) AS idDatoProspecto
                FROM $tbl_sasfe_datos_prospectos as a
                LEFT JOIN $tbl_sasfe_tipo_captados as b ON b.idTipoCaptado=a.idTipoCaptado
                WHERE a.agtVentasId IS NOT NULL AND a.agtVentasId=$agtVentasId
                AND a.idTipoCaptado IS NOT NULL AND a.idTipoCaptado=$elemTipoCaptado
                AND ( CAST(a.fechaAlta AS date)>='".$fechaDel."' AND CAST(a.fechaAlta AS date)<='".$fechaAl."' )
                ORDER BY a.agtVentasId
              ";
            // echo $query4.'<br/>';
            $db->setQuery($query4);
            $db->query();
            $rows4 = $db->loadObject();
            $rows1["eventosProgramados"]=0;
            $rows1["eventosCumplidos"]=0;
            $rows1["eventosNoCumplidos"]=0;
            $rows1["ptcEventosCumplidos"]=0;
            $rows1["ptcEventosNoCumplidos"]=0;
            $rows1["eventosxdia"]=0;
            $rows1["idsDatosProspectos"]=''; //Imp. 09/09/21
            if(count((array)$rows4)>0 && $rows4->idDatoProspecto!=""){
              //# de eventos programados, # de eventos cumplidos
              $query5 = "
                SELECT COUNT(*) AS eventosProgramados, COUNT(atendido) AS eventosCumplidos
                FROM $tbl_sasfe_movimientosprospecto
                WHERE opcionId=1 AND datoProspectoId IN ($rows4->idDatoProspecto)
                AND ( CAST(fechaHora AS date)>='".$fechaDel."' AND CAST(fechaHora AS date)<='".$fechaAl."' )
              ";
              // echo $query5.'<br/>';
              $db->setQuery($query5);
              $db->query();
              $rows5 = $db->loadObject();
              if(count((array)$rows5)>0){
                $rows1["eventosProgramados"]=$rows5->eventosProgramados;
                $rows1["eventosCumplidos"]=$rows5->eventosCumplidos;
                //Porcentajes
                $rows1["ptcEventosCumplidos"]=@round(($rows5->eventosCumplidos/$rows5->eventosProgramados)*100, 2);
                if($difDias>0){
                  $rows1["eventosxdia"]= @round($rows5->eventosProgramados/$difDias, 2);
                }
                //# de eventos no cumplidos
                $query6 = "
                  SELECT COUNT(*) AS eventosNoCumplidos
                  FROM $tbl_sasfe_movimientosprospecto
                  WHERE opcionId=1 AND atendido IS NULL AND datoProspectoId IN ($rows4->idDatoProspecto)
                  AND ( CAST(fechaHora AS date)>='".$fechaDel."' AND CAST(fechaHora AS date)<='".$fechaAl."' )
                ";
                // echo $query6.'<br/>';
                $db->setQuery($query6);
                $db->query();
                $rows6 = $db->loadObject();
                if(count((array)$rows6)>0){
                  $rows1["eventosNoCumplidos"]=$rows6->eventosNoCumplidos;
                  $rows1["ptcEventosNoCumplidos"]=@round(($rows6->eventosNoCumplidos/$rows5->eventosProgramados)*100, 2);
                }
              }

              //Imp. 08/09/21
              $rows1["idsDatosProspectos"]=$rows4->idDatoProspecto;
            }
          }
          //Agrega datos del arreglo temporal al arreglo general
          $arrGral[] = (object)$rows1;
          //Resetea arreglos
          $rows1 = array();
          $rows2 = array();
          $query3 = array();
          $query4 = array();
          $rows5 = array();
          $rows6 = array();
        }
      }//Fin foreach

      return $arrGral;
    }
    //>>>
    //>>Para el modulo de SMS
    //>>>
    /*
     * Obtener tipo de mensaje por id
    */
    public function obtCelularClienteDB($idDatoGral){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';
      $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';
      $query1 = "SELECT * FROM $tbl_sasfe_datos_clientes WHERE datoGeneralId=$idDatoGral";
      $db->setQuery($query1);
      $db->query();
      $rows1 = $db->loadObject();
      $numero = "";
      if(isset($rows1->idDatoCliente)){
        $idCliente = $rows1->idDatoCliente;
        $query2 = "SELECT * FROM $tbl_sasfe_telefonos WHERE datoClienteId=$idCliente AND tipoId=2 ";
        $db->setQuery($query2);
        $db->query();
        $rows2 = $db->loadObject();
        if(isset($rows2->numero) && $rows2->numero!=""){
          $numeroTmp = self::limpiarNumeroTelefonico($rows2->numero);
          if(strlen($numeroTmp)==10){
            $numero = $numeroTmp;
          }else{
            $numero = "";
          }
        }
      }
      // echo "<pre>";
      // print_r($rows1);
      // print_r($rows2);
      // echo "</pre>";
      return $numero;
    }
    // Obtener mensaje por su id
    function obtMensajeSMSPorIdDB($id){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_mensajes = $db->getPrefix().'sasfe_sms_mensajes';
      $query = "SELECT * FROM $tbl_sasfe_sms_mensajes WHERE idMensaje=$id";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();
      return $rows->texto;
    }
    // Obtener col de mensajes por tipo
    function obtMensajesSMSPorTipoIdDB($tipoId){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_mensajes = $db->getPrefix().'sasfe_sms_mensajes';
      $query = "SELECT * FROM $tbl_sasfe_sms_mensajes WHERE activo=1 AND tipoId=$tipoId";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();
      return $rows;
    }
    // Obtener col de mensajes por tipo
    function obtPromocionesSMSDB(){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_promociones = $db->getPrefix().'sasfe_sms_promociones';
      $query = "SELECT * FROM $tbl_sasfe_sms_promociones WHERE activo=1 ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();
      return $rows;
    }
    //Obtener todos los usuarios que coincidieron con los filtros
    //y solo se veran aquellos que tengan un celular dado de alta en la tabla de telefonos
    function obtUsuariosSMSPorFiltroDB($idAsesor, $idEstatus, $fechaDel, $fechaAl, $tipoProceso, $idFracc){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
      $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';
      $tbl_sasfe_telefonos = $db->getPrefix().'sasfe_telefonos';
      $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
      $idsUsuarios = array();
      $filtros = array();
      $query = "";
      //SELECT * FROM adr9x_sasfe_datos_generales WHERE idAsesor=40 AND idEstatus=87 AND fechaApartado IN ("2015-03-09", "2015-04-10")
      //por defecto solo se mostraran aquellos que corresponden al estatus (401=Apartado provisional, 400=Apartado definitivo, 87=Escriturado)
      $filtros[] = " idEstatus IN (400,401,87) ";
      if($idAsesor!=""){
        $filtros[] = " idAsesor=$idAsesor ";
      }
      if($idEstatus!=""){
        $filtros[] = " idEstatus=$idEstatus ";
      }
      if($fechaDel!="" && $fechaAl!=""){
        $filtros[] = " ( fechaApartado >= '$fechaDel' AND fechaApartado <= '$fechaAl' ) ";
      }
      //Saber todos los departamentos que perteneces a un fraccionamiento
      if($idFracc!=""){
        $db->setQuery(" SET SESSION GROUP_CONCAT_MAX_LEN = 1000000 ");
        $db->query();
        $queryDepto = "SELECT GROUP_CONCAT(idDepartamento) as idDepartamento FROM $tbl_sasfe_departamentos WHERE fraccionamientoId=$idFracc ";
        $db->setQuery($queryDepto);
        $db->query();
        $rowsIdsDeptos = $db->loadResult();
        if($rowsIdsDeptos!=""){
          $filtros[] = " departamentoId IN ($rowsIdsDeptos) ";
        }
      }
      if(count($filtros) > 0){
        $wordWhere = " WHERE ";
        $setWhere = implode(" AND ", $filtros);
        $query = $wordWhere.$setWhere;
      }
      // return $query;
      $query = "SELECT * FROM $tbl_sasfe_datos_generales $query";
      // return $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();
      if(count($rows)){
        //Recorrer bucle para obtener solo aquellos usuarios que cuenten con un celular
        foreach ($rows as $elem){
          //Obtener datos del cliente siempre que cuente con una telefono celular
          $query2 = "SELECT * FROM $tbl_sasfe_datos_clientes WHERE datoGeneralId=$elem->idDatoGeneral";
          $db->setQuery($query2);
          $db->query();
          $rows2 = $db->loadObject();
          if(isset($rows2->idDatoCliente)){
            $idCliente = $rows2->idDatoCliente;
            $correo = ($rows2->email!="")?strtolower(trim($rows2->email)):"";
            $query3 = "SELECT * FROM $tbl_sasfe_telefonos WHERE datoClienteId=$idCliente AND tipoId=2 ";
            $db->setQuery($query3);
            $db->query();
            $rows3 = $db->loadObject();
            if(isset($rows3->numero) && $rows3->numero!=""){
               $numero = self::limpiarNumeroTelefonico($rows3->numero);
               if(strlen($numero)==10){
                 //Obtener el total de mensajes que se le han enviado
                 $totalMsg = self::obtTotalEnvioClienteSMSDB(trim($idCliente), $tipoProceso);
                 $idsUsuarios[] = array("idDatoCliente"=>$rows2->idDatoCliente, "cliente"=>$rows2->nombre.' '.$rows2->aPaterno.' '.$rows2->aManterno, "celular"=>$numero, "totalMsg"=>$totalMsg, "email"=>$correo);
               }
            }
          }
        }
        $rows = $idsUsuarios;
      }
      return $rows;
    }
    //Salvar el historial del mensaje o promocion enviada
    function salvarHistorialSMSDB($tipoEnvio, $grupoUsuarioId, $usuarioId, $mensaje, $comentario, $fechaCreacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_historial_envios = $db->getPrefix().'sasfe_sms_historial_envios';
      $query = 'INSERT INTO '.$tbl_sasfe_sms_historial_envios.'
                (tipoEnvio, grupoUsuarioId, usuarioId, mensaje, comentario, fechaCreacion) VALUES
                ('.$tipoEnvio.', '.$grupoUsuarioId.', '.$usuarioId.', "'.$mensaje.'", "'.$comentario.'", "'.$fechaCreacion.'")
               ';
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $id = $db->insertid();
      return $id;
    }
    //comprobar los datos credito por usuario
    function checkCreditoPorUsuarioIdSMSDB($usuarioId){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_credito_usuarios = $db->getPrefix().'sasfe_sms_credito_usuarios';
      $query = "SELECT * FROM $tbl_sasfe_sms_credito_usuarios WHERE usuarioId=$usuarioId ";
      // return $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();
      return $rows;
    }
    //Salvar Credito SMS
    function salvarCreditoSMSDB($totalCredito, $tipoProceso, $usuarioId, $fechaCreacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_credito_usuarios = $db->getPrefix().'sasfe_sms_credito_usuarios';
      $query = 'INSERT INTO '.$tbl_sasfe_sms_credito_usuarios.'
                (creditos, tipoProceso, usuarioId, fechaCreacion) VALUES
                ('.$totalCredito.', "'.$tipoProceso.'", '.$usuarioId.', "'.$fechaCreacion.'")
               ';
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $id = $db->insertid();
      return $id;
    }
    //Actualiza credito en la tabla de creditos usuario
    function actualizarCreditoSMSDB($totalCredito, $tipoProceso, $usuarioId, $fechaActualizacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_credito_usuarios = $db->getPrefix().'sasfe_sms_credito_usuarios';
      $query = 'UPDATE '.$tbl_sasfe_sms_credito_usuarios.' SET creditos='.$totalCredito.', tipoProceso="'.$tipoProceso.'", fechaActualizacion="'.$fechaActualizacion.'"
                WHERE usuarioId='.$usuarioId.'
               ';
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $row = $db->getAffectedRows();
      $result = ($row>0) ? 1 : 0;
      return $result;
    }
    //Obtener informacion de la bolsa de creditos y automaticos
    function obtInfoCreditosBolsaAutomaticoDB($idCredito){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_creditos = $db->getPrefix().'sasfe_sms_creditos';
      $query = "SELECT * FROM $tbl_sasfe_sms_creditos WHERE idCredito=$idCredito ";
      // return $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();
      return $rows;
    }
    //Actualiza credito bolsa de creditos y automaticos
    function actualizarCreditoBolsaAutomaticosSMSDB($totalCredito, $tipoProceso, $idCredito, $fechaActualizacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_creditos = $db->getPrefix().'sasfe_sms_creditos';
      $query = 'UPDATE '.$tbl_sasfe_sms_creditos.' SET creditos='.$totalCredito.', tipo="'.$tipoProceso.'", fechaActualizacion="'.$fechaActualizacion.'"
                WHERE idCredito='.$idCredito.'
               ';
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $row = $db->getAffectedRows();
      $result = ($row>0) ? 1 : 0;
      return $result;
    }
    //Metodo para restar creditos de la bolsa
    function restarCreditoBolsaSMSDB($creditoRestar, $fechaActualizacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_creditos = $db->getPrefix().'sasfe_sms_creditos';
      $result = 0;
      //Obtener el total de creditos actuales
      $query = "SELECT creditos FROM $tbl_sasfe_sms_creditos WHERE idCredito=1";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();
      if($rows->creditos>=0){
        $creditos = $rows->creditos-$creditoRestar;
        $query2 = 'UPDATE '.$tbl_sasfe_sms_creditos.' SET creditos='.$creditos.', fechaActualizacion="'.$fechaActualizacion.'"
                WHERE idCredito=1
               ';
        // echo $query.'<br/>';
        $db->setQuery($query2);
        $db->query();
        $row2 = $db->getAffectedRows();
        $result = ($row2>0) ? 1 : 0;
      }
      //Obtener los creditos reales
      $query3 = "SELECT creditos FROM $tbl_sasfe_sms_creditos WHERE idCredito=1";
      $db->setQuery($query3);
      $db->query();
      $rows3 = $db->loadObject();
      //Creditos reales
      $result = $rows3->creditos;
      return $result;
    }
    //Metodo para restar creditos de los automaticos
    function restarCreditoAutomaticosSMSDB($creditoRestar, $fechaActualizacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_creditos = $db->getPrefix().'sasfe_sms_creditos';
      $result = 0;

      //Obtener el total de creditos actuales
      $query = "SELECT creditos FROM $tbl_sasfe_sms_creditos WHERE idCredito=2";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();

      if($rows->creditos>=0){
        $creditos = $rows->creditos-$creditoRestar;

        $query2 = 'UPDATE '.$tbl_sasfe_sms_creditos.' SET creditos='.$creditos.', fechaActualizacion="'.$fechaActualizacion.'"
                WHERE idCredito=2
               ';
        // echo $query.'<br/>';
        $db->setQuery($query2);
        $db->query();
        $row2 = $db->getAffectedRows();
        $result = ($row2>0) ? 1 : 0;
      }

      //Obtener los creditos reales
      $query3 = "SELECT creditos FROM $tbl_sasfe_sms_creditos WHERE idCredito=2";
      $db->setQuery($query3);
      $db->query();
      $rows3 = $db->loadObject();
      //Creditos reales
      $result = $rows3->creditos;

      return $result;
    }
    //Metodo para restar creditos de los usuarios (gerentes o asesores)
    function restarCreditoUsuariosSMSDB($creditoRestar, $tipoProceso, $usuarioId, $fechaActualizacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_credito_usuarios = $db->getPrefix().'sasfe_sms_credito_usuarios';
      $result = false;
      //Obtener el total de creditos actuales
      $query = "SELECT creditos FROM $tbl_sasfe_sms_credito_usuarios WHERE usuarioId=$usuarioId limit 1 ";
      // echo "query: ".$query.'<br/>';
      // echo "creditoRestar: ".$creditoRestar.'<br/>';
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();
      if(isset($rows->creditos) && $rows->creditos>0){
        //Comprobar que no enviae mas creditos de los que tiene disponible (No deberia de pasar pero por seguridad)
        if($rows->creditos>=$creditoRestar){
          $creditos = $rows->creditos-$creditoRestar;
          // echo $creditos.'<br/>';
          $resAct = self::actualizarCreditoSMSDB($creditos, $tipoProceso, $usuarioId, $fechaActualizacion);
          if($resAct>0){
            $result = true;
          }
        }
      }
      return $result;
    }
    // Metodo general para limpiar los telefonos
    private function limpiarNumeroTelefonico($numero){
      $numero =  str_replace(" ","",trim($numero));
      $numero =  str_replace("(","",$numero);
      $numero =  str_replace(".","",$numero);
      $numero =  str_replace("-","",$numero);
      $numero = substr($numero, -10);
      return $numero;
    }
    //Salvar el historial del mensaje o promocion enviada por cada uno de los clientes
    function salvarHistorialClientesSMSDB($usuarioId, $agtVentasId, $datoClienteId, $tipoEnvio, $mensajeId, $fechaCreacion){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_historial_envios_clientes = $db->getPrefix().'sasfe_sms_historial_envios_clientes';
      $query = 'INSERT INTO '.$tbl_sasfe_sms_historial_envios_clientes.'
                (usuarioId, agtVentasId, datoClienteId, tipoEnvio, mensajeId, fechaCreacion) VALUES
                ('.$usuarioId.', '.$agtVentasId.', '.$datoClienteId.', '.$tipoEnvio.', '.$mensajeId.', "'.$fechaCreacion.'")
               ';
      // echo $query.'<br/>';
      $db->setQuery($query);
      $db->query();
      $id = $db->insertid();
      return $id;
    }
    //Obtener el total de mensajes que se le ha enviado a un cliente
    function obtTotalEnvioClienteSMSDB($datoClienteId, $tipoEnvio){
      $db = JFactory::getDbo();
      $tbl_sasfe_sms_historial_envios_clientes = $db->getPrefix().'sasfe_sms_historial_envios_clientes';
      $totalRecibidos = 0;
      $query = "SELECT count(*) as totalRecibidos FROM $tbl_sasfe_sms_historial_envios_clientes WHERE datoClienteId=$datoClienteId AND tipoEnvio=$tipoEnvio ";
      // return $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();
      if(isset($rows->totalRecibidos)){
          $totalRecibidos = $rows->totalRecibidos;
      }
      return $totalRecibidos;
    }
    // Obtener datos general por su id
    public function obtDatoGralPorIdSMSDB($id){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
      $query = "SELECT * FROM $tbl_sasfe_datos_generales WHERE idDatoGeneral=$id ";
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObject();
      return $result;
    }
    //Actualizar el estatus de los envios
    public function actEnviosSMSDatoGralPorIdDB($id, $campo, $valor){
           $db =& JFactory::getDBO();
           $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';
           $query = "UPDATE $tbl_sasfe_datos_generales SET $campo=$valor WHERE idDatoGeneral=$id ";
           // echo '<br/>'.$query.'<br/>';
           $db->setQuery($query);
           $db->query();
           $row = $db->getAffectedRows();
           $result = ($row>0) ? 1 : 0;
           return $result;
    }
    //actualizar estatus activo/inactivo  por su id y tipo proceso
    public function actualizarActivoCreditoSMSDB($tipoProceso, $valor, $idCredito){
     $db = JFactory::getDBO();
     $tbl_sasfe_sms_creditos = $db->getPrefix().'sasfe_sms_creditos';
     $tbl_sasfe_sms_credito_usuarios = $db->getPrefix().'sasfe_sms_credito_usuarios';
     if($tipoProceso==1){
        $query = "UPDATE $tbl_sasfe_sms_creditos SET activo=$valor WHERE idCredito=$idCredito ";
     }else{
        // $query = "UPDATE $tbl_sasfe_sms_credito_usuarios SET activo=$valor WHERE idCreditoUsuario=$idCredito ";
        $query = "UPDATE $tbl_sasfe_sms_credito_usuarios SET activo=$valor WHERE usuarioId=$idCredito ";
     }
     $db->setQuery($query);
     $db->query();
     $row = $db->getAffectedRows();
     $result = ($row>0) ? 1 : 0;
     return $result;
    }
    //Obtener datos del cliente por el id general
    public function obtDatosClientePorIdGralDB($datoGeneralId){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';
      $query = "SELECT * FROM $tbl_sasfe_datos_clientes WHERE datoGeneralId=$datoGeneralId ";
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObject();
      return $result;
    }
    //Obtener todos los usuarios que coincidieron con los filtros para las promociones
    //y solo se veran aquellos que tengan un celular dado de alta en la tabla de datos_prospectos
    function obtUsuariosSMSPorFiltroPromoDB($idAsesor, $fechaDel, $fechaAl, $tipoProceso){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
      $idsUsuarios = array();
      $filtros = array();
      $query = "";

      //SELECT * FROM adr9x_sasfe_datos_generales WHERE idAsesor=40 AND idEstatus=87 AND fechaApartado IN ("2015-03-09", "2015-04-10")
      //por defecto solo se mostraran aquellos que corresponden al estatus (401=Apartado provisional, 400=Apartado definitivo, 87=Escriturado)
      $filtros[] = " duplicado=0 AND idRepDir=0 AND (celular IS NOT NULL OR celular!='') ";

      if($idAsesor!=""){
        $filtros[] = " agtVentasId=$idAsesor ";
      }
      // if($idEstatus!=""){
      //   $filtros[] = " idEstatus=$idEstatus ";
      // }
      if($fechaDel!="" && $fechaAl!=""){
        $filtros[] = " ( fechaAlta >= '$fechaDel' AND fechaAlta <= '$fechaAl' ) ";
      }

      // //Saber todos los departamentos que perteneces a un fraccionamiento
      // if($idFracc!=""){
      //   $db->setQuery(" SET SESSION GROUP_CONCAT_MAX_LEN = 1000000 ");
      //   $db->query();

      //   $queryDepto = "SELECT GROUP_CONCAT(idDepartamento) as idDepartamento FROM $tbl_sasfe_departamentos WHERE fraccionamientoId=$idFracc ";
      //   $db->setQuery($queryDepto);
      //   $db->query();
      //   $rowsIdsDeptos = $db->loadResult();
      //   if($rowsIdsDeptos!=""){
      //     $filtros[] = " departamentoId IN ($rowsIdsDeptos) ";
      //   }
      // }


      if(count($filtros) > 0){
        $wordWhere = " WHERE ";
        $setWhere = implode(" AND ", $filtros);

        $query = $wordWhere.$setWhere;
      }
      // return $query;

      $query = "SELECT * FROM $tbl_sasfe_datos_prospectos $query";
      // return $query;
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();

      if(count($rows)){
          //Recorrer bucle para obtener solo aquellos usuarios que cuenten con un celular
          foreach ($rows as $elem){
             $numero = self::limpiarNumeroTelefonico($elem->celular);
             if(strlen($numero)==10){
               //Obtener el total de mensajes que se le han enviado
               $totalMsg = self::obtTotalEnvioClienteSMSDB(trim($elem->idDatoProspecto), $tipoProceso);
               // $totalMsg = 0;
               $correo = ($elem->email!="")?$elem->email:"";
               $idsUsuarios[] = array("idDatoCliente"=>$elem->idDatoProspecto, "cliente"=>$elem->nombre.' '.$elem->aPaterno.' '.$elem->aManterno, "celular"=>$numero, "totalMsg"=>$totalMsg, "email"=>$correo);
             }
          }
        $rows = $idsUsuarios;
      }

      return $rows;
    }


    //Obtener datos de los prospectos por su id
    function obtDatosProspectoPorIdDB($idProspecto){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

      $query = "SELECT * FROM $tbl_sasfe_datos_prospectos WHERE idDatoProspecto=$idProspecto ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObject();

      return $rows;
    }

    //Obtener datos del cliente por su id
    public function obtDatosClientePorIdDB($idDatoCliente){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_clientes = $db->getPrefix().'sasfe_datos_clientes';

      $query = "SELECT * FROM $tbl_sasfe_datos_clientes WHERE idDatoCliente=$idDatoCliente ";
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObject();

      return $result;
    }

    // Revisar si el prospecto pertenece a gerencias diferentes
    // Imp. 18/05/20
    public function revisaGerenciasPorRfcDB($rfc){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

      // Verifica gerentes de ventas
      $query = "SELECT DISTINCT gteVentasId FROM $tbl_sasfe_datos_prospectos WHERE RFC  LIKE '%$rfc%' AND (gteVentasId IS NOT NULL OR gteProspeccionId IS NOT NULL) ";
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObjectList();
      if(count($result)>1){
        return 1; //Esta en dos o mas gerencias
      }else{
        // Verifica gerentes prospeccion
        $query2 = "SELECT DISTINCT gteProspeccionId FROM $tbl_sasfe_datos_prospectos WHERE RFC  LIKE '%$rfc%' AND (gteVentasId IS NOT NULL OR gteProspeccionId IS NOT NULL) ";
        $db->setQuery($query2);
        $db->query();
        $result2 = $db->loadObjectList();

        if(count($result)>1){
          return 1; //Esta en dos o mas gerencias
        }else{
          return 0; //Solo pertenece a una gerencia
        }
      }
    }



  // >>>>>>>>>>Inicio Modulo contactos
  public function obtSincronizacionesDelDiaDB($fecha){
    $db = JFactory::getDbo();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "SELECT * FROM $tbl_sasfe_datos_contactos WHERE DATE(fechaAlta) = '$fecha'";
    // echo $query."<br/>";
    $db->setQuery($query);
    $db->query();
    $result = $db->loadObjectList();

    return $result;
  }

  // Revisar duplicidad en tabla contactos
  public function checkDuplicidadSincContactoDB($correo, $telefono){
    $db = JFactory::getDbo();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    //Imp. 05/11/20
    $search = array();
    if($correo!=""){
      $search[] = "email='$correo'";
    }
    if($telefono!=""){
      $search[] = "telefono='$telefono'";
    }

    if(count($search)>0){
      $search = "(".implode(' OR ', $search).")";
      // echo $search."<br/>";

      // $query = "SELECT * FROM $tbl_sasfe_datos_contactos WHERE ( email='$correo' OR telefono='$telefono' )";
      $query = "SELECT * FROM $tbl_sasfe_datos_contactos WHERE $search ";
      // echo $query."<br/>";
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObjectList();

      // Regresa el primer registro que encontro
      if(count($result)>0){
        return $result[0];
      }else{
        return array();
      }
    }else{
      return array();
    }
  }

  // Obtener el ultimo gerente
  public function obtUltimoGerenteSincDB(){
    $db = JFactory::getDbo();
    $tbl_sasfe_sinc_gerentes = $db->getPrefix().'sasfe_sinc_gerentes';

    $query = "SELECT gteVentasId FROM $tbl_sasfe_sinc_gerentes ORDER BY idSincGerente DESC LIMIT 1";
    // echo $query."<br/>";
    $db->setQuery($query);
    $db->query();
    $row = $db->loadObject();
    $result = ($row!=null) ? $row->gteVentasId : 0;

    return $result;
  }

  // Salvar el id del gerente
  public function salvarIdGerenteSincDB($gteVentasId, $fechaHora){
    $db = JFactory::getDbo();
    $tbl_sasfe_sinc_gerentes = $db->getPrefix().'sasfe_sinc_gerentes';

    $query = "INSERT INTO $tbl_sasfe_sinc_gerentes
              (gteVentasId, fechaAlta) VALUES
              ($gteVentasId, '$fechaHora')
             ";
    $db->setQuery($query);
    $db->query();
    $id = $db->insertid();

    return $id;
  }

  // Obtener el ultimo asesor por id gerente
  public function obtUltimoAsesorSincDB($gteVentasId){
    $db = JFactory::getDbo();
    $tbl_sasfe_sinc_agentes = $db->getPrefix().'sasfe_sinc_agentes';

    $query = "SELECT agtVentasId FROM $tbl_sasfe_sinc_agentes WHERE gteVentasId=$gteVentasId
    ORDER BY idSincAgente DESC LIMIT 1";
    // echo $query."<br/>";
    $db->setQuery($query);
    $db->query();
    $row = $db->loadObject();
    $result = ($row!=null) ? $row->agtVentasId : 0;

    return $result;
  }

  // Salvar el id del gerente
  public function salvarIdAsesorSincDB($gteVentasId, $agtVentasId, $fechaHora){
    $db = JFactory::getDbo();
    $tbl_sasfe_sinc_agentes = $db->getPrefix().'sasfe_sinc_agentes';

    $query = "INSERT INTO $tbl_sasfe_sinc_agentes
              (gteVentasId, agtVentasId, fechaAlta) VALUES
              ($gteVentasId, $agtVentasId, '$fechaHora')
             ";
    $db->setQuery($query);
    $db->query();
    $id = $db->insertid();

    return $id;
  }

  /***
  * Obtener acciones por el id del contacto para el grid
  */
  public function ObtAccionesPorIdContactoDB($ds, $idDatoProspecto){
     $db = JFactory::getDbo();
     $tbl_sasfe_sinc_acciones = $db->getPrefix().'sasfe_sinc_acciones';
     $tbl_users = $db->getPrefix().'users';

     // SELECT a.*, DATE_FORMAT(a.fechaAlta, '%d/%m/%Y %H:%i:%s') AS fechaAlta2
     $query = "
              SELECT a.*, DATE_FORMAT(a.fechaAlta, '%d/%m/%Y') AS fechaAlta2,
              IF(b.name!=null OR b.name!='', b.name, 'Sistema') AS nombreAgtVentas,
              (CASE WHEN a.accionId=1 THEN 'Llamar' WHEN a.accionId=2 THEN 'Whats app'
              WHEN a.accionId=3 THEN 'SMS' WHEN a.accionId=4 THEN 'Correo' WHEN a.accionId=5 THEN 'Cita'
              WHEN a.accionId=6 THEN 'Descartado' WHEN a.accionId=7 THEN 'Reasignado'
              ELSE '' END) AS accion
              FROM adr9x_sasfe_sinc_acciones as a
              LEFT JOIN $tbl_users AS b ON b.id=a.agtVentasId
              WHERE a.idDatoContacto=$idDatoProspecto
              ";
     // echo $query;
     $ds->SelectCommand = $query;

     return $ds;
  }
  // >>>>>>>>>>Fin modulo contactos

  /***
   * Obtener datos para exportar el reporte de contactos por FUENTE
  */
  public function obtDatosParaReportesContactosPorFuenteDB($fechaDel, $fechaAl, $idGteVenta, $idAgtventas){
    $db = JFactory::getDbo();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';
    $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
    $tbl_users1 = $db->getPrefix().'users';

    // Imp. 14/09/21, Carlos
    $queryGte = "";
    if($idGteVenta>0){
      $queryGte = " AND gteVentasId=".$idGteVenta." ";
    }
    $queryAgt = "";
    if($idAgtventas>0){
      $queryAgt = " AND agtVentasId=".$idAgtventas." ";
    }

    $query = "
      SELECT a.*, b.nombre as fraccionamiento, c.name as gteVentas, d.name as agtVentas,
      (CASE WHEN a.estatusId=1 THEN 'Asignado' WHEN a.estatusId=2 THEN 'Seguimiento'
        WHEN a.estatusId=3 THEN 'Contactado' WHEN a.estatusId=4 THEN 'Descartado' WHEN a.estatusId=5 THEN 'Prospecto'
        WHEN a.estatusId=6 THEN 'Reasignado'
      ELSE '' END) AS estatus
      FROM $tbl_sasfe_datos_contactos as a
      LEFT JOIN $tbl_sasfe_fraccionamientos as b ON b.idFraccionamiento=a.desarrolloId
      LEFT JOIN $tbl_users1 as c ON c.id=a.gteVentasId
      LEFT JOIN $tbl_users1 as d ON d.id=a.agtVentasId
      WHERE ( CAST(a.fechaAlta AS date)>='".$fechaDel."' AND CAST(a.fechaAlta AS date)<='".$fechaAl."' ) $queryGte $queryAgt
      ORDER BY a.fuente
      ";

      // echo $query."<br/>";
      // exit();
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObjectList();

      return $result;
  }

  /***
   * Obtener datos para exportar reporte de detalles de acciones por contacto
  */
  public function obtDetalleAccionesContactosDB($fechaDel, $fechaAl, $gteVentasId, $agtVentasId){
    $db = JFactory::getDbo();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';
    $tbl_sasfe_sinc_acciones = $db->getPrefix().'sasfe_sinc_acciones';
    $tbl_users1 = $db->getPrefix().'users';
    $arrGteAgte = array();

    $queryGTAgte = "";
    if($gteVentasId > -1){
      $queryGTAgte .= " AND b.gteVentasId=$gteVentasId ";
    }
    if($agtVentasId > -1){
      $queryGTAgte .= " AND b.agtVentasId=$agtVentasId ";
    }
    // echo "queryGTAgte: ".$queryGTAgte."<br/>";

    $query = "
      SELECT a.*, CONCAT(b.nombre, ' ', b.aPaterno, ' ', b.aMaterno) as contacto, c.name as gteVentas, d.name as agtVentas,
      (CASE WHEN a.accionId=1 THEN 'Llamar' WHEN a.accionId=2 THEN 'Whats app'
                    WHEN a.accionId=3 THEN 'SMS' WHEN a.accionId=4 THEN 'Correo' WHEN a.accionId=5 THEN 'Cita'
                    WHEN a.accionId=6 THEN 'Descartado' WHEN a.accionId=7 THEN 'Reasignado'
                    ELSE '' END) AS accion, b.gteVentasId, b.agtVentasId as agtVentasId2
      FROM $tbl_sasfe_sinc_acciones as a
      LEFT JOIN $tbl_sasfe_datos_contactos as b ON b.idDatoContacto=a.idDatoContacto
      LEFT JOIN $tbl_users1 as c ON c.id=b.gteVentasId
      LEFT JOIN $tbl_users1 as d ON d.id=b.agtVentasId
      WHERE ( CAST(a.fechaAlta AS date)>='".$fechaDel."' AND CAST(a.fechaAlta AS date)<='".$fechaAl."' )
      $queryGTAgte
      ORDER BY contacto ASC
      ";

      // echo $query."<br/>";
      $db->setQuery($query);
      $db->query();
      $result = $db->loadObjectList();

      return $result;
  }

  /***
   * Contar el numero de acciones
  */
  public function contarAccionesContactoDB($idContacto){
    $db = JFactory::getDbo();
    $tbl_sasfe_sinc_acciones = $db->getPrefix().'sasfe_sinc_acciones';

    $query = "
      SELECT COUNT(*) totalAccContacto FROM $tbl_sasfe_sinc_acciones WHERE idDatoContacto=$idContacto AND accionId IN (1,2,3,4)
      ";
    // echo $query."<br/>";
    $db->setQuery($query);
    $db->query();
    $row = $db->loadResult();
    $result = ($row!=null) ? $row : 0;

    return $result;
  }

  /*
   * Obtener las configuraciones globales
  */
  public function ObtCatConfiguracionesDB($ds)
  {
      $db = JFactory::getDbo();
      $tbl_sasfe_configuraciones = $db->getPrefix().'sasfe_configuraciones';

      //Imp. 13/10/20
      //Seleccionar todos excepto lo siguientes
      $excepciones = "2";
      $whereExcepcion = " WHERE idConfiguracion NOT IN ($excepciones) ";
      $query = "SELECT * FROM $tbl_sasfe_configuraciones $whereExcepcion ORDER BY idConfiguracion ASC ";
      $queryUp = "UPDATE $tbl_sasfe_configuraciones SET valor='@valor', fechaAct=NOW()
                  WHERE idConfiguracion=@idConfiguracion
                 ";
      $ds->SelectCommand = $query;
      $ds->UpdateCommand = $queryUp;

      // echo $query;
      /*if($esAsesPros==1){
          $queryIns = "
                       INSERT INTO $tbl_sasfe_porcentajes_esp
                       (titulo, apartado, escritura, liquidacion, mult, esAsesPros) VALUES
                       ('@titulo', @apartado, @escritura, @liquidacion, @mult, '$esAsesPros')
                      ";
          $queryUp = "UPDATE $tbl_sasfe_porcentajes_esp SET titulo='@titulo', apartado=@apartado, escritura=@escritura, liquidacion=@liquidacion, mult=@mult
                      WHERE idPct = @idPct
                 ";
          $ds->SelectCommand = $query;
          // $ds->InsertCommand = $queryIns;
          // $ds->UpdateCommand = $queryUp;
      }

      if($esAsesPros==2){
          $queryIns = "
                       INSERT INTO $tbl_sasfe_porcentajes_esp
                       (titulo, apartado, escritura, mult, esAsesPros) VALUES
                       ('@titulo', @apartado, @escritura, @mult, '$esAsesPros')
                      ";
          $queryUp = "UPDATE $tbl_sasfe_porcentajes_esp SET titulo='@titulo', apartado=@apartado, escritura=@escritura, mult=@mult
                      WHERE idPct = @idPct
                 ";

          $ds->SelectCommand = $query;
          // $ds->InsertCommand = $queryIns;
          // $ds->UpdateCommand = $queryUp;
      }*/
      //echo $query;

      return $ds;
  }

  /***
   * Obtener datos de configuracion por si Id
  */
  public function obtDatosConfiguracionPorIdDB($idConfiguracion, $fechaAct){
    $db = JFactory::getDbo();
    $tbl_sasfe_configuraciones = $db->getPrefix().'sasfe_configuraciones';

    //Imp. 13/10/20
    $paramFecha = "";
    if($fechaAct!=""){
      $paramFecha = " AND CAST(fechaAct AS date)='".$fechaAct."' ";
    }
    $query = "
        SELECT a.* FROM $tbl_sasfe_configuraciones as a
        WHERE a.idConfiguracion=$idConfiguracion  $paramFecha
        ORDER BY a.idConfiguracion
    ";

    // echo $query."<br/>";
    $db->setQuery($query);
    $db->query();
    $result = $db->loadObject();

    return $result;
  }

  /***
   * Imp. 13/10/20
   * Actualizar dato de configuracion por su Id
  */
  public function ActDatoConfiguracionPorIdDB($idConfiguracion, $valor){
    $db = JFactory::getDbo();
    $tbl_sasfe_configuraciones = $db->getPrefix().'sasfe_configuraciones';

    $query = "UPDATE $tbl_sasfe_configuraciones SET valor='$valor'
                    WHERE idConfiguracion=$idConfiguracion
                    ";
    //  echo $query;
    //  exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  /***
   * Obtener col de motivos rechazo
  */
  public function obtDatosMotivosDB(){
    $db = JFactory::getDbo();
    $tbl_sasfe_motivos = $db->getPrefix().'sasfe_motivos';

    $query = "
        SELECT a.* FROM $tbl_sasfe_motivos as a
        ORDER BY a.idMotivo
    ";

    // echo $query."<br/>";
    $db->setQuery($query);
    $db->query();
    $result = $db->loadObjectList();

    return $result;
  }

  /***
   * Obtiene el tipo captado o fuente
  */
  public function obtTipoCaptadoDB($idTipo){
    $db = JFactory::getDbo();
    $tbl_sasfe_tipo_captados = $db->getPrefix().'sasfe_tipo_captados';

    $query = "
               SELECT * FROM $tbl_sasfe_tipo_captados WHERE idTipoCaptado=$idTipo
             ";
    $db->setQuery($query);
    $db->query();
    $rows = $db->loadObject();

    return $rows;
  }

  // >>>>>
  // >>>>>Inicio Expedientes digitales
  // >>>>>
  public function obtEnlaceDigitalPorIdPCDB($idProspecto, $idDatoGeneral){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';
    $query = array();
    $strQuery = "";

    if($idProspecto>0){
      $query[] = " datoProspectoId=$idProspecto ";
    }
    if($idDatoGeneral>0){
      $query[] = " datoGeneralId=$idDatoGeneral ";
    }

    if(count($query) > 0){
      $wordWhere = " WHERE ";
      $setWhere = implode(" OR ", $query);
      $strQuery = $wordWhere.$setWhere;
      // echo $strQuery;
    }else{
      return array();
    }

    $query = "
         SELECT * FROM $tbl_sasfe_enlaces_digitales $strQuery
       ";
    // echo $query;
    $db->setQuery($query);
    $db->query();
    $rows = $db->loadObject();

    return $rows;
  }

  public function insEnlaceDigitatDB($idProspecto, $idDatoGeneral, $tipoEnlace, $link){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';

    $campo = "";
    $tmpLink = "";
    switch ($tipoEnlace) {
      case 1:
        $campo = ", linkGeneral ";
        break;
      case 2:
        $campo = ", linkContrato ";
        break;
      case 3:
        $campo = ", linkEscrituras ";
        break;
      case 4:
        $campo = ", linkEntregas ";
        break;
    }

    if($campo!=""){
      $tmpLink = ", '$link' ";
    }
    // echo "campo: ".$campo ."<br/>";
    // echo "tmpLink: ".$tmpLink ."<br/>";

    $query = "INSERT INTO $tbl_sasfe_enlaces_digitales (datoProspectoId, datoGeneralId  $campo)
              VALUES ($idProspecto, $idDatoGeneral  $tmpLink)";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $id = $db->insertid();

    return $id;
  }

  public function actEnlaceDigitatDB($idEnlace, $idProspecto, $idDatoGeneral, $tipoEnlace, $link){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';

    $tmpLink = "";
    switch ($tipoEnlace) {
      case 1: $tmpLink = ", linkGeneral='$link' "; break;
      case 2: $tmpLink = ", linkContrato='$link' "; break;
      case 3: $tmpLink = ", linkEscrituras='$link' "; break;
      case 4: $tmpLink = ", linkEntregas='$link' "; break;
    }
    // echo "tmpLink: ".$tmpLink ."<br/>";

    $query = "UPDATE $tbl_sasfe_enlaces_digitales SET datoProspectoId=$idProspecto, datoGeneralId=$idDatoGeneral  $tmpLink
              WHERE idEnlace=$idEnlace
              ";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  // Actualiza desde las vistas internas de edicion de prospectos o CRM
  public function actEnlaceDigitalInternoDB($idEnlace, $idProspecto, $idDatoGeneral, $tipoEnlace, $link){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';
    $query = array();
    $strQuery = "";

    $tmpLink = "";
    switch ($tipoEnlace) {
      case 1: $tmpLink = ", linkGeneral='$link' "; break;
      case 2: $tmpLink = ", linkContrato='$link' "; break;
      case 3: $tmpLink = ", linkEscrituras='$link' "; break;
      case 4: $tmpLink = ", linkEntregas='$link' "; break;
    }
    // echo "tmpLink: ".$tmpLink ."<br/>";

    if($idProspecto>0){
      $query[] = " datoProspectoId=$idProspecto ";
    }
    if($idDatoGeneral>0){
      $query[] = " datoGeneralId=$idDatoGeneral ";
    }

    if(count($query) > 0){
      $strQuery = implode(" , ", $query);
      // echo $strQuery;
    }else{
      return array();
    }

    $query = "UPDATE $tbl_sasfe_enlaces_digitales SET $strQuery $tmpLink
              WHERE idEnlace=$idEnlace
              ";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  public function obtPorIdEnlaceDB($id){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';

    $query = "
         SELECT * FROM $tbl_sasfe_enlaces_digitales WHERE idEnlace=$id
       ";
    $db->setQuery($query);
    $db->query();
    $rows = $db->loadObject();

    return $rows;
  }


  /*// Imp. 07/01/21, Carlos
  public function buscarEnlaceDB($consulta, $idPC){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';
    // $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
    // $tbl_sasfe_datos_generales = $db->getPrefix().'sasfe_datos_generales';

    if($consulta==0){
      $where = " WHERE datoProspectoId=$idPC ";
    }else{
      $where = " WHERE datoGeneralId=$idPC ";
    }
    $query = "
         SELECT * FROM $tbl_sasfe_enlaces_digitales $where
       ";
    $db->setQuery($query);
    $db->query();
    $rows = $db->loadObject();

    return $rows;
  }

  public function crearEnlaceVacioDB($consulta, $idPC){
    $db = JFactory::getDbo();
    $tbl_sasfe_enlaces_digitales = $db->getPrefix().'sasfe_enlaces_digitales';

    if($consulta==0){
      $query = "INSERT INTO $tbl_sasfe_enlaces_digitales (datoProspectoId)
                    VALUES ($idPC)";
    }else{
      $query = "INSERT INTO $tbl_sasfe_enlaces_digitales (datoGeneralId)
                    VALUES ($idPC)";
    }
    //echo $query;
    //exit();
    $db->setQuery($query);
    $db->query();
    $id = $db->insertid();

    return $id;
  }*/

  // >>>>>Fin Expedientes digitales

}
?>
