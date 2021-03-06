<?php

/**
 * @file
 * Contains \Drupal\form_test\SystemConfigFormTestForm.
 */

namespace Drupal\form_test;

use Drupal\Core\Form\ConfigFormBase;

/**
 * Tests the ConfigFormBase class.
 */
class SystemConfigFormTestForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'form_test_system_config_test_form';
  }

}
