<?php

namespace Drupal\company\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class CompanyController extends ControllerBase {

  public function display() {
  
	global $base_url;
	
	$compobj = new \Drupal\company\Model\CompanyModel;
	$data = $compobj->getCompanyDetailsById(1);
	$encrypt = new \Drupal\library\Controller\Encrypt;
    
	
	 return array(
      '#theme' => 'companyview',
      '#data' => array(
						'logo' => file_create_url("public://logo.png"),
						'name' => $data->companyname,
						'type' => $data->codevalues,
						'email' => $data->email,
						'phone' => $data->phone,
						'address'=> $data->address1,
						'id'     => $encrypt->encode($data->companypk)
	                  ),
    );
	
  }
}
