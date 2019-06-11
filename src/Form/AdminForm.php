<?php
/**
 * @file
 * Contains Drupal\welcome\Form\MessagesForm.
 */

namespace Drupal\duo_layouts\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AdminForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'duo_layouts.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'duo_layouts_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('duo_layouts.adminsettings');

    $form['duo_layouts_background_colors'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Background Color Options'),
      '#description' => $this->t('Enter section background color options as VALUE|OPTION (e.g., c-bgd-color-1|Color 1 - one per line).'),
      '#default_value' => $config->get('duo_layouts_background_colors'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('duo_layouts.adminsettings')
      ->set('duo_layouts_background_colors', $form_state->getValue('duo_layouts_background_colors'))
      ->save();
  }
}