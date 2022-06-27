<?php

namespace Drupal\group\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\group\Entity\GroupContentInterface;
use Drupal\group\Entity\Storage\GroupContentTypeStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Checks the amount of times a single content entity can be added to a group.
 */
class GroupContentCardinalityValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a GroupContentCardinalityValidator object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($group_content, Constraint $constraint) {
    assert($group_content instanceof GroupContentInterface);
    assert($constraint instanceof GroupContentCardinality);
    if (!isset($group_content)) {
      return;
    }

    // Only run our checks if a group was referenced.
    if (!$group = $group_content->getGroup()) {
      return;
    }

    // Only run our checks if an entity was referenced.
    if (!$entity = $group_content->getEntity()) {
      return;
    }

    // Get the plugin for the group content entity.
    $plugin = $group_content->getPlugin();

    // Get the cardinality settings from the plugin.
    $group_cardinality = $plugin->getGroupCardinality();
    $entity_cardinality = $plugin->getEntityCardinality();

    // Exit early if both cardinalities are set to unlimited.
    if ($group_cardinality <= 0 && $entity_cardinality <= 0) {
      return;
    }

    // Get the entity_id field label for error messages.
    $field_name = $group_content->getFieldDefinition('entity_id')->getLabel();

    // Enforce the group cardinality if it's not set to unlimited.
    if ($group_cardinality > 0) {
      $storage = $this->entityTypeManager->getStorage('group_content_type');
      assert($storage instanceof GroupContentTypeStorageInterface);
      $group_content_type_id = $storage->getGroupContentTypeId($group->bundle(), $plugin->getRelationTypeId());

      // Get the group content entities for this piece of content.
      $properties = ['type' => $group_content_type_id, 'entity_id' => $entity->id()];
      $group_instances = $this->entityTypeManager
        ->getStorage('group_content')
        ->loadByProperties($properties);

      // Get the groups this content entity already belongs to, not counting
      // the current group towards the limit.
      $group_ids = [];
      foreach ($group_instances as $instance) {
        assert($instance instanceof GroupContentInterface);
        if ($instance->getGroupId() != $group->id()) {
          $group_ids[] = $instance->getGroupId();
        }
      }
      $group_count = count(array_unique($group_ids));

      // Raise a violation if the content has reached the cardinality limit.
      if ($group_count >= $group_cardinality) {
        $this->context->buildViolation($constraint->groupMessage)
          ->setParameter('@field', $field_name)
          ->setParameter('%content', $entity->label())
          // We manually flag the entity reference field as the source of the
          // violation so form API will add a visual indicator of where the
          // validation failed.
          ->atPath('entity_id.0')
          ->addViolation();
      }
    }

    // Enforce the entity cardinality if it's not set to unlimited.
    if ($entity_cardinality > 0) {
      // Get the current instances of this content entity in the group.
      $entity_instances = $group->getContentByEntity($entity, $plugin->getRelationTypeId());
      $entity_count = count($entity_instances);

      // If the current group content entity has an ID, exclude that one.
      if ($group_content_id = $group_content->id()) {
        foreach ($entity_instances as $instance) {
          assert($instance instanceof GroupContentInterface);
          if ($instance->id() == $group_content_id) {
            $entity_count--;
            break;
          }
        }
      }

      // Raise a violation if the content has reached the cardinality limit.
      if ($entity_count >= $entity_cardinality) {
        $this->context->buildViolation($constraint->entityMessage)
          ->setParameter('@field', $field_name)
          ->setParameter('%content', $entity->label())
          ->setParameter('%group', $group->label())
          // We manually flag the entity reference field as the source of the
          // violation so form API will add a visual indicator of where the
          // validation failed.
          ->atPath('entity_id.0')
          ->addViolation();
      }
    }
  }

}
