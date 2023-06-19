<?php

namespace Drupal\csgov_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Overrides the media based on source field and custom rules.
 *
 * Available configuration keys:
 * - override: Flag to enable the overrides ('random_file' or 'random_image').
 *
 * If enabled and there is no media found by lookup yet, each migrated entity
 * receives a random media for the specific field.
 *
 * @MigrateProcessPlugin(
 *   id = "csgov_migrate_media_override"
 * )
 */
class MediaOverride extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!empty($this->configuration['override']) && empty($value)) {

      if (str_starts_with($this->configuration['override'], 'random_')) {
        $bundle = substr($this->configuration['override'], 7);
        $query = \Drupal::database()
          ->select('migrate_map_csgov_migrate_media_' . $bundle, 'm');
        $query->addField('m', 'destid1');
        $value = $query->orderRandom()->execute()->fetchField();
      }

    }
    return $value;
  }

}
