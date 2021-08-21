<?php
/**
 * fecha: 24-09-29
 * company: company
 * @author Karlos
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.model');

class SasfeModelContacto extends JModelLegacy{

  //Insertar datos del contacto
  public function insertarContacto($gteVentasId, $agtVentasId, $nombre, $aPaterno, $aMaterno, $email, $telefono, $fuente, $estatusId, $desarrolloId,
                                  $fechaAlta, $fechaActualizacion, $activo, $usuarioId, $usuarioIdActualizacion, $credito){
       $db = JFactory::getDBO();
       $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

       $query = "INSERT INTO $tbl_sasfe_datos_contactos (gteVentasId, agtVentasId, nombre, aPaterno, aMaterno, email, telefono, fuente, estatusId, desarrolloId,
                                  fechaAlta, fechaActualizacion, activo, usuarioId, usuarioIdActualizacion, credito
                             )
                      VALUES ($gteVentasId, $agtVentasId, '$nombre', '$aPaterno', '$aMaterno', '$email', '$telefono', '$fuente', $estatusId, $desarrolloId,
                             '$fechaAlta', '$fechaActualizacion', '$activo', $usuarioId, $usuarioIdActualizacion, '$credito'
                             )";
       // echo $query;
       // exit();
       $db->setQuery($query);
       $db->query();
       $id = $db->insertid();

       return $id;
   }

  // Acualiza datos contacto
  public function actDatosContacto($idDatoContacto, $nombre, $aPaterno, $aMaterno, $email, $telefono,
                    $estatusId, $fechaActualizacion, $usuarioIdActualizacion, $desarrolloId, $credito,
                    $fuente){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET nombre='$nombre', aPaterno='$aPaterno', aMaterno='$aMaterno',
              email='$email', telefono='$telefono', estatusId=$estatusId, fechaActualizacion='$fechaActualizacion', usuarioIdActualizacion=$usuarioIdActualizacion,
              desarrolloId=$desarrolloId, credito='$credito', fuente='$fuente'
              WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  // Acualiza estatus contacto
  public function actEstatusContacto($idDatoContacto, $estatusId, $fechaActualizacion, $usuarioIdActualizacion){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET estatusId=$estatusId, fechaActualizacion='$fechaActualizacion',
              usuarioIdActualizacion=$usuarioIdActualizacion
              WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  // Acualiza id agente de ventas para contacto
  public function actAgtVentasContacto($idDatoContacto, $agtVentasId, $fechaActualizacion, $usuarioIdActualizacion){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET agtVentasId=$agtVentasId, fechaActualizacion='$fechaActualizacion',
              usuarioIdActualizacion=$usuarioIdActualizacion
              WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  // Acualiza id gerente de ventas para contacto
  public function actGteVentasContacto($idDatoContacto, $gteVentasId, $agtVentasId, $fechaActualizacion, $usuarioIdActualizacion){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET gteVentasId=$gteVentasId, agtVentasId=$agtVentasId, fechaActualizacion='$fechaActualizacion',
              usuarioIdActualizacion=$usuarioIdActualizacion
              WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  // Acualiza fecha contacto
  public function actFechaContacto($idDatoContacto, $fechaContacto, $fechaActualizacion, $usuarioIdActualizacion){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET fechaContacto='$fechaContacto', fechaActualizacion='$fechaActualizacion',
              usuarioIdActualizacion=$usuarioIdActualizacion
              WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  // Descartar contacto
  public function descartarContacto($idDatoContacto, $motivo_descarte, $comentario_descarte, $fechaDescarte, $fechaActualizacion, $usuarioIdActualizacion){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET motivo_descarte='$motivo_descarte', comentario_descarte='$comentario_descarte', fechaDescarte='$fechaDescarte',
                fechaActualizacion='$fechaActualizacion', usuarioIdActualizacion=$usuarioIdActualizacion
              WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  //Inserta accion
  public function insertarAccion($idDatoContacto, $agtVentasId, $accionId, $comentario, $fechaAlta){
    $db = JFactory::getDBO();
    $tbl_sasfe_sinc_acciones = $db->getPrefix().'sasfe_sinc_acciones';

    $query = "INSERT INTO $tbl_sasfe_sinc_acciones (idDatoContacto, agtVentasId, accionId, comentario, fechaAlta)
              VALUES ($idDatoContacto, $agtVentasId, $accionId, '$comentario', '$fechaAlta')";

    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $id = $db->insertid();

    return $id;
  }

  // Obtener datos del contacto
  public function obtenerDatosContacto($idDatoContacto){
    if($idDatoContacto>0){
      $db = JFactory::getDbo();
      $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

      $query = "
        SELECT a.*
        FROM $tbl_sasfe_datos_contactos AS a
        WHERE a.idDatoContacto=$idDatoContacto
      ";
      $db->setQuery($query);
      $db->query();
      $rows = $db->loadObjectList();
    }else{
      $rows = array();
    }

    return $rows;
  }

  // Desactivar contacto
  public function desactivaContacto($idDatoContacto, $fechaProspecto){
    $db = JFactory::getDBO();
    $tbl_sasfe_datos_contactos = $db->getPrefix().'sasfe_datos_contactos';

    $query = "UPDATE $tbl_sasfe_datos_contactos SET activo=0, fechaProspecto='$fechaProspecto' WHERE idDatoContacto=$idDatoContacto";
    // echo $query;
    // exit();
    $db->setQuery($query);
    $db->query();
    $row = $db->getAffectedRows();
    $result = ($row>0) ? 1 : 0;

    return $result;
  }

  //Conversion a prospecto
  public function convertirAProspecto($fechaAlta, $gteVentasId, $agtVentasId, $nombre, $aPaterno, $aManterno, $email, $telefono,
                                   $idTipoCaptado, $estatusId, $desarrolloId){
     $db = JFactory::getDBO();
     $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

     $query = "INSERT INTO $tbl_sasfe_datos_prospectos (fechaAlta, gteVentasId, agtVentasId, nombre, aPaterno, aManterno, email, telefono,
                              idTipoCaptado, estatusId, desarrolloId)
                    VALUES ('$fechaAlta', $gteVentasId, $agtVentasId, '$nombre', '$aPaterno', '$aManterno', '$email', '$telefono',
                            $idTipoCaptado, $estatusId, $desarrolloId
                           )";
     // echo $query;
     // exit();
     $db->setQuery($query);
     $db->query();
     $id = $db->insertid();

     return $id;
  }



  public function consultaAccionesContacto($idDatoContacto, $limit = ""){
    $db = JFactory::getDBO();
    $tbl_sasfe_sinc_acciones = $db->getPrefix().'sasfe_sinc_acciones';
    $limite = ($limit != "")?" LIMIT $limit ":"";

    $query = "SELECT * FROM  $tbl_sasfe_sinc_acciones WHERE idDatoContacto=$idDatoContacto $limite ";

    $db->setQuery($query);
    $db->query();
    $rows = $db->loadObjectList();

    return $rows;

  }


        /*public function insertarProspecto($fechaAlta, $nombre, $aPaterno, $aManterno, $RFC, $fechaNac, $edad, $telefono, $celular, $genero,
                                          $NSS, $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, $comentario, $empresa, $idTipoCaptado, $email,
                                          $usuarioId, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                          $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $altaProspectadorId, $desarrolloId){
           $db = JFactory::getDBO();
           $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
           $fechaNac = ($fechaNac!="") ?"'".$fechaNac."'" :"NULL";

           $query = "INSERT INTO $tbl_sasfe_datos_prospectos (
                                 fechaAlta, nombre, aPaterno, aManterno, RFC, fechaNac, edad, telefono, celular, genero,
                                 NSS, montoCredito, tipoCreditoId, subsidio, puntosHasta, comentario, empresa, idTipoCaptado, email,
                                 usuarioId, gteProspeccionId, gteVentasId, prospectadorId, agtVentasId, estatusId,
                                 duplicado, periodoAsignacion, fechaAsignacionAgt, idRepDir, altaProspectadorId, desarrolloId
                                 )
                          VALUES ('$fechaAlta', '$nombre', '$aPaterno', '$aManterno', '$RFC', $fechaNac, $edad, '$telefono', '$celular', '$genero',
                                 '$NSS', $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, '$comentario', '$empresa', $idTipoCaptado, '$email',
                                  $usuarioId, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                  $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $altaProspectadorId, $desarrolloId
                                 )";
           // echo $query;
           // exit();
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           return $id;
       }

       public function actualizarProspecto($nombre, $aPaterno, $aManterno, $RFC, $fechaNac, $edad, $telefono, $celular, $genero,
                                          $NSS, $montoCredito, $tipoCreditoId, $subsidio, $puntosHasta, $comentario, $empresa, $idTipoCaptado, $email,
                                          $usuarioIdActualizacion, $gteProspeccionId, $gteVentasId, $prospectadorId, $agtVentasId, $estatusId,
                                          $rfcDuplicado, $periodoAsignacion, $fechaAsignacionAgt, $gerencias, $desarrolloId, $idDatoProspecto){
           $db = JFactory::getDBO();
           $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
           $fechaNac = ($fechaNac!="") ?"'".$fechaNac."'" :"NULL";

           $query = "UPDATE $tbl_sasfe_datos_prospectos SET nombre='$nombre', aPaterno='$aPaterno', aManterno='$aManterno', RFC='$RFC', fechaNac=$fechaNac, edad=$edad, telefono='$telefono', celular='$celular', genero='$genero',
                                 NSS='$NSS', montoCredito=$montoCredito, tipoCreditoId=$tipoCreditoId, subsidio=$subsidio, puntosHasta=$puntosHasta, comentario='$comentario', empresa='$empresa', idTipoCaptado=$idTipoCaptado, email='$email',
                                 usuarioIdActualizacion=$usuarioIdActualizacion, gteProspeccionId=$gteProspeccionId, gteVentasId=$gteVentasId, prospectadorId=$prospectadorId, agtVentasId=$agtVentasId, estatusId=$estatusId,
                                 duplicado=$rfcDuplicado, periodoAsignacion=$periodoAsignacion, fechaAsignacionAgt=$fechaAsignacionAgt, idRepDir=$gerencias, desarrolloId=$desarrolloId
                     WHERE idDatoProspecto=$idDatoProspecto ";
           // echo $query;
           // exit();
           $db->setQuery($query);
           $db->query();
       }

       public function obtenerDatosProspecto($idDatoProspecto){
           if($idDatoProspecto>0){
            $db = JFactory::getDbo();
            $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
            $tbl_sasfe_departamentos = $db->getPrefix().'sasfe_departamentos';
            $tbl_sasfe_fraccionamientos = $db->getPrefix().'sasfe_fraccionamientos';
            // $query = "
            //             SELECT *
            //             FROM $tbl_sasfe_datos_prospectos
            //             WHERE idDatoProspecto=$idDatoProspecto
            //           ";

            $query = "
                      SELECT a.*, b.numero as numeroDpto, c.nombre as nFracc
                      FROM $tbl_sasfe_datos_prospectos AS a
                      LEFT JOIN $tbl_sasfe_departamentos AS b ON b.idDepartamento=a.departamentoId
                      LEFT JOIN $tbl_sasfe_fraccionamientos AS c ON c.idFraccionamiento=b.fraccionamientoId
                      WHERE a.idDatoProspecto=$idDatoProspecto
                    ";
            $db->setQuery($query);
            $db->query();
            $rows = $db->loadObjectList();
          }else{
                $rows = array();
          }

          return $rows;
       }

       //Inserta el evento
       public function insertarEvento($datoProspectoId, $ev_comentario, $opcionId, $ev_fechahora, $ev_tiempo, $ev_tipoevento, $usuarioId, $fechaCreacion){
           $db = JFactory::getDBO();
           $tbl_sasfe_movimientosprospecto = $db->getPrefix().'sasfe_movimientosprospecto';
           $ev_fechahora = ($ev_fechahora!="") ?"'".$ev_fechahora."'" :"NULL";

           $query = "INSERT INTO $tbl_sasfe_movimientosprospecto (
                                 datoProspectoId, comentario, opcionId, fechaHora, tiempoRecordatorioId, tipoEventoId,
                                 usuarioId, fechaCreacion
                                 )
                          VALUES ($datoProspectoId, '$ev_comentario', $opcionId, $ev_fechahora, $ev_tiempo, $ev_tipoevento,
                                  $usuarioId, '$fechaCreacion'
                                 )";
           // echo $query;
           // exit();
           $db->setQuery($query);
           $db->query();
           $id = $db->insertid();

           return $id;
       }

      //Verificar el rfc en db del prospectador
      public function comprobarRFCDB($rfc, $idGte){
        $db = JFactory::getDBO();
        $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

        //$query = "SELECT * FROM $tbl_sasfe_datos_prospectos WHERE RFC='$rfc' ";
        $query = "SELECT * FROM $tbl_sasfe_datos_prospectos WHERE RFC  LIKE '%$rfc%' AND (gteVentasId IS NOT NULL OR gteProspeccionId IS NOT NULL) ";
        $db->setQuery($query);
        $db->query();
        $rows = $db->loadObjectList();
        $options = array();
        $valor = array();
        if(count($rows) > 0){
          $parametro = 0;
          //Comprobar si son gerencias diferentes
          $query1 = "SELECT * FROM $tbl_sasfe_datos_prospectos WHERE RFC  LIKE '%$rfc%' AND (gteVentasId IS NOT NULL OR gteProspeccionId IS NOT NULL)  AND gteVentasId != $idGte ";
          $db->setQuery($query1);
          $db->query();
          $result1 = $db->loadObjectList();
          if(count($result1)>0){ //Estara duplicado en 2 o mas gerencias
            $parametro = 1;
          }else{
            $query2 = "SELECT * FROM $tbl_sasfe_datos_prospectos WHERE RFC  LIKE '%$rfc%' AND (gteVentasId IS NOT NULL OR gteProspeccionId IS NOT NULL)  AND gteProspeccionId != $idGte ";
            $db->setQuery($query2);
            $db->query();
            $result2 = $db->loadObjectList();
            if(count($result2)>0){ //Estara duplicado en 2 o mas gerencias
              $parametro = 1;
            }
          }
          $valor = array('parametro'=>$parametro);
        }
        //print_r($rows);
        return $valor;
      }

      //Actualizar el asesor o agente de ventas y cambiar estatus
      public function agregarAgtVentasOAsesor($agtVentasId, $estatusId, $comentarioAsignar, $periodoAsignacion, $usuarioIdActualizacion, $gteProspeccionId, $duplicado, $fechaAsignacionAgt, $idDatoProspecto){
         $db = JFactory::getDBO();
         $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

         $query = "UPDATE $tbl_sasfe_datos_prospectos SET agtVentasId=$agtVentasId, estatusId=$estatusId, comentarioAsignar='$comentarioAsignar',
                   periodoAsignacion=$periodoAsignacion, usuarioIdActualizacion=$usuarioIdActualizacion, gteProspeccionId=$gteProspeccionId,
                   duplicado='$duplicado', fechaAsignacionAgt=$fechaAsignacionAgt
                   WHERE idDatoProspecto=$idDatoProspecto ";
         // echo $query;
         $db->setQuery($query);
         $db->query();
      }

      //Asignar un gerente de ventas a prospectos
      public function asignarGteVentaAProspectos($usuarioIdActualizacion, $gteProspeccionId, $gteVentasId, $duplicado, $periodoAsignacion, $comentarioAsignar, $idDatoProspecto){
         $db = JFactory::getDBO();
         $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

         $query = "UPDATE $tbl_sasfe_datos_prospectos SET usuarioIdActualizacion=$usuarioIdActualizacion, gteProspeccionId=$gteProspeccionId, gteVentasId=$gteVentasId,
                   duplicado='$duplicado', periodoAsignacion=$periodoAsignacion, comentarioAsignar='$comentarioAsignar'
                   WHERE idDatoProspecto=$idDatoProspecto ";
         // echo $query;
         $db->setQuery($query);
         $db->query();
      }

      //Salvar la fecha e id de la proteccion
      public function actFechaProteccionProspecto($idTiempoProteccion, $fechaProteccion, $idDatoProspecto){
         $db = JFactory::getDBO();
         $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

         $query = "UPDATE $tbl_sasfe_datos_prospectos SET idTiempoProteccion=$idTiempoProteccion, fechaProteccion='$fechaProteccion'
                   WHERE idDatoProspecto=$idDatoProspecto ";
         // echo $query;
         $db->setQuery($query);
         $db->query();
      }

      //Salvar la fecha limite de apartado y el id del departamento
      public function apartarDepartamento($departamentoId, $fechaLimiteApartado, $idDatoProspecto){
         $db = JFactory::getDBO();
         $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

         $query = "UPDATE $tbl_sasfe_datos_prospectos SET departamentoId=$departamentoId, fechaLimiteApartado='$fechaLimiteApartado'
                   WHERE idDatoProspecto=$idDatoProspecto ";
         // echo $query;
         $db->setQuery($query);
         $db->query();
      }

      //Resetear el apartado de la propiedad
      //Limpiar los campos departamentoId, fechaLimiteApartado por el id del prospectador
      public function resetProspectadorPorIdProspecto($idDatoProspecto){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';

        $query = "
                 UPDATE $tbl_sasfe_datos_prospectos SET departamentoId=NULL, fechaLimiteApartado=NULL
                 WHERE idDatoProspecto=$idDatoProspecto";
        $db->setQuery($query);
        $db->query();
        $row = $db->getAffectedRows();
        $result = ($row>0) ? 1 : 0;

        return $result;
      }

      //Metodo para salvar los ids departamentos asigandos previamente
      public function agregarIdsDptosPorIdProspecto($objDatosProspecto, $departamentoId, $idDatoProspecto){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
        $arrIdsDptos = array();
        if(count($objDatosProspecto)>0 && $departamentoId!=""){
          $deptosPreasignaron = $objDatosProspecto[0]->deptosPreasignaron;
          $arrIdsDptos = explode(",", $deptosPreasignaron);
          array_push($arrIdsDptos, $departamentoId);
          $arrIdsDptos = array_filter($arrIdsDptos);
          // sort($arrIdsDptos);
        }
        if(count($arrIdsDptos)>0){
          $idsDptos = implode(",",$arrIdsDptos);
          $query = "
                 UPDATE $tbl_sasfe_datos_prospectos SET deptosPreasignaron='$idsDptos' WHERE idDatoProspecto=$idDatoProspecto";
          $db->setQuery($query);
          $db->query();
          // $row = $db->getAffectedRows();
          // $result = ($row>0) ? 1 : 0;
          // return $result;
        }
      }
      //Asignar departamento de forma permanente
      public function asigarDptoPermanente($fechaDptoAsignado, $idDatoProspecto){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
        $query = "
                 UPDATE $tbl_sasfe_datos_prospectos SET fechaLimiteApartado=NULL, fechaDptoAsignado='$fechaDptoAsignado'
                 WHERE idDatoProspecto=$idDatoProspecto";
        $db->setQuery($query);
        $db->query();
        $row = $db->getAffectedRows();
        $result = ($row>0) ? 1 : 0;
        return $result;
      }

      //Marcar como no proceder con la asignacion de departamento
      public function noProcederAsigarDpto($comentarioNoProcesados, $fechaNoProcede, $idDatoProspecto){
        $db = JFactory::getDbo();
        $tbl_sasfe_datos_prospectos = $db->getPrefix().'sasfe_datos_prospectos';
        $query = "
                 UPDATE $tbl_sasfe_datos_prospectos SET idNoProcesados=1, comentarioNoProcesados='$comentarioNoProcesados', fechaNoProcede='$fechaNoProcede'
                 WHERE idDatoProspecto=$idDatoProspecto";
        $db->setQuery($query);
        $db->query();
        $row = $db->getAffectedRows();
        $result = ($row>0) ? 1 : 0;
        return $result;
      }*/
}

?>
