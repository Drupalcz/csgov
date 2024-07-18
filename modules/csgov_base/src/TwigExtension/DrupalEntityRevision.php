<?php

declare(strict_types = 1);

namespace Drupal\csgov_base\TwigExtension;

use Drupal\Component\Uuid\Uuid;
use Drupal\Core\Entity\RevisionableStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Custom Twig extension to load entity revision with a twig function.
 */
class DrupalEntityRevision extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('drupal_entity_revision', [$this, 'entityRevision']),
    ];
  }

  /**
   * Returns the rendered entity revision.
   *
   * Example:
   *
   * @code
   *  {{ drupal_entity_revision('paragraph', paragraph.target_revision_id) }}
   * @endcode
   *
   * @param string $entity_type
   *   The entity type.
   * @param string $selector
   *   UUID or ID of the entity revision.
   *
   * @return array
   *   Returns a render array for a given entity.
   */
  public static function entityRevision(string $entity_type, string $selector): array {
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    assert($storage instanceof RevisionableStorageInterface);

    if (Uuid::isValid($selector)) {
      $entities = $storage->loadByProperties(['uuid' => $selector]);
      $entity = reset($entities);
    }
    // Fall back to entity ID.
    else {
      $entity = $storage->loadRevision($selector);
    }

    if ($entity) {
      return \Drupal::service('twig_tweak.entity_view_builder')
        ->build($entity);
    }

    return [];
  }

}
