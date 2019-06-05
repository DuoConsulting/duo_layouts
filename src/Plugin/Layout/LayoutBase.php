<?php

namespace Drupal\duo_layouts\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

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
    $max_width_classes = array_keys($this->getMaxWidthOptions());
    $column_width_classes = array_keys($this->getColumnWidthOptions());
    $top_margins = array_keys($this->getTopMarginOptions());
    $bottom_margins = array_keys($this->getBottomMarginOptions());
    return [
      'max_width' => array_shift($max_width_classes),
      'column_widths' => array_shift($column_width_classes),
      'top_margin' => array_shift($top_margins),
      'bottom_margin' => array_shift($bottom_margins),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['max_width'] = [
      '#type' => 'select',
      '#title' => $this->t('Max Width'),
      '#default_value' => $this->configuration['max_width'],
      '#options' => $this->getMaxWidthOptions(),
      '#description' => $this->t('Specify the max width for this section.'),
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
    $this->configuration['max_width'] = $form_state->getValue('max_width');
    $this->configuration['column_widths'] = $form_state->getValue('column_widths');
    $this->configuration['top_margin'] = $form_state->getValue('top_margin');
    $this->configuration['bottom_margin'] = $form_state->getValue('bottom_margin');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['#attributes']['class'] = [
      'layout',
      $this->getPluginDefinition()->getTemplate(),
      $this->getPluginDefinition()->getTemplate() . '--' . $this->configuration['column_widths'],
      $this->configuration['max_width'],
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
      'c-width-default' => 'Default',
      'c-width-fs' => 'Fullscreen',
      'c-width12' => '100%',
      'c-width11' => '92%',
      'c-width10' => '83%',
      'c-width9' => '75%',
      'c-width8' => '66%',
      'c-width7' => '58%',
      'c-width6' => '50%',
      'c-width5' => '42%',
      'c-width4' => '33%',
      'c-width3' => '25%',
      'c-width2' => '17%',
      'c-width1' => '8%',
    ];
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
      'c-padding-top-default' => 'Default',
      'c-padding-top-half' => 'Half',
      'c-padding-top-quarter' => 'Quarter',
      'c-padding-top-zero' => 'Zero',
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
      'c-padding-bottom-default' => 'Default',
      'c-padding-bottom-half' => 'Half',
      'c-padding-bottom-quarter' => 'Quarter',
      'c-padding-bottom-zero' => 'Zero',
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
