<?php

namespace Drupal\company\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

class WorkorderForm extends FormBase {
  public function getFormId() {
    return 'workorder_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {  
  
    $libobj = new \Drupal\library\Lib\LibController;
    $wkobj = new \Drupal\company\Model\WorkOrderModel;

    $mode = $libobj->getActionMode();
	if($mode == 'edit'){
      $pk = $libobj->getIdFromUrl();	
      $data = $wkobj->getWorkOrderDetailsById($pk);
    }
	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attached']['library'][] = 'company/workorder-lib';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['department']['#prefix'] = '<div class="row"> <div class="panel panel-inverse">
                                      <div class="panel-heading">Work Order</div><div class="panel-body">';
    $form['workorder']['workname'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Work order Name:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
	  '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codevalues : '',
    );
	
	 $form['workorder']['workcode'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Work order No:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
	  '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codename : '',
    );	
	$form['workorder']['submit'] = array(
      '#type'          => 'submit',
      '#default_value' => ($mode == 'add') ? $this->t('Submit') : $this->t('Update'),
      '#button_type'   => 'primary',
      '#attributes'    => ['class' => ['btn btn-info']],
      '#prefix'        => '<br/><div class="row"><div class="col-md-2"></div><div class="col-md-4">',
      '#suffix'        => '',
    );

    $form['workorder']['cancel'] = array(
      '#type' => 'link',
	  '#title' => t('Cancel'),
      '#attributes'               => ['class'   => ['btn btn-default']],
      //'#limit_validation_errors'  => array(),
      '#suffix'                   => '</div></div>',
      '#url' => \Drupal\Core\Url::fromRoute('company.projectlist'),
    );
	$form_state->setCached(FALSE);
    return $form;

    }
 
  public function validateForm(array &$form, FormStateInterface $form_state) { 
   
  }
  
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
		
		$worobj = new \Drupal\company\Model\WorkorderModel;	
		$field = $form_state->getValues();
		$workname = $field['workname'];
		$workcode = $field['workcode'];
		$field = array(	'codename'	=>	$workcode,
						'codevalues'=>	$workname	
					);	
	$libobj = new \Drupal\library\Lib\LibController;
	$mode = $libobj->getActionMode();
    if($mode == 'add' )
    { 
		$worobj->setWorkOrder( $field );
		drupal_set_message("Word order has been created.");
    }
    if($mode == 'edit' )
    {
      $pk = $libobj->getIdFromUrl();
      $worobj->updateWorkOrder($field,$pk);
      drupal_set_message("succesfully Updated.");
    }
		
	$form_state->setRedirect('company.projectlist');
	}
	
  //  $form_state->setRebuild();
  }


?>
