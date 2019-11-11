/**
 * @file
 * Activate parallax backgrounds.
 *
 */
(function ($, Drupal) {

  $(document).ready(function() {

    var $window = $(window);
    var $parallax   = $('.parallax');

    $parallax.each(function(index) {
      updateParallax($(this), $window);
      $(this).fadeIn('slow');
    })

    $window.on('scroll', Foundation.util.throttle(function(e) {
      $parallax.each(function(index) {
        updateParallax($(this), $window);
      })
    }, 50));

  });

  function updateParallax($element, $window) {
    var viewportPosition = $element.parent().offset().top - $window.scrollTop();
    var elementHeight = $element.parent().outerHeight();
    var totalHeight = $window.height() + elementHeight;
    var elementPosition = viewportPosition + elementHeight;
    var distanceTravelled = totalHeight - elementPosition;
    var percentTravelled = (distanceTravelled / totalHeight);

    if (percentTravelled >= 0) {
      var shift = -1 * (200 - percentTravelled * 200);
      $element.css({
        'transform': 'translateY(' + shift + 'px)'
      });
    }
  }

})(jQuery, Drupal);
