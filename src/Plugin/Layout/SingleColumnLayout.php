<?php

namespace Drupal\duo_layouts\Plugin\Layout;

/**
 * Configurable single column layout plugin class.
 *
 * @internal
 *   Plugin classes are internal.
 */
class SingleColumnLayout extends LayoutBase {

  /**
   * {@inheritdoc}
   */
  protected function getColumnWidthOptions() {
    return [
      '100' => '100%',
    ];
  }

}
