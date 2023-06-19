<?php

namespace Drupal\csgov_migrate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides configuration form for CS Gov Migrate module.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'csgov_migrate_settings_form';
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
      '#title' => $this->t('Export site content for default content migration'),
      '#description' => $this->t('If checked, the default content data will be refreshed.'),
      '#states' => [
        'disabled' => [':input[name="import"]' => ['checked' => TRUE]],
      ],
    ];
    $form['import'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Import site content from default content migration'),
      '#description' => $this->t('If checked, the content will be migrated.'),
      '#states' => [
        'disabled' => [':input[name="export"]' => ['checked' => TRUE]],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (!empty($form_state->getValue('export'))) {
      batch_set(_csgov_migrate_get_batch('export'));
    }
    elseif (!empty($form_state->getValue('import'))) {
      batch_set(_csgov_migrate_get_batch('import'));
    }

    parent::submitForm($form, $form_state);
  }

}
