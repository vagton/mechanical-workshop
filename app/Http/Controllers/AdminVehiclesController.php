<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

class AdminVehiclesController extends CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "id";
		$this->limit = "20";
		$this->orderby = "id,desc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = false;
		$this->button_action_style = "button_icon";
		$this->button_add = true;
		$this->button_edit = true;
		$this->button_delete = true;
		$this->button_detail = false;
		$this->button_show = false;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = true;
		$this->table = "vehicles";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Cliente - Nome", "name" => "clients_id", "join" => "clients,name"];
		$this->col[] = ["label" => "Cliente - CPF", "name" => "clients_id", "join" => "clients,cpf", "callback_php" => 'Callback::cpf($row->clients_cpf)'];
		$this->col[] = ["label" => "Marca", "name" => "brand"];
		$this->col[] = ["label" => "Modelo", "name" => "model"];
		$this->col[] = ["label" => "Placa", "name" => "plate"];
		$this->col[] = ["label" => "Ano", "name" => "year"];
		$this->col[] = ["label"=>"Cor","name"=>"color"];
		$this->col[] = ["label" => "KM Atual", "name" => "km_current", "callback_php" => 'Callback::number($row->km_current,1)'];
		$this->col[] = ["label"=>"Tipo de Combustível","name"=>"type_fuel"];
		$this->col[] = ["label" => "Criado", "name" => "created_at", "callback_php" => 'Callback::date($row->created_at)'];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'Cliente', 'name' => 'clients_id', 'type' => 'select2', 'validation' => 'required|exists:clients,id', 'width' => 'col-sm-10', 'datatable' => 'clients,name', 'datatable_format' => 'name,\' - \',cpf', 'datatable_ajax' => 'true'];
		$this->form[] = ['label' => 'Marca', 'name' => 'brand', 'type' => 'text', 'validation' => 'required|string|min:1|max:190', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Modelo', 'name' => 'model', 'type' => 'text', 'validation' => 'required|string|min:1|max:190', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Placa', 'name' => 'plate', 'type' => 'text', 'validation' => 'required|string|alpha_num|min:6|max:20', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Ano', 'name' => 'year', 'type' => 'text', 'validation' => 'integer|digits:4|min:'.(date('Y')-120).'|max:'.(date('Y')+3), 'width' => 'col-sm-10'];
		$this->form[] = ['label'=>'Cor','name'=>'color','type'=>'select2','validation'=>'string|min:1|max:190','width'=>'col-sm-10','dataenum'=>'Prata;Branco;Preto;Azul;Vermelho;Marrom;Verde;Cinza;Rosa;Ouro;Vinho;Laranja;Roxo;Ciano'];
		$this->form[] = ['label' => 'Km Atual', 'name' => 'km_current', 'type' => 'text', 'validation' => 'numeric|min:0|max:999999', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Tipo de Combustível', 'name' => 'type_fuel', 'type' => 'select', 'validation' => 'string|max:190', 'width' => 'col-sm-10', 'dataenum' => 'Diesel;Gasolina;Álcool;Etanol;Gás Natural;Elétrico'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ['label' => 'Marca', 'name' => 'brand', 'type' => 'text', 'validation' => 'required|string|min:1|max:190', 'width' => 'col-sm-10'];
		//$this->form[] = ['label' => 'Modelo', 'name' => 'model', 'type' => 'text', 'validation' => 'required|string|min:1|max:190', 'width' => 'col-sm-10'];
		//$this->form[] = ['label' => 'Placa', 'name' => 'plate', 'type' => 'text', 'validation' => 'required|string|alpha_num|min:6|max:20', 'width' => 'col-sm-10'];
		//$this->form[] = ['label' => 'Km Atual', 'name' => 'km_current', 'type' => 'money', 'validation' => 'numeric|min:0|max:999999', 'width' => 'col-sm-10', 'priceformat_parameters' => ['thousandsSeparator' => '.', 'centsSeparator' => ',', 'centsLimit' => 1]];
		//$this->form[] = ['label' => 'Cliente', 'name' => 'clients_id', 'type' => 'select2', 'validation' => 'required|exists:clients,id', 'width' => 'col-sm-10', 'datatable' => 'clients,id', 'datatable_format' => 'name,\' - \',cpf', 'datatable_ajax' => 'true'];
		# OLD END FORM

		/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
		$this->sub_module = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
		$this->addaction = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
		$this->button_selected = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
		$this->alert        = array();



		/* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
		$this->index_button = array();



		/* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
		$this->table_row_color = array();


		/*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
		$this->index_statistic = array();



		/*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
		$this->script_js = '

			var km = $("input[name=km_current]");
			var decimal = parseFloat(km.val()).toFixed(1);
			km.val(decimal);
			km.mask("999990.0", {reverse:true});

			$("input[name=year]").mask("9999");

		';


		/*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
		$this->pre_index_html = null;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
		$this->post_index_html = null;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
		$this->load_js = array();



		/*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
		$this->style_css = NULL;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
		$this->load_css = array();
	}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	public function hook_query_index(&$query)
	{
		//Your code here

	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{
		//Your code here
	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	public function hook_before_add(&$postdata)
	{ }

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	public function hook_after_add($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	public function hook_before_edit(&$postdata, $id)
	{
		//Your code here
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_edit($id)
	{
		//Your code here 

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_before_delete($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id)
	{
		//Your code here

	}



	//By the way, you can still create your own method in here... :) 


}
