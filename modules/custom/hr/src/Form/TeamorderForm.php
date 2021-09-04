<?php

namespace Drupal\hr\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

class TeamorderForm extends FormBase {
  public function getFormId() {
    return 'teamorder_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {  
  
    $libobj = new \Drupal\library\Lib\LibController;
    $tmobj = new \Drupal\hr\Model\TeamorderModel;
    $wkobj = new \Drupal\company\Model\WorkorderModel;

    $mode = $libobj->getActionMode();
	if($mode == 'edit'){
      $pk = $libobj->getIdFromUrl();	
      $data = $tmobj->getTeamOrderDetailsById($pk);
    }
	$form['#attached']['library'][] = 'singleportal/master-validation';
	$form['#attributes']['class'] = 'form-horizontal';
	$form['#attributes']['autocomplete'] = 'off';
    $form['department']['#prefix'] = '<div class="row"> <div class="panel panel-inverse">
                                      <div class="panel-heading">Team Order</div><div class="panel-body">';
    $form['teamorder']['teamname'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Team order Name:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
	  '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codevalues : '',
    );
	
	 $form['teamorder']['teamcode'] = array(
      '#type'          => 'textfield',
      '#title'         => t('Team order No:'),
      '#attributes'    => ['class' => ['form-control', 'validate[required,custom[onlyLetterSp]]']],
      '#prefix'        => '<div class="row">',
	  '#suffix'        => '</div>',
      '#default_value' => isset($data)? $data->codename : '',
    );
	$workorderlist = $wkobj->getWorkorderList();
      $workorder_names[''] = 'Select Work order name';
      foreach($workorderlist AS $item)
      {
      $workorder_names[$item->codename]  = $item->codevalues;
      }
      
      if($mode == 'edit'){
        $codepk = $data->parent;
        $res = $wkobj->getWorkorderDetailsById($codepk);
        $workord = $res->codename;
      }
	 $form['teamorder']['workorder'] = array(
      '#type'          => 'select',
      '#title'         => t('Work order name :'),
      '#options'       => $workorder_names,
      '#attributes'    => ['class' => ['form-control', 'validate[required]']],
      '#prefix'        => '<div class="row"><div class="col-md-12">',
      '#suffix'        => '</div></div>',
      '#default_value' => isset($data)? $workord : '',
   // $form['designation']['#type'] = 'actions';
    );
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
      '#url' => \Drupal\Core\Url::fromRoute('hr.teamorderlist'),
    );
	$form_state->setCached(FALSE);
    return $form;

    }
 
  public function validateForm(array &$form, FormStateInterface $form_state) { 
   
  }
  
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
		
		$tmobj = new \Drupal\hr\Model\TeamorderModel;	
		$wkobj = new \Drupal\company\Model\WorkorderModel;

		$field = $form_state->getValues();
		$teamname = $field['teamname'];
		$teamcode = $field['teamcode'];
		$parent = $field['workorder'];
		$parent = $wkobj->getWorkorderId($parent);
    
		$field = array(	'codename'	=>	$teamcode,
						'codevalues'=>	$teamname,
						'parent'=> $parent->codepk
					);
		$libobj = new \Drupal\library\Lib\LibController;

	$mode = $libobj->getActionMode();
    if($mode == 'add' )
    { 
	  $tmobj->setTeamOrder( $field );
	  drupal_set_message("Team order has been created.");
    }
    if($mode == 'edit' )
    {
      $pk = $libobj->getIdFromUrl();
      $tmobj->updateTeamOrder($field,$pk);
      drupal_set_message("Team order succesfully Updated.");
    }
	$form_state->setRedirect('hr.teamorderlist');
	}

	//$form_state->setRebuild();
  }

?>
