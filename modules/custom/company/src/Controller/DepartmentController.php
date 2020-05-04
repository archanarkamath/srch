<?php

namespace Drupal\company\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class DepartmentController extends ControllerBase {
  public function display() {
    
   $dptobj = new \Drupal\company\Model\DepartmentModel;
   $result = $dptobj->getAllDepartmentDetails();
   
    global $base_url;
    $rows = array();
    $sl = 0;
    foreach ($result as $row => $content) { 
      $sl++;
      $html = ['#markup' => '<a href="'.$base_url.'/department/edit/'.$content->codepk.'" style="text-align:center"> 
      <i class="icon-note" title="" data-toggle="tooltip" data-original-title="Edit"></i></a>'];
      $rows[] =   array(
                    'data' =>     array( $sl, $content->codevalues, $content->codename, render($html))
      );
    }
    $element['display']['Departmentlist'] = array(
      '#type'       => 'table',
      '#header'     =>  array(t('Sl No.'), t('Department Name'), t('Department Code'), t('Action')),      
      '#rows'       =>  $rows,
      '#attributes' => ['class' => ['text-center table table-hover table-striped table-bordered dataTable'], 'border' => '1', 'rules' => 'all', 'style'=>['text-align-last: center;']],
      '#prefix'     => '<div class="panel panel-info">
                        <h3 class="box-title">Department Details</h3><hr>
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">    
                        <div id="editable-datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row"><div class="col-sm-6"><a href ="add"><span  type="button" class="btn btn-info">
                        <i class="mdi mdi-plus"></i> Add </span></a></div> <br><br><br></div></div><div class="row"><div class="col-sm-12">',
      '#suffix'     => '</div></div></div></div></div></div>',
	  '#empty'		=>	'No Department has been created yet.'
    );
    return $element;
  }
}