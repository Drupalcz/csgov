<?php

namespace Drupal\csgov_migrate\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;

/**
 * Plugin implementation of the 'entity reference UUID' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_entity_uuid",
 *   label = @Translation("Entity UUID"),
 *   description = @Translation("Display the UUID of the referenced entities."),
 *   field_types = {
 *     "entity_reference",
 *     "entity_reference_revisions",
 *     "file",
 *     "image",
 *     "svg_image_field",
 *   }
 * )
 */
class EntityReferenceUuidFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      if ($entity->uuid()) {
        $elements[$delta] = [
          '#plain_text' => $entity->uuid(),
          '#cache' => [
            'tags' => $entity->getCacheTags(),
          ],
        ];
      }
    }

    return $elements;
  }

}
