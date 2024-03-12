<?php

namespace Drupal\csgov_admin_improvements\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CsGovBasicSettings extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'system.site',
      'system.theme.global',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'csgov_basic_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'markup',
      '#markup' => $this->t('This form gather various settings from the administration to one place.
      It should display the currently used values and save them to the appropriate configs.'),
    ];

    // Load configurations
    $site_config = $this->config('system.site');
    $theme_config = \Drupal::config('system.theme')->get('default');

    // Site name field
    $form['site_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site name'),
      '#default_value' => $site_config->get('name'),
    ];

    // Site logo field
    // Assuming you have the file URI
    if (!empty(\Drupal::config("$theme_config.settings")->get('logo'))) {
      $file_uri = \Drupal::config("$theme_config.settings")->get('logo');
      $file = \Drupal::entityTypeManager()->getStorage('file')->loadByProperties(['uri' => $file_uri]);
      $file_id = !empty($file) ? reset($file)->id() : NULL;
    }

    $form['site_logo_upload'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload logo image'),
      '#upload_location' => 'public://site-logo/',
      '#default_value' => isset($file_id) && !empty($file_id) ? [$file_id] : [],
      '#upload_validators' => [
        'file_validate_extensions' => ['svg png jpg jpeg gif'],
      ],
      '#description' => $this->t('Allowed extensions: svg png jpg jpeg gif')
    ];

    // Display current logo as a reference.
    // Attempt to retrieve the custom logo path from the theme's settings.
    $logo_path = \Drupal::config("$theme_config.settings")->get('logo.path');

    // Check if a custom logo has been set and display it.
    if ($logo_path) {
      $file_url = \Drupal::service('file_url_generator')->generateAbsoluteString($logo_path);
      $form['current_logo'] = [
        '#type' => 'item',
        '#title' => $this->t('Current custom Logo'),
        '#markup' => '<img width="250px" height="auto"  src="' . $file_url . '" alt="' . $this->t('Current logo') . '" />',
      ];
    } else {
      $form['current_logo'] = [
        '#type' => 'item',
        '#markup' => $this->t('No custom logo is currently set.'),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $logo_fid = $form_state->getValue(['site_logo_upload', 0]);
    if (!empty($logo_fid)) {
      $file = \Drupal\file\Entity\File::load($logo_fid);
      if ($file) {
        $file->setPermanent();
        $file->save();

        $theme_name = \Drupal::config('system.theme')->get('default');
        if ($theme_name == 'csgov_theme') {
          \Drupal::configFactory()->getEditable('csgov_theme.settings')
            ->set('logo.path', $file->getFileUri())
            ->clear('logo.use_default') // Assuming there's a setting to use the default logo, clear it.
            ->save();
        }
      }
    }

    // Save other configurations
    $this->config('system.site')
      ->set('name', $form_state->getValue('site_name'))
      ->save();
  }
}
