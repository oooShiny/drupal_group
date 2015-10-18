<?php
/**
 * @file
 * Contains Drupal\group\Form\GroupForm.
 */

namespace Drupal\group\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the group entity edit forms.
 *
 * @ingroup group
 */
class GroupForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.group.collection');
    return parent::save($form, $form_state);
  }
}