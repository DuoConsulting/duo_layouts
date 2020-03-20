// This snippet fixes an issue where input fields are not editable in ckeditor
// modals within the layout builder modal. See kettering_theme_preprocess_page for
// links to the issues.

(function ($, Drupal) {

  orig_allowInteraction = $.ui.dialog.prototype._allowInteraction;
  $.ui.dialog.prototype._allowInteraction = function(event) {
     if ($(event.target).closest('.cke_dialog').length) {
        return true;
     }
     return orig_allowInteraction.apply(this, arguments);
  };

})(jQuery, Drupal);
