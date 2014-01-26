(function() {
  var $;

  $ = jQuery;

  window.GroundworkCSS || (window.GroundworkCSS = {});

  $(function() {
    var $body;
    $body = $('body');
    return $body.on('click', '.dismissible', function() {
      var $box;
      $box = $(this);
      $box.addClass('dismiss animated');
      return setTimeout(function() {
        return $box.hide(250, function() {
          return $box.remove();
        });
      }, 1000);
    });
  });

}).call(this);
