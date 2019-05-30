/**
 * @file
 * JS for Back To Top link.
 */

(function ($) {

  Drupal.behaviors.csgov_theme_BackToTop = {
    attach: function (context, settings) {
      var $body = $('body, html');
      var backToTop = $('.back-to-top__link', context);

      // Toggle class on backToTop.
      $(window).scroll(function () {
        if ($(this).scrollTop() > 250) {
          backToTop.addClass('is-visible');
        } else {
          backToTop.removeClass('is-visible');
        }
      });

      // Scroll smoothly to top on click.
      backToTop.click(function (event) {
        $body.animate({
          scrollTop: 0
        }, 800, function () {
          $body.attr('tabindex','-1').focus().removeAttr('tabindex');
        });
        event.preventDefault();
      });

    }
  };

})(jQuery);
