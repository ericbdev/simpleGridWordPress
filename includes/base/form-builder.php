<?php
date_default_timezone_set('America/New_York');
/**
 * TODO
 *
 * - Get site name from WP
 * - Build some kind of email builder
 * - Build some kind of validation system which will make an K->V array of Label / Message for email
 *
 **/
class form_builder {
	public $request;
	public $post;
	public $strings;
	public $tables;
	public $errorVals;
	public $placeholder;
	public $errors;
	public $lang;
	public $proceed;
	public $staticVars;
	public $date;
	public $userEntered;
	public $sendTo;
	public $sendFromAddr;
	public $sendFromName;


	public function __construct($lang) {
		///* @var wpdb $wpdb */
		//$this->wpdb = $wpdb;
		//$this->wpdb->show_errors();
		//$this->wpdb->hide_errors();
		$this->sendFromName       = (function_exists('get_bloginfo') ? get_bloginfo('name') : 'Site Name');
		$this->sendFromAddr       = (function_exists('admin_email') ? get_bloginfo('admin_email') : 'info@site.com');
		$this->sendTo       = "";
		$this->lang        = $lang;
		$this->userEntered = array(
			'entered' => false,
			'message' => ''
		);
		$this->proceed     = true;
		$this->date        = date("Y-m-d H:i:s");
		$this->request     = (isset($_REQUEST) ? $_REQUEST : false);
		$this->post        = (isset($_POST) ? $_POST : false);

		$this->errorVals = array(
			'form_full_name'       => array(
				'required' => _x('Please enter your name', 'Form', theme_domain())
			),
			'form_email'       => array(
				'required' => _x('Please enter a valid e-mail address', 'Form', theme_domain()),
				'valid'    => _x('Please enter a valid e-mail address', 'Form', theme_domain()),
			),
			'form_telephone'       => array(
				'required' => _x('Please enter your phone number', 'Form', theme_domain()),
			),
			'form_message'   => array(
				'required' => _x('Please enter a message', 'Form', theme_domain()),
			),
		);

		$this->placeholder = array(
			'form_full_name'     => _x('Name', 'Form', theme_domain()),
			'form_email'     => _x('Email', 'Form', theme_domain()),
			'form_telephone'     => _x('Phone', 'Form', theme_domain()),
			'form_message' => _x('Message', 'Form', theme_domain()),
		);

	}
	/**
	 * See WordPress' Docs
	 * @param $value
	 * @return array|string
	 */
	private function stripslashes_deep($value) {
		if ( is_array($value) ):
			$value = array_map('stripslashes_deep', $value);
		elseif ( is_object($value) ):
			$vars = get_object_vars( $value );
			foreach ($vars as $key=>$data) {
				$value->{$key} = $this->stripslashes_deep( $data );
			}
		elseif ( is_string( $value ) ):
			$value = stripslashes($value);
		endif;

		return $value;
	}
	/** See WordPress' Docs**/
	private function parse_str($string, &$array){
		parse_str( $string, $array );
		if ( get_magic_quotes_gpc() ):
			$array = $this->stripslashes_deep( $array );
		endif;
	}
	/** Merges arrays to provide a default**/
	private function parse_args( $args, $defaults = '' ) {
		if ( is_object( $args ) )
			$r = get_object_vars( $args );
		elseif ( is_array( $args ) )
			$r =& $args;
		else
			$this->parse_str( $args, $r );

		if ( is_array( $defaults ) )
			return array_merge( $defaults, $r );
		return $r;
	}

	private function less_one_day($date) {
		$lastEntry        = strtotime($date);
		$lastEntryPlusOne = $lastEntry + (24 * 60 * 60);
		$currentDate      = strtotime($this->date);

		return ($lastEntryPlusOne <= $currentDate);
	}
	private function is_type($needle, $haystack){
		$return = false;
		if(is_array($haystack)):
			$return = in_array($needle, $haystack);
		else:
			return ($needle == $haystack);
		endif;
		return $return;
	}


	private function different_day($date) {
		$lastEntry = array(
			'year' => date('Y', strtotime($date)),
			'month' => date('m', strtotime($date)),
		    'day' => date('d', strtotime($date))
		);
		$currentDate = array(
			'year' => date('Y', strtotime($this->date)),
			'month' => date('m', strtotime($this->date)),
		    'day' => date('d', strtotime($this->date))
		);
		$lastDay = intval($lastEntry['day']);
		$currentDay = intval($currentDate['day']);

		return ($lastDay !== $currentDay);
	}

