<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCatgtedepto extends JControllerForm {

    function cancel($key = NULL)
    {
        $id_cat = JRequest::getVar('dato_cat_catId');
        $this->setRedirect( 'index.php?option=com_sasfe&view=catgtedeptos');
    }

    public function add(){
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0;
       $this->setRedirect( 'index.php?option=com_sasfe&view=catgtedepto&layout=edit&id='.$id);
    }

    public function edit($key = NULL, $urlVar = NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];

        $this->setRedirect( 'index.php?option=com_sasfe&view=catgtedepto&layout=edit&id='.$id);
    }

    function apply(){
      $this->procesarDatosGteDepto();
    }
    function save($key = NULL, $urlVar = NULL)
    {
     $this->procesarDatosGteDepto();
    }

    function saveandnew(){
        $this->procesarDatosGteDepto();
    }

    public function procesarDatosGteDepto(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = JModelLegacy::getInstance('Catgtedepto', 'SasfeModel');  //leer el modelo correspondiente
        // $arrForm = JRequest::get( 'post' ); //lee todas las variables por post

        $gteVentasId = JRequest::getVar('usuarioIdGteVenta');
        $departamentosId = (JRequest::getVar('idsDeptos')!='') ? JRequest::getVar('idsDeptos') : '';
        $idUrl = JRequest::getVar('check_un');

        // echo "gteVentasId: ".$gteVentasId."<br/>";
        // echo "departamentosId: ".$departamentosId."<br/>";
        // exit();
        // echo "<pre>";
        // print_r($arrForm);
        // echo "</pre>";
        // exit();

        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){
             $id = $model->insertarGteDepto($gteVentasId, $departamentosId);
        }else{
            $model->actualizarGteDepto($gteVentasId, $departamentosId, $idUrl);
        }

        $msg = JText::sprintf('Registro salvado correctamente.');
        $idoption = ($idUrl==0) ? $id: $idUrl;
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');

        switch ($task) {
            case "apply":
                $this->setRedirect( 'index.php?option=com_sasfe&view=catgtedepto&layout=edit&id='.$idoption, $msg);
                break;
            case "save":
                $this->setRedirect( 'index.php?option=com_sasfe&view=catgtedeptos',$msg);
                break;
            case "saveandnew":
                $this->setRedirect( 'index.php?option=com_sasfe&view=catgtedeptos&layout=edit&id=0',$msg);
                break;
        }
    }

}

?>
