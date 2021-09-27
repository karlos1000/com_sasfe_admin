<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerFechasdtu extends JControllerForm {

    function cancel($key = NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }


    function dtuFechasMasivo(){
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $arrDateTime = SasfehpHelper::obtDateTimeZone();
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $fecha = JRequest::getVar('fecha_dtu');
        $idsDatoGeneral = JRequest::getVar('idsDatoGeneral'); //Imp. 27/09/21, Se cambio por el id de los departamentos
        $idFracc = JRequest::getVar('idFracc');

        if($idsDatoGeneral!=""){
           // $resp = $modelGM->ActBatchFechasDTU(SasfehpHelper::conversionFecha($fecha), $idsDatoGeneral);
           $resp = $modelGM->ActBatchFechasDTU2(SasfehpHelper::conversionFecha($fecha), $idsDatoGeneral);
           if($resp){
                $this->setRedirect( 'index.php?option=com_sasfe&view=fechasdtu&idFracc='.$idFracc, "La(s) fecha(s) DTU se han actualizado correctamente.");
           }else{
                $this->setRedirect( 'index.php?option=com_sasfe&view=fechasdtu&idFracc='.$idFracc, "La(s) fecha(s) DTU no fueron actualizado(s).");
           }
        }else{
            $this->setRedirect( 'index.php?option=com_sasfe&view=fechasdtu&idFracc='.$idFracc, "La(s) fecha(s) DTU no fueron actualizado(s).");
        }

        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // exit();
    }
}

?>
