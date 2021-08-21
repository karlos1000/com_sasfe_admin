<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class SasfeControllerSmspromocion extends JControllerForm {
                     
    function cancel($key=NULL)
    {         
        $this->setRedirect( 'index.php?option=com_sasfe&view=smspromociones');
    }
    
    public function add($key=NULL){           
       $varid = JRequest::getVar('id');
       $id = ($varid!='') ? $varid : 0; 
        
       $this->setRedirect( 'index.php?option=com_sasfe&view=smspromocion&layout=edit&id='.$id.' ');                     
    }
    
    public function edit($key=NULL, $urlVar=NULL){
        $cid = JRequest::getVar('cid', array(0));
        JArrayHelper::toInteger($cid);
        $id = (JRequest::getVar('id')!='') ? JRequest::getVar('id'): $cid[0];        
        
        $this->setRedirect( 'index.php?option=com_sasfe&view=smspromocion&layout=edit&id='.$id.' ');               
    }        
    
    function apply(){
      $this->procesarPromocion();         
    }
    function save($key=NULL, $urlVar=NULL)
    {  
     $this->procesarPromocion();                
    }
    
    function saveandnew(){        
        $this->procesarPromocion();       
    }
    
    public function procesarPromocion(){
        jimport('joomla.filesystem.file');
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora

        //leer el modelo correspondiente
        $model = JModelLegacy::getInstance('smspromocion', 'SasfeModel');
        $modelGM = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        // Initialise variables.
        $user = JFactory::getUser();
        
        $arrForm = JRequest::get( 'post' ); //lee todas las variables por post
        // $file = JRequest::getVar('fracc_imagen', null, 'files', 'array'); 
        // $save_folder = JPATH_SITE.'/media/com_sasfe/upload_files/'; 
        
        //obtener valores del formulario       
        $titulo = JRequest::getVar('mensaje_titulo');
        $texto = JRequest::getVar('promocion_texto');        
        $activo = (JRequest::getVar('promocion_activo')!='') ? JRequest::getVar('promocion_activo') : '0';
        $idUrl = JRequest::getVar('check_un');        

       // echo '$texto: ' .$texto. '<br/>';
       // echo '$activo: ' .$activo. '<br/>';        
       // echo '$idUrl: ' .$idUrl;
       // exit();
               
        //si $idUrl=0 se crea un nuevo nuevo registro de lo contrario se edita
        if($idUrl==0){                                              
             $id = $model->insertarPromocion($titulo, $texto, $activo, $fechaHora);
             // echo 'El id creado es: ' .$id;           
        }else{
            $model->actualizarPromocion($titulo, $texto, $activo, $idUrl);
        }
        // exit();
                
        $msg = JText::sprintf('Registro salvado correctamente.');                        
        $idoption = ($idUrl==0) ? $id: $idUrl;        
        $jinput = JFactory::getApplication()->input;
        $task = $jinput->get('task');
                
        switch ($task) {
            case "apply":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=smspromocion&layout=edit&id='.$idoption.' ', $msg); 
                break;
            case "save":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=smspromociones',$msg);                          
                break;
            case "saveandnew":                
                $this->setRedirect( 'index.php?option=com_sasfe&view=smspromocion&layout=edit&id=0',$msg);           
                break;
        }
    }
                
}

?>
