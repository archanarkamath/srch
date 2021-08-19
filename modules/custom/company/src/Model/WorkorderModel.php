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
		$data['workorder']['codetype']	=	'workorder';
		
		$query = \Drupal::database();
        $last_workorder_id = $query ->insert( DataModel::CODEVAL )
				   ->fields($data['workorder'])
				   ->execute();
		   
		foreach( $data['teamorder']  AS  $team )
		{
			//inserting column codetype & parent
			$team['codetype']	=	'teamorder';
			$team['parent']	=	$last_workorder_id;
			
			$query ->insert( DataModel::CODEVAL )
               ->fields($team)
               ->execute();
		}
	}

	/*
	* @param $id which includes work order no
	* to get work order dertails
	*/
	public function getWorkorderDetailsById( $id )
	{
		$query = db_select( DataModel::CODEVAL, 'n' );
				$query->fields('n');	
				$query->condition('codetype', 'workorder', "=");
				$query->condition('codepk', $id, "=");
				$query->condition('status', 1, "=");
				$result = $query->execute()->fetch();
			
		return $result;
	}

	/*
	* @param $work_ord_no which includes work order no
	* to get team order dertails
	*/
	public function getTeamListByWorkorderno( $work_ord_no )
	{
		$query = db_select( DataModel::CODEVAL, 'n' );
				$query->fields('n');	
				$query->condition('codetype', 'teamorder', "=");
				$query->condition('parent', $work_ord_no, "=");
				$query->condition('status', 1, "=");
				$result = $query->execute()->fetchAll();
				
		return $result;
	}
}