<?php

namespace Drupal\csgov_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

class OneColumnLayout extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'container_type' => 0,
      'hero' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['container_type'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Container Fluid'),
      '#description' => 'Makes the content break the container.',
      '#default_value' => $this->configuration['container_type'],
    ];
    $form['hero'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hero'),
      '#description' => 'Extra behaviour for Hero section.',
      '#default_value' => $this->configuration['hero'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // any additional form validation that is required
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['container_type'] = $form_state->getValue('container_type');
    $this->configuration['hero'] = $form_state->getValue('hero');
  }

}
