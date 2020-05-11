<?php

namespace Drupal\company\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

class DesignationForm extends FormBase {
  public function getFormId() {
    return 'designation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {  
  
    $libobj = new \Drupal\library\Lib\LibController;
    $desobj = new \Drupal\company\Model\DesignationModel;
    $depobj = new \Drupal\company\Model\DepartmentModel;

    $mode = $libobj->getActionMode();
    $form_title = 'Add Designation Details';
    if($mode == 'edit'){
      $pk = $libobj->getIdFromUrl();	
      $data = $desobj->getDesignationDetailsById($pk);
	  $form_title = 'Edit Designation Details';
    }
	
	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['designation']['#prefix'] = '<div class="row"> <div class="panel panel-inverse">
                                      <div class="panel-heading"> ' .$form_title. '</div><div class="panel-body">';
    $form['designation']['name'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Designation Name:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row"><div class="col-md-12">',
      '#suffix'        => '</div></div>',
      '#default_value' => isset($data)? $data->codevalues : '',
    );
    $form['designation']['code'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Designation Code:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row"><div class="col-md-12">',
      '#suffix'        => '</div></div>',
      '#default_value' => isset($data)? $data->codename : '',
      '#disabled'      =>  isset($data)? "disabled" : '',
      '#field_suffix' => '<i class="fadehide mdi mdi-help-circle" title="Unique code for this Designation used for internal backend purpose. Cannot be changed once added" data-toggle="tooltip"></i>',

    );
    
    	$deplist = $depobj->getAllDepartmentDetails();
      $dept_option[''] = 'Select Department';
      foreach($deplist AS $item)
      {
      $dept_option[$item->codename]  = $item->codevalues;
      }
      
      if($mode == 'edit'){
        $codepk = $data->parent;
        $res = $depobj->getDepartmentDetailsById($codepk);
        $dept = $res->codename;
      }
      
    $form['designation']['departmentlist'] = array(
      '#type'          => 'select',
      '#title'         => t('Department :'),
      '#options'       => $dept_option,
      '#attributes'    => ['class' => ['form-control', 'validate[required]']],
      '#prefix'        => '<div class="row"><div class="col-md-12">',
      '#suffix'        => '</div></div>',
      '#default_value' => isset($data)? $dept : '',
      '#field_suffix' => '<i class="fadehide mdi mdi-help-circle" title="Select Department name in which this Designation belongs" data-toggle="tooltip"></i>',

    );
   // $form['designation']['#type'] = 'actions';
    $form['designation']['submit'] = array(
      '#type'          => 'submit',
      '#default_value' => ($mode == 'add') ? $this->t('Submit') : $this->t('Update'),
      '#button_type'   => 'primary',
      '#attributes'    => ['class' => ['btn btn-info']],
      '#prefix'        => '<br/><div class="row"><div class="col-md-2"></div><div class="col-md-6">',
      '#suffix'        => '',
    );

    $form['designation']['cancel'] = array(
      '#type' => 'link',
	  '#title' => t('Cancel'),
      '#attributes' => ['class'   => ['btn btn-default']],
      '#prefix'    => '',
      '#suffix'    => '</div></div>',	  
      '#url' => \Drupal\Core\Url::fromRoute('company.Designationview'),
    );
    $form['designation']['cancel']['#submit'][] = '::ActionCancel';
    $form['company']['#suffix'] = '</div></div>';
    return $form;

    }
 
  public function validateForm(array &$form, FormStateInterface $form_state) { 
  }
  
	public function ActionCancel(array &$form, FormStateInterface $form_state)
	{	  
    $form_state->setRedirect('company.Designationview');
	}

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $libobj = new \Drupal\library\Lib\LibController;
    $desobj = new \Drupal\company\Model\DesignationModel;
    $depobj = new \Drupal\company\Model\DepartmentModel;

    $field = $form_state->getValues();
    $name = $field['name'];
    $codename = $field['code'];
    $parent = $field['departmentlist'];
    
    $parent = $depobj->getDepartmentId($parent);
   
    $field  = array(
      'codevalues' =>  $name,
      'codename'   =>  $codename,
      'parent'   =>  $parent->codepk,
      'codetype'   => 'designation',             
     );

    $mode = $libobj->getActionMode();
    if($mode == 'add' )
    { 
      $desobj->setDesignation($field);
      drupal_set_message($field['codevalues'] . " has been succesfully created.");
    }
    if($mode == 'edit' )
    {
      $pk = $libobj->getIdFromUrl();
      $desobj->updateDesignation($field,$pk);
      drupal_set_message($field['codevalues'] . " has succesfully Updated.");
    }
   
   $form_state->setRedirect('company.Designationview');

  }
}
?>
