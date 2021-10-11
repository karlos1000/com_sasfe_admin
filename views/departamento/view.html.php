<?php

defined('_JEXEC') or die;

class  SasfeViewDepartamento extends JViewLegacy
{
	public function display($tpl = null)
	{
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                    JError::raiseError(500, implode("\n", $errors));
                    return false;
            }
            $this->user = JFactory::getUser();
            $this->groups = JAccess::getGroupsByUser($this->user->id, true); //obtiene grupo/s por id de usuario
            $arrGroups = array(8,10,11,12,13,14,15,16);
            $this->tmpGroup = array();

            $img = JURI::root().'media/com_sasfe/images/loading_transparent.gif';
            $this->imgLoading = "<div><img alt='Loading...' src='$img' style='width:12px;'></div>";

            foreach($this->groups as $item){
                if(in_array($item, $arrGroups)){
                    $this->tmpGroup[] = ($item==8) ? 10 : $item;
                }
            }

            if(in_array("10", $this->groups)){
                $this->gruopID = 10;
            }
            if(in_array("11", $this->groups)){
                $this->gruopID = 11;
            }
            if(in_array("12", $this->groups)){
                $this->gruopID = 12;
            }
            if(in_array("13", $this->groups)){
                $this->gruopID = 13;
            }
            if(in_array("14", $this->groups)){
                $this->gruopID = 14;
            }
            if(in_array("15", $this->groups)){
                $this->gruopID = 15;
            }
            if(in_array("8", $this->groups)){
                $this->gruopID = 10;
            }
            if(in_array("16", $this->groups)){
                $this->gruopID = 16;
            }
            //echo 'Encontrado: ' .$this->gruopID;


            JViewLegacy::loadHelper('sasfehp');
            $model = $this->getModel();
            $modelMB = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');  //leer el modelo correspondiente

            $this->id_dpt = JRequest::getVar('depto_id');
            $this->idDatoGral = JRequest::getVar('idDatoGral');
            //$this->idDatoGral = SasfehpHelper::obtIdDatoGralPorIdDpt($this->id_dpt); //Comprobar si se actualiza o se crea
            $this->precioViv = SasfehpHelper::obtPrecioVivienda($this->id_dpt); //Precio de la vivienda

            if($this->idDatoGral>0){
                $this->data = $model->obtDatosByDpt($this->idDatoGral); //Obtener los generales por su id dpto
                $this->dataCustomer = $model->obtDatosClientePorIdDatoGral($this->idDatoGral); //Obtener los datos del cliente
                $this->dataCredit = $model->obtDatosCdtoPorIdDatoGral($this->idDatoGral); //Obtener los datos credito
                $this->dataNomina = $model->obtDatosNominaPorIdDatoGral($this->idDatoGral); //Obtener los datos credito

                if(count($this->data)>0){
                    $this->historico = $this->data[0]->esHistorico;
                }else{
                    $this->historico = 0;
                }

            }else{
                $this->data = array();
                $this->historico = 0;
            }

            $this->NumDpt = SasfehpHelper::obtNumDptPorId($this->id_dpt);
            $this->idFracc = JRequest::getVar('idFracc');

            $this->idUsuario = $this->user->id;
            // $this->fechaAcceso =date("Y-m-d H:i:s");
            $obtDateTimeZone = SasfehpHelper::obtDateTimeZone();
            $this->fechaAcceso = $obtDateTimeZone->fechaHora;

            $this->ColGteVtas = SasfehpHelper::obtColElemPorIdCatalogo(1);
            $this->ColTitulacion = SasfehpHelper::obtColElemPorIdCatalogo(2);
            $this->ColAsesores = SasfehpHelper::obtColElemPorIdCatalogo(3);
            $this->ColProspectadores = SasfehpHelper::obtColElemPorIdCatalogo(4);
            $this->ColEstatus = SasfehpHelper::obtColElemPorIdCatalogo(5);
            $this->ColMotCancel = SasfehpHelper::obtColElemPorIdCatalogo(6);
            $this->ColTiposCto = SasfehpHelper::obtColElemPorIdCatalogo(7);
            $this->ColEstados = SasfehpHelper::obtColEstadosRep();
            $this->colAcabados = $modelMB->obtTodosAcabadosFraccDB($this->idFracc);
            $this->colServicios = $modelMB->obtTodosServiciosFraccDB($this->idFracc);

