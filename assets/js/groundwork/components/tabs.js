(function() {
  var $;

  $ = jQuery;

  window.GroundworkCSS || (window.GroundworkCSS = {});

  $(function() {
    var $body;
    $body = $('body');
    $('.tabs').each(function() {
      var $activeTab, $tabs;
      $tabs = $(this);
      $activeTab = $tabs.children('ul').children('li.active');
      if ($activeTab.length) {
        $tabs = $activeTab.parents('.tabs');
        return $tabs.find($activeTab.attr('aria-controls')).addClass('active');
      } else {
        $tabs.children('ul').children('li').first().addClass('active');
        return $tabs.children('div').first().addClass('active');
      }
    });
    $body.on('click', '.tabs > ul li', function(e) {
      var $tab, $tabs;
      $tab = $(this);
      $tabs = $tab.parents('.tabs');
      $tab.addClass('active');
      $tab.siblings('li').removeClass('active');
      $tabs.find('> div, > ul > div').hide();
      $tabs.find($tab.attr('aria-controls')).show();
      return e.preventDefault();
    });
    GroundworkCSS.transformTabs = function() {
      var accordion, ipad, mobile, notaccordion, notipad, notmobile, notsmalltablet, smalltablet, viewportWidth;
      viewportWidth = $(window).width();
      accordion = '.tabs.accordion';
      mobile = '.tabs.accordion.mobile';
      smalltablet = '.tabs.accordion.small-tablet';
      ipad = '.tabs.accordion.ipad';
      notaccordion = '.tabs:not(.accordion)';
      notmobile = ':not(.mobile)';
      notsmalltablet = ':not(.small-tablet)';
      notipad = ':not(.ipad)';
      if (viewportWidth <= 480) {
        GroundworkCSS.restoreTabStructure(mobile);
        return GroundworkCSS.convertToAccordion(notaccordion + notmobile);
      } else if (viewportWidth < 768) {
        GroundworkCSS.restoreTabStructure(mobile + ', ' + smalltablet);
        return GroundworkCSS.convertToAccordion(notaccordion + notmobile + notsmalltablet);
      } else if (viewportWidth <= 1024) {
        GroundworkCSS.restoreTabStructure(mobile + ', ' + smalltablet + ', ' + ipad);
        return GroundworkCSS.convertToAccordion(notaccordion + notmobile + notsmalltablet + notipad);
      } else if (viewportWidth > 1024) {
        return GroundworkCSS.restoreTabStructure(accordion);
      }
    };
    GroundworkCSS.convertToAccordion = function(tabTypes) {
      tabTypes = $(tabTypes);
      return tabTypes.each(function() {
        var $tabs;
        $tabs = $(this);
        $tabs.addClass('accordion');
        return $tabs.find('> div').each(function() {
          var $tab, $tabpanel, tablink;
          $tab = $(this);
          $tabpanel = $tab.clone();
          tablink = 'li[aria-controls="#' + $tabpanel.attr('id') + '"]';
          $tabs.find(tablink).after($tabpanel);
          return $tab.remove();
        });
      });
    };
    GroundworkCSS.restoreTabStructure = function(tabTypes) {
      return $(tabTypes).each(function() {
        var $tabs;
        $tabs = $(this);
        $tabs.removeClass('accordion');
        if ($tabs.hasClass('vertical')) {
          GroundworkCSS.adjustVerticalTabs($tabs);
        }
        return $tabs.children('ul').children('div').each(function() {
          var $tab, $tabpanel;
          $tab = $(this);
          $tabpanel = $tab.clone();
          $tabs.append($tabpanel);
          return $tab.remove();
        });
      });
    };
    return GroundworkCSS.adjustVerticalTabs = function(tabs) {
      tabs = $(tabs);
      if (!tabs.length) {
        tabs = $('.tabs.vertical');
      }
      return tabs.each(function() {
        if ($(this).hasClass('vertical')) {
          $(this).children('ul').css({
            'min-height': '0px'
          });
          if (!$(this).hasClass('accordion')) {
            $(this).children('[role="tabpanel"]').css({
              'padding-left': $(this).children('ul').width() + 10 + 'px'
            });
            return $(this).children('ul').css({
              'min-height': $(this).height() + 'px'
            });
          }
        }
      });
    };
  });

  $(window).on('load resize', function() {
    clearTimeout(GroundworkCSS.delayedAdjustTabs);
    return GroundworkCSS.delayedAdjustTabs = setTimeout(function() {
      GroundworkCSS.transformTabs();
      return GroundworkCSS.adjustVerticalTabs();
    }, 50);
  });

}).call(this);
