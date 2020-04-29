<?php

namespace Drupal\duo_layouts\Plugin\Layout;

/**
 * Configurable two column layout plugin class.
 *
 * @internal
 *   Plugin classes are internal.
 */
class TwoColumnLayout extends LayoutBase {

  /**
   * {@inheritdoc}
   */
  protected function getColumnWidthOptions() {
    return [
      '50-50' => '50%/50%',
      '33-67' => '33%/67%',
      '67-33' => '67%/33%',
      '40-60' => '40%/60%',
      '60-40' => '60%/40%',
      '25-75' => '25%/75%',
      '75-25' => '75%/25%',
    ];
  }

}
