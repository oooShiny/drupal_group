<?php

namespace Drupal\Tests\group\Kernel\QueryAlter;

use Drupal\group\Entity\GroupTypeInterface;

/**
 * Tests that Group properly checks access for "complex" grouped entities.
 *
 * By complex entities we mean entities that can be published or unpublished and
 * have a way of determining who owns the entity. This leads to far more complex
 * query alters as we need to take ownership and publication state into account.
 *
 * @coversDefaultClass \Drupal\group\QueryAccess\EntityQueryAlter
 * @group group
 */
class EntityQueryAlterComplexTest extends EntityQueryAlterTestBase {

  /**
   * {@inheritdoc}
   */
  protected $entityTypeId = 'node';

  /**
   * {@inheritdoc}
   */
  protected $isPublishable = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $pluginId = 'node_as_content:page';

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installSchema('node', ['node_access']);
    $this->installEntitySchema('node');
  }

  /**
   * {@inheritdoc}
   */
  protected function setUpContent(GroupTypeInterface $group_type) {
    parent::setUpContent($group_type);

    // Add two nodes, one of which belongs to a group.
    $this->createNodeType(['id' => 'page']);
    $this->createNode(['type' => 'page']);
    $group = $this->createGroup(['type' => $group_type->id()]);
    $group->addContent($this->createNode(['type' => 'page']), $this->pluginId);
    return $group;
  }

  /**
   * Creates a node.
   *
   * @param array $values
   *   (optional) The values used to create the entity.
   *
   * @return \Drupal\node\Entity\Node
   *   The created node entity.
   */
  protected function createNode(array $values = []) {
    $storage = $this->entityTypeManager->getStorage('node');
    $node = $storage->create($values + [
      'title' => $this->randomString(),
    ]);
    $node->enforceIsNew();
    $storage->save($node);
    return $node;
  }

  /**
   * Creates a node type.
   *
   * @param array $values
   *   (optional) The values used to create the entity.
   *
   * @return \Drupal\node\Entity\NodeType
   *   The created node type entity.
   */
  protected function createNodeType(array $values = []) {
    $storage = $this->entityTypeManager->getStorage('node_type');
    $node_type = $storage->create($values + [
      'type' => $this->randomMachineName(),
      'label' => $this->randomString(),
    ]);
    $storage->save($node_type);
    return $node_type;
  }

}