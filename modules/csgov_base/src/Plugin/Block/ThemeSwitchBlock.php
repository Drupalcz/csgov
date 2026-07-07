<?php

declare(strict_types = 1);

namespace Drupal\csgov_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the dark mode switch (light / dark / follow the OS).
 *
 * Placing this block is what enables dark mode: without it csgov_theme
 * forces the light theme on the whole site.
 *
 * @Block(
 *  id = "csgov_theme_switch",
 *  admin_label = @Translation("Theme switch (dark mode)"),
 * )
 */
class ThemeSwitchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'switch_size' => 'm',
      'switch_label_visible' => FALSE,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $form['switch_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Size'),
      '#options' => [
        'xs' => 'xs',
        's' => 's',
        'm' => 'm',
        'l' => 'l',
        'xl' => 'xl',
      ],
      '#default_value' => $this->configuration['switch_size'],
    ];
    $form['switch_label_visible'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show the mode label next to the toggle'),
      '#default_value' => $this->configuration['switch_label_visible'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['switch_size'] = $form_state->getValue('switch_size');
    $this->configuration['switch_label_visible'] = (bool) $form_state->getValue('switch_label_visible');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // The SDC lives in the active theme's namespace so that starterkit
    // derivatives of csgov_theme (which carry their own copy of the
    // component) keep working.
    $theme = \Drupal::theme()->getActiveTheme()->getName();
    return [
      '#type' => 'component',
      '#component' => $theme . ':theme-switch',
      '#props' => [
        'switch_size' => $this->configuration['switch_size'],
        'switch_label_visible' => (bool) $this->configuration['switch_label_visible'],
      ],
      '#cache' => [
        'contexts' => ['theme'],
      ],
    ];
  }

}
