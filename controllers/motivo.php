<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerMotivo extends JControllerForm {

    function cancel($key=NULL)
    {
        $this->setRedirect( 'index.php?option=com_sasfe&view=motivos');
    }

    public function add($key=NULL){
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0;

       $this->setRedirect( 'index.php?option=com_sasfe&view=motivo&layout=edit&id='.$id.' ');
    }

    public function edit($key=NULL, $urlVar=NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];

        $this->setRedirect( 'index.php?option=com_sasfe&view=motivo&layout=edit&id='.$id.' ');
    }

    function apply(){
      $this->procesarMotivo();
    }
    function save($key=NULL, $urlVar=NULL)
    {
     $this->procesarMotivo();
    }

    function saveandnew(){
        $this->procesarMotivo();
    }

    public function procesarMotivo(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('motivo', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        // Initialise variables.
        $user = JFactory::getUser();
        $arrForm = JRequest::get( 'post' ); //lee todas las variables por post

        //obtener valores del formulario
        $titulo = JRequest::getVar('titulo');
        $texto = JRequest::getVar('texto');
        $activo = (JRequest::getVar('activo')!='') ? JRequest::getVar('activo') : '0';
        $idUrl = JRequest::getVar('check_un');

       // echo '$texto: ' .$texto. '<br/>';
       // echo '$activo: ' .$activo. '<br/>';
       // echo '$idUrl: ' .$idUrl;
       // exit();

        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){
            // $id = $model->insertarPromocion($titulo, $texto, $activo, $fechaHora);
            $id = $model->insertarMotivo($titulo, $texto, $activo, $fechaHora);
            // echo 'El id creado es: ' .$id;
        }else{
            // $model->actualizarPromocion($titulo, $texto, $activo, $idUrl);
            $model->actualizarMotivo($titulo, $texto, $activo, $idUrl);
        }
        // exit();

        $msg = JText::sprintf('Registro salvado correctamente.');
        $idoption = ($idUrl==0) ? $id: $idUrl;
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');

        switch ($task) {
            case "apply":
                $this->setRedirect( 'index.php?option=com_sasfe&view=motivo&layout=edit&id='.$idoption.' ', $msg);
                break;
            case "save":
                $this->setRedirect( 'index.php?option=com_sasfe&view=motivos',$msg);
                break;
            case "saveandnew":
                $this->setRedirect( 'index.php?option=com_sasfe&view=motivo&layout=edit&id=0',$msg);
                break;
        }
    }

}

?>
