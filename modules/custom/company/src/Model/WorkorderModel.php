<?php

namespace Drupal\company\Model;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class WorkorderModel extends ControllerBase {
	
	public function getWorkorderList()
	{
		$query = db_select('srch_companyinfo', 'n');
				$query->leftjoin('srch_codevalues', 'cd', 'cd.codename = n.companytype');
				$query->leftjoin('srch_cities', 'ct', 'ct.id = n.city');
				$query->leftjoin('srch_states', 'st', 'st.id = n.state');
				$query->leftjoin('srch_countries', 'cnt', 'cnt.id = n.country');
				$query->addField('ct', 'name', 'cityname');
				$query->addField('st', 'name', 'statename');
				$query->addField('cnt', 'name', 'countryname');
				$query->fields('n');
				$query->fields('cd', ['codevalues']);				
				$query->condition('n.companypk', $id, "=");
				$query->condition('n.status', 1, "=");
				$result = $query->execute()->fetch();
		
		$res = @$result;	
		
		return $res;
	}
}