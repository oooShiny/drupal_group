<?php

namespace Drupal\group\Plugin\Group\RelationHandler;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Url;
use Drupal\group\Entity\GroupInterface;

/**
 * Provides operations for the group_membership relation plugin.
 */
class GroupMembershipOperationProvider implements OperationProviderInterface {

  use OperationProviderTrait;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new GroupMembershipOperationProvider.
   *
   * @param \Drupal\group\Plugin\Group\RelationHandler\OperationProviderInterface $parent
   *   The default operation provider.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   */
  public function __construct(OperationProviderInterface $parent, TranslationInterface $string_translation, AccountProxyInterface $current_user) {
    $this->parent = $parent;
    $this->stringTranslation = $string_translation;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupOperations(GroupInterface $group) {
    $operations = $this->parent->getGroupOperations($group);

    if ($group->getMember($this->currentUser)) {
      if ($group->hasPermission('leave group', $this->currentUser)) {
        $operations['group-leave'] = [
          'title' => $this->t('Leave group'),
          'url' => new Url('entity.group.leave', ['group' => $group->id()]),
          'weight' => 99,
        ];
      }
    }
    elseif ($group->hasPermission('join group', $this->currentUser)) {
      $operations['group-join'] = [
        'title' => $this->t('Join group'),
        'url' => new Url('entity.group.join', ['group' => $group->id()]),
        'weight' => 0,
      ];
    }

    // We cannot use the user.is_group_member:%group_id cache context for the
    // join and leave operations, because they end up in the group operations
    // block, which is shown for most likely every group in the system. Instead,
    // we cache per user, meaning the block will be auto-placeholdered in most
    // set-ups.
    // @todo With the new VariationCache, we can use the above context.
    $operations['#cache']['contexts'] = ['user'];

    return $operations;
  }

}
