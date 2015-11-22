<?php

/**
 * @file
 * Contains \Drupal\group\Entity\Access\GroupTypeAccessControlHandler.
 */

namespace Drupal\group\Entity\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the group type entity type.
 *
 * @see \Drupal\group\Entity\GroupType
 */
class GroupTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /* @var $entity \Drupal\group\Entity\GroupTypeInterface */
    if ($operation == 'delete') {
      return parent::checkAccess($entity, $operation, $account)->addCacheableDependency($entity);
    }
    return parent::checkAccess($entity, $operation, $account);
  }

}
