(function() {
  var $;

  $ = jQuery;

  window.GroundworkCSS || (window.GroundworkCSS = {});

  $(function() {
    var $body;
    $body = $('body');
    return GroundworkCSS.equalizeColumns = function() {
      return $('.row.equalize').each(function() {
        var $row, collapsed, tallest;
        $row = $(this);
        tallest = 0;
        collapsed = false;
        $row.children().each(function(i) {
          var $col;
          $col = $(this);
          $col.css('minHeight', '1px');
          collapsed = $col.outerWidth() === $row.outerWidth();
          if (!collapsed) {
            if (!$col.hasClass('equal')) {
              $col.addClass('equal');
            }
            if ($col.outerHeight() > tallest) {
              return tallest = $col.outerHeight();
            }
          }
        });
        if (!collapsed) {
          return $row.children().css('min-height', tallest);
        }
      });
    };
  });

  $(window).on('load resize', function() {
    return GroundworkCSS.equalizeColumns();
  });

}).call(this);
