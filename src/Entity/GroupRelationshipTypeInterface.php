<?php

namespace Drupal\group\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a relationship type entity.
 */
interface GroupRelationshipTypeInterface extends ConfigEntityInterface {

  /**
   * Gets the group type the content type was created for.
   *
   * @return \Drupal\group\Entity\GroupTypeInterface
   *   The group type for which the content type was created.
   */
  public function getGroupType();

  /**
   * Gets the group type ID the content type was created for.
   *
   * @return string
   *   The group type ID for which the content type was created.
   */
  public function getGroupTypeId();

  /**
   * Gets the group relation the content type uses.
   *
   * @return \Drupal\group\Plugin\Group\Relation\GroupRelationInterface
   *   The group relation the content type uses.
   */
  public function getPlugin();

  /**
   * Gets the group relation type ID the content type uses.
   *
   * @return string
   *   The group relation type ID the content type uses.
   */
  public function getPluginId();

  /**
   * Updates the configuration of the group relation.
   *
   * Any keys that were left out will be reset to the default.
   *
   * @param array $configuration
   *   An array of group relation configuration.
   */
  public function updatePlugin(array $configuration);

  /**
   * Loads relationship type entities by their responsible plugin ID.
   *
   * @param string|string[] $plugin_id
   *   The group relation type ID or an array of plugin IDs. If more than one
   *   plugin ID is provided, this will load all of the relationship types that
   *   match any of the provided plugin IDs.
   *
   * @return \Drupal\group\Entity\GroupRelationshipTypeInterface[]
   *   An array of relationship type entities indexed by their IDs.
   */
  public static function loadByPluginId($plugin_id);

  /**
   * Loads relationship type entities which could serve a given entity type.
   *
   * @param string $entity_type_id
   *   An entity type ID which may be served by one or more relationship types.
   *
   * @return \Drupal\group\Entity\GroupRelationshipTypeInterface[]
   *   An array of relationship type entities which serve the given entity.
   */
  public static function loadByEntityTypeId($entity_type_id);

}
