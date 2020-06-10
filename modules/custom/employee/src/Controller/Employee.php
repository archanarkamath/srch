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
  $result = $empobj->getEmployeeList();
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
  
/*
* Display Employee Profile 
* @parameter Logged in User
* @output passing data variable to template file
*/
  
  public function profile() {
	$empobj = new EmployeeModel; 
	$avatar = $empobj->getUserPic();
	$user = \Drupal::currentUser();
	$prsnl_details = $empobj->getPersonalDetailsById($user->id());
	$ofc_details = $empobj->getOfficialDetailsById($user->id());
	$cont_details = $empobj->getContactDetailsById($user->id());
	$academic_details = $empobj->getAcademicDetailsById($user->id());
	$academic = [];
	foreach($academic_details AS $val)
	{
		$academic[$val->class] = $val->board . ' ('. date("Y", strtotime($val->yearofpassing)) . ')';
	}
	
	$prevEmp_details = $empobj->getPrevEmployeementDetailsById($user->id());
	
	$expr = [];
	foreach($prevEmp_details AS $item)
	{
		$expr[] = [
					'organisation'	=>	$item->organisation,
					'designation'	=>	$item->designation,
					'fromdate'		=>	date("j F Y", strtotime($item->fromdate)),
					'todate'		=>	date("j F Y", strtotime($item->todate)),
				  ];
	}
	
	switch($prsnl_details->gender)
	{
		CASE 'M':
			$gender = 'Male';
			break;
		CASE 'F':
			$gender = 'Female';
			break;
		default:
			$gender = 'Other';
			break;
	}
	
	return array(
      '#theme' => 'employee-profile',
      '#data' => array(
						'profpic'		=>	$avatar,
						'name'			=> 	$prsnl_details->firstname . ' ' . $prsnl_details->lastname,
						'fathername'	=> 	$prsnl_details->fathername,
						'mothername'	=> 	$prsnl_details->mothername,
						'dob'			=> 	date("j F Y", strtotime($prsnl_details->dob)),
						'marital'		=> 	($prsnl_details->marital == 'M') ? 'Married' : 'Unmarried',
						'bloodgroup'	=> 	$prsnl_details->bloodgroup,
						'religion'		=> 	$prsnl_details->religion,
						'nationality'	=> 	$prsnl_details->nationality,
						'gender'		=> 	$gender,
						
						'phoneno'		=>	$cont_details->phoneno,
						'altphone'		=>	$cont_details->altphone,
						'emrgphone'		=>	$cont_details->emrgphone,
						'relationship'	=>	$cont_details->relationship,
						'pers_email'	=>	$cont_details->email,
						'res_address1'	=>	$cont_details->res_address1,
						'res_address2'	=>	$cont_details->res_address2,
						'res_state'		=>	$cont_details->res_state,
						'res_city'		=>	$cont_details->res_city,
						'res_country'	=>	$cont_details->res_country,
						'res_pincode'	=>	$cont_details->res_pincode,
						'perm_address1'	=>	$cont_details->perm_address1,
						'perm_address2'	=>	$cont_details->perm_address2,
						'perm_state'	=>	$cont_details->perm_state,
						'perm_city'		=>	$cont_details->perm_city,
						'perm_country'	=>	$cont_details->perm_country,
						'perm_pincode'	=>	$cont_details->perm_pincode,
						
						'empid'			=> 	$ofc_details->empid,
						'branch'		=>	$ofc_details->branch,
						'department'	=>	$ofc_details->department,
						'designation' 	=> 	$ofc_details->designation,
						'jobtype' 		=> 	$ofc_details->jobtype,
						'jobnature' 	=> 	$ofc_details->jobnature,
						'email' 		=> 	$ofc_details->email,
						'joining' 		=> 	date("j F Y", strtotime($ofc_details->joining)),
						'jobshift' 		=> 	$ofc_details->jobshift ,
						
						'qual'			=>	$academic,
						'expr'			=>	$expr
				),
    );
	
  }
  
}
