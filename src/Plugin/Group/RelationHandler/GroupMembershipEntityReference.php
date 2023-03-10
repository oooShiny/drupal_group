<?php

namespace Drupal\group\Plugin\Group\RelationHandler;

use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Configures the entity reference for the group_membership relation plugin.
 */
class GroupMembershipEntityReference implements EntityReferenceInterface {

  use EntityReferenceTrait;

  /**
   * Constructs a new GroupMembershipEntityReference.
   *
   * @param \Drupal\group\Plugin\Group\RelationHandler\EntityReferenceInterface $parent
   *   The default entity reference handler.
   */
  public function __construct(EntityReferenceInterface $parent) {
    $this->parent = $parent;
  }

  /**
   * {@inheritdoc}
   */
  public function configureField(BaseFieldDefinition $entity_reference) {
    $this->parent->configureField($entity_reference);

    $handler_settings = $entity_reference->getSetting('handler_settings');
    $handler_settings['include_anonymous'] = FALSE;
    $entity_reference->setSetting('handler_settings', $handler_settings);
  }

}
