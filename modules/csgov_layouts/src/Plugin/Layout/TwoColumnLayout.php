<?php

namespace Drupal\csgov_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

class TwoColumnLayout extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'container_type' => 0,
      'sidebar_position' => 0,
      'section_ratio' => 'section-ratio--48',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['section_ratio'] = [
      '#type' => 'select',
      '#title' => $this->t('Layout options'),
      '#description' => 'Choose ratio for the columns.',
      '#options' => [
        'section-ratio--39' => $this->t('Ratio 3:9'),
        'section-ratio--48' => $this->t('Ratio 4:8'),
        'section-ratio--57' => $this->t('Ratio 5:7'),
        'section-ratio--66' => $this->t('Ratio 6:6'),
      ],
      '#default_value' => $this->configuration['section_ratio'],
    ];
    $form['sidebar_position'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Left Sidebar'),
      '#description' => 'Moves the sidebar to left hand side.',
      '#default_value' => $this->configuration['sidebar_position'],
    ];
    $form['container_type'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Container Fluid'),
      '#description' => 'Makes the content break the container.',
      '#default_value' => $this->configuration['container_type'],
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
    $this->configuration['sidebar_position'] = $form_state->getValue('sidebar_position');
    $this->configuration['section_ratio'] = $form_state->getValue('section_ratio');
  }

}
