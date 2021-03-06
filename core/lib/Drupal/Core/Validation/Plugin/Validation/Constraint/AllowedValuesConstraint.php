<?php

/**
 * @file
 * Contains \Drupal\Core\Validation\Constraint\AllowedValuesConstraint.
 */

namespace Drupal\Core\Validation\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraints\Choice;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Checks for the value being allowed.
 *
 * @Plugin(
 *   id = "AllowedValues",
 *   label = @Translation("Allowed values", context = "Validation")
 * )
 *
 * @see \Drupal\Core\TypedData\AllowedValuesInterface
 */
class AllowedValuesConstraint extends Choice {

  public $minMessage = 'You must select at least %limit choice.|You must select at least %limit choices.';
  public $maxMessage = 'You must select at most %limit choice.|You must select at most %limit choices.';
}
