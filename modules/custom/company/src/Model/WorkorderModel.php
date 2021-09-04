<?php

namespace Drupal\company\Model;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\library\Lib\DataModel;

class WorkorderModel extends ControllerBase {
	
	public function getWorkorderList()
	{
		$query = db_select( DataModel::CODEVAL, 'n');
				$query->fields('n');			
				$query->condition('n.codetype', 'workorder', "=");
				$query->condition('n.status', 1, "=");
				$query->orderBy('createdon', 'DESC');
				$result = $query->execute()->fetchAll();
		
		$res = @$result;	
		
		return $res;
	}
	
	/*
	* @param $data array which includes work & team details
	* which needs to be insert in srch_codevalues table
	*/
	public function setWorkOrder( $data )
	{
		$data['codetype']	=	'workorder';
		
		$query = \Drupal::database();
        $last_workorder_id = $query ->insert( DataModel::CODEVAL )
				   ->fields($data)
				   ->execute();
		   
		
	}
	
	public function getWorkOrderDetailsById($id = 1)
	{
		$query = db_select('srch_codevalues', 'n');
				$query->fields('n');	
				$query->condition('codetype', 'workorder', "=");
				$query->condition('codepk', $id, "=");
				$query->condition('status', 1, "=");
				$result = $query->execute()->fetchAll();
		
		$res = @$result[0];	
		return $res;
	}
	

	public function updateWorkOrder($field, $id)
		{
			$query = \Drupal::database();
			  $query->update('srch_codevalues')
				  ->fields($field)
				  ->condition('codepk', $id)
				  ->execute();
		}

	public function getWorkorderId($codename)
	{
		$query = db_select('srch_codevalues', 'codepk');
				$query->fields('codepk');	
				$query->condition('status', 1, "=");
				$query->condition('codename', $codename , "=");
				$result = $query->execute()->fetch();
		
		$res = $result;	
		return $res;
	}  
}