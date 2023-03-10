<?php

namespace Drupal\group\Form;

use Drupal\group\Entity\Form\GroupRelationshipDeleteForm;

/**
 * Provides a form for leaving a group.
 */
class GroupLeaveForm extends GroupRelationshipDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    $message = 'Are you sure you want to leave %group?';
    $replace = ['%group' => $this->getEntity()->getGroup()->label()];
    return $this->t($message, $replace);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Leave group');
  }

}
