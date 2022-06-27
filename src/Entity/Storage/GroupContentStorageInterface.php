<?php

namespace Drupal\group\Entity\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\group\Entity\GroupInterface;

/**
 * Defines an interface for group content entity storage classes.
 */
interface GroupContentStorageInterface extends ContentEntityStorageInterface {

  /**
   * Creates a GroupContent entity for placing an entity in a group.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to add to the group.
   * @param \Drupal\group\Entity\GroupInterface $group
   *   The group to add the entity to.
   * @param string $plugin_id
   *   The group relation type ID to add the entity with.
   * @param array $values
   *   (optional) Extra values to add to the GroupContent entity.
   *
   * @return \Drupal\group\Entity\GroupContentInterface
   *   A new GroupContent entity.
   */
  public function createForEntityInGroup(EntityInterface $entity, GroupInterface $group, $plugin_id, $values = []);

  /**
   * Retrieves all GroupContent entities for a group.
   *
   * @param \Drupal\group\Entity\GroupInterface $group
   *   The group entity to load the group content entities for.
   * @param string $plugin_id
   *   (optional) A group relation type ID to filter on.
   *
   * @return \Drupal\group\Entity\GroupContentInterface[]
   *   A list of GroupContent entities matching the criteria.
   */
  public function loadByGroup(GroupInterface $group, $plugin_id = NULL);

  /**
   * Retrieves all GroupContent entities that represent a given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An entity which may be within one or more groups.
   * @param string $plugin_id
   *   (optional) A group relation type ID to filter on.
   *
   * @return \Drupal\group\Entity\GroupContentInterface[]
   *   A list of GroupContent entities which refer to the given entity.
   */
  public function loadByEntity(EntityInterface $entity, $plugin_id = NULL);

  /**
   * Retrieves all GroupContent entities by their responsible plugin ID.
   *
   * @param string $plugin_id
   *   The group relation type ID.
   *
   * @return \Drupal\group\Entity\GroupContentInterface[]
   *   A list of GroupContent entities indexed by their IDs.
   */
  public function loadByPluginId($plugin_id);

}
