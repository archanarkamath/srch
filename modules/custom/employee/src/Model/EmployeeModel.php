<?php

namespace Drupal\employee\Model;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\library\Lib\DataModel;

class EmployeeModel extends ControllerBase  {
	
	public function createUser($userdata)
	{
		
		$user = \Drupal\user\Entity\User::create();
			
		//Mandatory.
		$user->setUsername($userdata['username']);
		$user->setPassword($userdata['password']);
		$user->setEmail($userdata['email']);
		//$user->enforceIsNew();
		
		/* Drupal wont allow to set authenticated role manually as it is bydefault */
		if( $userdata['role'] != 'authenticated')
		{
			$user->addRole($userdata['role']); 
		} 
		//optional
		$language = 'en';
		$user->set("init", $userdata['email']);
		$user->set("langcode", $language);
		$user->set("preferred_langcode", $language);
		$user->set("preferred_admin_langcode", $language); 
		$user->activate();
		
		if(isset($userdata['image']))
		{
			$user->set('user_picture', $userdata['image']); 
		} 
		
		
		 //Save user account.
		 $user->save();
		 
		 return $user;
	}

	public static function setPersonalInfo($user, $data, $opt, &$context )
	{
		//$context['message'] = "Now processing word...";
		$query = \Drupal::database();	
		
		$query->query('start transaction');
		
		//personal info 
		$query->query('insert into '.DataModel::EMPPERSONAL.' set userpk = :userpk, firstname = :fname, lastname= :lname, fathername= :fthname, mothername= :mthname, gender= :gender, dob = :dob,marital= :marital, bloodgroup= :blood, religion= :religion, nationality= :nationality', 
		array(':userpk'=>$user->Id(), ':fname'=>$data['personal']['firstname'], ':lname'=>$data['personal']['lastname'], ':fthname'=>$data['personal']['fname'], ':mthname'=>$data['personal']['mname'], ':gender'=>$data['personal']['gender'], ':dob'=>$data['personal']['dob'], ':marital'=>$data['personal']['marital'], ':blood'=>$data['personal']['blood'],':religion'=>$data['personal']['religion'],':nationality'=>$data['personal']['nationality']));
		
		//contact info
		$query->query('insert into '.DataModel::EMPCONTACT.' set userpk = :userpk, phoneno = :phoneno, altphoneno= :altphoneno, emrgphoneno= :emrgphoneno, relationship= :relationship, email= :email, res_address1= :res_address1, res_address2= :res_address2, res_state= :res_state, res_city= :res_city, res_country= :res_country, res_pincode= :res_pincode, perm_address1= :perm_address1, perm_address2= :perm_address2, perm_state= :perm_state, perm_city= :perm_city, perm_country= :perm_country, perm_pincode= :perm_pincode, status= :status', 
		array(':userpk'=>$user->Id(), ':phoneno'=>$data['contact']['phoneno'], ':altphoneno'=>$data['contact']['altphoneno'], ':emrgphoneno'=>$data['contact']['emergencyno'], ':relationship'=>$data['contact']['relationship'], ':email'=>$data['contact']['email'], ':res_address1'=>$data['contact']['address1'], 
		':res_address2'=>$data['contact']['address2'],':res_state'=>$data['contact']['state'], ':res_city'=>$data['contact']['city'], ':res_country'=>$data['contact']['country'], ':res_pincode'=>$data['contact']['pincode'], ':perm_address1'=>$data['contact']['permanentaddress1'], ':perm_address2'=>$data['contact']['permanentaddress2'], ':perm_state'=>$data['contact']['permanentstate'], ':perm_city'=>$data['contact']['permanentcity'], ':perm_country'=>$data['contact']['permanentcountry'], ':perm_pincode'=>$data['contact']['permanentpincode'], ':status'=> 1));
		
		//academic info
		foreach($data['qualification'] AS $qual)
		{
			$query->query('insert into '.DataModel::EMPACADEMIC.' set userpk = :userpk, class = :class, stream= :stream, board= :board, yearofpassing= :yearofpassing, score = :score', 
			array(':userpk'=>$user->Id(), ':class'=>$qual['class'], ':stream'=>$qual['stream'], ':board'=>$qual['university'], ':yearofpassing'=>$qual['yearofpassing'], ':score'=>$qual['score']));
		
		}
		
		//employeement info
		foreach($data['experience'] AS $qual)
		{
			$query->query('insert into '.DataModel::EMPEXPRNC.' set userpk = :userpk, organisation = :organisation, designation= :designation, fromdate= :fromdate, todate= :todate', 
			array(':userpk'=>$user->Id(),':organisation'=>$qual['organisation'], ':designation'=>$qual['designation'], ':fromdate'=>$qual['fromdate'], ':todate'=>$qual['todate']));
		
		}
		
		//official info
		$query->query('insert into '.DataModel::EMPOFFICIAL.' set userpk = :userpk, empid = :empid, department= :department, branch= :branch, designation= :designation, jobnature = :jobnature, email= :email, doj= :doj, jobtype= :jobtype, shifttime= :shifttime', 
		array(':userpk'=>$user->Id(), ':empid'=>$data['official']['id'], ':department'=>$data['official']['department'], ':branch'=>$data['official']['branch'], ':designation'=>$data['official']['designation'], ':jobnature'=>$data['official']['jobnature'], ':email'=>$data['official']['officialemail'], ':doj'=>$data['official']['doj'], ':jobtype'=>$data['official']['jobtype'],':shifttime'=>$data['official']['shifttime']));
		
		$query->query('commit');
		
	}
	
	public static function finishOperation()
  {
	  $response = new RedirectResponse(\Drupal\Core\Url::fromRoute('employee.emplist')->toString());
	  $response->send();
	  return;
    }
	
	public function checkUserIdExist($username)
	{
		$query = db_select(DataModel::USERDATA, 'name');
				$query->fields('name');	
				$query->condition('status', 1, "=");
				$query->condition('name', $username , "=");
				$result = $query->execute()->fetch();
		
		$res = $result;	
		return $res;
	}
	
	public function checkEMailIdExist($email)
	{
		$query = db_select(DataModel::USERDATA, 'mail');
				$query->fields('mail');	
				$query->condition('status', 1, "=");
				$query->condition('mail', $email , "=");
				$result = $query->execute()->fetch();
		
		$res = $result;	
		return $res;
	}
	
  public function getPersonalDetailsById($id)
	{
		$query = db_select(DataModel::EMPPERSONAL, 'n');
		$query->fields('n');	
		$query->condition('userpk', $id ,"=");
    $result = $query->execute()->fetchAll();
		return $result;
	}
  public function getEmployeeDetails()
	{
		$query = db_select(DataModel::EMPPERSONAL, 'n');
		$query -> innerJoin(DataModel::EMPOFFICIAL, 'nf','n.userpk = nf.userpk');
		$query->orderBy('n.createdon', 'DESC');
		$query->fields('n');	
		$query->fields('nf');	
		$result = $query->execute()->fetchAll();
		return $result;
    
	}

	public function getEmployeeCount()
	{
		$query = db_select(DataModel::EMPPERSONAL, 'n');
		$query->condition('status', 1, "=");
		$query->fields('n');	
		$result = $query->execute()->fetchAll();
		return count($result);
	}
	
}
