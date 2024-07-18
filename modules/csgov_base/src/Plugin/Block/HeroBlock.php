<?php

declare(strict_types = 1);

namespace Drupal\csgov_base\Plugin\Block;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\csgov_base\EntityVersionMatcher;

/**
 * Provides a 'HeroBlock' block to place Hero Paragraph on Block Layout page.
 *
 * @Block(
 *  id = "hero_block",
 *  admin_label = @Translation("Hero block"),
 * )
 */
class HeroBlock extends ContentBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() : array {
    $build = [];

    /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    ['entity' => $entity, 'entity_version' => $entity_version] = $this->getCurrentEntityVersion();

    // No need to continue if current entity doesn't have has_hero field.
    if (
      !$entity instanceof ContentEntityInterface ||
      !$entity->hasField('field_csgov_has_hero')
    ) {
      return $build;
    }

    if (!$entity->get('field_csgov_has_hero')->isEmpty()) {

      $build['hero_block'] = [
        '#theme' => 'hero_block',
        '#title' => $this->t('Hero block'),
        '#paragraphs' => $entity->get('field_csgov_hero'),
        '#is_revision' => $entity_version == EntityVersionMatcher::ENTITY_VERSION_REVISION,
        '#cache' => [
          'tags' => $entity->getCacheTags(),
        ],
      ];
    }

    return $build;
  }

}
