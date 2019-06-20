<?php

namespace Drupal\duo_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\File\Entity\File;

/**
 * Base class of layouts with configurable widths.
 *
 * @internal
 *   Plugin classes are internal.
 */
abstract class LayoutBase extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $max_width_classes    = array_keys($this->getMaxWidthOptions());
    $bg_colors            = array_keys($this->getBackgroundColors());
    $column_width_classes = array_keys($this->getColumnWidthOptions());
    $top_margins          = array_keys($this->getTopMarginOptions());
    $bottom_margins       = array_keys($this->getBottomMarginOptions());

    return [
      'background_image' => '',
      'heading'          => '',
      'max_width'        => array_shift($max_width_classes),
      'background_color' => array_shift($bg_colors),
      'column_widths'    => array_shift($column_width_classes),
      'top_margin'       => array_shift($top_margins),
      'bottom_margin'    => array_shift($bottom_margins),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['heading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section Heading'),
      '#default_value' => $this->configuration['heading'],
      '#description' => $this->t('Specify a heading for this section.'),
    ];

    $form['max_width'] = [
      '#type' => 'select',
      '#title' => $this->t('Max Width'),
      '#default_value' => $this->configuration['max_width'],
      '#options' => $this->getMaxWidthOptions(),
      '#description' => $this->t('Specify the max width for this section.'),
    ];

    $form['background_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Background Color'),
      '#default_value' => $this->configuration['background_color'],
      '#options' => $this->getBackgroundColors(),
      '#description' => $this->t('Select a background color for this section.'),
    ];

    $form['background_image'] = [
      '#type' => 'managed_file',
      '#name' => 'background_image',
      '#title' => t('Background Image'),
      '#size' => 20,
      '#description' => t('Allows jpg, jpeg, png, and gif file formats.'),
      '#upload_validators' => array(
        'file_validate_extensions' => array('jpg jpeg png gif'),
      ),
      '#upload_location' => 'public://background_image/',
      '#default_value' => array($this->configuration['background_image']),
    ];

    $form['column_widths'] = [
      '#type' => 'select',
      '#title' => $this->t('Column Widths'),
      '#default_value' => $this->configuration['column_widths'],
      '#options' => $this->getColumnWidthOptions(),
      '#description' => $this->t('Specify the column widths for this section.'),
    ];

    $form['top_margin'] = [
      '#type' => 'select',
      '#title' => $this->t('Top Margin'),
      '#default_value' => $this->configuration['top_margin'],
      '#options' => $this->getTopMarginOptions(),
      '#description' => $this->t('Specify the top margin for this section.'),
    ];

    $form['bottom_margin'] = [
      '#type' => 'select',
      '#title' => $this->t('Bottom Margin'),
      '#default_value' => $this->configuration['bottom_margin'],
      '#options' => $this->getBottomMarginOptions(),
      '#description' => $this->t('Specify the bottom margin for this section.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['heading'] = $form_state->getValue('heading');
    $this->configuration['max_width'] = $form_state->getValue('max_width');
    $this->configuration['background_color'] = $form_state->getValue('background_color');

    // File handling.
    $form_file = $form_state->getValue('background_image');
    if (isset($form_file[0]) && !empty($form_file[0])) {
      $file = File::load($form_file[0]);
      $file->setPermanent();
      $file->save();
      $this->configuration['background_image'] = $file->id();
    } else {
      $this->configuration['background_image'] = '';
    }

    $this->configuration['column_widths'] = $form_state->getValue('column_widths');
    $this->configuration['top_margin'] = $form_state->getValue('top_margin');
    $this->configuration['bottom_margin'] = $form_state->getValue('bottom_margin');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['heading'] = [
      '#markup' => $this->configuration['heading'],
    ];

    // Get the file url for the background image.
    if ($this->configuration['background_image']) {
      $file = \Drupal\file\Entity\File::load($this->configuration['background_image']);
      $build['#attributes']['background_image_url'] = [
        $file->url(),
      ];
    }

    $build['#attributes']['class'] = [
      'layout',
      $this->getPluginDefinition()->getTemplate(),
      $this->getPluginDefinition()->getTemplate() . '--' . $this->configuration['column_widths'],
      $this->configuration['max_width'],
      $this->configuration['background_color'],
      $this->configuration['top_margin'],
      $this->configuration['bottom_margin'],
    ];

    return $build;
  }

  /**
   * Gets the max width options for the configuration form.
   *
   * The first option will be used as the default value.
   *
   * @return string[]
   *   The max widths options array where the keys are strings that will be added to
   *   the CSS classes and the values are the human readable labels.
   */
  protected function getMaxWidthOptions() {
    return [
      'layout--width-default' => t('Default'),
      'layout--width-fs' => t('Fullscreen'),
      'layout--width-100' => '100%',
      'layout--width-90' => '90%',
      'layout--width-80' => '80%',
      'layout--width-70' => '70%',
      'layout--width-60' => '60%',
      'layout--width-50' => '50%',
      'layout--width-40' => '40%',
      'layout--width-30' => '30%',
      'layout--width-20' => '20%',
      'layout--width-10' => '10%',
    ];
  }

  /**
   * Gets the background color options for the configuration form.
   *
   * The first option will be used as the default value.
   *
   * @return string[]
   *   The background colors options array where the keys are strings that will be added to
   *   the CSS classes and the values are the human readable labels.
   */
  protected function getBackgroundColors() {
    $config = \Drupal::config('duo_layouts.adminsettings');
    $config_options = $config->get('duo_layouts_background_colors');
    $options = array('' => t('None'));

    if ($config_options) {
      $lines = explode("\n", $config_options);
      
      foreach ($lines as $i => $value) {
        $option = explode('|', $value);
        $options[$option[0]] = t($option[1]);
      }
    }

    return $options;
  }

  /**
   * Gets the top margin options for the configuration form.
   *
   * The first option will be used as the default value.
   *
   * @return string[]
   *   The top margins options array where the keys are strings that will be added to
   *   the CSS classes and the values are the human readable labels.
   */
  protected function getTopMarginOptions() {
    return [
      'layout--padding-top-default' => t('Default'),
      'layout--padding-top-half' => t('Half'),
      'layout--padding-top-quarter' => t('Quarter'),
      'layout--padding-top-zero' => t('Zero'),
    ];
  }

  /**
   * Gets the bottom margin options for the configuration form.
   *
   * The first option will be used as the default value.
   *
   * @return string[]
   *   The bottom margins options array where the keys are strings that will be added to
   *   the CSS classes and the values are the human readable labels.
   */
  protected function getBottomMarginOptions() {
    return [
      'layout--padding-bottom-default' => t('Default'),
      'layout--padding-bottom-half' => t('Half'),
      'layout--padding-bottom-quarter' => t('Quarter'),
      'layout--padding-bottom-zero' => t('Zero'),
    ];
  }

  /**
   * Gets the width options for the configuration form.
   *
   * The first option will be used as the default 'column_widths' configuration
   * value.
   *
   * @return string[]
   *   The width options array where the keys are strings that will be added to
   *   the CSS classes and the values are the human readable labels.
   */
  abstract protected function getColumnWidthOptions();

}
