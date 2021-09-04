<?php

namespace Drupal\hr\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\library\Controller\Encrypt;

class TeamorderController extends ControllerBase {

  public function listing() {
    
   $tmobj = new \Drupal\hr\Model\TeamorderModel;
   $wkobj = new \Drupal\company\Model\WorkOrderModel;
   $result = $tmobj->getTeamorderList();
   $encrypt = new Encrypt;
   
    global $base_url;
	$asset_url = $base_url.'/'.\Drupal::theme()->getActiveTheme()->getPath();
    $rows = array();
    $sl = 0;
	
    foreach ($result as $content) { 
	  $sl++;
	  $work = $wkobj->getWorkOrderDetailsById($content->parent);
      $html = ['#markup' => '<a href="'.$base_url.'/teamorder/edit/'.$content->codepk.'" style="text-align:center"> 
      <i class="icon-note" title="" data-toggle="tooltip" data-original-title="Edit"></i></a>'];
      $rows[] =   array(
                    'data' =>  array( $sl, $content->codevalues, $content->codename, $work->codevalues, render($html))
      );
    }
	
    $element['display']['TeamOrderList'] = array(
      '#type'       => 'table',
      '#header'     =>  array(t('No'), t('Team Name'), t('Team Order'), t('Work order name'), t('Action')),      
      '#rows'       =>  $rows,
      '#attributes' => ['class' => ['text-center table table-hover table-striped table-bordered dataTable'], 'border' => '1', 'rules' => 'all', 'style'=>['text-align-last: center;']],
      '#prefix'     => '<div class="panel panel-info">
                        <h3 class="box-title  col-md-10">Team List</h3>
					<div class=" col-md-2">
                        <a href="#" id="exportit" data-toggle="tooltip" data-original-title="Word Document"><img src="'.$asset_url.'/assets/images/icon/word.png" /></a> &nbsp;
						<a href="'.$base_url.'/department/export/excel" data-toggle="tooltip" data-original-title="Excel"><img src="'.$asset_url.'/assets/images/icon/excel.png" /></a> &nbsp;
						<a id="" data-toggle="tooltip" data-original-title="PDF"><img src="'.$asset_url.'/assets/images/icon/pdf.png" /></a> &nbsp;
						<a id="printit" data-toggle="tooltip" data-original-title="Print"><img src="'.$asset_url.'/assets/images/icon/print.png" /></a> 
						</div>
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
						<hr>
                        <div id="editable-datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row"><div class="col-sm-6"><a href ="add"><span  type="button" class="btn btn-info">
                        <i class="mdi mdi-plus"></i> Add </span></a></div> <br><br><br></div></div><div class="row"><div class="col-sm-12" id="printable">',
      '#suffix'     => '</div></div></div></div></div></div>',
	  '#empty'		=>	'No Team order has been created yet.'
    );
    return $element;
  }
}
