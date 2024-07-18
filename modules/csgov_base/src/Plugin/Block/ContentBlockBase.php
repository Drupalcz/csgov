<?php

declare(strict_types = 1);

namespace Drupal\csgov_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\csgov_base\EntityVersionMatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for content blocks.
 */
class ContentBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Entity version matcher.
   *
   * @var \Drupal\csgov_base\EntityVersionMatcher
   */
  protected EntityVersionMatcher $entityVersionMatcher;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    EntityVersionMatcher $entity_version_matcher
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityVersionMatcher = $entity_version_matcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) : static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('csgov_base.entity_version_matcher'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() : array {
    $matcher = $this->entityVersionMatcher->getType();

    if (
      !$matcher['entity'] ||
      $matcher['entity_version'] == EntityVersionMatcher::ENTITY_VERSION_REVISION
    ) {
      return parent::getCacheTags();
    }
    return Cache::mergeTags(
      parent::getCacheTags(),
      $matcher['entity']->getCacheTags()
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() : array {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() : array {
    return [];
  }

  /**
   * Return current entity version.
   */
  protected function getCurrentEntityVersion() : array {
    return $this->entityVersionMatcher->getType();
  }

}
