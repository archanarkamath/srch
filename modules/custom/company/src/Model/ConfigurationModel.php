<?php

namespace Drupal\company\Model;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\library\Lib\DataModel;

class ConfigurationModel extends ControllerBase {

	public function getJobNature()
	{
		$query = db_select(DataModel::CODEVAL, 'n'); 
		$query->fields('n');		
		$query->condition('status', 1, "=");
		$query->condition('codetype', 'jobnature', "=");
		$query->orderBy('createdon', 'DESC');
		$result = $query->execute();
		
		$natureofjob[''] = 'Select Nature of job';
		 foreach($result AS $item)
		 {
		  $natureofjob[$item->codename]  = $item->codevalues;
		 }
	   
		return $natureofjob;
	}

	public function getJobType()
	{
		$query = db_select(DataModel::CODEVAL, 'n'); 
		$query->fields('n');		
		$query->condition('status', 1, "=");
		$query->condition('codetype', 'jobtype', "=");
		$query->orderBy('createdon', 'DESC');
		$result = $query->execute();
		$jobtype[''] = 'Select Type of job';
		foreach($result AS $item)
		{
			$jobtype[$item->codename]  = $item->codevalues;
		}
		return $jobtype;
	}
	
	public function getJobShift()
	{
		$query = db_select(DataModel::CODEVAL, 'n'); 
		$query->fields('n');		
		$query->condition('status', 1, "=");
		$query->condition('codetype', 'jobshift', "=");
		$query->orderBy('createdon', 'DESC');
		$result = $query->execute()->fetchAll();
		$jobshift[''] = 'Select Shift type';
		 foreach($result AS $item)
		 {
		   $jobshift[$item->codename]  = $item->codevalues;
		 }
		return $jobshift;
	}
	
	public function updateEmpIdType($field)
	{
		$query = \Drupal::database();
          $query->update(DataModel::CODEVAL)
              ->fields($field)
              ->condition('codename', 'EMPID', "=")
              ->condition('codetype', 'employeeid', "=")
              ->execute();
	}
	
	public function getEmpIdType()
	{
		$query = db_select(DataModel::CODEVAL, 'n'); 
		$query->fields('n')		
		->condition('codename', 'EMPID', "=")
        ->condition('codetype', 'employeeid', "=");
		$result = $query->execute()->fetch();
		$res = @$result;	
		
		return $res;
	}
	
	public function setShiftTiming($field)
	{
		$query = \Drupal::database();
		 $result =  $query ->insert(DataModel::CODEVAL)
				   ->fields($field)
				   ->execute();
	}
	
	public function getShiftTimingList()
	{
		$query = db_select(DataModel::CODEVAL, 'n'); 
		$query->fields('n');		
		$query->condition('status', 1, "=");
		$query->condition('codetype', 'jobshift', "=");
		$query->orderBy('createdon', 'DESC');
		$result = $query->execute()->fetchAll();
		
		return $result;
	}
	
	public function getShiftDetailsById($pk)
	{
		$query = db_select(DataModel::CODEVAL, 'n'); 
		$query->fields('n');		
		$query->condition('codepk', $pk, "=");
		$query->condition('codetype', 'jobshift', "=");
		$result = $query->execute()->fetch();
		
		return $result;
	}
	
	public function updateShiftTiming($field, $pk)
	{
		$query = \Drupal::database();
        $query->update(DataModel::CODEVAL)
              ->fields($field)
              ->condition('codepk', $pk, "=")
              ->condition('codetype', 'jobshift', "=")
              ->execute();
	}
	
}
