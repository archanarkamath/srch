<?php

namespace Drupal\hr\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

class UnitInformationForm extends FormBase {
  public function getFormId() {
    return 'unitinformation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {  
  
    	
	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['unit']['#prefix'] = '<div class="row"> <div class="panel panel-inverse">
                                      <div class="panel-heading">Unit Information</div><div class="panel-body">';
    $form['unit']['id'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Employee ID:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
      '#suffix'        => '</div>',
      );
	
	
    $form['department']['submit'] = array(
      '#type'          => 'submit',
      '#default_value' => 'Submit',
      '#button_type'   => 'primary',
      '#attributes'    => ['class' => ['btn btn-info']],
      '#prefix'        => '<br/><div class="row"><div class="col-md-2"></div><div class="col-md-4">',
      '#suffix'        => '',
    );

    $form['company']['#suffix'] = '</div></div>';
    return $form;

    }
 
  public function validateForm(array &$form, FormStateInterface $form_state) { 
    
  }
  
	public function ActionCancel(array &$form, FormStateInterface $form_state)
	{	  
    
	}

  public function submitForm(array &$form, FormStateInterface $form_state) {
    

  }
}
?>
