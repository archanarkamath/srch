<?php

namespace Drupal\leave\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\library\Controller\Encrypt;

class Leave extends ControllerBase {
 public function leavelist() {

 $element['display']['Leavelist'] = array(
      '#type' 	    => 'table',
      '#header' 	  =>  array(t('Request Type.'), t('Leave type'),t('Start date'), t('End date'),  t('Number of days'), t('Submit'), t('Action')),
      '#rows'		    =>  $rows,
      '#attributes' => ['class' => ['table text-center table-hover table-striped table-bordered dataTable'], 'style'=>['text-align-last: center;']],
      '#prefix'     => '<div class="panel panel-info">
                        <h3 class="box-title col-md-10">Leave Request</h3>
                       <div class=" col-md-2">
                        <a href="#" id="exportit" data-toggle="tooltip" data-original-title="Word Document"><img src="'.$asset_url.'/assets/images/icon/word.png" /></a> &nbsp;
						<a href="'.$base_url.'/branch/export/excel" data-toggle="tooltip" data-original-title="Excel"><img src="'.$asset_url.'/assets/images/icon/excel.png" /></a> &nbsp;
						<a id="" data-toggle="tooltip" data-original-title="PDF"><img src="'.$asset_url.'/assets/images/icon/pdf.png" /></a> &nbsp;
						<a id="printit" data-toggle="tooltip" data-original-title="Print"><img src="'.$asset_url.'/assets/images/icon/print.png" /></a> &nbsp;
						<a id="" data-toggle="tooltip" data-original-title="Download"><img src="'.$asset_url.'/assets/images/icon/download.png" /></a> 
						</div>
                        <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">	
                        <hr>
                        <div id="editable-datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row"><div class="col-sm-6"><a href ="#"><span  type="button" class="btn btn-info" style="background-color: #4c5667">
                        <i class="mdi mdi-plus"></i> Apply Leave </span></a></div> <br><br><br></div></div><div class="row"><div class="col-sm-12">',
      '#suffix'     => '</div></div></div></div></div></div>',
	  '#empty'		=>	'No Leaves applied yet.'
    );
    return $element;

 }
}