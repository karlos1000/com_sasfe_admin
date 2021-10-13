<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerExpdigitales extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe');
    }

    function delete(){
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $idsProspectos = JRequest::getVar('cid', array(), '', 'array');
        // Sanitize the input
        JArrayHelper::toInteger($idsProspectos);
        //print_r($idsProspectos);

        $model = JModelLegacy::getInstance('expdigitales', 'SasfeModel');
        $result = $model->removerProspectoPorId($idsProspectos);

        // echo '<pre>'; print_r($result); echo '</pre>';

       if(count($result['resultDel'])>0){
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $text = JText::sprintf($msn);
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales', $text);
        }
        else{
            $text = JText::sprintf('Registro no eliminado');
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales', $text);
        }
    }

    //Borrar expdigitales repetidos
    function borrarProsRepetidos(){
         // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = JModelLegacy::getInstance('expdigitales', 'SasfeModel');
        $idsProspectos = JRequest::getVar('cid', array(), '', 'array');
        $borrar_prosp = JRequest::getVar('borrar_prosp');

        //Borrar varios expdigitales
        if($borrar_prosp==0){
            // Sanitize the input
            JArrayHelper::toInteger($idsProspectos);
            $result = $model->removerProspectoPorId($idsProspectos);
        }else{
            //Borrar uno por uno
            $result = $model->removerProspectoPorId(array($borrar_prosp));
        }

        if(count($result['resultDel'])>0){
            $msn = (count($result['resultDel'])>1) ? 'Registros eliminados' : 'Registro eliminado';
            $text = JText::sprintf($msn);
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales&layout=repetidos', $text);
        }
        else{
            $text = JText::sprintf('Registro no eliminado');
            $this->setRedirect('index.php?option=com_sasfe&view=expdigitales&layout=repetidos', $text);
        }
    }


    function cancelrepetidos()
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=expdigitales');
    }

    //Ir a la vista de expdigitales repetidos
    public function repetidos(){
        $this->setRedirect( 'index.php?option=com_sasfe&view=expdigitales&layout=repetidos');
    }

    //Ir a la vista de expdigitales no procesados
    public function noprocesados(){
        $this->setRedirect( 'index.php?option=com_sasfe&view=expdigitales&layout=noprocesados');
    }

    // Imp. Buscar enlace
    public function buscarenlacedigital(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel'); //leer el modelo correspondiente

        $idProspecto = (isset($_POST['idProspecto']) && $_POST['idProspecto']>0) ?$_POST['idProspecto'] :0;
        $idDatoGeneral = (isset($_POST['idDatoGeneral']) && $_POST['idDatoGeneral']>0) ?$_POST['idDatoGeneral'] :0;
        $arr = array("result"=>false);
        // print_r($_POST);
        // exit();

        $datosEnlace = $modelGM->obtEnlaceDigitalPorIdPCDB($idProspecto, $idDatoGeneral);
        if($datosEnlace){
            $arr = array("result"=>true, "datosEnlace"=>$datosEnlace);
        }

        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($arr);
        exit();
    }

    // Imp. 13/10/21, Carlos
    // Nota: Todos los idClientes del CRM antes del modulo prospectos no tendran el dato "datoProspectoId" en la tabla sasfe_enlaces_digitales
    public function agregarenlace(){
        jimport('joomla.filesystem.file');
        require_once(JPATH_COMPONENT.'/helpers/sasfehp.php' );
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel'); //leer el modelo correspondiente
        // $dTZone = SoulapphpHelper::obtDateTimeZone();
        $idEnlace = (isset($_POST['idEnlace']) && $_POST['idEnlace']>0) ?$_POST['idEnlace'] :0;
        $idProspecto = (isset($_POST['idProspecto']) && $_POST['idProspecto']>0) ?$_POST['idProspecto'] :0;
        $idDatoGeneral = (isset($_POST['idDatoGeneral']) && $_POST['idDatoGeneral']>0) ?$_POST['idDatoGeneral'] :0;
        $tipoEnlace = (isset($_POST['tipoEnlace']) && $_POST['tipoEnlace']>0) ?$_POST['tipoEnlace'] :0;
        $link = (isset($_POST['link']) && $_POST['link']!="") ?$_POST['link'] :"";
        $vistaInterna = (isset($_POST['vistaInterna']) && $_POST['vistaInterna']!="") ?$_POST['vistaInterna'] :0;
        $arr = array("result"=>false);
        $datosEnlace = array();
        // print_r($_POST);
        // exit();

        if($idEnlace>0){ //Actualiza
            if($vistaInterna>0){ // Si se invoca desde la edicion de prospectos o CRM
                $res = $modelGM->actEnlaceDigitalInternoDB($idEnlace, $idProspecto, $idDatoGeneral, $tipoEnlace, $link);

                if($vistaInterna==1 && $res>0){ // Si se invoca desde la edicion de prospectos
                    $datosEnlace = $modelGM->obtEnlaceDigitalPorIdPCDB($idProspecto, 0);
                }
                if($vistaInterna==2 && $res>0){ // Si se invoca desde la edicion de CRM
                    $datosEnlace = $modelGM->obtEnlaceDigitalPorIdPCDB($idProspecto, $idDatoGeneral);
                }
            }else{
                $res = $modelGM->actEnlaceDigitatDB($idEnlace, $idProspecto, $idDatoGeneral, $tipoEnlace, $link);
            }
        }else{ //Crear
            $res = $modelGM->insEnlaceDigitatDB($idProspecto, $idDatoGeneral, $tipoEnlace, $link);

            if($vistaInterna>0){ // Si se invoca desde la edicion de prospectos o CRM
                if($vistaInterna==1 && $res>0){ // Si se invoca desde la edicion de prospectos o CRM
                    $datosEnlace = $modelGM->obtEnlaceDigitalPorIdPCDB($idProspecto, 0);
                }
                if($vistaInterna==2 && $res>0){ // Si se invoca desde la edicion de CRM
                    $datosEnlace = $modelGM->obtEnlaceDigitalPorIdPCDB($idProspecto, $idDatoGeneral);
                }
            }
        }

        if($res){
            $arr = array("result"=>true, "datosEnlace"=>$datosEnlace);
        }
        // exit();

        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($arr);
        exit();
    }

}

?>
