<?php
	
	include 'formx_builder.php';
	include_once 'formx_render_basic.php';
	
	define('BASE_URL', '');
	
	function get_formx_field_path() {
		
		return 'fields/';
	}
	
	function get_formx_filter_path() {
		
		return 'filters/';
	}
	
	function get_formx_validate_path() {
		
		return 'validates/';
	}
	 
	$formx = new formx_builder(); 
	 
	$formx->add_text('text')
		->set_label('Text')
		->set_value('default')
		->set_validates(array('required' => 'Custom error message for [field]')); // use [field] so formx change with label value
	 
	$formx->add_password('password')
		->set_label('Password');
		
	$formx->add_hidden('hidden');
	
	$formx->add_textarea('textarea')
		->set_label('Textarea');
		
	$formx->add_select('select')
		->set_label('Select')
		->set_value(array('' => 'Selecione', 0 => 'OK', 1 => 'NO'));
		
	$formx->add_select('select2')
		->set_label('Select 2')
		->set_value(array('' => 'Selecione', 0 => 'OK', 1 => 'NO'), 1); //1 selected

	$formx->add_radio('radio')
		->set_label('Radio')
		->set_value(array('key1' => 'OK', 'key2' => 'NO'));
		
	$formx->add_multiple('multiple')
		->set_label('Multiple')
		->set_value(array('key1' => 'OK', 'key2' => 'NO'));
		
	$formx->add_checkbox('checkbox')
		->set_label('Checkbox')
		->set_value(array('key1' => 'OK', 'key2' => 'NO'));
		
	$formx->add_file('file')
		->set_label('File');
		
	$formx->add_submit_button('submit')
		->set_label('Enviar');
		
	# form was submitted ?
	if ($formx->is_post()) {
		
		# without errors ?
		if ($formx->has_error() == false) {
			
			# retrieve post value for html_field_name text input
			echo 'Text: '; 
			var_dump($formx->get_posted('text'));
			
			echo 'Password: '; 
			var_dump($formx->get_posted('password'));
			
			echo 'Hidden:'; 
			var_dump($formx->get_posted('hidden'));
			
			echo 'Textarea:'; 
			var_dump($formx->get_posted('textarea'));
			
			echo 'Select: ';
			var_dump($formx->get_posted('select'));
			
			echo 'Select2:';
			var_dump($formx->get_posted('select2'));
			
			echo 'Radio:';
			var_dump($formx->get_posted('radio'));
			
			echo 'Multiple:';
			var_dump($formx->get_posted('multiple'));
			
			echo 'Checkbox:';
			var_dump($formx->get_posted('checkbox'));
			
			echo 'File:';
			var_dump($formx->get_posted('file'));
		}
	}
	
	# custom form rendering
	$html = new formx_render_basic($formx);
	
	echo $html;