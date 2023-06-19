<?php

/**
 * @file
 * Enables modules and site configuration for a CS Gov site installation.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function csgov_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['site_information']['site_name']['#default_value'] = 'CS Gov Starterkit';
  $form['regional_settings']['site_default_country']['#default_value'] = 'CZ';
}
