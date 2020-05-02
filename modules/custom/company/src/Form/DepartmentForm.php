<?php

namespace Drupal\company\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

class DepartmentForm extends FormBase {
  public function getFormId() {
    return 'department_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {  
  
    $libobj = new \Drupal\library\Lib\LibController;
    $brnobj = new \Drupal\company\Model\DepartmentModel;

    $mode = $libobj->getActionMode();
    
    if($mode == 'edit'){
      $pk = $libobj->getIdFromUrl();	
      $data = $brnobj->getDepartmentDetailsById($pk);
    }
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['department']['#prefix'] = '<div class="row"> <div class="panel panel-inverse">
                                      <div class="panel-heading"> Department details</div><div class="panel-body">';
    $form['department']['name'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Department Name:'),
      '#required'      => TRUE,
      '#attributes'    => ['class' => ['form-control']],
      '#prefix'        => '<div class="row">',
      '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codevalues : '',
    );
    $form['department']['code'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Department Code:'),
      '#required'      => TRUE,
      '#attributes'    => ['class' => ['form-control']],
      '#prefix'        => '<div class="row">',
      '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codename : '',
      '#disabled'      =>  isset($data)? "disabled" : '',
      '#field_suffix' => '<i class="mdi mdi-help-circle" title="Unique Code for this department used for internal backend purpose. Cannot be changed once added" data-toggle="tooltip"></i>',

    );
    $form['department']['#type'] = 'actions';
    $form['department']['submit'] = array(
      '#type'          => 'submit',
      '#default_value' => ($mode == 'add') ? $this->t('Submit') : $this->t('Update'),
      '#button_type'   => 'primary',
      '#attributes'    => ['class' => ['btn btn-info']],
      '#prefix'        => '<br/><div class="row"><div class="col-md-2"></div><div class="col-md-4">',
      '#suffix'        => '',
    );

    $form['department']['cancel'] = array(
      '#type' => 'link',
	  '#title' => t('Cancel'),
      '#attributes'               => ['class'   => ['btn btn-default']],
      //'#limit_validation_errors'  => array(),
      '#prefix'                   => '',
      '#suffix'                   => '</div></div>',
      '#url' => \Drupal\Core\Url::fromRoute('company.departmentview'),
    );
    //$form['department']['cancel']['#submit'][] = '::ActionCancel';
    $form['company']['#suffix'] = '</div></div>';
        return $form;

    }
 
  public function validateForm(array &$form, FormStateInterface $form_state) { 
  }
  
	public function ActionCancel(array &$form, FormStateInterface $form_state)
	{	  
    $form_state->setRedirect('company.departmentview');
	}

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $libobj = new \Drupal\library\Lib\LibController;
    $brnobj = new \Drupal\company\Model\DepartmentModel;

    $field = $form_state->getValues();
    $name = $field['name'];
    $codename = $field['code'];

    $field  = array(
      'codevalues' =>  $name,
      'codename'   =>  $codename,
      'codetype'   => 'department',             
     );

    $mode = $libobj->getActionMode();
    if($mode == 'add' )
    { 
      $brnobj->setDepartment($field);
      drupal_set_message("succesfully saved.");
    }
    if($mode == 'edit' )
    {
      $pk = $libobj->getIdFromUrl();
      $brnobj->updateDepartment($field,$pk);
      drupal_set_message("succesfully Updated.");
    }
   
   $form_state->setRedirect('company.departmentview');

  }
}
?>