	private function convert( $str ) {
		return iconv( "UTF-8", "ISO-8859-1", $str );
	}


	public function sanitize($field_name, $type = 'text') {
		$var = $this->request[$field_name];
		return trim($var);
	}

	public function has_error($field_name){
		if (isset($this->errors[$field_name]) && $this->errors[$field_name]):
			return true;
		else:
			return false;
		endif;
	}

	public function get_placeholder($field_name){
		return $this->placeholder[$field_name];
	}

	function get_error_message($field_name, $type = false){
		$message_type = ($type ? $type : 'required');
		if(isset($this->errorVals[$field_name][$message_type])):
			return $this->errorVals[$field_name][$message_type];
		else:
			return $this->errorVals['default']['required'];
		endif;
	}

	/** TODO: Enable retrieving of different error messages through use of $type and $this->has_error($field_name, $type)**/
	public function get_label($field_name, $type = false, $has_error = false) {
		if($has_error):
			echo "<label for='$field_name' class='error'>".$this->get_error_message($field_name, $type)."</label>";
		else:
			echo "<label for='$field_name'>{$this->placeholder[$field_name]}".($type ? ' *': '')."</label>";
		endif;
	}
	public function get_error_label($field_name, $type = 'required') {
		if ($this->has_error($field_name)):
			$this->get_label($field_name, $type, true);
			exit();
		endif;
		return false;
	}


	public function is_valid($field_name, $type = 'text') {
		$return = false;

		if ($type == 'email'):
			/** TODO: FIND A USE FOR THIS? **/
		endif;
		/**  TODO: a string of '0' could be valid in a given context**/
		if (isset($this->request[$field_name])) :
			if ($this->request[$field_name] !== ''
				&& $this->request[$field_name] !== 0
				&& $this->request[$field_name] !== '0'):
				$return = true;
			endif;
		endif;
		return $return;
	}

	private function create_internal_options($field_name, $extras){
		$return = '';
		if ($extras['placeholder']) $return .= "<option value=''>{$this->placeholder[$field_name]}</option>";
		foreach ($extras['options'] as $option):
			$return .= "<option value='{$option}'>{$option}</option>";
		endforeach;

		return $return;

	}
	private function create_internal($field_name, $extras) {
		$return = '';

		switch ($extras['type']):
			case 'phone':
				break;
		endswitch;
		if ($extras['placeholder'] && !$this->is_type($extras['type'], 'select')) $return .= " placeholder='{$this->placeholder[$field_name]}'";
		if ($extras['classes']) $return .= " class='{$extras['classes']}'";

		$return .= " name='$field_name' id='{$extras['field_id']}'";

		if ($this->is_valid($field_name, $extras['type']) && $this->is_type($extras['type'], array('text', 'phone','email'))):
			$return .= " value='{$this->request[$field_name]}'";
		endif;

		if ($this->is_valid($field_name, $extras['type']) && ($this->is_type($extras['type'], array('checkbox', 'radio')))):
			$return .= " checked='checked'";
		endif;
		return $return;

	}

	public function create($field_name, $optional = array()) {

		$defaults = array(
			'required'    => false,
			'field_id'    => false,
			'type'        => 'text',
			'placeholder' => false,
			'classes'     => false
		);

		if (!$optional['field_id']) $optional['field_id'] = $field_name . '_id';

		$extras = $this->parse_args($optional, $defaults);

		if (!$extras['field_id']) $extras['field_id'] = $field_name;
		$return = '';


		switch ($extras['type']):
			case 'textarea':
				$return .= "<textarea";
				$return .= $this->create_internal($field_name, $extras);
				$return .= '>';
				if($this->is_valid($field_name, $extras['type'])):
					$return .= $this->request[$field_name];
				endif;
				$return .= "</textarea>";
				break;

			case 'checkbox':
			case 'radio':
				/* TODO: This doesnt look finished? */
				$return .= "<input";
				$return .= " type='{$extras['type']}' ";
				$return .= $this->create_internal($field_name, $extras);
				$return .= "/>";

				break;
			case 'select':
				$return .= "<select";
				$return .= $this->create_internal($field_name, $extras);
				$return .= ">";
				if(isset($extras['options'])):
					$return .= $this->create_internal_options($field_name, $extras);
				endif;

				$return .="</select>";

				break;

			case 'text':
			case 'phone':
			case 'email':
			default:
				$return .= "<input";
				$return .= " type='text' ";
				$return .= $this->create_internal($field_name, $extras);
				$return .= "/>";

				break;
		endswitch;

		echo $return;
	}
}

$form = new form_builder(get_lang_active());