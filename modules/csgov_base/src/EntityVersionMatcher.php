<?php

declare(strict_types = 1);

namespace Drupal\csgov_base;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\RevisionableStorageInterface;
use Drupal\Core\Entity\TranslatableRevisionableInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Match routes with entity versions (full, revision, preview).
 */
class EntityVersionMatcher {

  /**
   * Entity version constant for canonical.
   */
  const ENTITY_VERSION_CANONICAL = 'canonical';

  /**
   * Entity version constant for revision.
   */
  const ENTITY_VERSION_REVISION = 'revision';

  /**
   * Entity version constant for preview.
   */
  const ENTITY_VERSION_PREVIEW = 'preview';

  /**
   * Creates a new EntityVersionMatcher instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The currently active route match object.
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager service.
   */
  public function __construct(
    private EntityTypeManagerInterface $entityTypeManager,
    private RouteMatchInterface $routeMatch,
    private LanguageManagerInterface $languageManager,
  ) {
  }

  /**
   * Gets the current page main entity type.
   *
   * @return string|int|bool
   *   Returns entity type or false.
   */
  protected function getCurrentEntityType() : string|int|bool {
    $types = array_keys($this->entityTypeManager->getDefinitions());
    $params = $this->routeMatch->getParameters()->all();
    foreach ($types as $type) {
      if (!empty($params[$type])) {
        return $type;
      }
    }
    // Special case for node preview.
    if (!empty($this->routeMatch->getRawParameter('node_preview'))) {
      return 'node';
    }
    return FALSE;
  }

  /**
   * Get type information for the current entity.
   *
   * Check whether the current entity is viewed as full entity,
   * entity preview or entity revision.
   *
   * @return array
   *   Returns array with entity version and entity object.
   */
  public function getType() : array {
    $entity_version = static::ENTITY_VERSION_CANONICAL;
    $entity = FALSE;
    $language = $this->languageManager->getCurrentLanguage(LanguageInterface::TYPE_CONTENT);

    if ($entity_type = $this->getCurrentEntityType()) {
      switch ($this->routeMatch->getRouteName()) {
        // Canonical.
        case "entity.$entity_type.canonical":
          $entity = $this->routeMatch->getParameter($entity_type);
          break;

        // Revision.
        case "entity.$entity_type.revision":
          $entity_version = static::ENTITY_VERSION_REVISION;
          $storage = $this->entityTypeManager->getStorage($entity_type);
          assert($storage instanceof RevisionableStorageInterface);

          $revision_id = $this->routeMatch->getParameter("{$entity_type}_revision");
          if ($revision_id instanceof ContentEntityInterface) {
            $revision_id = $revision_id->getRevisionId();
          }
          $revision = $storage->loadRevision($revision_id);
          assert($revision instanceof TranslatableRevisionableInterface);

          $entity = $revision->getTranslation($language->getId());
          break;

        // Preview.
        case "entity.$entity_type.preview":
          $entity_version = static::ENTITY_VERSION_PREVIEW;
          $entity = $this->routeMatch->getParameter("{$entity_type}_preview");
          break;
      }
    }
    return [
      'entity_version' => $entity_version,
      'entity' => $entity,
    ];
  }

}
