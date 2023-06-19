<?php

namespace Drupal\csgov_migrate\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Render\Markup;

/**
 * Plugin implementation of the 'layout builder JSON' formatter.
 *
 * @FieldFormatter(
 *   id = "layout_builder_json",
 *   label = @Translation("Layout Builder JSON"),
 *   description = @Translation("Display the Layout Builder JSON data."),
 *   field_types = {
 *     "layout_section",
 *   }
 * )
 */
class LayoutBuilderJsonFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $entity = $items->getEntity();

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#markup' => Markup::create(serialize($item->section)),
        '#cache' => [
          'tags' => $entity->getCacheTags(),
        ],
      ];
    }

    return $elements;
  }

}
