<?php

/**
 * @file
 * Contains \Drupal\ckeditor\Plugin\CKEditorPlugin\DrupalImageWidget.
 */

namespace Drupal\ckeditor\Plugin\CKEditorPlugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;
use Drupal\Core\Annotation\Translation;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\ckeditor\Annotation\CKEditorPlugin;

/**
 * Defines the "drupalimagecaption" plugin.
 *
 * @CKEditorPlugin(
 *   id = "drupalimagecaption",
 *   label = @Translation("Drupal image caption widget"),
 *   module = "ckeditor"
 * )
 */
class DrupalImageCaption extends PluginBase implements CKEditorPluginInterface, CKEditorPluginContextualInterface {

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return array(
      array('ckeditor', 'drupal.ckeditor.drupalimagecaption-theme'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'ckeditor') . '/js/plugins/drupalimagecaption/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  function isEnabled(Editor $editor) {
    $filters = array();
    if (!empty($editor->format)) {
      $filters = entity_load('filter_format', $editor->format)
        ->filters()
        ->getAll();
    }

    // Automatically enable this plugin if the text format associated with this
    // text editor uses the filter_caption filter and the DrupalImage button is
    // enabled.
    if (isset($filters['filter_caption']) && $filters['filter_caption']->status) {
      foreach ($editor->settings['toolbar']['buttons'] as $row) {
        if (in_array('DrupalImage', $row)) {
          return TRUE;
        }
      }
    }

    return FALSE;
  }

}
