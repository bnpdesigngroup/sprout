(function() {
  var $;

  $ = jQuery;

  window.GroundworkCSS || (window.GroundworkCSS = {});

  GroundworkCSS.formSelectors = ['.error input', '.error textarea', '.invalid input', '.invalid textarea', 'input.error', 'textarea.error', 'input.invalid', 'textarea.invalid', 'input[aria-invalid="true"]', 'textarea[aria-invalid="true"]'].join(',');

  $(function() {
    var $body;
    $body = $('body');
    return $body.on('click', GroundworkCSS.formSelectors, function() {
      $(this).focus();
      return $(this).select();
    });
  });

}).call(this);
