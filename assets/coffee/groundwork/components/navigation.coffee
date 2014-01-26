# Responsive Navigation

$ = jQuery

window.GroundworkCSS ||= {}

GroundworkCSS.responsiveNavigationIndex = 0
GroundworkCSS.responsiveNavigationElements = []

class GroundworkCSS.ResponsiveNavigation
  constructor: (el) ->
    @index = GroundworkCSS.responsiveNavigationIndex++
    @el = $(el)
    @init()

  init: ->
    @defaultLabel()
    @setupMarkers()
    unless @el.hasClass('nocollapse')
      @hamburgerHelper()

  defaultLabel: ->
    unless @el.hasClass('nocollapse')
      @el.attr('title', 'Menu') if @el.attr('title') == undefined

  setupMarkers: ->
    @el.find('ul').each ->
      if $(@).find('li').length
        $(@).attr('role', 'menu')
    @el.find('> ul').attr('role', 'menubar') unless $(@el).hasClass('vertical')
    @el.find('li').each ->
      if $(@).find('ul').length
        $(@).attr('role', 'menu')

  hamburgerHelper: ->
    @el.prepend('<button class="hamburger"></button>')

$ = jQuery
$ ->

  $body = $('body')

  GroundworkCSS.mouseBindings = -> # needs more <3
    $body.on 'mouseenter', '.nav:not(.vertical) li[role="menu"]', (e) ->
      $menu = $(@)
      $('.nav:not(.vertical)').not($menu).each ->
        $otherNav = $(@)
        unless $otherNav.find('button.hamburger').is(':visible')
          $otherNav.find('ul[aria-expanded="true"]').attr('aria-expanded', 'false')
      unless $menu.parents('.nav').find('button.hamburger').is(':visible')
        clearTimeout(GroundworkCSS.delayMenuClose)
        $expandedSiblings = $menu.siblings().find('ul[aria-expanded="true"]')
        $expandedSiblings.attr('aria-expanded', 'false')
        $targetMenu = $(e.target).parents('li[role="menu"]').children('ul')
        $targetMenu.attr('aria-expanded', 'true')

    $body.on 'mouseleave', '.nav:not(.vertical) li[role="menu"]', (e) ->
      $menu = $(@)
      unless $menu.parents('.nav').find('button.hamburger').is(':visible')
        GroundworkCSS.delayMenuClose = setTimeout( =>
          $menu.find('ul[aria-expanded="true"]').attr('aria-expanded', 'false')
        , 500)

  GroundworkCSS.touchBindings = ->
    $body.on 'click', '.nav li[role="menu"] > a, .nav li[role="menu"] > button', (e) ->
      $list = $(@).siblings('ul')
      $menu = $(@).parent('[role="menu"]')
      if $list.attr('aria-expanded') != 'true'
        $list.attr('aria-expanded', 'true')
      else
        $list.attr('aria-expanded', 'false')
        $list.find('[aria-expanded="true"]').attr('aria-expanded', 'false')
      if $menu.attr('aria-pressed') != 'true'
        $menu.attr('aria-pressed', 'true')
      else
        $menu.attr('aria-pressed', 'false')
        $menu.find('[aria-pressed="true"]').attr('aria-pressed', 'false')
        $menu.find('[aria-expanded="true"]').attr('aria-expanded', 'false')
      e.preventDefault()

    $body.on 'click', '.nav button.hamburger', (e) ->
      $list = $(@).siblings('ul')
      if $list.attr('aria-expanded') != 'true'
        $list.attr('aria-expanded', 'true')
      else
        $list.attr('aria-expanded', 'false')
        $list.find('[aria-pressed="true"]').attr('aria-pressed', 'false')
        $list.find('[aria-expanded="true"]').attr('aria-expanded', 'false')
      e.preventDefault()

  $('.nav').each ->
    GroundworkCSS.responsiveNavigationElements.push( new GroundworkCSS.ResponsiveNavigation(@) )

  GroundworkCSS.touchBindings()
  unless Modernizr.touch
    GroundworkCSS.mouseBindings()
