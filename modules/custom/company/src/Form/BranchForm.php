<?php
/**
 * @file
 * Contains \Drupal\company\Form\BranchForm.
 */

namespace Drupal\company\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\library\Lib\LibController;

/**
 * BranchForm
 */
class BranchForm extends FormBase {
	
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
	return 'branch_form';

  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	    
	$libobj = new \Drupal\library\Lib\LibController;
	$brnobj = new \Drupal\company\Model\BranchModel;
	$encrypt = new \Drupal\library\Controller\Encrypt;
	
	$mode = $libobj->getActionMode();
	$form_state->setCached(FALSE);
  $form_title = 'Add Branch details';
	if($mode == 'edit'){
		$pk = $libobj->getIdFromUrl();
		$pk = $encrypt->decode($pk);
		$form_title = 'Edit Branch details';
		$data = $brnobj->getBranchDetailsById($pk);
	}
	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
	$form['branch']['#prefix'] = '<div class="row"> <div class="panel panel-inverse">
									<div class="panel-heading">'.$form_title.'</div><div class="panel-body">';
    $form['branch']['name'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Branch Name:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
      //'#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codevalues : '',
      '#field_suffix' => '<i class="fadehide mdi mdi-help-circle" title="Branch name of your company " data-toggle="tooltip"></i>',

    );
	
    
    $form['branch']['code'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Branch Code:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      //'#prefix'        => '<div class="row">',
      '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codename : '',
      '#disabled'      =>  isset($data)? "disabled" : '',
      '#field_suffix' => '<i class="fadehide mdi mdi-help-circle" title="Unique code for this branch used for internal backend purpose. Cannot be changed once added" data-toggle="tooltip"></i>',
    );
    	
	$statelist = $libobj->getStateList();
	
	$form['branch']['state'] = array(
		'#type'    => 'select',
		'#title'   => t('State:'),
		'#options' => $statelist,
    '#attributes'    => ['class' => ['form-control', 'validate[required]']],
		'#prefix'        => '<div class="row">',
		'#default_value' => isset($data)? $data->state : $form_state->getValue('state'),
		'#ajax' => [
					'callback' => '::getCityList',
					'wrapper' => 'citylist',
					'event' => 'change',
					'progress' => [
					  'type' => 'throbber',
					  'message' => t(''),
					],
				  ],
    );
    
	if (!empty($form_state->getValue('state'))) {
		$statePk = $form_state->getValue('state');
    }
	else{
		$statePk = isset($data)? $data->state : '';
	}
	
	$cityLst = [];
	$cityLst = $libobj->getCityListByState($statePk);
	
	$form['branch']['city'] = array(
      '#type'          => 'select',
      '#title'         => t('City:'),
      '#options'       => $cityLst,
    '#attributes'    => ['class' => ['form-control', 'validate[required]']],
      '#prefix'        => '<div id="citylist">',
      '#suffix'        => '</div></div>',
      '#default_value' => isset($data)? $data->city : $form_state->getValue('city'),
    );
    
	$form['branch']['location'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Location:'),
    '#attributes'    => ['class' => ['form-control', 'validate[required]']],
		'#prefix'        => '<div class="row">',
      

      '#default_value' => isset($data)? $data->location : '',
    );
$form['branch']['pincode'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Pincode'),
    '#attributes'    => ['class' => ['form-control', 'validate[required]'], 'id' => ['pincode']],
    '#default_value' => isset($data)? $data->pincode : '',
    		'#suffix'        => '</div>',

  ); 
  
    $form['branch']['#type'] = 'actions';
    $form['branch']['submit'] = array(
      '#type'          => 'submit',
      '#default_value' => ($mode == 'add') ? $this->t('Submit') : $this->t('Update'),
      '#button_type'   => 'primary',
      '#attributes'    => ['class' => ['btn btn-info']],
      '#prefix'        => '<div class="row"><div class="col-md-5"></div><div class="col-md-4">',
      '#suffix'        => '',
		  );
		  
	$form['branch']['cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
	  '#attributes' => ['class' => ['btn btn-default']],
	  '#limit_validation_errors' => array(),
	  '#prefix' => '',
	  '#suffix' => '</div></div>',
		  );
	$form['branch']['cancel']['#submit'][] = '::ActionCancel';
	
	$form['branch']['#suffix'] = '</div></div>';
	
  return $form;


  }
  
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
	  $field = $form_state->getValues();
  }
	public function ActionCancel(array &$form, FormStateInterface $form_state)
	{	  
	$form_state->setRedirect('company.branchview');
	}
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	
  
    $libobj = new \Drupal\library\Lib\LibController;
	$brnobj = new \Drupal\company\Model\BranchModel;
	$encrypt = new \Drupal\library\Controller\Encrypt;
	
	$field = $form_state->getValues();
   
    $name = $field['name'];
    $code = $field['code'];
    $location = $field['location'];
    $city = $field['city'];
    $state = $field['state'];
    $pincode = $field['pincode'];
    
	 $data  = array(
              'codevalues' =>  $name,
              'codename' =>  $code,
              'codetype' => 'branch',
              'location' =>  $location,
              'city' =>  $city,
              'state' =>  $state,
              'pincode' =>  $pincode,
          );
		  
		$mode = $libobj->getActionMode();

		if($mode == 'add')
		{ 
			$brnobj->setBranch($data);
			drupal_set_message("succesfully saved.");
		}
		if($mode == 'edit')
		{
			$pk = $libobj->getIdFromUrl();
			$pk = $encrypt->decode($pk);
			$brnobj->updateBranch($data, $pk);
			drupal_set_message("succesfully Updated.");
		}
  	$form_state->setRedirect('company.branchview');
	
  }
  public function getCityList(array $form, FormStateInterface $form_state)
  {
	return $form['branch']['city'];
  }
}
?>
