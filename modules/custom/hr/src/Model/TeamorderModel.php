<?php

namespace Drupal\hr\Model;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\library\Lib\DataModel;

class TeamorderModel extends ControllerBase {
	
	public function getTeamorderList()
	{
		$query = db_select( DataModel::CODEVAL, 'n');
				$query->fields('n');			
				$query->condition('n.codetype', 'teamorder', "=");
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
	public function setTeamOrder( $data )
	{
		$data['codetype']	=	'teamorder';
		
		$query = \Drupal::database();
        $last_teamorder_id = $query ->insert( DataModel::CODEVAL )
				   ->fields($data)
				   ->execute();
		   
		
	}
	
	public function getTeamOrderDetailsById($id = 1)
	{
		$query = db_select('srch_codevalues', 'n');
				$query->fields('n');	
				$query->condition('codetype', 'teamorder', "=");
				$query->condition('codepk', $id, "=");
				$query->condition('status', 1, "=");
				$result = $query->execute()->fetchAll();
		
		$res = @$result[0];	
		return $res;
	}
	public function updateTeamOrder($field, $id)
			{
				$query = \Drupal::database();
				  $query->update('srch_codevalues')
					  ->fields($field)
					  ->condition('codepk', $id)
					  ->execute();
			}
}