            $modelPct = JModelLegacy::getInstance('Catporcentajes', 'SasfeModel');  //leer el modelo correspondiente
            $this->asesorSinPrev = $modelPct->obtPorcentajeAsesorProsSinPreventa(1,0);
            $this->asesorConPrev = $modelPct->obtPorcentajeAsesorProsSinPreventa(1,1);
            $this->prospSinPrev = $modelPct->obtPorcentajeAsesorProsSinPreventa(2,0);
            $this->prospConPrev = $modelPct->obtPorcentajeAsesorProsSinPreventa(2,1);


            $this->pctAsesores = $modelPct->obtPorcentajeAsesorProsp(1);
            $this->pctProspectadores = $modelPct->obtPorcentajeAsesorProsp(2);

            parent::display($tpl);
            $this->addToolBar();
            $this->setDocument();
	}

        protected function addToolBar()
        {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-detalleDepto {background-image: url(../media/com_sasfe/images/catalogo.png);}');
            $document->addStyleDeclaration('.icon-32-reallocate {background-image: url(../media/com_sasfe/images/catalogo.png);}');
            JToolBarHelper::title(JText::_('Detalle departamento: ' .$this->NumDpt), 'detalleDepto');

            if($this->gruopID!=16 && $this->gruopID!=11){
            if($this->historico==0){
                if($this->idDatoGral>0){
                    JToolBarHelper::custom('departamento.reallocate','reallocate','reallocate', 'Reasignar', false, false);
                }
                JToolBarHelper::apply('departamento.apply');
                JToolBarHelper::save('departamento.save');
                //JToolBarHelper::save2new('departamento.saveandnew');
                JToolBarHelper::spacer();
            }

            if($this->historico==1){
                if($this->gruopID==8 || $this->gruopID==10 || $this->gruopID==12){
                    JToolBarHelper::apply('departamento.apply');
                    JToolBarHelper::save('departamento.save');
                    JToolBarHelper::spacer();
                    }
                }
            }
            //Solo para los gerentes de venta
            //permitirles cancelar
            if($this->gruopID==11){
                JToolBarHelper::apply('departamento.apply');
            }

            JToolBarHelper::cancel('departamento.cancel');
            //submenu
            JSubMenuHelper::addEntry('Escritorio', 'index.php?option=com_sasfe', false);
            JSubMenuHelper::addEntry('Cat&aacute;logos', 'index.php?option=com_sasfe&view=catalogos', false);
            //JSubMenuHelper::addEntry('Inventario de autos nuevos', 'index.php?option=com_sasfe&view=inventorycarsnew', true);
        }

        protected function setDocument()
        {
            jimport('joomla.environment.uri');
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('Detalles departamento: ' .$this->NumDpt));
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/style.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.validate.js');
            $document->addStyleSheet(JURI::root().'media/com_sasfe/css/alertify.min.css');
            $document->addScript(JURI::root().'media/com_sasfe/js/alertify.js');

            $document->addScript(JURI::root().'media/com_sasfe/js/function.js');
			$document->addScript(JURI::root().'media/com_sasfe/js/accounting.min.js');
            $document->addScript(JURI::root().'media/com_sasfe/js/jquery.formatCurrency-1.4.0.min.js');
            $document->addScript(JURI::root() ."administrator/components/com_sasfe/views/departamento/submitbutton.js?ver=2");
            //$document->addScript(JURI::root().'administrator/components/com_sasfe/views/departamento/submitbutton.js');
        }
}
