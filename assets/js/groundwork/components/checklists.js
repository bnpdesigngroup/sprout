(function() {
  var $;

  $ = jQuery;

  window.GroundworkCSS || (window.GroundworkCSS = {});

  $(function() {
    var $body;
    $body = $('body');
    return $body.on('click', '.checklist:not([readonly]) li:not([readonly])', function() {
      var $item;
      $item = $(this);
      if ($item.attr('aria-checked') === "true" || $item.attr('data-checked') === "true" || $item.attr('checked') === "checked" || $item.hasClass('checked') || $item.hasClass('completed')) {
        $item.attr('aria-checked', "false");
      } else {
        $item.attr('aria-checked', "true");
      }
      return $item.removeClass('checked completed').removeAttr('data-checked checked');
    });
  });

}).call(this);
