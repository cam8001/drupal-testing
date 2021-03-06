<?php

/**
 * @file
 * Contains Drupal\shortcut\Access\ShortcutSetEditAccessCheck.
 */

namespace Drupal\shortcut\Access;

use Drupal\Core\Access\StaticAccessCheckInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides an access check for shortcut link delete routes.
 */
class ShortcutSetEditAccessCheck implements StaticAccessCheckInterface {

  /**
   * {@inheritdoc}
   */
  public function appliesTo() {
    return array('_access_shortcut_set_edit');
  }

  /**
   * {@inheritdoc}
   */
  public function access(Route $route, Request $request) {
    $account = \Drupal::currentUser();
    $shortcut_set = $request->attributes->get('shortcut_set');
    // Sufficiently-privileged users can edit their currently displayed shortcut
    // set, but not other sets. Shortcut administrators can edit any set.
    if ($account->hasPermission('administer shortcuts')) {
      return static::ALLOW;
    }
    if ($account->hasPermission('customize shortcut links')) {
      return !isset($shortcut_set) || $shortcut_set == shortcut_current_displayed_set() ? static::ALLOW : static::DENY;
    }
    return static::DENY;
  }

}
