<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatconfiguraciones extends JControllerForm {

    function cancel()
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=catalogos');
    }

    /*function apply(){
      $this->procesarPorcentaje();
    }
    function save()
    {
        $this->procesarPorcentaje();
    }*/

    /*public function procesarPorcentaje(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = JModelLegacy::getInstance('Catporcentajes', 'SasfeModel');  //leer el modelo correspondiente
        $arrPct = array();
        //obtener valores del formulario

        //ASESOR
        //Apartado asesor sin preventa
        $idPorcentajeASPApar = JRequest::getVar('idPorcentajeASPApar');
        $pctUnoASPApar = JRequest::getVar('pctUnoASPApar');
        $pctDosASPApar = JRequest::getVar('pctDosASPApar');
        $arrPct[1] = (object)array("idPorcentaje"=>$idPorcentajeASPApar, "pctUno"=>$pctUnoASPApar, "pctDos"=>$pctDosASPApar);

        //Escritura asesor sin preventa
        $idPorcentajeASPEsc = JRequest::getVar('idPorcentajeASPEsc');
        $pctUnoASPEsc = JRequest::getVar('pctUnoASPEsc');
        $pctDosASPEsc = JRequest::getVar('pctDosASPEsc');
        $arrPct[2] = (object)array("idPorcentaje"=>$idPorcentajeASPEsc, "pctUno"=>$pctUnoASPEsc, "pctDos"=>$pctDosASPEsc);

        //Liquidacion asesor sin preventa
        $idPorcentajeASPLiq = JRequest::getVar('idPorcentajeASPLiq');
        $pctUnoASPLiq = JRequest::getVar('pctUnoASPLiq');
        $pctDosASPLiq = JRequest::getVar('pctDosASPLiq');
        $arrPct[3] = (object)array("idPorcentaje"=>$idPorcentajeASPLiq, "pctUno"=>$pctUnoASPLiq, "pctDos"=>$pctDosASPLiq);

        //Apartado asesor con preventa
        $idPorcentajeACPApar = JRequest::getVar('idPorcentajeACPApar');
        $pctUnoACPApar = JRequest::getVar('pctUnoACPApar');
        $pctDosACPApar = JRequest::getVar('pctDosACPApar');
        $arrPct[4] = (object)array("idPorcentaje"=>$idPorcentajeACPApar, "pctUno"=>$pctUnoACPApar, "pctDos"=>$pctDosACPApar);

        //Escritura asesor con preventa
        $idPorcentajeACPEsc = JRequest::getVar('idPorcentajeACPEsc');
        $pctUnoACPEsc = JRequest::getVar('pctUnoACPEsc');
        $pctDosACPEsc = JRequest::getVar('pctDosACPEsc');
        $arrPct[5] = (object)array("idPorcentaje"=>$idPorcentajeACPEsc, "pctUno"=>$pctUnoACPEsc, "pctDos"=>$pctDosACPEsc);

        //Liquidacion asesor con preventa
        $idPorcentajeACPLiq = JRequest::getVar('idPorcentajeACPLiq');
        $pctUnoACPLiq = JRequest::getVar('pctUnoACPLiq');
        $pctDosACPLiq = JRequest::getVar('pctDosACPLiq');
        $arrPct[6] = (object)array("idPorcentaje"=>$idPorcentajeACPLiq, "pctUno"=>$pctUnoACPLiq, "pctDos"=>$pctDosACPLiq);


        //PROSPECTADOR
        //Apartado sin preventa prospectador
        $idPorcentajePSApar = JRequest::getVar('idPorcentajePSApar');
        $pctUnoPSApar = JRequest::getVar('pctUnoPSApar');
        $pctDosPSApar = JRequest::getVar('pctDosPSApar');
        $arrPct[7] = (object)array("idPorcentaje"=>$idPorcentajePSApar, "pctUno"=>$pctUnoPSApar, "pctDos"=>$pctDosPSApar);

        //Escritura sin preventa prospectador
        $idPorcentajePSEsc = JRequest::getVar('idPorcentajePSEsc');
        $pctUnoPSEsc = JRequest::getVar('pctUnoPSEsc');
        $pctDosPSEsc = JRequest::getVar('pctDosPSEsc');
        $arrPct[8] = (object)array("idPorcentaje"=>$idPorcentajePSEsc, "pctUno"=>$pctUnoPSEsc, "pctDos"=>$pctDosPSEsc);

        //Apartado con preventa prospectador
        $idPorcentajePCApar = JRequest::getVar('idPorcentajePCApar');
        $pctUnoPCApar = JRequest::getVar('pctUnoPCApar');
        $pctDosPCApar = JRequest::getVar('pctDosPCApar');
        $arrPct[9] = (object)array("idPorcentaje"=>$idPorcentajePCApar, "pctUno"=>$pctUnoPCApar, "pctDos"=>$pctDosPCApar);

        //Escritura con preventa prospectador
        $idPorcentajePEsc = JRequest::getVar('idPorcentajePEsc');
        $pctUnoPCEsc = JRequest::getVar('pctUnoPCEsc');
        $pctDosPCEsc = JRequest::getVar('pctDosPCEsc');
        $arrPct[10] = (object)array("idPorcentaje"=>$idPorcentajePEsc, "pctUno"=>$pctUnoPCEsc, "pctDos"=>$pctDosPCEsc);

        foreach($arrPct as $elem){
            $model->actualizarDatosPorcentaje($elem->pctUno, $elem->pctDos, $elem->idPorcentaje);
        }

        $msg = JText::sprintf('Registro salvado correctamente.');
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');

        switch ($task) {
            case "apply":
                $this->setRedirect( 'index.php?option=com_sasfe&view=catporcentajes', $msg);
                break;
            case "save":
                $this->setRedirect( 'index.php?option=com_sasfe&view=catalogos',$msg);
                break;
        }
    }*/

}

?>
