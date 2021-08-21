<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSmsmensaje extends JControllerForm {
                     
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=smsmensajes');
    }
    
    public function add($key=NULL){           
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0; 
        
       $this->setRedirect( 'index.php?option=com_sasfe&view=smsmensaje&layout=edit&id='.$id.' ');                     
    }
    
    public function edit($key=NULL, $urlVar=NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=smsmensaje&layout=edit&id='.$id.' ');               
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
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('smsmensaje', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        // Initialise variables.
        $user = JFactory::getUser();
        
        $arrForm = JRequest::get( 'post' ); //lee todas las variables por post
        // $file = JRequest::getVar('fracc_imagen', null, 'files', 'array'); 
        // $save_folder = JPATH_SITE.'/media/com_sasfe/upload_files/'; 
        
        //obtener valores del formulario       
        $titulo = JRequest::getVar('mensaje_titulo');
        $texto = JRequest::getVar('mensaje_texto');        
        $activo = (JRequest::getVar('mensaje_activo')!='') ? JRequest::getVar('mensaje_activo') : '0';
        $tipoId = JRequest::getVar('tipoId');
        $idUrl = JRequest::getVar('check_un');        

       // echo '$titulo: ' .$titulo. '<br/>'; 
       // echo '$texto: ' .$texto. '<br/>';
       // echo '$activo: ' .$activo. '<br/>';        
       // echo '$tipoId: ' .$tipoId. '<br/>';        
       // echo '$idUrl: ' .$idUrl;
       // exit();
               
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarMensaje($titulo, $texto, $activo, $tipoId, $fechaHora);
             // echo 'El id creado es: ' .$id;           
        }else{
            $model->actualizarMensaje($titulo, $texto, $activo, $tipoId, $idUrl);
        }
        // exit();
                
        $msg = JText::sprintf('Registro salvado correctamente.');                        
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
                
        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=smsmensaje&layout=edit&id='.$idoption.' ', $msg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=smsmensajes',$msg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=smsmensaje&layout=edit&id=0',$msg);           
                break;
        }
    }
                
}

?>
