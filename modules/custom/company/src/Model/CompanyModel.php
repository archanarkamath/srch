<?php

namespace Drupal\company\Model;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class CompanyModel extends ControllerBase {
	
	public function getCompanyDetailsById($id = 1)
	{
		$query = db_select('srch_companyinfo', 'n');
				$query->leftjoin('srch_codevalues', 'cd', 'cd.codename = n.companytype');
				$query->fields('n');
				$query->fields('cd', ['codevalues']);				
				$query->condition('n.companypk', $id, "=");
				$query->condition('n.status', 1, "=");
				$result = $query->execute()->fetch();
		
		$res = @$result;	
		
		return $res;
	}
	
	public function setCompany($field)
	{
		$query = \Drupal::database();
           $query ->insert('srch_companyinfo')
               ->fields($field)
               ->execute();
	}
	
	public function updateCompany($field)
	{
		$query = \Drupal::database();
          $query->update('srch_companyinfo')
              ->fields($field)
              ->condition('companypk', 1)
              ->execute();
	}
	
	public function getCompanyTypeList()
	{
		$query = db_select('srch_codevalues', 'n');
				$query->fields('n');	
				$query->condition('status', 1, "=");
				$query->condition('codetype', 'organisation', "=");
				$query->orderBy('weight', "ASC");
				$result = $query->execute()->fetchAll();
		
		$res = $result;	
		
		return $res;
	}

}