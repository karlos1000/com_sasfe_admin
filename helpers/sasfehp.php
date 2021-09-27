<?php
// No direct access
defined('_JEXEC') or die;

/**
 * test component helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @since		1.6
 */
class SasfehpHelper
{
    public static function obtTodosFraccionamientos(){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $todosFracc =  $model->obtTodosFraccionamientosDB();

           return $todosFracc;
    }

    public static function obtTodosFraccionamientosGerente($gerenteId){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $todosFracc =  $model->obtFraccionamientosGerenteDB($gerenteId);

      return $todosFracc;
}

    public static function obtFraccionamientoByDesarrollo($desarrollo){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $todosFracc =  $model->obtFraccionamientoByDesarrolloDB($desarrollo);

      return $todosFracc;
}

    public static function obtTodosCatGenericos(){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $todosFracc =  $model->obtTodosCatGenericosDB();

           return $todosFracc;
    }

    public static function obtCatGenericoPorId($idCatGen){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $todosFracc =  $model->obtTodosCatGenericoPorIdDB($idCatGen);

           return $todosFracc;
    }

    /***
     * Comprobar el idDatoGeneral si exite si no regresar 0
    */
    public static function obtIdDatoGralPorIdDpt($idDpt){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $collElemCat =  $model->obtIdDatoGralPorIdDptDB($idDpt);

        return $collElemCat;
    }

    /***
     * Obtener el numero de departamento por su id
    */
    public static function obtNumDptPorId($idDpt){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $collElemCat =  $model->obtNumDptPorIdDB($idDpt);

        return $collElemCat;
    }

    /***
     * Obtiene la coleccion de los elementos de los catalogos dependiendo de su id catalago
     */
    public static function obtColElemPorIdCatalogo($idCat){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $collElemCat =  $model->obtColElemPorIdCatalogoDB($idCat);

           return $collElemCat;
    }

    /***
     * Obtiene la coleccion de los estados de la republica
     */
    public static function obtColEstadosRep(){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $collElemCat =  $model->obtColEstadosRepDB();

           return $collElemCat;
    }

    /**
     * Obtiene el precio de la vivienda por su id
     */
    public static function obtPrecioVivienda($id_dpt){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $precioViv =  $model->obtPrecioviviendaDB($id_dpt);

           return $precioViv;
    }
    /**
     * Obtiene la suma total de los depositos
     */
    public static function sumaTotalDepositos($idCto){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $total =  $model->sumaTotalDepositosDB($idCto);

           return $total;
    }
    /**
     * Obtiene la suma total de los pagares
     */
    public static function sumaTotalPagares($idCto){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $total =  $model->sumaTotalPagaresDB($idCto);

           return $total;
    }
    /**
     * Obtiene la suma total de los acabados por vivienda
     */
    public static function sumaTotalAcabado($idDatoGral){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $total =  $model->sumaTotalAcabadoDB($idDatoGral);

           return $total;
    }
    /**
     * Obtiene la suma total de los servicios por vivienda
     */
    public static function sumaTotalServicios($idDatoGral){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $total =  $model->sumaTotalServiciosDB($idDatoGral);

           return $total;
    }

    /**
     * Obtiene diferencia entre total pagares pagados - total de todos pagares
    */
    public static function porPagarPagares($idDatoCto){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $total =  $model->porPagarPagaresDB($idDatoCto);

           return $total;
    }

    public static function obtCrtPermisos($idGrupo, $ver, $editar){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $total =  $model->obtCrtPermisosDB($idGrupo, $ver, $editar);

       return $total;
    }

    public static function obtSelectInactivoAP($idSel){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $arrElem =  $model->obtSelectInactivoAPDB($idSel);

       return $arrElem;
    }

