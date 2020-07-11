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
    $brnobj = new \Drupal\company\Model\DepartmentModel;

    $mode = $libobj->getActionMode();
    
   
	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['department']['#prefix'] = '<div class="row"> <div class="panel panel-inverse"><h3 class="box-title">Academic Details</h3>
                                      <hr/><div class="panel-body">';
    $form['workorder']['name'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Work order Name:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
      '#default_value' => isset($data)? $data->codevalues : '',
    );
	
	 $form['workorder']['workcode'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Work order No:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codevalues : '',
    );
    
	
	// repeater Field fro Team Form
	
	if (empty($name_field_qual)) {
      $name_field_qual = $form_state->set('num_qual', 1);
    }
	
	$form['team']['actions'] = [
        '#type' => 'actions',
      ];
      $form['team']['actions']['add_name_qual'] = [
        '#type' => 'submit',
		'#name' => 'Team',
		'#prefix' => $html,
        '#value' => ' ',
		'#limit_validation_errors' => array(),
        '#submit' => array('::addOneQual'),
	    '#attributes' => ['class' => ['addmore']],
        '#ajax' => [
          'callback' => '::addmoreCallbackQual',
          'wrapper' => "qual-id",
        ],
      ];
	  
	$form['workorder']['academics'] = [
      //'#prefix' => $html,
	  '#prefix' => '<div id="qual-id"><div class="panel-body">',
      '#suffix' => '</div></div>',
	  '#attributes' => ['class' => ['']],
	  '#type' => 'table',
	  '#title' => 'Sample Table',
	  '#header' => [ ['data' => 'SLNO', 'class' => 'text-center'], 
					 ['data' => 'Team Name', 'class' => 'text-center'], 
					 ['data' => 'Team Order No', 'class' => 'text-center'],
					 ['data' => 'Action', 'class' => 'text-left'],
					]
    ];
	
	
	
	
	 for ($i = 0; $i <  $form_state->get('num_qual'); $i++) {   
		$cntq = $i + 1;
     
	 $form['workorder']['academics'][$i]['slno'] = [
      '#type' 		   => 'item',
	  '#markup' => $cntq,
      '#title_display' => 'invisible',	  
    ];

    $form['workorder']['academics'][$i]['stream'] = [
      '#type' 			=> 'textfield',
      '#title' 			=> $this->t('Team Name'),
      '#default_value' 	=> isset($temp_stor[$i]['stream']) ? $temp_stor[$i]['stream'] : '',
      '#title_display' => 'invisible',
	  '#attributes'    => ['class' => ['form-control']],
    
    ];
	 $form['workorder']['academics'][$i]['university'] = [
      '#type' 			=> 'textfield',
      '#title' 			=> $this->t('Team Order No'),
      '#default_value' 	=> isset($temp_stor[$i]['university']) ? $temp_stor[$i]['university'] : '',
     '#title_display' => 'invisible',
	  '#attributes'    => ['class' => ['form-control']],    
    ];
		
	if ($i ==  $form_state->get('num_qual') - 1) {
        $form['workorder']['academics'][$i]['actions']['remove_name_qual'] = [
          '#type' => 'submit',
		  '#name' => 'qualification_remove'.$i,
          '#value' => '.',
		  '#attributes' => ['class' => ['removeitem']],
		  '#limit_validation_errors' => array(),
          '#submit' => array('::removeCallbackQual'),
          '#ajax' => [
            'callback' => '::addmoreCallbackQual',
            'wrapper' => "qual-id",
			'progress' => [
					  'type' => 'throbber',
					  'message' => t(''),
					],
          ],
        ];
      }
    }
	
	
	// End of Repeater Field
	
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
      '#url' => \Drupal\Core\Url::fromRoute('company.departmentview'),
    );
    return $form;

    }
 
  public function validateForm(array &$form, FormStateInterface $form_state) { 
   
  }
  
  public function ActionCancel(array &$form, FormStateInterface $form_state)
  {	  
	$form_state->setRedirect('company.departmentview');
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
		
	}
	
	
 public function addmoreCallbackQual(array &$form, FormStateInterface $form_state) {
    $name_field_qual = $form_state->get('num_qual');
    return $form['teamdetails'];
  }  
  
  public function addOneQual(array &$form, FormStateInterface $form_state) {
    $name_field_qual = $form_state->get('num_qual');
    $add_button_qual = $name_field_qual + 1;
    $form_state->set('num_qual', $add_button_qual);
    $form_state->setRebuild();
  }
  
   public function removeCallbackQual(array &$form, FormStateInterface $form_state) {
    $name_field_qual = $form_state->get('num_qual');
	
    if ($name_field_qual > 1) {
      $remove_button_qual = $name_field_qual - 1;
      $form_state->set('num_qual', $remove_button_qual);
      
    }
    $form_state->setRebuild();
  }

  

}
?>
