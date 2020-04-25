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
 *   id = "change_password_block",
 *   admin_label = @Translation("Change Password block"),
 * )
 */
class ChangePassword extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
	global $base_url;
    return [
      '#markup' => $this->t('<a href="'.$base_url.'/settings/password"><div class="col-lg-3 col-sm-6 col-xs-12">
                                <div class="white-box" style="box-shadow: 0 4px 8px 0 grey;">
                                    <h3 class="box-title">Change password</h3>
                                    <ul class="list-inline two-part">
                                        <li><i class="icon-lock text-info"></i></li>
                                        <li class="text-right"><span class="counter">23</span></li>
                                    </ul>
                                </div>
                            </div></a>'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['my_block_settings'] = $form_state->getValue('my_block_settings');
  }
}