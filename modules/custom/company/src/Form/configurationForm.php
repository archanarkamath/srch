<?php
/**
 * @file
 * Contains \Drupal\company\Form\ConfigurationForm.
 */

namespace Drupal\company\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\library\Lib\LibController;

class ConfigurationForm extends FormBase {

  public function getFormId() {
	return 'configuration_form';

  }
  
public function buildForm(array $form, FormStateInterface $form_state) {
	global $base_url;
	$user = \Drupal::currentUser();
	
	$configobj = new \Drupal\company\Model\ConfigurationModel;
	
	$form['company']['#attributes']['enctype'] = "multipart/form-data";
	$form['#attached']['library'][] = 'singleportal/bootstrap-toggle';
	$form['company']['#prefix'] = ' <div class="row">
									
				<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="'.$base_url.'/organisation/config" role="tab"  ><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> <b>General</b></span></a>
				</li>
				<li role="presentation" class="">
					<a href="'.$base_url.'/organisation/config/shift"  role="tab"  ><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs"><b>Timing</b></span></a>
				</li>
				</ul><br/><br/>';
	$form['company']['#suffix'] = '</div>';
	
	
	$data = $configobj->getEmpIdType();
	
	$form['company']['empidtype'] = array(
      '#type' => 'checkbox',
      '#title' => t('Automatic EmployeeID'),
      //'#required' => TRUE,
 	  '#attributes' => ['class' => ['form-control'], 'data-toggle' => 'toggle', 
								'data-on' => 'ON', 'data-off' => 'OFF', 
								'data-onstyle' => 'info'],
	 '#prefix' => '<div class="row">',
	 '#default_value' => !empty($data)? ($data->codevalues == 'Automatic')? 1 : 0 : '',
	 '#disabled' => ($user->hasPermission('admin configuration')) ? false : true,
   '#field_suffix' => '<i class="mdi mdi-help-circle" title="The code which is being used for employee ID generation. For EX:- If your code is ABC then Employee ID will be ABC001, ABC021, ABC0156" data-toggle="tooltip"></i>',

    );
	
	$form['company']['codeformat'] = array(
      '#type' => 'textfield',
      '#title' => t('Code Format:'),
	  '#attributes' => ['class' => ['form-control']],
	  '#states' => [
        'visible' => [
          ':input[name="empidtype"]' => [ 'checked' => TRUE,],
        ],
      ],
	 //'#prefix' => '<div class="row">',
	 '#suffix' => '</div>',
	 '#default_value' => !empty($data)? $data->description : '',
	 '#disabled' => ($user->hasPermission('admin configuration')) ? false : true,
    );
	
	
	$form['company']['#type'] = 'actions';
    $form['company']['submit'] = array(
      '#type' => 'submit',
      '#default_value' => $this->t('Submit'),
      '#button_type' => 'primary',
	  '#attributes' => ['class' => ['btn btn-info']],
	  '#prefix' => '<br/><div class="row"><div class="col-md-2"></div><div class="col-md-4">',
	  '#suffix' => '',
	 '#disabled' => ($user->hasPermission('admin configuration')) ? false : true,
		  );
    return $form;

	  
  }
  
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
	
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
	$configobj = new \Drupal\company\Model\ConfigurationModel;	
	
    $field = $form_state->getValues();
	$employeeIdType = ($field['empidtype']) ? 'Automatic' : 'Manual';
    
	 $field  = array(
              'codevalues'  =>  $employeeIdType,
              'description' =>  ($employeeIdType == 'Automatic') ? $field['codeformat'] : '',              
          );
		 
		 
	 $configobj->updateEmpIdType($field);
	 drupal_set_message("Employee ID Configuration has been updated.");
	 
  }
}
?>
