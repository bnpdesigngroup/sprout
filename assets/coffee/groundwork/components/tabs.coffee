# Responsive Tabs

$ = jQuery

window.GroundworkCSS ||= {}

$ ->

  $body = $('body')

  $('.tabs').each ->
    $tabs = $(@)
    $activeTab = $tabs.children('ul').children('li.active')
    if $activeTab.length
      $tabs = $activeTab.parents('.tabs')
      $tabs.find($activeTab.attr('aria-controls')).addClass('active')
    else
      $tabs.children('ul').children('li').first().addClass('active')
      $tabs.children('div').first().addClass('active')

  $body.on 'click', '.tabs > ul li', (e) ->
    $tab = $(@)
    $tabs = $tab.parents('.tabs')
    $tab.addClass('active')
    $tab.siblings('li').removeClass('active')
    $tabs.find('> div, > ul > div').hide()
    $tabs.find($tab.attr('aria-controls')).show()
    e.preventDefault()

  GroundworkCSS.transformTabs = ->
    viewportWidth = $(window).width()
    accordion = '.tabs.accordion'
    mobile = '.tabs.accordion.mobile'
    smalltablet = '.tabs.accordion.small-tablet'
    ipad = '.tabs.accordion.ipad'
    notaccordion = '.tabs:not(.accordion)'
    notmobile = ':not(.mobile)'
    notsmalltablet = ':not(.small-tablet)'
    notipad = ':not(.ipad)'
    if viewportWidth <= 480
      GroundworkCSS.restoreTabStructure(mobile)
      GroundworkCSS.convertToAccordion(notaccordion + notmobile)
    else if viewportWidth < 768
      GroundworkCSS.restoreTabStructure(mobile + ', ' + smalltablet)
      GroundworkCSS.convertToAccordion(notaccordion + notmobile + notsmalltablet)
    else if viewportWidth <= 1024
      GroundworkCSS.restoreTabStructure(mobile + ', ' + smalltablet + ', ' + ipad)
      GroundworkCSS.convertToAccordion(notaccordion + notmobile + notsmalltablet + notipad)
    else if viewportWidth > 1024
      GroundworkCSS.restoreTabStructure(accordion)

  GroundworkCSS.convertToAccordion = (tabTypes) ->
    tabTypes = $(tabTypes)
    tabTypes.each ->
      $tabs = $(@)
      $tabs.addClass('accordion')
      $tabs.find('> div').each ->
        $tab = $(@)
        $tabpanel = $tab.clone()
        tablink = 'li[aria-controls="#'+$tabpanel.attr('id')+'"]'
        $tabs.find(tablink).after($tabpanel)
        $tab.remove()

  GroundworkCSS.restoreTabStructure = (tabTypes) ->
    $(tabTypes).each ->
      $tabs = $(@)
      $tabs.removeClass('accordion')
      if $tabs.hasClass('vertical')
        GroundworkCSS.adjustVerticalTabs($tabs)
      $tabs.children('ul').children('div').each ->
        $tab = $(@)
        $tabpanel = $tab.clone()
        $tabs.append($tabpanel)
        $tab.remove()

  GroundworkCSS.adjustVerticalTabs = (tabs) ->
    tabs = $(tabs)
    unless tabs.length
      tabs = $('.tabs.vertical')
    tabs.each ->
      if $(@).hasClass('vertical')
        $(@).children('ul').css
          'min-height': '0px'
        unless $(@).hasClass('accordion')
          $(@).children('[role="tabpanel"]').css
            'padding-left': $(@).children('ul').width() + 10 + 'px'
          $(@).children('ul').css
            'min-height': $(@).height() + 'px'

$(window).on 'load resize', ->
  clearTimeout(GroundworkCSS.delayedAdjustTabs)
  GroundworkCSS.delayedAdjustTabs = setTimeout( ->
    GroundworkCSS.transformTabs()
    GroundworkCSS.adjustVerticalTabs()
  , 50)
