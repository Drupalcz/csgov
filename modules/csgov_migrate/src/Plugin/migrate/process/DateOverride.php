<?php

namespace Drupal\csgov_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Overrides the date based on source field and custom rules.
 *
 * Available configuration keys:
 * - override: Flag to enable the overrides, defaults to false.
 *
 * If enabled, each migrated entity receives a different date for the specific
 * field. The first migrated usually receives an old date, the second a more
 * recent one, then today's date, and a future date for the last migrated.
 *
 * field_board_published_date:
 * -3 weeks, -2 weeks, -1 week, today, +1 week, +2 weeks, ...
 *
 * field_board_removed_date:
 * +1 week, +2 weeks, +3 weeks, +4 weeks, +5 weeks, ...
 *
 * field_event_date:
 * -1 week, today, +1 week, +2 weeks, +3 weeks, +4 weeks, ...
 *
 * field_news_date:
 * -2 weeks, -1 week, today, +1 week, +2 weeks, +3 weeks, ...
 *
 * @MigrateProcessPlugin(
 *   id = "csgov_migrate_date_override"
 * )
 */
class DateOverride extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!empty($this->configuration['override'])) {
      $counter = &drupal_static('csgov_migrate_date_override_' . $this->configuration['source'], 0);

      $format = 'Y-m-d';
      if ($this->configuration['source'] === 'field_board_published_date') {
        $diff = ($counter - 3) * 604800;
      }
      elseif ($this->configuration['source'] === 'field_board_removed_date') {
        $diff = ($counter - 3 + 4) * 604800;
      }
      elseif ($this->configuration['source'] === '@_field_event_date/0'
        || $this->configuration['source'] === '@_field_event_date/1'
      ) {
        $diff = ($counter - 1) * 604800;
        $format = 'Y-m-d\TH:i:s';
      }
      elseif ($this->configuration['source'] === 'field_news_date') {
        $diff = ($counter - 2) * 604800;
      }
      $value = date($format, time() + ($diff ?? 0));

      $counter++;
    }
    return $value;
  }

}
