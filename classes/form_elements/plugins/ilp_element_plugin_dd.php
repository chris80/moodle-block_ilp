<?php

require_once($CFG->dirroot.'/blocks/ilp/classes/form_elements/ilp_element_plugin_itemlist.php');

class ilp_element_plugin_dd extends ilp_element_plugin_itemlist{
	
	public $tablename;
	public $data_entry_tablename;
	public $items_tablename;
	protected $selecttype;	//1 for single, 2 for multi
	protected $id;		//loaded from pluginrecord
	
    /**
     * Constructor
     */
    function __construct() {
    	parent::__construct();
    	$this->tablename = "block_ilp_plu_dd";
    	$this->data_entry_tablename = "block_ilp_plu_dd_ent";
	$this->items_tablename = "block_ilp_plu_dd_items";
    }
	
	
	/**
     * TODO comment this
     * beware - different from parent method because of variable select type
     * radio and other single-selects inherit from parent
     */
    public function load($reportfield_id) {
		$reportfield		=	$this->dbc->get_report_field_data($reportfield_id);	
		if (!empty($reportfield)) {
			$this->reportfield_id	=	$reportfield_id;
			$this->plugin_id	=	$reportfield->plugin_id;
			$plugin			=	$this->dbc->get_form_element_plugin($reportfield->plugin_id);
			$pluginrecord		=	$this->dbc->get_form_element_by_reportfield($this->tablename,$reportfield->id);
			if (!empty($pluginrecord)) {
				$this->id			=	$pluginrecord->id;
				$this->label			=	$reportfield->label;
				$this->description		=	$reportfield->description;
				$this->required			=	$reportfield->req;
				$this->position			=	$reportfield->position;
				//$this->optionlist		=	$pluginrecord->optionlist;
				//if( empty( $this->selecttype ) ){
					$this->selecttype	=	$pluginrecord->selecttype;
				//}
			}
		}
		return false;	
    }	

	

    public function audit_type() {
        return get_string('ilp_element_plugin_dd_type','block_ilp');
    }
    
    /**
    * function used to return the language strings for the plugin
    */
    function language_strings(&$string) {
        $string['ilp_element_plugin_dd'] 			= 'Select';
        $string['ilp_element_plugin_dd_type'] 			= 'select';
        $string['ilp_element_plugin_dd_description'] 		= 'A drop-down selector';
	$string[ 'ilp_element_plugin_dd_optionlist' ] 		= 'Option List';
	$string[ 'ilp_element_plugin_dd_single' ] 		= 'Single select';
	$string[ 'ilp_element_plugin_dd_multi' ] 		= 'Multi select';
	$string[ 'ilp_element_plugin_dd_typelabel' ] 		= 'Select type (single/multi)';
	$string[ 'ilp_element_plugin_dd_existing_options' ] 	= 'existing options';
	$string[ 'ilp_element_plugin_error_item_key_exists' ]		= 'The following key already exists in this element';
	$string[ 'ilp_element_plugin_error_duplicate_key' ]	= 'Duplicate key';
        
        return $string;
    }

   	/**
     * Delete a form element
     */
    public function delete_form_element($reportfield_id) {
    	return parent::delete_form_element($this->tablename, $reportfield_id);
    }
    
/*
these 2 methods now in parent
	protected function get_option_list_text( $reportfield_id , $sep="\n" ){
		$optionlist = $this->get_option_list( $reportfield_id );
		$rtn = '';
		if( !empty( $optionlist ) ){
			foreach( $optionlist as $key=>$value ){
				$rtn .= "$key:$value$sep";
			}
		}
		return $rtn;
	}
	public function return_data( &$reportfield ){
		$data_exists = $this->dbc->plugin_data_item_exists( $this->tablename, $reportfield->id );
		if( empty( $data_exists ) ){
			//if no, get options list
			$reportfield->optionlist = $this->get_option_list_text( $reportfield->id );
		}
		else{
			$reportfield->existing_options = $this->get_option_list_text( $reportfield->id , '<br />' );
		}
	}
*/
	/**
	* handle user input
	**/
	 public	function entry_specific_process_data($reportfield_id,$entry_id,$data) {
		/*
		* parent method is fine for simple form element types
		* dd types will need something more elaborate to handle the intermediate
		* items table and foreign key
		*/
		return $this->entry_process_data($reportfield_id,$entry_id,$data); 	
	 }
}

