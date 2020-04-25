<?php

namespace Drupal\dashboard\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "employeelist_count_block",
 *   admin_label = @Translation("Employee List Count block"),
 * )
 */
class EmployeelistCount extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
	global $base_url;
	$emp_obj = new \Drupal\employee\Model\EmployeeModel();
	$count = $emp_obj->getEmployeeCount();
	
    return [
      '#markup' => $this->t('<a href="'.$base_url.'/employee"><div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box" style="box-shadow: 0 4px 8px 0 grey;">
                                    <h3 class="box-title">Employees</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-people text-purple"></i></li>
                                        <li class="text-right"><span class="counter">'.$count.'</span></li>
                                    </ul>
                                </div>
                            </div></a>'),
    ];
  }


}