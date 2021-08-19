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
    $wrkobj = new \Drupal\company\Model\WorkorderModel;

    $mode = $libobj->getActionMode();
    if($mode == 'edit'){
      $pk = $libobj->getIdFromUrl();
      $data_work = $wrkobj->getWorkorderDetailsById( $pk );
      $data_team = $wrkobj->getTeamListByWorkorderno( $pk );
    }


	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attached']['library'][] = 'company/workorder-lib';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['department']['#prefix'] = '<div class="row"> <div class="panel panel-inverse"><h3 class="box-title">Work Order</h3>
                                      <hr/><div class="panel-body">';
    $form['workorder']['workname'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Work order Name:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
      '#default_value' => isset($data_work)? $data_work->codevalues : '',
    );
	
	 $form['workorder']['workcode'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Work order No:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#suffix'        => '</div>',
      '#default_value' => isset($data_work)? $data_work->codename : '',
    );
    
	
	// repeater Field fro Team Form
	
	$team_field_count = $form_state->get('num_team');
	
	if (empty($team_field_count)) {
      $team_field_count = $form_state->set('num_team', 1);
    }
	
	$form['addmore']['actions'] = [
        '#type' => 'actions',
      ];
      $form['addmore']['actions']['add_team'] = [
        '#type' => 'submit',
		'#name' => 'Team',
		//'#prefix' => $html,
        '#value' => ' ',
		'#limit_validation_errors' => array(),
        '#submit' => array('::addOneTeam'),
	    '#attributes' => ['class' => ['addmore']],
        '#ajax' => [
          'callback' => '::addmoreCallbackTeam',
          'wrapper' => "team-id",
        ],
      ];
	  
	$form['team']['teamorder'] = [
      //'#prefix' => $html,
	  '#prefix' => '<div id="team-id"><div class="panel-body">',
      '#suffix' => '</div></div>',
	  '#attributes' => ['class' => ['']],
	  '#type' => 'table',
	  '#title' => 'Sample Table',
	  '#header' => [ ['data' => 'SLNO', 'class' => 'text-center', 'width' => '1%'], 
					 ['data' => 'Team Name', 'class' => 'text-center', 'width' => '13%'], 
					 ['data' => 'Team Order No', 'class' => 'text-center', 'width' => '13%'],
					 ['data' => 'Action', 'class' => 'text-left', 'width' => '13%'],
					]
    ];
	
	
	
	
	 for ($i = 0; $i <  $form_state->get('num_team'); $i++) {   
		$cntq = $i + 1;
     
	 $form['team']['teamorder'][$i]['slno'] = [
      '#type' 		   => 'item',
	  '#markup' => $cntq,
      '#title_display' => 'invisible',	  
    ];

    $form['team']['teamorder'][$i]['name'] = [
      '#type' 			=> 'textfield',
      '#title' 			=> $this->t('Team Name'),
      '#default_value' 	=> '',
      '#title_display' => 'invisible',
	  '#attributes'    => ['class' => ['form-control']],
	  '#prefix'	=> '',    
    ];
	 $form['team']['teamorder'][$i]['order'] = [
      '#type' 			=> 'textfield',
      '#title' 			=> $this->t('Team Order No'),
      '#default_value' 	=> '',
     '#title_display' => 'invisible',
	  '#attributes'    => ['class' => ['form-control']],    
    ];
		
	if ($i ==  $form_state->get('num_team') - 1) {
        $form['team']['teamorder'][$i]['actions']['remove_name_team'] = [
          '#type' => 'submit',
		  '#name' => 'qualification_remove'.$i,
          '#value' => '.',
		  '#attributes' => ['class' => ['removeitem']],
		  '#limit_validation_errors' => array(),
          '#submit' => array('::removeCallbackTeam'),
          '#ajax' => [
            'callback' => '::addmoreCallbackTeam',
            'wrapper' => "team-id",
			'progress' => [
					  'type' => 'throbber',
					  'message' => t(''),
					],
          ],
        ];
      }
    }
	
	
	// End of Repeater Field
	
	$form['save']['submit'] = array(
      '#type'          => 'submit',
      '#default_value' => ($mode == 'add') ? $this->t('Submit') : $this->t('Update'),
      '#button_type'   => 'primary',
      '#attributes'    => ['class' => ['btn btn-info']],
      '#prefix'        => '<br/><div class="row"><div class="col-md-2"></div><div class="col-md-4">',
      '#suffix'        => '',
    );

    $form['save']['cancel'] = array(
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
		$data = array(
						'workorder' => array(
												'codename'	=>	$field['workcode'],
												'codevalues'=>	$field['workname']	
											),
						'teamorder'	=>	array()
					);
					
		//looping team repeater array and collecting data
		foreach( $field['teamorder'] AS $team )
		{
			$data['teamorder'][]	=	array( 
												'codename' 	=>	$team['order'],
												'codevalues'=>	$team['name']
											);
		}
		
		$worobj->setWorkOrder( $data );
		
		drupal_set_message("Word Order has been created.");
		
		$form_state->setRedirect('company.projectlist');
	}
	
	
 public function addmoreCallbackTeam(array &$form, FormStateInterface $form_state) {
    $team_field_count = $form_state->get('num_team');
    return $form['team'];
  }  
  
  public function addOneTeam(array &$form, FormStateInterface $form_state) {
    $team_field_count = $form_state->get('num_team');
    $add_button_team = $team_field_count + 1;
    $form_state->set('num_team', $add_button_team);
    $form_state->setRebuild();
  }
  
   public function removeCallbackTeam(array &$form, FormStateInterface $form_state) {
    $team_field_count = $form_state->get('num_team');
	
    if ($team_field_count > 1) {
      $remove_button_team = $team_field_count - 1;
      $form_state->set('num_team', $remove_button_team);
      
    }
    $form_state->setRebuild();
  }

  

}
?>
