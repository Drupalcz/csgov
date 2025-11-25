<?php

namespace Drupal\csgov\Installer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the default content form.
 */
class SettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'csgov_install_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $install_state = NULL) {
    $form['#title'] = 'CSGOV settings';

    $form['migrate'] = [
      '#type' => 'fieldgroup',
      '#title' => $this->t('Default content'),
    ];
    $form['migrate']['csgov_install_migrate'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Process default content migration'),
      '#default_value' => $install_state['csgov_migrate'] ?? 1,
    ];

    $form['guides'] = [
      '#type' => 'fieldgroup',
      '#title' => $this->t('Documentation'),
    ];
    $form['guides']['csgov_install_guides'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Install user documentation (guides)'),
      '#default_value' => $install_state['csgov_guides'] ?? 0,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and continue'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $build_info = $form_state->getBuildInfo();
    $build_info['args'][0]['parameters']['csgov_migrate'] = $form_state->getValue('csgov_install_migrate');
    $build_info['args'][0]['parameters']['csgov_guides'] = $form_state->getValue('csgov_install_guides');
    $form_state->setBuildInfo($build_info);
  }

}
