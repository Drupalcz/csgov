<?php
namespace Drupal\csgov_admin_improvements\Controller;

/**
 * @file
 * Contains \Drupal\csgov_admin_improvements\Controller\ListController.
 */

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

/**
 * List controller.
 */
class ListController {
  use StringTranslationTrait;

  /**
   * Controller to build platform settings page.
   *
   * @return array
   *   Render array.
   */
  public function build(): array {
    $current_user = User::load(\Drupal::currentUser()->id());
    $faked_blocks = [];

    if ($current_user->hasPermission('access administration pages')) {
      $faked_blocks['site_settings'] = [
        'title' => $this->t('Site settings'),
        'description' => $this->t('Update site settings.'),
        'content' => [
          '#theme' => 'admin_block_content',
          '#content' => [
            'site_footer_translations' => [
              'url' => Url::fromRoute('csgov_admin_improvements.basic_settings_form'),
              'title' => $this->t('CSGOV Site settings'),
              'description' => '',
              'options' => '',
            ],
          ],
        ],
      ];
    }

    if ($current_user->hasPermission('administer menu')) {
      $faked_blocks['main_menu'] = [
        'title' => $this->t('Menus'),
        'description' => '',
        'content' => [
          '#theme' => 'admin_block_content',
          '#content' => [
            'navigation' => [
              'url' => Url::fromUri('internal:/admin/structure/menu/manage/main'),
              'title' => $this->t('Edit main menu'),
              'description' => '',
              'options' => '',
            ],
          ],
        ],
      ];
    }


    if ($current_user->hasPermission('access taxonomy overview')) {
      $faked_blocks['taxonomy'] = [
        'title' => $this->t('Taxonomy'),
        'description' => '',
        'content' => [
          '#theme' => 'admin_block_content',
          '#content' => [
            'navigation' => [
              'url' => Url::fromUri('internal:/admin/structure/taxonomy'),
              'title' => $this->t('Edit taxonomy terms'),
              'description' => '',
              'options' => '',
            ],
          ],
        ],
      ];
    }


    return [
      '#theme' => 'admin_page',
      '#blocks' => $faked_blocks,
    ];
  }

}
