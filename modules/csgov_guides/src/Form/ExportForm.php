<?php

namespace Drupal\csgov_guides\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides export form for CSGOV Guides module.
 */
class ExportForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'csgov_guides_export_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['export'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Export guide content for migration'),
      '#description' => $this->t('If checked, all guide-related content (nodes, paragraphs, media, files) will be exported to JSON files in the data/ directory.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (!empty($form_state->getValue('export'))) {
      batch_set(_csgov_guides_get_batch('export'));
    }

    parent::submitForm($form, $form_state);
  }

}
