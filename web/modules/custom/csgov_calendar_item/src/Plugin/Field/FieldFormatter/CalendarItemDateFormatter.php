<?php

namespace Drupal\csgov_calendar_item\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'calendar_item_date_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "calendar_item_date_formatter",
 *   label = @Translation("Calendar Item Date"),
 *   field_types = {
 *     "daterange",
 *     "datetime",
 *     "timestamp",
 *     "created",
 *     "changed",
 *   }
 * )
 */
class CalendarItemDateFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return array
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    $field = $item->getFieldDefinition();
    switch ($field->getType()) {
      case 'daterange':
        $value = $item->start_date;
        break;
      case 'datetime':
        $value = $item->date;
        break;
      default:
        /** @var \Drupal\Core\Datetime\DateFormatterInterface $date_formatter */
        $date_formatter = \Drupal::service('date.formatter');
        $date = $date_formatter->format($item->value, 'custom', 'Y-m-d');
        $value = new DrupalDatetime($date);
        break;
    }
    $build = [
      '#theme' => 'calendar_item_date',
      '#datetime' => $value,
      '#attached' => [
        'library' => [
          'csgov_calendar_item/calendar_item',
        ],
      ],
    ];
    return $build;
  }

}
