<?php

namespace Drupal\employee\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\library\Controller\Encrypt;
use Drupal\employee\Model\EmployeeModel;

class Employee extends ControllerBase {
  
 public function emplist() {
 	
  $empobj = new EmployeeModel;    
  $result = $empobj->getEmployeeDetails();
  $encrypt = new Encrypt;

    global $base_url;
    $asset_url = $base_url.'/'.\Drupal::theme()->getActiveTheme()->getPath();
	$rows = [];
    foreach ($result as $row => $content) {
	  $codepk_encoded = $encrypt->encode($content->userpk);
      $html = ['#markup' => '<a href="'.$base_url.'/employee/edit/'.$codepk_encoded.'" style="text-align:center"> 
      <i class="icon-note" title="" data-toggle="tooltip" data-original-title="Edit"></i></a>'];
      $rows[] = 	array(
                    'data' =>	  array( $content->empid, $content->firstname.' '.$content->lastname , $content->doj, $content->designation, $content->department, render($html))
      );
    }
    $element['display']['employeelist'] = array(
      '#type' 	    => 'table',
      '#header' 	  =>  array(t('Employee ID.'), t('Name'),t('Date of joining'), t('Designation'),  t('Department'), t('Action')),
      '#rows'		    =>  $rows,
      '#attributes' => ['class' => ['table text-center table-hover table-striped table-bordered dataTable'], 'style'=>['text-align-last: center;']],
      '#prefix'     => '<div class="panel panel-info">
                        <h3 class="box-title col-md-10">Employees List</h3>
                        <div class=" col-md-2">
                        <i class="mdi-file-import mdi fa-fw"></i><i class="mdi-file-import mdi fa-fw"></i><i class="mdi-file-import mdi fa-fw"></i><i class="mdi-file-import mdi fa-fw"></i> <i class="mdi-printer mdi fa-fw"></i> <i class="mdi-download mdi fa-fw"></i></div>
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">	
                        <hr>
                        <div id="editable-datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row"><div class="col-sm-6"><a href ="'.$base_url.'/employee/add/personal"><span  type="button" class="btn btn-info" style="background-color: #4c5667">
                        <i class="mdi mdi-plus"></i> Add </span></a></div> <br><br><br></div></div><div class="row"><div class="col-sm-12">',
      '#suffix'     => '</div></div></div></div></div></div>',
	  '#empty'		=>	'No Employee has been added yet.'
    );
    return $element;
  }
  
  public function profile() {
	  
	return array(
      '#theme' => 'digital-profile',
      '#data' => array(),
    );
	
  }
  
}
