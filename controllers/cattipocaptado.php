<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerCattipocaptado extends JControllerForm {
                     
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=cattipocaptados');
    }
    
    public function add($key=NULL){           
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0; 
        
       $this->setRedirect( 'index.php?option=com_sasfe&view=cattipocaptado&layout=edit&id='.$id.' ');                     
    }
    
    public function edit($key=NULL, $urlVar=NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=cattipocaptado&layout=edit&id='.$id.' ');               
    }        
    
    function apply(){
      $this->procesarTipoCaptado();         
    }
    function save($key=NULL, $urlVar=NULL)
    {  
     $this->procesarTipoCaptado();                
    }
    
    function saveandnew(){        
        $this->procesarTipoCaptado();       
    }
    
    public function procesarTipoCaptado(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('Cattipocaptado', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        // Initialise variables.
        $user = JFactory::getUser();
        
        $arrForm = JRequest::get( 'post' ); //lee todas las variables por post
        // $file = JRequest::getVar('fracc_imagen', null, 'files', 'array'); 
        // $save_folder = JPATH_SITE.'/media/com_sasfe/upload_files/'; 
        
        //obtener valores del formulario       
        $nombre = JRequest::getVar('tipocaptado_nombre');        
        $activo = (JRequest::getVar('tipocaptado_activo')!='') ? JRequest::getVar('tipocaptado_activo') : '0';
        $idUrl = JRequest::getVar('check_un');        

       // echo '$nombre: ' .$nombre. '<br/>';
       // echo '$activo: ' .$activo. '<br/>';        
       // echo '$idUrl: ' .$idUrl;
       // exit();
               
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarTipoCaptado($nombre, $activo);
             echo 'El id creado es: ' .$id;           
        }else{
            $model->actualizarTipoCaptado($nombre, $activo, $idUrl);
        }        
                
        $msg = JText::sprintf('Registro salvado correctamente.');                        
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
                
        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=cattipocaptado&layout=edit&id='.$idoption.' ', $msg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=cattipocaptados',$msg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=cattipocaptado&layout=edit&id=0',$msg);           
                break;
        }
    }
                
}

?>
