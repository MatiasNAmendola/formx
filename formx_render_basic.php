<?php

	class formx_render_basic {

		private $html_form = '';
		
		public function __construct(formx_builder $formx, $show_errors_on_form = true, $show_errors_on_field = false) {
			
			if ($show_errors_on_form | $show_errors_on_field) {
				
				$show_errors_on_form  = $show_errors_on_form == false && $show_errors_on_field ? false : true;
				$show_errors_on_field = $show_errors_on_form == false && $show_errors_on_field ? true : false;
			}
			
			$this->html_form = '
				<form action="' . $formx->get_form_action() . '" method="' . $formx->get_form_method() . '" enctype="' . $formx->get_form_enctype() . '" id="' . $formx->get_form_id() . '" class="' . $formx->get_form_class() . ' formx">
					<fieldset>
			';

			if ($formx->get_form_legend() !== '') {
				$this->html_form.= '<legend>' . $formx->get_form_legend() . '</legend>';
			}
			
			$this->render_error($formx->get_form_errors(), $show_errors_on_form);
			
			$form_fields = $formx->get_form_fields();
			
			foreach ($form_fields as $field_object_name => $field_object) {
				
				$field_value = $field_object->get_is_post() ? $field_object->get_post_value() : $field_object->get_value();
				
				switch ($field_object->get_type()) {
					
					case 'text':
						
						$this->html_form.= '<div class="formx_text ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_text_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
				
						break;
						
					case 'textarea':
						
						$this->html_form.= '<div class="formx_textarea ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_textarea_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'select':
						
						$this->html_form.= '<div class="formx_select ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_select_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'radio':
						
						$this->html_form.= '<div class="formx_radio ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_radio_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'password':
						
						$this->html_form.= '<div class="formx_password ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_password_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'multiple':
						
						$this->html_form.= '<div class="formx_multiple ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_multiple_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'hidden':
						
						$this->render_hidden_field($field_object, $field_value);
						
						break;
						
					case 'file':
						
						$this->html_form.= '<div class="formx_file' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_file_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'checkbox':
						
						$this->html_form.= '<div class="formx_checkbox ' . (count($field_object->get_error_message()) > 0 ? 'formx_error' : '') . '">';
						$this->render_checkbox_field($field_object, $field_value);
						$this->render_error($field_object->get_error_message(), $show_errors_on_field);
						$this->html_form.= '</div>';
						
						break;
						
					case 'submit_button':
						
						$this->html_form.= '<div class="formx_button">';
						$this->html_form.= '<input type="submit" value="' . $field_object->get_label() . '" />';
						$this->html_form.= '</div>';
						
						break;
				
					default:
						break;
				}
				
			}
			
			unset($form_fields);
				
			$this->html_form.= '
					</fieldset>
				</form>
			';
		}
		
		private function render_checkbox_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<div class="formx_options">';
			
			foreach($field_value as $k => $field) { 
				
				$this->html_form.= '<input type="checkbox" value="' . $field['field_value'] . '" ' . ($field['selected'] ? 'checked="checked"' : '') . ' name="' . $field_object->get_name() . '[]" id="formx_' . $field_object->get_name() . '_' . $k . '" tabindex="' . $field_object->get_tabindex() . '" class="' . $field_object->get_html_class() . '">';
				$this->html_form.= '<label for="formx_' . $field_object->get_name() . '_' . $k . '">' . $field['field_label'] . '</label>';
				
				if (isset($field_value[$k+1])) {
					
					$this->html_form.= '<br />';	
				}
				
			}
			
			$this->html_form.= '</div>';
			$this->html_form.= '<div style="clear:both;margin:0"></div>';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_file_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<input type="file" ' . $this->render_field_options($field_object) . ' />';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_hidden_field($field_object, $field_value) {
			
			$this->html_form.= '<input type="hidden" value="' . $field_value . '" ' . $this->render_field_options($field_object) . ' />';
		}
		
		private function render_multiple_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<select name="' . $field_object->get_name() . '[]" id="formx_' . $field_object->get_name() . '" tabindex="' . $field_object->get_tabindex() . '" class="' . $field_object->get_html_class() . '" multiple="multiple">';
			
			foreach($field_value as $k => $field) { 
			
				$this->html_form.= '<option value="' . $field['field_value'] . '" ' . ($field['selected'] ? 'selected="selected"' : '') . '>' . $field['field_label'] . '</option>';
				
			}
			
			$this->html_form.= '</select>';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_password_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<input type="password" value="' . $field_value . '" ' . $this->render_field_options($field_object) . ' />';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_radio_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<div class="formx_options">';
			
			foreach($field_value as $k => $field) { 
				
				$this->html_form.= '<input type="radio" value="' . $field['field_value'] . '" ' . ($field['selected'] ? 'checked="checked"' : '') . ' name="' . $field_object->get_name() . '" id="formx_' . $field_object->get_name() . '_' . $k . '" tabindex="' . $field_object->get_tabindex() . '" class="' . $field_object->get_html_class() . '">';
				$this->html_form.= '<label for="formx_' . $field_object->get_name() . '_' . $k . '">' . $field['field_label'] . '</label>';
				
				if (isset($field_value[$k+1])) {
					
					$this->html_form.= '<br />';	
				}
				
			}
			
			$this->html_form.= '</div>';
			$this->html_form.= '<div style="clear:both;margin:0"></div>';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_select_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
				
			$this->html_form.= '<select ' . $this->render_field_options($field_object) . '>';
			
			foreach($field_value as $k => $field) { 
			
				$this->html_form.= '<option value="' . $field['field_value'] . '" ' . ($field['selected'] ? 'selected="selected"' : '') . '>' . $field['field_label'] . '</option>';
				
			}
			
			$this->html_form.= '</select>';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_textarea_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<br />';
				
			$this->html_form.= '<textarea ' . $this->render_field_options($field_object) . '>' . $field_value . '</textarea>';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_text_field($field_object, $field_value) {
			
			$this->render_default_label($field_object);
			
			$this->html_form.= '<input type="text" value="' . $field_value . '" ' . $this->render_field_options($field_object) . ' />';
			
			if ($field_object->get_help() !== '') {
				
				$this->html_form.= '<p class="help">' . $field_object->get_help() . '</p>';
			}
		}
		
		private function render_default_label($field_object) {
			
			if ($field_object->get_label() !== '') {
				
				$this->html_form.= '<label for="formx_' . $field_object->get_name() . '">' . $field_object->get_label() . (in_array('required', $field_object->get_validates()) ? ' *' : '') . '</label>';
				
			} else {
				
				$this->html_form.= '<label></label>';
				
			}
		}
		
		private function render_field_options($field_object) {
			
			return 'name="' . $field_object->get_name() . '" id="formx_' . $field_object->get_name() . '" tabindex="' . $field_object->get_tabindex() . '" class="' . $field_object->get_html_class() . '"';
		}
		
		private function render_error($errors_message, $render = false) {
			
			if ($render === false) {
				
				return;
			}
			
			if (count($errors_message) > 0) {
					
				$this->html_form.= '<ul class="formx_error">';
				
				$class = '';
				
				foreach($errors_message as $k => $error_message) {
					
					if (!isset($errors_message[$k + 1])) {
						
						$class = ' class="last"';
					}
					
					$this->html_form.= '<li'. $class . '><p>' . $error_message . '</p></li>';
				}
					
				$this->html_form.= '</ul><br />';
				
			}
			
		}
		
		public function __toString() {
			
			return $this->html_form;
		}
		
	}