    /*
     * Grid de telefonos de los clientes
     */
    public static function ObtTelsPorClienteGrid($idCliente, $historico, $ocultarEditGrid){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtTelsPorCliente($ds, $idCliente);

         $grid = new KoolGrid("telefonosclienteGrid");
         self::defineGrid($grid, $ds);
         self::defineColumn($grid, "idTelefono", "Id", false, true, 1);
         self::defineColumn($grid, "datoClienteId", "Id Cliente", false, true, 1);
         self::defineColumn($grid, "numero", "N&uacute;mero", true, false, 1);
         self::defineColumn($grid, "tipoId", "Tipo", true, false, 1);

        if($historico==0 && $ocultarEditGrid==false){
                self::defineColumnEdit($grid);
                //Show Function Panel
                $grid->MasterTable->ShowFunctionPanel = true;
                //Insert Settings
                $grid->MasterTable->InsertSettings->Mode = "Form";
                $grid->MasterTable->EditSettings->Mode = "Form";
                $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGrid($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "500px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            $grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
       }

       public static function defineColumn($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
       {
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        if($name_field == 'tipoId'){
            $column = new GridDropDownColumn();
            $colTipoTels = $model->obtTodosTipoTelsDB();

            foreach($colTipoTels as $elem)
            {
               $column->AddItem($elem->nombre,$elem->idTipoTel);
            }
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator(self::GetValidator($validator));

        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEdit($grid)
       {

           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }


     /*
     * Grid de referencias de los clientes
     */
    public static function ObtRefsPorClienteGrid($idCliente, $historico, $ocultarEditGrid){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtRefsPorCliente($ds, $idCliente);

         $grid = new KoolGrid("referenciasClienteGrid");
         self::defineGridRef($grid, $ds);
         self::defineColumnRef($grid, "idReferencias", "Id", false, true, 1);
         self::defineColumnRef($grid, "datoClienteId", "Id Cliente", false, true, 1);
         self::defineColumnRef($grid, "nombreReferencia", "Nombre", true, false, 1);
         self::defineColumnRef($grid, "telefono", "Tel&eacute;fono", true, false, 1);
         self::defineColumnRef($grid, "comentarios", "Comentarios", true, false, 0);

         if($historico==0 && $ocultarEditGrid==false){
                self::defineColumnEditRef($grid);
                //Show Function Panel
                $grid->MasterTable->ShowFunctionPanel = true;
                //Insert Settings
                $grid->MasterTable->InsertSettings->Mode = "Form";
                $grid->MasterTable->EditSettings->Mode = "Form";
                $grid->MasterTable->InsertSettings->ColumnNumber = 1;
         }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGridRef($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "500px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            $grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
       }

       public static function defineColumnRef($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
       {

        if ($name_field == 'comentarios') {
            $column = new GridTextAreaColumn();
            $column->Width = "300px";
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0){
            $column->addvalidator(self::GetValidator($validator));
        }
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEditRef($grid)
       {

           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }

     /*
     * Grid de depositos de los clientes
     */
    public static function ObtDepositosPorDptGrid($idDatoCto, $historico, $ocultarEditGrid){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtDepositosPorDpt($ds, $idDatoCto);

         $grid = new KoolGrid("depositosDptGrid");
         self::defineGridDpt($grid, $ds);
         self::defineColumnDpt($grid, "selDpt", "Sel", true);
         self::defineColumnDpt($grid, "idDeposito", "Id", false, true, 1);
         self::defineColumnDpt($grid, "datoCreditoId", "Id Dato Cr&eacute;dito", false, true, 1);
         self::defineColumnDpt($grid, "deposito", "Deposito", true, false, 1);
         self::defineColumnDpt($grid, "fecha", "Fecha", true, false, 1);
         self::defineColumnDpt($grid, "comentarios", "Comentarios", true, false, 0);

         if($historico==0 && $ocultarEditGrid==false){
                self::defineColumnEditDpt($grid);
                //Show Function Panel
                $grid->MasterTable->ShowFunctionPanel = true;
                //Insert Settings
                $grid->MasterTable->InsertSettings->Mode = "Form";
                $grid->MasterTable->EditSettings->Mode = "Form";
                $grid->MasterTable->InsertSettings->ColumnNumber = 1;
         }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGridDpt($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "750px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            //$grid->ClientSettings->ClientEvents["OnPageChange"] = "Handle_OnPageChange";
            //$grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
            $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
            $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDelete";
            $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
       }

       public static function defineColumnDpt($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
       {
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
        $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
        $scrpFolder = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar';
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $dateC = date("Y-m-d");

        if($name_field == 'selDpt') {
           $column = new GridCustomColumn();
           $column->ItemTemplate  = '<input type="checkbox" id="{idDeposito}"  class="selectCheckDpt" value="" />';
           $column->Align = "center";
        }
        elseif ($name_field == 'comentarios') {
            $column = new GridTextAreaColumn();
            $column->Width = "300px";
        }
        elseif($name_field == 'fecha'){
            $column = new GridDateTimeColumn();
            $column->Picker = new KoolDatePicker();
            $column->Picker->scriptFolder = $scrpFolder;
            $column->Picker->Localization->Load($calLangueaje);
            $column->Picker->styleFolder = "default";
            $column->Picker->DateFormat = "Y-m-d";
            $column->Picker->ShowToday = true;
            $column->DefaultValue = $dateC;
            $column->Width = "100px";
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator(self::GetValidator($validator));

        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEditDpt($grid)
       {
           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }


    /*
     * Grid de depositos de los clientes
     */
    public static function ObtPagaresPorDptGrid($idDatoCto, $historico, $ocultarEditGrid){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtPagaresPorDpt($ds, $idDatoCto);

         $grid = new KoolGrid("pagaresDptGrid");
         self::defineGridPagares($grid, $ds);
         self::defineColumnPagares($grid, "idPagare", "Id", false, true, 1);
         self::defineColumnPagares($grid, "datoCreditoId", "Id Dato Cr&eacute;dito", false, true, 1);
         self::defineColumnPagares($grid, "cantidad", "Cantidad", true, false, 1);
         self::defineColumnPagares($grid, "fechaVenc", "Fecha Venc.", true, false, 1);
         self::defineColumnPagares($grid, "estatuPagareId", "Estatus", true, false, 1);
         self::defineColumnPagares($grid, "anotaciones", "Anotaciones", true, false, 0);

         if($historico==0 && $ocultarEditGrid==false){
                self::defineColumnEditPagares($grid);
                //Show Function Panel
                $grid->MasterTable->ShowFunctionPanel = true;
                //Insert Settings
                $grid->MasterTable->InsertSettings->Mode = "Form";
                $grid->MasterTable->EditSettings->Mode = "Form";
                $grid->MasterTable->InsertSettings->ColumnNumber = 1;
         }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGridPagares($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "700px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEditPagares";
            $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDeletePagares";
            $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsertPagares";
       }

       public static function defineColumnPagares($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
       {
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
        $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
        $scrpFolder = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar';
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $dateC = date("Y-m-d");

        if ($name_field == 'anotaciones') {
            $column = new GridTextAreaColumn();
            $column->Width = "300px";
        }
        elseif($name_field == 'estatuPagareId'){
            $column = new GridDropDownColumn();
            $colEstatusPag = $model->obtTodosEstatusDB();
            foreach($colEstatusPag as $elem)
            {
               $column->AddItem($elem->nombre,$elem->idEstatusCat);
            }
        }
        elseif($name_field == 'fechaVenc'){
            $column = new GridDateTimeColumn();
            $column->Picker = new KoolDatePicker();
            $column->Picker->scriptFolder = $scrpFolder;
            $column->Picker->Localization->Load($calLangueaje);
            $column->Picker->styleFolder = "default";
            $column->Picker->DateFormat = "Y-m-d";
            $column->Picker->ShowToday = true;
            $column->DefaultValue = $dateC;
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator(self::GetValidator($validator));

        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEditPagares($grid)
       {

           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }

      /*
     * Grid de post venta
     */
    public static function ObtPostVentaPorDptGrid($idDatoGral, $historico, $ocultarEditGrid){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtPostVentaPorDpt($ds, $idDatoGral);

         $grid = new KoolGrid("postventaGrid");
         self::defineGridPostVenta($grid, $ds);
         self::defineColumnPostVenta($grid, "idDatoPV", "Id", false, true, 1);
         self::defineColumnPostVenta($grid, "datoGeneralId", "Id Dato General", false, true, 1);
         self::defineColumnPostVenta($grid, "dato", "Post Venta", true, false, 1);
         self::defineColumnPostVenta($grid, "fecha", "Fecha Reporte PV", true, false, 1);
         self::defineColumnPostVenta($grid, "atencionFecha", "Fecha Atenci&oacute;n", true, false, 1);
         self::defineColumnPostVenta($grid, "detRealizado", "Detalle Realizado", true, false, 1);
         self::defineColumnPostVenta($grid, "areaIdPV", "Area", true, false, 1);

         if($historico==0 && $ocultarEditGrid==false){
             self::defineColumnEditPostVenta($grid);
            //Show Function Panel
            $grid->MasterTable->ShowFunctionPanel = true;
            //Insert Settings
            $grid->MasterTable->InsertSettings->Mode = "Form";
            $grid->MasterTable->EditSettings->Mode = "Form";
            $grid->MasterTable->InsertSettings->ColumnNumber = 1;
         }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGridPostVenta($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "800px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            $grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
       }

       public static function defineColumnPostVenta($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
       {
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolCalendar' . DIRECTORY_SEPARATOR . 'koolcalendar.php';
        $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
        $scrpFolder = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar';
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $dateC = date("Y-m-d");

        if ($name_field == 'dato') {
            $column = new GridTextAreaColumn();
            $column->Width = "300px";
        }
        elseif($name_field == 'fecha'){
            $column = new GridDateTimeColumn();
            $column->Picker = new KoolDatePicker();
            $column->Picker->scriptFolder = $scrpFolder;
            $column->Picker->Localization->Load($calLangueaje);
            $column->Picker->styleFolder = "default";
            $column->Picker->DateFormat = "Y-m-d";
            $column->Picker->ShowToday = true;
            $column->DefaultValue = $dateC;
        }
        elseif($name_field == 'atencionFecha'){
            $column = new GridDateTimeColumn();
            $column->Picker = new KoolDatePicker();
            $column->Picker->scriptFolder = $scrpFolder;
            $column->Picker->Localization->Load($calLangueaje);
            $column->Picker->styleFolder = "default";
            $column->Picker->DateFormat = "Y-m-d";
            $column->Picker->ShowToday = true;
            $column->DefaultValue = $dateC;
        }elseif($name_field == 'areaIdPV'){
            $column = new GridDropDownColumn();
            $colAreasPV = $model->obtTodasAreasPostVentaDB();

            foreach($colAreasPV as $elem)
            {
               $column->AddItem($elem->nombre,$elem->idDato);
            }
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator(self::GetValidator($validator));

        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEditPostVenta($grid)
       {

           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }


    public static function ObtServiciosPorDptGrid($idDatoGral, $historico, $ocultarEditGrid, $idFracc){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtServiciosPorDpt($ds, $idDatoGral);

         $grid = new KoolGrid("serviciosDptGrid");
         self::defineGridServicios($grid, $ds);
         self::defineColumnServicios($grid, "idServicio", "Id", false, true, 1);
         self::defineColumnServicios($grid, "datoGeneralId", "Id Dato General", false, true, 1);
         self::defineColumnServicios($grid, "nombre", "Nombre", true, false, 1, $idFracc);
         self::defineColumnServicios($grid, "monto", "Monto", true, false, 1);
         self::defineColumnServicios($grid, "aplica", "Aplica", true, false, 1);
         self::defineColumnServicios($grid, "comentarios", "Comentarios", true, false, 0);

         if($historico==0 && $ocultarEditGrid==false){
            self::defineColumnEditServicios($grid);
            //Show Function Panel
            $grid->MasterTable->ShowFunctionPanel = true;
            //Insert Settings
            $grid->MasterTable->InsertSettings->Mode = "Form";
            $grid->MasterTable->EditSettings->Mode = "Form";
            $grid->MasterTable->InsertSettings->ColumnNumber = 1;
         }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGridServicios($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "700px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "OnRowConfirmEditServicios";
            $grid->ClientSettings->ClientEvents["OnRowDelete"] = "OnRowDeleteServicios";
            $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "OnConfirmInsertServicios";
            $grid->ClientSettings->ClientEvents["OnStartInsert"] = "Handle_startInsertServicios";
            //$grid->ClientSettings->ClientEvents["OnRowStartEdit"] = "Handle_OnRowStartEditServ";

       }

       public static function defineColumnServicios($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $idFracc=0)
       {
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');

        if ($name_field == 'comentarios') {
            $column = new GridTextAreaColumn();
            $column->Width = "300px";
        }
        elseif($name_field == 'nombre'){
            $column = new GridDropDownColumn();
            $colEstatusServicios = $model->obtTodosServiciosFraccDB($idFracc);
            foreach($colEstatusServicios as $elem)
            {
               $column->AddItem($elem->nombre,$elem->idDatoCE);
            }
        }
        else if($name_field == 'aplica'){
            $column = new GridDropDownColumn();
            $column->AddItem('SI',1);
            $column->AddItem('Base',2);
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0){
            $column->addvalidator(self::GetValidator($validator));
        }
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEditServicios($grid)
       {
           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }


    public static function ObtAcabadosPorDptGrid($idDatoGral, $historico, $ocultarEditGrid, $idFracc){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtAcabadosPorDpt($ds, $idDatoGral);

         $grid = new KoolGrid("acabadosDptGrid");
         self::defineGridAcabados($grid, $ds);
         self::defineColumnAcabados($grid, "idAcabados", "Id", false, true, 1);
         self::defineColumnAcabados($grid, "datoGeneralId", "Id Dato General", false, true, 1);
         self::defineColumnAcabados($grid, "nombre", "Nombre", true, false, 1,$idFracc);
         self::defineColumnAcabados($grid, "precio", "Precio", true, false, 1);
         self::defineColumnAcabados($grid, "estatus", "Estatus", true, false, 1);

         if($historico==0 && $ocultarEditGrid==false){
             self::defineColumnEditAcabados($grid);
            //Show Function Panel
            $grid->MasterTable->ShowFunctionPanel = true;
            //Insert Settings
            $grid->MasterTable->InsertSettings->Mode = "Form";
            $grid->MasterTable->EditSettings->Mode = "Form";
            $grid->MasterTable->InsertSettings->ColumnNumber = 1;
         }
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
       }

       public static function defineGridAcabados($grid, $ds){
            $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
            $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
            $grid->styleFolder="default";
            $grid->Width = "600px";

            //$grid->RowAlternative = true;
            $grid->AjaxEnabled = true;
            $grid->DataSource = $ds;
            $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
            $grid->Localization->Load($base);

            $grid->AllowInserting = true;
            $grid->AllowEditing = true;
            $grid->AllowDeleting = true;
            $grid->AllowSorting = true;
            $grid->ColumnWrap = true;
            $grid->CharSet = "utf8";

            //$grid->MasterTable->DataSource = $ds;
            $grid->MasterTable->AutoGenerateColumns = false;
            $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
            $grid->MasterTable->Pager->ShowPageSize = true;
            $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
            $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "OnRowConfirmEditAcabados";
            $grid->ClientSettings->ClientEvents["OnRowDelete"] = "OnRowDeleteAcabados";
            $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "OnConfirmInsertAcabados";
            $grid->ClientSettings->ClientEvents["OnStartInsert"] = "Handle_startInsert";

       }

       public static function defineColumnAcabados($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0, $idFracc=0)
       {
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');

        if($name_field == 'estatus'){
            $column = new GridDropDownColumn();
            $colEstatusAcabados = $model->obtTodosEstatusAcabadosDB();
            foreach($colEstatusAcabados as $elem)
            {
               $column->AddItem($elem->nombre,$elem->idEstatusAcabados);
            }
        }
        elseif($name_field == 'nombre'){
            $column = new GridDropDownColumn();
            $colEstatusAcabados = $model->obtTodosAcabadosFraccDB($idFracc);
            foreach($colEstatusAcabados as $elem)
            {
               $column->AddItem($elem->nombre,$elem->idDatoCE);
            }
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
            $column->addvalidator(self::GetValidator($validator));

        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
       }

       public static function defineColumnEditAcabados($grid)
       {
           $column = new GridCustomColumn();
           $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "100px";
           $column->ItemTemplate = "
                                    <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a> |
                                    <a class='kgrLinkDelete'{record_editable} onclick='grid_delete(this)' href='javascript:void 0' type='button'>Eliminar</a>
                                   ";
           $grid->MasterTable->AddColumn($column);
       }


       public static function GetValidator($type){

        switch ($type) {
            case 1: //required
                $validator = new RequiredFieldValidator();
                $validator->ErrorMessage = "Campo requerido";
                return $validator;
                break;
            }
        }


    /*
     *  Obtener todos los departamentos por id de fraccionamiento
     */
    public static function obtTodosDepartamentosPorIdFracc($idFracc){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $colDpts =  $model->obtTodosDepartamentosPorIdFraccDB($idFracc);

           return $colDpts;
    }

    /*
     *  Obtener todos los campos de datos generales por id de departamento
    */
    public static function obtDatosGralsPorIdDepartamentos($idDpt){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $datosGrales =  $model->obtDatosGralsPorIdDepartamentosDB($idDpt);

           return $datosGrales;
    }


    /*
     * Grid para cambiar las fechas del dtu "Solo aplica para el director o super usuario"
    */
    public static function ObtTodasLasFechasDTU($idFracc){
        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtTodasFechasDTU($ds, $idFracc);

         $grid = new KoolGrid("depositosDptGrid");
         self::defineGridFechaDTU($grid, $ds);
         self::defineColumnFechaDTU($grid, "idDatoGeneral", "Id", false, true, 1);
         self::defineColumnFechaDTU($grid, "numero", "Departamento", true, true, 1);
         self::defineColumnFechaDTU($grid, "fechaDTU", "Fecha DTU", true, false, 1);
         self::defineColumnFechaDTU($grid, "fechaCierre", "Fecha Cierre", true, true, 1);
         self::defineColumnFechaDTU($grid, "fechaApartado", "Fecha Apartado", true, true, 1);
         self::defineColumnFechaDTU($grid, "cliente", "Cliente", true, true, 1);
         self::defineColumnFechaDTU($grid, "estatus", "Estatus", true, true, 1);

         self::defineColumnEditFechaDTU($grid);

         //Show Function Panel
         $grid->MasterTable->ShowFunctionPanel = false;
         //Insert Settings
         $grid->MasterTable->InsertSettings->Mode = "Form";
         $grid->MasterTable->EditSettings->Mode = "Form";
         $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
    }

    public static function defineGridFechaDTU($grid, $ds){
        $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
        $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
        $grid->styleFolder="default";
        $grid->Width = "800px";

        //$grid->RowAlternative = true;
        $grid->AjaxEnabled = true;
        $grid->DataSource = $ds;
        $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
        $grid->Localization->Load($base);

        $grid->AllowInserting = true;
        $grid->AllowEditing = true;
        $grid->AllowDeleting = true;
        $grid->AllowSorting = true;
        $grid->ColumnWrap = true;
        // $grid->SingleColumnSorting = true;
        //$grid->CharSet = "utf8";

        //$grid->MasterTable->DataSource = $ds;
        $grid->MasterTable->AutoGenerateColumns = false;
        $grid->AllowFiltering = false;
        $grid->FilterOptions  = array("No_Filter", "Equal", "Contain", "Start_With","End_With");
        $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
        $grid->MasterTable->Pager->ShowPageSize = true;
        $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
        //$grid->ClientSettings->ClientEvents["OnPageChange"] = "Handle_OnPageChange";
        //$grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
        $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDelete";
        $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
    }

    public static function defineColumnFechaDTU($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
    {
        $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
        $scrpFolder = JURI::base().'components/com_sasfe/common/KoolControls/KoolCalendar/';//JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar';
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $dateC = date("Y-m-d");

        if($name_field == 'fechaDTU'){
            $column = new GridDateTimeColumn();
            $column->Picker = new KoolDatePicker();
            $column->Picker->scriptFolder = $scrpFolder;
            $column->Picker->Localization->Load($calLangueaje);
            $column->Picker->styleFolder = "default";
            $column->Picker->DateFormat = "Y-m-d";
            $column->Picker->ShowToday = true;
            $column->DefaultValue = $dateC;
        }
        elseif($name_field == 'numero'){
            $column = new gridboundcolumn();
            $column->AllowFiltering = true;
            $column->Sort = 1;
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0){
            $column->addvalidator(self::GetValidator($validator));
        }
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
    }

    public static function defineColumnEditFechaDTU($grid)
    {
        $column = new GridCustomColumn();
        $column->HeaderText = "Acciones";
        $column->Align = "center";
        $column->Width = "100px";
        // $column->ItemTemplate = "
        //                          <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a>
        //                         ";
        $column->ItemTemplate = '
                                 <a class="kgrLinkEdit"{record_editable} onclick="grid_edit(this)" href="javascript:void 0" type="button">Editar</a>
                                 <label class="contCheckbox">&nbsp;<input type="checkbox" idCheck="{idDatoGeneral}" value="0" class="selDelMul"><span class="checkmark"></span></label>
                                ';
        $grid->MasterTable->AddColumn($column);
    }


    /*
     * Imp. 27/09/21
     * Grid para cambiar las fechas del dtu "Solo aplica para el director o super usuario"
    */
    public static function ObtTodasLasFechasDTU2($idFracc){
        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtTodasFechasDTU2($ds, $idFracc);

         $grid = new KoolGrid("depositosDptGrid");
         self::defineGridFechaDTU2($grid, $ds);
         self::defineColumnFechaDTU2($grid, "idDepartamento", "Id", false, true, 1);
         self::defineColumnFechaDTU2($grid, "numero", "Departamento", true, true, 1);
         self::defineColumnFechaDTU2($grid, "fechaDTU", "Fecha DTU", true, false, 1);
         self::defineColumnEditFechaDTU2($grid);

         //Show Function Panel
         $grid->MasterTable->ShowFunctionPanel = false;
         //Insert Settings
         $grid->MasterTable->InsertSettings->Mode = "Form";
         $grid->MasterTable->EditSettings->Mode = "Form";
         $grid->MasterTable->InsertSettings->ColumnNumber = 1;
        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
    }

    public static function defineGridFechaDTU2($grid, $ds){
        $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
        $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
        $grid->styleFolder="default";
        $grid->Width = "800px";

        //$grid->RowAlternative = true;
        $grid->AjaxEnabled = true;
        $grid->DataSource = $ds;
        $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
        $grid->Localization->Load($base);

        $grid->AllowInserting = true;
        $grid->AllowEditing = true;
        $grid->AllowDeleting = true;
        $grid->AllowSorting = true;
        $grid->ColumnWrap = true;
        // $grid->SingleColumnSorting = true;
        //$grid->CharSet = "utf8";

        //$grid->MasterTable->DataSource = $ds;
        $grid->MasterTable->AutoGenerateColumns = false;
        $grid->AllowFiltering = false;
        // $grid->FilterOptions  = array("No_Filter", "Equal", "Contain", "Start_With","End_With");
        $grid->FilterOptions  = array("No_Filter", "Contain");
        $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
        $grid->MasterTable->Pager->ShowPageSize = true;
        $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
        //$grid->ClientSettings->ClientEvents["OnPageChange"] = "Handle_OnPageChange";
        //$grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
        $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDelete";
        $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
    }

    public static function defineColumnFechaDTU2($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
    {
        $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
        $scrpFolder = JURI::base().'components/com_sasfe/common/KoolControls/KoolCalendar/';//JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar';
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');
        $dateC = date("Y-m-d");

        if($name_field == 'fechaDTU'){
            $column = new GridDateTimeColumn();
            $column->Picker = new KoolDatePicker();
            $column->Picker->scriptFolder = $scrpFolder;
            $column->Picker->Localization->Load($calLangueaje);
            $column->Picker->styleFolder = "default";
            $column->Picker->DateFormat = "Y-m-d";
            $column->Picker->ShowToday = true;
            $column->DefaultValue = $dateC;
        }
        elseif($name_field == 'numero'){
            $column = new gridboundcolumn();
            $column->AllowFiltering = true;
            $column->Sort = 1;
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0){
            $column->addvalidator(self::GetValidator($validator));
        }
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
    }

    public static function defineColumnEditFechaDTU2($grid)
    {
        $column = new GridCustomColumn();
        $column->HeaderText = "Acciones";
        $column->Align = "center";
        $column->Width = "100px";
        $column->ItemTemplate = '
                                 <a class="kgrLinkEdit"{record_editable} onclick="grid_edit(this)" href="javascript:void 0" type="button">Editar</a>
                                 <label class="contCheckbox">&nbsp;<input type="checkbox" idCheck="{idDepartamento}" value="0" class="selDelMul"><span class="checkmark"></span></label>
                                ';
        $grid->MasterTable->AddColumn($column);
    }


    /***
    * Obtener los datos del fraccionamiento por su id
    */
    public static function obtDatosFraccPorId($idFracc){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $collElemCat =  $model->obtDatosFraccPorIdDB($idFracc);

        return $collElemCat;
    }


    /*
     * Grid para los porcentajes
    */
    public static function ObtCatalogoPorcentajes($esAsesPros){
        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        // $dbConn = mysql_connect($host, $user, $pass) or die("cannot connect");
        // mysql_select_db($db, $dbConn) or die("cannot connect database");
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        // $ds = new MySQLDataSource($dbConn);
        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtPorcentajesDB($ds, $esAsesPros);

         if($esAsesPros==1){
            $grid = new KoolGrid("porcentajesAsesGrid");
            self::defineGridCatPct($grid, $ds);
            self::defineColumnCatPct($grid, "idPct", "Id", false, true, 1);
            self::defineColumnCatPct($grid, "titulo", "T&iacute;tulo", true, false, 1);
            self::defineColumnCatPct($grid, "apartado", "Apartado", true, false, 1);
            self::defineColumnCatPct($grid, "escritura", "Escritura", true, false, 1);
            self::defineColumnCatPct($grid, "liquidacion", "Liquidaci&oacute;n", true, false, 1);
            self::defineColumnCatPct($grid, "mult", "Valor", true, false, 1);
            self::defineColumnEditCatPct($grid);
         }

         if($esAsesPros==2){
            $grid = new KoolGrid("porcentajesProspGrid");
            self::defineGridCatPct($grid, $ds);
            self::defineColumnCatPct($grid, "idPct", "Id", false, true, 1);
            self::defineColumnCatPct($grid, "titulo", "T&iacute;tulo", true, false, 1);
            self::defineColumnCatPct($grid, "apartado", "Apartado", true, false, 1);
            self::defineColumnCatPct($grid, "escritura", "Escritura", true, false, 1);
            self::defineColumnCatPct($grid, "mult", "Valor", true, false, 1);
            self::defineColumnEditCatPct($grid);
         }

         //Show Function Panel
         $grid->MasterTable->ShowFunctionPanel = true;
         //Insert Settings
         $grid->MasterTable->InsertSettings->Mode = "Form";
         $grid->MasterTable->EditSettings->Mode = "Form";
         $grid->MasterTable->InsertSettings->ColumnNumber = 1;

        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
    }

    public static function defineGridCatPct($grid, $ds){
        $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
        $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
        $grid->styleFolder="default";
        $grid->Width = "700px";

        //$grid->RowAlternative = true;
        $grid->AjaxEnabled = true;
        $grid->DataSource = $ds;
        $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
        $grid->Localization->Load($base);

        $grid->AllowInserting = true;
        $grid->AllowEditing = true;
        $grid->AllowDeleting = true;
        $grid->AllowSorting = true;
        $grid->ColumnWrap = true;
        $grid->CharSet = "utf8";

        //$grid->MasterTable->DataSource = $ds;
        $grid->MasterTable->AutoGenerateColumns = false;
        $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
        $grid->MasterTable->Pager->ShowPageSize = true;
        $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
        //$grid->ClientSettings->ClientEvents["OnPageChange"] = "Handle_OnPageChange";
        //$grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
        $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
        $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDelete";
        $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";

    }

    public static function defineColumnCatPct($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
    {
        $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
        $scrpFolder = JURI::base().'components/com_sasfe/common/KoolControls/KoolCalendar/';//JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar';
        $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');

        $column = new gridboundcolumn();

        if($validator > 0){
            $column->addvalidator(self::GetValidator($validator));
        }
        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
    }

    public static function defineColumnEditCatPct($grid)
    {
        $column = new GridCustomColumn();
        $column->HeaderText = "Acciones";
        $column->Align = "center";
        $column->Width = "100px";
        $column->ItemTemplate = "
                                 <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a>
                                ";
        $grid->MasterTable->AddColumn($column);
    }

    /*
     * Metodos para los reportes
     */
    public static function obtTodosFraccionamientosPorId($idFracc){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $todosFracc =  $model->obtTodosFraccionamientosPorIdDB($idFracc);

           return $todosFracc;
    }

    //obtener datos del catalogo porcentajes por su id
    public static function obtDatosPorcentajePorId($id){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $todosPcts =  $model->obtDatosPorcentajePorIdDB($id);

           return $todosPcts;
    }

    //obtener referencias por idCliente
    public static function obtColReferenciasPorIdCliente($idCliente){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $referencias =  $model->obtColReferenciasPorIdClienteDB($idCliente);

           return $referencias;
    }

    //obtener todos los depositos por el idCredito
    public static function obtColDepositoPorIdCredito($idCredito){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $depositos =  $model->obtColDepositoPorIdCreditoDB($idCredito);

           return $depositos;
    }

    //obtener todos los telefonos por el idCliente
    public static function obtColTelefonosPorIdCliente($idCliente){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $telefonos =  $model->obtColTelefonosPorIdClienteDB($idCliente);

           return $telefonos;
    }

    //obtener todos los pagares por id de credito
    public static function obtColPagaresPorIdCliente($idCredito){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $telefonos =  $model->obtColPagaresPorIdClienteDB($idCredito);

           return $telefonos;
    }

    //obtener todos los acabados por id de dato general
    public static function obtColAcabadosPorIdDatoGral($idGral){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $acabados =  $model->obtColAcabadosPorIdDatoGralDB($idGral);

           return $acabados;
    }

    //obtener todos los servicios por id de dato general
    public static function obtColServiciosPorIdDatoGral($idGral){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $servicios =  $model->obtColServiciosPorIdDatoGralDB($idGral);

           return $servicios;
    }

    //obtener todos la post venta por id de dato general
    public static function obtColPostVentaPorIdDatoGral($idGral){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $postventas =  $model->obtColPostVentaPorIdDatoGralDB($idGral);

           return $postventas;
    }

    /*
     *  Obtener el maximo numero total por cada tabla
     */
    public static function obtFilasTotalesTablasPorIdFracc($idFracc){
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        $col =  $model->obtFilasTotalesTablasPorIdFraccDB($idFracc);

        return $col;
    }


    //>>>>Para el modulo CRM
    //Obtener la fecha
    public static function obtDateTimeZone(){
        $dateByZone = new DateTime("now", new DateTimeZone('America/Mexico_City') );
        $fecha = $dateByZone->format('Y-m-d'); //fecha
        $hora = $dateByZone->format('H:i:s'); //Hora
        $fechaHora = $dateByZone->format('Y-m-d H:i:s'); //fecha y hora
        //Formatos d/m/Y
        $fechaF2 = $dateByZone->format('d/m/Y'); //fecha formato d/m/Y
        $fechaHoraF2 = $dateByZone->format('d/m/Y H:i:s'); //fecha y hora d/m/Y H:i:s

        return (object)array("fecha"=>$fecha, "hora"=>$hora, "fechaHora"=>$fechaHora, "fechaF2"=>$fechaF2, "fechaHoraF2"=>$fechaHoraF2);
    }

    //convertir fechas de formato (dd/mm/YYYY) a (YYYY-mm-dd)
    public static function conversionFecha($fecha){
        list($dd, $mm, $yyyy) = explode("/", $fecha);
        $fecha = $yyyy.'-'.$mm.'-'.$dd;
        return $fecha;
    }

    //convertir fechas de formato (YYYY-mm-dd) a (dd/mm/YYYY)
    public static function conversionFechaF2($fecha){
        $fecha = date("d/m/Y",strtotime($fecha));
        return $fecha;
    }

    //convertir fechas de formato (YYYY-mm-dd hh:mm:ss) a (dd/mm/YYYY hh:mm:ss)
    public static function conversionFechaF3($fecha){
        $explFecha = explode(" ", $fecha);
        $fecha = date("d/m/Y",strtotime($fecha));
        $fecha = $fecha." ".$explFecha[1];

        return $fecha;
    }

    //convertir fechas de formato (dd/mm/YYYY hh:mm:ss) a (YYYY-mm-dd hh:mm:ss)
    public static function conversionFechaF4($fecha){
        $explFecha = explode(" ", $fecha);
        $hora = $explFecha[1].':00';
        $fecha = self::conversionFecha($explFecha[0]);
        $fecha = $fecha." ".$hora;

        return $fecha;
    }

    //Metodo para obtener dias atras o dias posteriores a la fecha actual
    public static function diasPrevPos($dias, $dataCurrent, $ctr){
        $fecha = "";
        //Obtiene dias atras
        if($ctr=="prev") {
          $fecha = strtotime ( "-$dias day" , strtotime ( $dataCurrent ) ) ;
          $fecha = date ('d/m/Y', $fecha);
        }
        //Obtiene dias adelante
        if($ctr=="pos") {
          $fecha = strtotime ( "+$dias day" , strtotime ( $dataCurrent ) ) ;
          $fecha = date ('d/m/Y', $fecha);
        }

      return $fecha;
    }
    //Metodo para obtener meses atras o meses posteriores a la fecha actual
    public static function mesesPrevPos($noMeses, $dataCurrent, $ctr){
        $fecha = "";
        //Obtiene meses atras
        if($ctr=="prev") {
          $fecha = strtotime ( "-$noMeses month" , strtotime ( $dataCurrent ) ) ;
          $fecha = date ('d/m/Y', $fecha);
        }
        //Obtiene meses adelante
        if($ctr=="pos") {
          $fecha = strtotime ( "+$noMeses month" , strtotime ( $dataCurrent ) ) ;
          $fecha = date ('d/m/Y', $fecha);
        }

      return $fecha;
    }


    /***
     * obtiene coleccion de tipos de evento
     */
    public static function obtColTipoEvento(){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $collRes =  $model->obtColTipoEventoDB();

           return $collRes;
    }

    /***
     * Obtiene la coleccion de los tiempos recordatorios
     */
    public static function obtColTiempoRecordatorios(){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->obtColTiempoRecordatoriosDB();

       return $collRes;
    }


    /***
     * Implementado 31/08/17
     * Obtiene usuarios de joomla segun el catalogo
        case 1: gerentes_venta = grupo 11
        case 3: asesores(agentes de venta) = grupo 18
        case 4: prospectadores = grupo 17
        case 4: prospectadores = grupo 19
     */
    public static function obtUsuariosJoomlaPorGrupo($idGrupo){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->obtUsuariosJoomlaPorGrupoDB($idGrupo);

       return $collRes;
    }

    /**
     * Obtener datos del usuario catalogo por id del usuario joomla
    */
    public static function obtUsuarioDatosCatalogoPorIdUsrJoomla($idUsrJoomla){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->obtUsuarioDatosCatalogoPorIdUsrJoomlaDB($idUsrJoomla);

       return $collRes;
    }

    /**
     * Obtener datos del usuario catalogo por id del gerente joomla
    */
    public static function obtUsuarioDatosCatalogoPorIdUsrGteJoomla($idGteJoomla){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->obtUsuarioDatosCatalogoPorIdUsrGteJoomlaDB($idGteJoomla);

       return $collRes;
    }

    /**
     *  Obtener ids de usuario joomla por en datos catalogo por id del gerente prospeccion o ventas
    */
    public static function obtIdsUsrDatosCatPorUsrIdGteJoomla($usuarioIdGteJoomla){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $strIds =  $model->obtIdsUsrDatosCatPorUsrIdGteJoomlaDB($usuarioIdGteJoomla);

       return $strIds;
    }

    /**
     *  Revisar si tiene eventos programados para el dia actual corriendo
    */
    public static function checkEventosHoy($idUrsJoomla, $fechaHoy){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $strIds =  $model->checkEventosHoyDB($idUrsJoomla, $fechaHoy);

       return $strIds;
    }


    /**
     * Metodo para generar un archivo ics (calendario de outlook)
    */
    public static function generarICS($dtstart, $dtend, $description, $location, $titulo_invite, $file_name){
       // ob_start();
       date_default_timezone_set('America/Mexico_City');
       $dtstart = gmdate('Ymd\THis\Z', strtotime($dtstart));
       $dtend = gmdate('Ymd\THis\Z', strtotime($dtend));
       $todaystamp = gmdate('Ymd\THis\Z');
       $uid = date('Ymd').'T'.date('His').'-'.$_SERVER['HTTP_HOST'];
       $name_cal = "Evento";

        $calendar[0]    = "BEGIN:VCALENDAR";
        $calendar[1]    = "VERSION:2.0";
        $calendar[2]    = "PRODID:-//www.marudot.com//iCal Event Maker";
        $calendar[3]    = "X-WR-CALNAME:".$name_cal;
        $calendar[4]    = "CALSCALE:GREGORIAN";
        $calendar[5]    = "BEGIN:VTIMEZONE";
        $calendar[6]    = "TZID:America/Mexico_City";
        $calendar[7]    = "TZURL:http://tzurl.org/zoneinfo-outlook/America/Mexico_City";
        $calendar[8]    = "X-LIC-LOCATION:America/Mexico_City";
        $calendar[9]    = "BEGIN:DAYLIGHT";
        $calendar[10]   = "TZOFFSETFROM:-0600";
        $calendar[11]   = "TZOFFSETTO:-0500";
        $calendar[12]   = "TZNAME:CDT";
        $calendar[13]   = "RRULE:FREQ=YEARLY;BYMONTH=4;BYDAY=1SU";
        $calendar[14]   = "END:DAYLIGHT";
        $calendar[15]   = "BEGIN:STANDARD";
        $calendar[16]   = "TZOFFSETFROM:-0500";
        $calendar[17]   = "TZOFFSETTO:-0600";
        $calendar[18]   = "TZNAME:CST";
        $calendar[19]   = "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU";
        $calendar[20]   = "END:STANDARD";
        $calendar[21]   = "END:VTIMEZONE";
        $calendar[22]   = "BEGIN:VEVENT";
        $calendar[23]   = "DTSTAMP:".$todaystamp;
        $calendar[24]   = "UID:".$uid;
        $calendar[25]   = "DTSTART;TZID='America/Mexico_City':".$dtstart;
        $calendar[26]   = "DTEND;TZID='America/Mexico_City':".$dtend;
        $calendar[27]   = "SUMMARY:".$titulo_invite;
        $calendar[27]   = "DESCRIPTION:".$description;
        $calendar[29]   = "LOCATION:".$location;
        $calendar[30]   = "BEGIN:VALARM";
        $calendar[31]   = "ACTION:DISPLAY";
        $calendar[32]   = "DESCRIPTION:".$description;
        $calendar[33]   = "TRIGGER:-PT5M";
        $calendar[34]   = "END:VALARM";
        $calendar[35]   = "END:VEVENT";
        $calendar[36]   = "END:VCALENDAR";

        $calendar = implode("\r\n", $calendar);
        // file_put_contents($file_name.".ics", $calendar);
        echo $calendar;

        // ob_get_clean();
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$file_name.'.ics"');
        Header('Content-Length: '.strlen($calendar));
        Header('Connection: close');
        JFactory::getApplication()->close();
    }

    /*
     * Grid de eventos y comentarios por el id del prospecto
     */
    public static function ObtEventosComentariosGrid($idDatoProspecto, $tipoOpc){
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
        include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

        $config = new JConfig();
        $host = $config->host;
        $user = $config->user;
        $pass = $config->password;
        $db = $config->db;
        $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
        mysqli_select_db($dbConn, $db) or die("cannot connect database");
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

        $ds = new MySQLiDataSource($dbConn);
        $ds = $model->ObtEventosComentariosDB($ds, $idDatoProspecto, $tipoOpc);

         $grid = new KoolGrid("eventosComentariosGrid");
         self::defineGridEvCom($grid, $ds);
         self::defineColumnEvCom($grid, "idMovPros", "Id", false, true);
         self::defineColumnEvCom($grid, "fechaEvCom", "Fecha", true, false);
         self::defineColumnEvCom($grid, "prospecto", "Usuario", true, false);
         // self::defineColumnEvCom($grid, "tipoEvento", "Tipo", true, false);
         self::defineColumnEvCom($grid, "opcTipo", "Tipo", true, false);
         self::defineColumnEvCom($grid, "comentario", "Comentario", true, false);
         self::defineColumnEvCom($grid, "checkAtendido", "Atendido", true, false);

        //pocess grid
        $grid->Process();
        $dbConn->close();

        return $grid;
    }

     public static function defineGridEvCom($grid, $ds){
          $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
          $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
          $grid->styleFolder="default";
          $grid->Width = "900px";
          //$grid->RowAlternative = true;
          $grid->AjaxEnabled = true;
          $grid->DataSource = $ds;
          $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
          $grid->Localization->Load($base);
          $grid->AllowInserting = true;
          $grid->AllowEditing = true;
          $grid->AllowDeleting = true;
          $grid->AllowSorting = true;
          $grid->ColumnWrap = true;
          $grid->CharSet = "utf8";
          //$grid->MasterTable->DataSource = $ds;
          $grid->MasterTable->AutoGenerateColumns = false;
          $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
          $grid->MasterTable->Pager->ShowPageSize = true;
          $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
          $grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
     }

     public static function defineColumnEvCom($grid, $name_field, $name_header, $visible=true, $read_only=false, $validator=0)
     {
        $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
        if($name_field == 'checkAtendido'){
           $column = new GridCustomColumn();
           // $column->HeaderText = "Acciones";
           $column->Align = "center";
           $column->Width = "50px";
           $column->ItemTemplate = '<input type="checkbox" {checkAtendido} disabled> ';
        }
        else{
            $column = new gridboundcolumn();
        }

        if($validator > 0)
          $column->addvalidator(self::GetValidator($validator));

        $column->DataField = $name_field;
        $column->HeaderText = $name_header;
        $column->ReadOnly = $read_only;
        $column->Visible = $visible;
        $grid->MasterTable->AddColumn($column);
     }


    /**
     *  Obtener departamentos disponibles por id del fraccionamiento
    */
    public static function obtenerDepartamentosDisponibles($idFracc, $idProspectado, $idGteV){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $col =  $model->obtenerDepartamentosDisponiblesDB($idFracc, $idProspectado, $idGteV);

       return $col;
    }

    /***
     * Obtiene la coleccion de los tipos de captados
     */
    public static function obtColTipoCaptados(){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->obtColTipoCaptadosDB();

       return $collRes;
    }


    /**
     * Salvar el historial para el prospecto a partir de la asignacion de la casa
    */
    public static function salvarHistorialProspecto($datoProspectoId, $estatusId, $comentario, $fechaCreacion){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->salvarHistorialProspectoDB($datoProspectoId, $estatusId, $comentario, $fechaCreacion);

       return $collRes;
    }

    /**
     * Obtener el historial de prospecto por su id
    */
    public static function obtHistorialProspecto($datoProspectoId){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $collRes =  $model->obtHistorialProspectoDB($datoProspectoId);

       return $collRes;
    }

    /**
     * Obtener usuarios de jooma
    */
    public static function obtInfoUsuariosJoomla($id){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtInfoUsuariosJoomlaDB($id);

       return $colRes;
    }

    /**
     * Obtener usuarios de joomla por grupo
    */
    public static function obtInfoUsuariosJoomlaPorGrupo($idGrupo){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtInfoUsuariosJoomlaPorGrupoDB($idGrupo);

       return $colRes;
    }


    /**
     * Seleccionar todos los prospectos repetidos por el id del prospecto seleccionado
     * para ver directamente con cual se relaciona
    */
    public static function obtProspectosRelacionadosRepetidos($rfc, $idDatoProspecto){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtProspectosRelacionadosRepetidosDB($rfc, $idDatoProspecto);

       return $colRes;
    }

    /***
     * Limpiar los montos
     */
    public static function limpiarFormatoMonto($monto){
        $resMonto = str_replace(",", "", $monto);
        $resMonto = str_replace("$", "", $resMonto);
        return $resMonto;
    }

     /**
     * Obtener todos los departamentos
    */
    public static function obtTodosDepartamentosArr(){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtTodosDepartamentosArrDB();

       return $colRes;
    }

    //>>>>
    //>>>Metodo encargado de enviar notificacion via correo electronico
    //>>>>
    public static function notificarPorCorreo($asunto, $arrCorreos, $body){
      $mailer = JFactory::getMailer();
      $config = JFactory::getConfig();
      $sender = array($config->get('mailfrom'), $config->get('fromname'));
      $mailer->setSender($sender);

      // $body   = '<h2>Our mail</h2>'
      //         . '<div>A message to our dear readers'
      //         . '</div>';
      // $arrCorreos = array('carlos.ramirez@framelova.com', 'karlos.0@live.com.mx');

      $mailer->addRecipient($arrCorreos);
      $mailer->setSubject($asunto);
      $mailer->isHtml(true);
      $mailer->Encoding = 'base64';
      $mailer->setBody($body);

      $send = $mailer->Send();
      if($send!==true){
          echo 'Error sending email: ';
      }else{
          echo 'Mail sent';
      }
    }



    /***
     * Obtener datos para exportar el reporte de prospectos
    */
    public static function obtDatosParaReportesProspectos($agtVentasId, $fechaDel, $fechaAl, $difDias){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtDatosParaReportesProspectosDB($agtVentasId, $fechaDel, $fechaAl, $difDias);

       return $colRes;
    }

    /**
     * Convertir un cadena en forma vertical
    */
    public static function letrasVerticales($texto){
      $res = "";
      $len = 1;
      $chars = preg_split('/(?<!^)(?!$)/u', $texto );
      foreach( array_chunk($chars, $len) as $a ){
        $res .= join("", $a).'<br/>';
      }
      /*
      $arrTexto = str_split($texto);
      $arrTexto = array_reverse($arrTexto);
      $res = "";
      foreach($arrTexto as $letra){
        $res .= $letra."<br>";
      }
      */
      return $res;
    }


    public static function letrasVerticales2($texto){
      $res = "";
      $len = 10;
      $chars = preg_split('/(?<!^)(?!$)/u', $texto );
      $index=0;

      $partes = array_chunk($chars, $len);
      $totalPartes = count($partes);
      $cadenas = array();

      for($i=0; $i<$len; $i++){
        $cadenas[$i] = "";
      }

      for($i=0; $i<$len; $i++){
        for($ii=0; $ii<$totalPartes; $ii++){
          $cadenas[$i] .=  (isset($partes[$ii][$i])) ? self::reemEspacio($partes[$ii][$i]." ") :"";
        }
      }

        $html = "";
        foreach($cadenas as $elem) {
         $html .='<span style="font-size:20px;">'.$elem.'</span><br/>';
      }
      return $html;
    }
    public static function reemEspacio($char){
      return str_replace(" ", "&nbsp;&nbsp;", $char);
    }

    /***
     * Obtiene la coleccion de todos los asesores o agentes de ventas que pertenecen a un gerente de ventas
     */
    public static function obtColAsesoresAgtVentaXIdGteVentas($idGteVentas){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $collElemCat =  $model->obtColAsesoresAgtVentaXIdGteVentasDB($idGteVentas);
           return $collElemCat;
    }

    public static function obtColAsesoresAgtVentaXIds($idsAsesores){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $collElemCat =  $model->obtColAsesoresAgtVentaXIdsDB($idsAsesores);
      return $collElemCat;
    }

    public static function obtColAsesoresActivosIds(){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $collElemCat =  $model->obtColAsesoresActivosIdsDB();
      return $collElemCat;
    }

    /***
     * Obtiene la coleccion de todos los prospectadores por el id del gerente (prospeccion o ventas)
     */
    public static function obtColProspectadoresXIdGte($idGte){
           $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
           $collElemCat =  $model->obtColProspectadoresXIdGteDB($idGte);
           return $collElemCat;
    }
    /**
     * EVENTOS PARA RECARGAR EL AJAX KOOLGRID
    */
    public static function obtColUsuarioXGte($ctrCol,$gteId){
      //Obtiene la coleccion de usuarios agentes de venta
      if($ctrCol==1){
        $html = '';
        $colAsesores = self::obtColAsesoresAgtVentaXIdGteVentas($gteId);
        $html .= '<select id="usuarioAgenteVentas" name="usuarioAgenteVentas" class="required">';
          $html .= '<option value="">--Seleccione--</option>';
        if(count($colAsesores)>0){
              foreach ($colAsesores as $elemAgtV) {
                $html .= '<option value="'.$elemAgtV->usuarioIdJoomla.'">'.$elemAgtV->nombre.'</option>';
              }
        }
        $html .= '</select>';
        return $html;
      }
      //Obtiene la coleccion de usuarios prospectadores
      if($ctrCol==2){
        $html1 = '';
        $colProspectadores = self::obtColProspectadoresXIdGte($gteId);
        $html1 .= '<select id="usuarioProspectador" name="usuarioProspectador" class="required">';
          $html1 .= '<option value="">--Seleccione--</option>';
        if(count($colProspectadores)>0){
              foreach ($colProspectadores as $elemProspec) {
                $html1 .= '<option value="'.$elemProspec->usuarioIdJoomla.'">'.$elemProspec->nombre.'</option>';
              }
        }
        $html1 .= '</select>';
        return $html1;
      }
    }
    /***
     * Obtener informacion de los prospectos por rango de fechas
    */
    public static function obtDatosProspectosPorFechas($fechaDel, $fechaAl){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtDatosProspectosPorFechasDB($fechaDel, $fechaAl);
       return $colRes;
    }
    /***
     * Obtener datos para exportar el reporte de prospectos por FUENTE
    */
    public static function obtDatosParaReportesProspectosPorFuente($agtVentasId, $fechaDel, $fechaAl, $difDias){
       $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
       $colRes =  $model->obtDatosParaReportesProspectosPorFuenteDB($agtVentasId, $fechaDel, $fechaAl, $difDias);
       return $colRes;
    }
    /**
     *
     */
    public static function obtPermisoABCProspectoSu(){
      $user = JFactory::getUser();
      $groups = JAccess::getGroupsByUser($user->id, true);
      $arrUsuarios = array();
      //Super usuario - direccion
      if(in_array("8", $groups) || in_array("10", $groups)){
        $arrUsuarios['usrGteVentas'] = true;
        $arrUsuarios['usrGteProspeccion'] = true;
        $arrUsuarios['usrAgtVentas'] = true;
        $arrUsuarios['usrProspectador'] = true;
      }
      //Gerente ventas
      if(in_array("11", $groups)){
        $arrUsuarios['usrGteVentas'] = true;
        $arrUsuarios['usrGteProspeccion'] = false;
        $arrUsuarios['usrAgtVentas'] = true;
        $arrUsuarios['usrProspectador'] = true;
      }
      //Gerente prospeccion
      if(in_array("19", $groups)){
        $arrUsuarios['usrGteVentas'] = false;
        $arrUsuarios['usrGteProspeccion'] = true;
        $arrUsuarios['usrAgtVentas'] = false;
        $arrUsuarios['usrProspectador'] = true;
      }
      return (object)$arrUsuarios;
    }
    //>>>
    //>>Para el modulo de SMS
    //>>>
    /*
     * Obtener tipo de mensaje por id
    */
    public static function obtTipoMensajePorId($id){
      $arrMsg = array();
      $msg = "";
      switch ($id) {
          case 1: $msg = "General"; break;
          case 2: $msg = "Apartado definitivo"; break;
          case 3: $msg = "Aviso de retenci&oacute;n"; break;
          case 4: $msg = "Firma de escrituras"; break;
          case 5: $msg = "Entrega de vivienda"; break;
      }
      return $msg;
    }
    // opciones placeholder permitidas
    public static function opcionesPlaceholderSMS(){
      $arrPlaceholder = array("{aPaternoCliente}", "{telAgencia}");
      return $arrPlaceholder;
    }
    // placeholder reemplazar
    public static function reemplazarPlaceholderSMS($mensaje, $placeholder, $reemplazar){
      $mensaje = str_replace($placeholder, $reemplazar, $mensaje);
      return $mensaje;
    }
    // Obtener celular del cliente
    public static function obtCelularCliente($idDatoGral){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $celular =  $model->obtCelularClienteDB($idDatoGral);
      return $celular;
    }
    // Obtener mensaje por su id
    public static function obtMensajeSMSPorId($id){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $dato =  $model->obtMensajeSMSPorIdDB($id);
      return $dato;
    }
    // Enviar mensaje proveedor bulksms
    public static function enviarSMS($mensaje, $telefono){
      //$telefono = "2225336420"; //Desabilitar
      $telCod = "+52"; //Mexico
      $username = 'Esphabit'; //usuario: bulksms, cambiar por el id indicado
      $password = 'Us3rE5ph4b1t2018'; //pass: bulksms, cambiar por el pass indicado
      $messages = array();
      $messages[] = array('to'=>"$telCod"."$telefono", 'body'=>$mensaje);
      //Enviar mensaje
      $result = self::send_message_bulksms(json_encode($messages), 'https://api.bulksms.com/v1/messages', $username, $password);
      if($result['http_status'] != 201){
        // print "Error sending.  HTTP status " . $result['http_status'];
        // print " Response was " .$result['server_response'];
        // return "No fue posible enviar mensaje";
        return false;
      }else{
        // print "Response " . $result['server_response'];
        // return "Mensaje enviado";
        return true;
      }
      // echo "<pre>";
      // print_r($messages);
      // echo "</pre>";
    }
    //Api bulksms encargada de enviar el mensaje
    public static function send_message_bulksms($post_body, $url, $username, $password){
      $ch = curl_init( );
      $headers = array(
          'Content-Type:application/json',
          'Authorization:Basic '. base64_encode("$username:$password")
      );
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt ( $ch, CURLOPT_URL, $url );
      curl_setopt ( $ch, CURLOPT_POST, 1 );
      curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
      curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
      // Allow cUrl functions 20 seconds to execute
      curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
      // Wait 10 seconds while trying to connect
      curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
      $output = array();
      $output['server_response'] = curl_exec( $ch );
      $curl_info = curl_getinfo( $ch );
      $output['http_status'] = $curl_info[ 'http_code' ];
      curl_close( $ch );
      // echo "<pre>";
      // print_r($curl_info);
      // echo "</pre>";
      return $output;
    }
    //Obtener todos los mensajes generales
    public static function obtMensajesSMSPorTipoId($tipoId){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->obtMensajesSMSPorTipoIdDB($tipoId);
      return $col;
    }
    //Obtener todos las promociones
    public static function obtPromocionesSMS(){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->obtPromocionesSMSDB();
      return $col;
    }
    //Obtener todos los usuarios que coincidieron con los filtros
    //y solo se veran aquellos que tengan un celular dado de alta en la tabla de telefonos
    public static function obtUsuariosSMSPorFiltro($idAsesor="", $idEstatus="", $fechaDel="", $fechaAl="", $tipoProceso=0, $idFracc=""){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->obtUsuariosSMSPorFiltroDB($idAsesor, $idEstatus, $fechaDel, $fechaAl, $tipoProceso, $idFracc);
      return $col;
    }
    //Salvar el historial del mensaje o promocion enviada
    public static function salvarHistorialSMS($tipoEnvio, $grupoUsuarioId, $usuarioId, $mensaje, $comentario, $fechaCreacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $id =  $model->salvarHistorialSMSDB($tipoEnvio, $grupoUsuarioId, $usuarioId, $mensaje, $comentario, $fechaCreacion);
      return $id;
    }
    //Comprobar que en la tabla de sms_credito_usuarios existe el usuario
    public static function checkCreditoPorUsuarioIdSMS($usuarioId){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $dato =  $model->checkCreditoPorUsuarioIdSMSDB($usuarioId);
      return $dato;
    }
    //Salvar Credito SMS
    public static function salvarCreditoSMS($totalCredito, $tipoProceso, $usuarioId, $fechaCreacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $id =  $model->salvarCreditoSMSDB($totalCredito, $tipoProceso, $usuarioId, $fechaCreacion);
      return $id;
    }
    //Actualiza credito en la tabla de creditos usuario
    public static function actualizarCreditoSMS($totalCredito, $tipoProceso, $usuarioId, $fechaActualizacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $id =  $model->actualizarCreditoSMSDB($totalCredito, $tipoProceso, $usuarioId, $fechaActualizacion);
      return $id;
    }
    //Obtener informacion de la bolsa de creditos y automaticos
    public static function obtInfoCreditosBolsaAutomatico($idCredito){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $datos =  $model->obtInfoCreditosBolsaAutomaticoDB($idCredito);
      return $datos;
    }
    //Actualiza credito bolsa de creditos y automaticos
    public static function actualizarCreditoBolsaAutomaticosSMS($totalCredito, $tipoProceso, $idCredito, $fechaActualizacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $id =  $model->actualizarCreditoBolsaAutomaticosSMSDB($totalCredito, $tipoProceso, $idCredito, $fechaActualizacion);
      return $id;
    }
    //Metodo para restar creditos de la bolsa
    public static function restarCreditoBolsaSMS($creditoRestar, $fechaActualizacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $res =  $model->restarCreditoBolsaSMSDB($creditoRestar, $fechaActualizacion);
      return $res;
    }
    //Metodo para restar creditos de los automaticos
    public static function restarCreditoAutomaticosSMS($creditoRestar, $fechaActualizacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $res =  $model->restarCreditoAutomaticosSMSDB($creditoRestar, $fechaActualizacion);
      return $res;
    }
    //Metodo para restar creditos de los usuarios (gerentes o asesores)
    public static function restarCreditoUsuariosSMS($creditoRestar, $tipoProceso, $usuarioId, $fechaActualizacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $res =  $model->restarCreditoUsuariosSMSDB($creditoRestar, $tipoProceso, $usuarioId, $fechaActualizacion);
      return $res;
    }
    //Salvar el historial del mensaje o promocion enviada por cada uno de los clientes
    public static function salvarHistorialClientesSMS($usuarioId, $agtVentasId, $datoClienteId, $tipoEnvio, $mensajeId, $fechaCreacion){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $id =  $model->salvarHistorialClientesSMSDB($usuarioId, $agtVentasId, $datoClienteId, $tipoEnvio, $mensajeId, $fechaCreacion);
      return $id;
    }
    //Obtener el total de mensajes que se le ha enviado a un cliente
    public static function obtTotalEnvioClienteSMS($datoClienteId){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $id =  $model->obtTotalEnvioClienteSMSDB($datoClienteId);
      return $id;
    }
    // Obtener datos generales por su id
    public static function obtDatoGralPorIdSMS($id){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $datos =  $model->obtDatoGralPorIdSMSDB($id);
      return $datos;
    }
    //Actualizar el estatus de los envios
    public static function actEnviosSMSDatoGralPorId($id, $campo, $valor){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $datos =  $model->actEnviosSMSDatoGralPorIdDB($id, $campo, $valor);
      return $datos;
    }
    //actualizar estatus activo/inactivo  por su id y tipo proceso
    public static function actualizarActivoCreditoSMS($tipoProceso, $valor, $idCredito){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $res =  $model->actualizarActivoCreditoSMSDB($tipoProceso, $valor, $idCredito);
      return $res;
    }
    //Obtener datos del cliente por el id general
    public static function obtDatosClientePorIdGral($datoGeneralId){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $res =  $model->obtDatosClientePorIdGralDB($datoGeneralId);
      return $res;
    }
    //Obtener todos los usuarios que coincidieron con los filtros para las promociones
    //y solo se veran aquellos que tengan un celular dado de alta en la tabla de datos_prospectos
    public static function obtUsuariosSMSPorFiltroPromo($idAsesor="", $fechaDel="", $fechaAl="", $tipoProceso=0){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->obtUsuariosSMSPorFiltroPromoDB($idAsesor, $fechaDel, $fechaAl, $tipoProceso);
      return $col;
    }
    //Obtener datos de los prospectos por su id
    public static function obtDatosProspectoPorId($idProspecto=0){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->obtDatosProspectoPorIdDB($idProspecto);
      return $col;
    }
    //Obtener datos de los prospectos por su id tabla datos_clientes
    public static function obtDatosClientePorId($idDatoCliente=0){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->obtDatosClientePorIdDB($idDatoCliente);
      return $col;
    }

    // Revisar si el prospecto pertenece a gerencias diferentes
    // Imp. 18/05/20
    public static function revisaGerenciasPorRfc($rfc=""){
      $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
      $col =  $model->revisaGerenciasPorRfcDB($rfc);
      return $col;
    }





  // >>>>>>>>>>Inicio Modulo contactos
  // Obtener coleccion de las sincronizaciones del día
  public static function obtSincronizacionesDelDia($fecha=""){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $col =  $model->obtSincronizacionesDelDiaDB($fecha);
    return $col;
  }

  // Revisar duplicidad
  public static function checkDuplicidadSinc($correo="", $telefono=""){
    //Revisa en tabla contacto
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $col =  $model->checkDuplicidadSincContactoDB($correo, $telefono);
    return $col;
  }

  // Obtener el ultimo gerente
  public static function obtUltimoGerenteSinc(){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $col =  $model->obtUltimoGerenteSincDB();
    return $col;
  }

  // Guardar el id del gerente
  public static function salvarIdGerenteSinc($gteVentasId, $fechaHora){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $res =  $model->salvarIdGerenteSincDB($gteVentasId, $fechaHora);
    return $res;
  }

  // Obtener el ultimo asesor
  public static function obtUltimoAsesorSinc($gteVentasId){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $col =  $model->obtUltimoAsesorSincDB($gteVentasId);
    return $col;
  }

  // Guardar el id del asesor
  public static function salvarIdAsesorSinc($gteVentasId, $agtVentasId, $fechaHora){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $res =  $model->salvarIdAsesorSincDB($gteVentasId, $agtVentasId, $fechaHora);
    return $res;
  }


  // Limpiar telefono
  public static function soloNumeros($telefono){
    return preg_replace('/[^0-9]+/', '', $telefono);
  }

  // Validar email
  public static function validarEmail($email){
    $res = 0;
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $res = 1;
    }
    return $res;
  }

  // Colores por estatus del contacto
  public static function colorEstatusContacto($idEstatus){
    $res = "";
    switch ($idEstatus) {
      case 1: $res = ""; break; //Asignado
      case 2: $res = "colorAzul"; break; //Seguimiento
      case 3: $res = "colorAmarillo"; break; //Contactado
      case 4: $res = "colorRojo"; break; //Descartado
      case 5: $res = "colorVerde"; break; //Prospecto
    }

    return $res;
  }

  // Coleccion de acciones para contacto
  public static function colAccionesContacto(){
    $arr = array();
    $arr[] = (object)array("idAccion"=>1, "nombre"=>"Llamar (contar)");
    $arr[] = (object)array("idAccion"=>2, "nombre"=>"Whats app (contar)");
    $arr[] = (object)array("idAccion"=>3, "nombre"=>"SMS (contar)");
    $arr[] = (object)array("idAccion"=>4, "nombre"=>"Correo (contar)");
    $arr[] = (object)array("idAccion"=>5, "nombre"=>"Cita");
    $arr[] = (object)array("idAccion"=>6, "nombre"=>"Recontacto");
    $arr[] = (object)array("idAccion"=>7, "nombre"=>"Reasignacion");

    return (object)$arr;
  }

  // Coleccion de estatus del contacto
  public static function colEstatusContacto(){
    $arr = array();
    $arr[] = (object)array("idEstatus"=>1, "nombre"=>"Asignado", "activo"=>1);
    $arr[] = (object)array("idEstatus"=>2, "nombre"=>"Seguimiento", "activo"=>1);
    $arr[] = (object)array("idEstatus"=>3, "nombre"=>"Contactado", "activo"=>1);
    $arr[] = (object)array("idEstatus"=>4, "nombre"=>"Descartado", "activo"=>1);
    $arr[] = (object)array("idEstatus"=>5, "nombre"=>"Prospecto", "activo"=>1);
    $arr[] = (object)array("idEstatus"=>6, "nombre"=>"Reasignado", "activo"=>1);

    return (object)$arr;
  }


  /*
   * Grid de acciones
  */
  public static function ObtAccionesGrid($idDatoContacto){
    include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolAjax' . DIRECTORY_SEPARATOR . 'koolajax.php';
    include_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_sasfe' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'KoolControls' . DIRECTORY_SEPARATOR . 'KoolGrid' . DIRECTORY_SEPARATOR . 'koolgrid.php';

    $config = new JConfig();
    $host = $config->host;
    $user = $config->user;
    $pass = $config->password;
    $db = $config->db;
    $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
    mysqli_select_db($dbConn, $db) or die("cannot connect database");
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

    $ds = new MySQLiDataSource($dbConn);
    $ds = $model->ObtAccionesPorIdContactoDB($ds, $idDatoContacto);

     $grid = new KoolGrid("eventosComentariosGrid");
     self::defineGridAccionCont($grid, $ds);
     self::defineColumnAccionCont($grid, "idAccion", "Id", false, true);
     self::defineColumnAccionCont($grid, "fechaAlta2", "Fecha", true, false);
     self::defineColumnAccionCont($grid, "nombreAgtVentas", "Agente", true, false);
     self::defineColumnAccionCont($grid, "accion", "Acci&oacute;n", true, false);
     self::defineColumnAccionCont($grid, "comentario", "Comentario", true, false);
     // self::defineColumnAccionCont($grid, "checkAtendido", "Atendido", true, false);

    //pocess grid
    $grid->Process();
    $dbConn->close();

    return $grid;
  }

  public static function defineGridAccionCont($grid, $ds){
    $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
    $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
    $grid->styleFolder="default";
    $grid->Width = "900px";
    //$grid->RowAlternative = true;
    $grid->AjaxEnabled = true;
    $grid->DataSource = $ds;
    $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
    $grid->Localization->Load($base);
    $grid->AllowInserting = true;
    $grid->AllowEditing = true;
    $grid->AllowDeleting = true;
    $grid->AllowSorting = true;
    $grid->ColumnWrap = true;
    $grid->CharSet = "utf8";
    //$grid->MasterTable->DataSource = $ds;
    $grid->MasterTable->AutoGenerateColumns = false;
    $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
    $grid->MasterTable->Pager->ShowPageSize = true;
    $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
    $grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
  }

  public static function defineColumnAccionCont($grid, $name_field, $name_header, $visible=true, $read_only=false, $validator=0){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    if($name_field == 'checkAtendido'){
       $column = new GridCustomColumn();
       // $column->HeaderText = "Acciones";
       $column->Align = "center";
       $column->Width = "50px";
       $column->ItemTemplate = '<input type="checkbox" {checkAtendido} disabled> ';
    }
    else{
        $column = new gridboundcolumn();
    }

    if($validator > 0)
      $column->addvalidator(self::GetValidator($validator));

    $column->DataField = $name_field;
    $column->HeaderText = $name_header;
    $column->ReadOnly = $read_only;
    $column->Visible = $visible;
    $grid->MasterTable->AddColumn($column);
  }
  // >>>>>>>>>>Fin modulo contactos

  // >>>>>>>>>>Inicio de reportes para modulo de contactos
  /***
   * Obtener datos para exportar el reporte de contactos por FUENTE
  */
  public static function obtDatosParaReportesContactosPorFuente($fechaDel, $fechaAl, $idGteVenta, $idAgtventas){
     $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
     $colRes =  $model->obtDatosParaReportesContactosPorFuenteDB($fechaDel, $fechaAl, $idGteVenta, $idAgtventas);
     return $colRes;
  }

  /***
   * Obtener datos para exportar reporte de detalles de acciones por contacto
  */
  public static function obtDetalleAccionesContactos($fechaDel, $fechaAl, $gteVentasId=-1, $agtVentasId=-1){
     $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
     $colRes =  $model->obtDetalleAccionesContactosDB($fechaDel, $fechaAl, $gteVentasId, $agtVentasId);
     return $colRes;
  }

  // >>>>>>>>>>Fin de reportes para modulo de contactos


  /*
   * Grid para las configuraciones globales
  */
  public static function ObtCatalogoConfiguraciones(){
    $config = new JConfig();
    $host = $config->host;
    $user = $config->user;
    $pass = $config->password;
    $db = $config->db;

    $dbConn = mysqli_connect($host, $user, $pass, $db) or die("cannot connect");
    mysqli_select_db($dbConn, $db) or die("cannot connect database");
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');

    $ds = new MySQLiDataSource($dbConn);
    $ds = $model->ObtCatConfiguracionesDB($ds);

    $grid = new KoolGrid("configuracionesGrid");
    self::defineGridCatConfig($grid, $ds);
    self::defineColumnCatConfig($grid, "idConfiguracion", "Id", false, true, 1);
    self::defineColumnCatConfig($grid, "nombre", "Nombre", true, true, 1);
    self::defineColumnCatConfig($grid, "valor", "Valor", true, false, 1);
    // self::defineColumnCatConfig($grid, "fechaCreacion", "Fecha Creacion", true, true, 1);
    self::defineColumnEditCatConfig($grid);

    //Show Function Panel
    $grid->MasterTable->ShowFunctionPanel = true;
    $grid->MasterTable->FunctionPanel->ShowInsertButton = false;
    //Insert Settings
    $grid->MasterTable->InsertSettings->Mode = "Form";
    $grid->MasterTable->EditSettings->Mode = "Form";
    $grid->MasterTable->InsertSettings->ColumnNumber = 1;

    //pocess grid
    $grid->Process();
    $dbConn->close();

    return $grid;
  }

  public static function defineGridCatConfig($grid, $ds){
    $base = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolGrid/localization/es.xml';
    $grid->scriptFolder = JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolGrid';
    $grid->styleFolder="default";
    $grid->Width = "800px";

    //$grid->RowAlternative = true;
    $grid->AjaxEnabled = true;
    $grid->DataSource = $ds;
    $grid->AjaxLoadingImage =  JURI::root().'administrator/components/com_sasfe/common/KoolControls/KoolAjax/loading/5.gif';
    $grid->Localization->Load($base);

    $grid->AllowInserting = true;
    $grid->AllowEditing = true;
    $grid->AllowDeleting = true;
    $grid->AllowSorting = true;
    $grid->ColumnWrap = true;
    $grid->CharSet = "utf8";

    //$grid->MasterTable->DataSource = $ds;
    $grid->MasterTable->AutoGenerateColumns = false;
    $grid->MasterTable->Pager = new GridPrevNextAndNumericPager();
    $grid->MasterTable->Pager->ShowPageSize = true;
    $grid->MasterTable->Pager->PageSizeOptions = "10,25,50,100,150";
    //$grid->ClientSettings->ClientEvents["OnPageChange"] = "Handle_OnPageChange";
    //$grid->ClientSettings->ClientEvents["OnGridCommit"] = "Handle_OnGridCommit";
    // $grid->ClientSettings->ClientEvents["OnRowConfirmEdit"] = "Handle_OnRowConfirmEdit";
    // $grid->ClientSettings->ClientEvents["OnRowDelete"] = "Handle_OnRowDelete";
    // $grid->ClientSettings->ClientEvents["OnConfirmInsert"] = "Handle_OnConfirmInsert";
  }

  public static function defineColumnCatConfig($grid,$name_field, $name_header, $visible=true, $read_only=false, $validator=0)
  {
      $calLangueaje = JPATH_SITE.'/administrator/components/com_sasfe/common/KoolControls/KoolCalendar/localization/es.xml';
      $scrpFolder = JURI::base().'components/com_sasfe/common/KoolControls/KoolCalendar/';
      $model = JModelLegacy::getInstance('globalmodelsbk', 'SasfeModel');

      $column = new gridboundcolumn();

      if($validator > 0){
          $column->addvalidator(self::GetValidator($validator));
      }
      $column->DataField = $name_field;
      $column->HeaderText = $name_header;
      $column->ReadOnly = $read_only;
      $column->Visible = $visible;
      $grid->MasterTable->AddColumn($column);
  }

  public static function defineColumnEditCatConfig($grid)
  {
      $column = new GridCustomColumn();
      $column->HeaderText = "Acciones";
      $column->Align = "center";
      $column->Width = "100px";
      $column->ItemTemplate = "
        <a class='kgrLinkEdit'{record_editable} onclick='grid_edit(this)' href='javascript:void 0' type='button'>Editar</a>
      ";
      $grid->MasterTable->AddColumn($column);
  }

  // Obtener datos configuracion
  public static function obtDatosConfiguracionPorId($idConfiguracion=0, $fechaAct=""){
     $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
     $colRes =  $model->obtDatosConfiguracionPorIdDB($idConfiguracion, $fechaAct);
     return $colRes;
  }

  //Imp. 13/10/20
  // Actualizar dato configuracion por su id
  public static function ActDatoConfiguracionPorId($idConfiguracion=0, $valor=""){
     $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
     $colRes =  $model->ActDatoConfiguracionPorIdDB($idConfiguracion, $valor);
     return $colRes;
  }
  // Obtener col de motivos rechazo
  public static function obtDatosMotivos(){
     $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
     $colRes =  $model->obtDatosMotivosDB();
     return $colRes;
  }

  // Limpiar punto en la sincronizacion de datos
  public static function limpPuntoSinc($dato){
    $res = (trim($dato)!=".")?trim($dato):"";
    return $res;
  }

  // Imp. 29/10/20
  // Contar el numero de acciones
  public static function contarAccionesContacto($idContacto){
    $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
    $res = $model->contarAccionesContactoDB($idContacto);
    return $res;
  }


  /***
   * Obtiene el tipo captado o fuente
  */
  public static function obtTipoCaptado($idTipo=""){
     $model = JModelLegacy::getInstance('Globalmodelsbk', 'SasfeModel');
     $collRes =  $model->obtTipoCaptadoDB($idTipo);

     return $collRes;
  }

  // Encriptar cadena
  public static function encriptarCadena($cadena){
    $semilla = "__!?/?!__".rand(100,1000000);
    $cad = rtrim(strtr(base64_encode($cadena.$semilla), '+/', '-_'), '=');
    return $cad;
  }

  //Desencriptar cadena
  public static function desencriptarCadena($cadena){
    $cad = base64_decode(str_pad(strtr($cadena, '-_', '+/'), strlen($cadena) % 4, '=', STR_PAD_RIGHT));
    $arrCad = explode("__!?/?!__", $cad); //Remover semilla
    $cad = $arrCad[0];
    return $cad;
  }

}
