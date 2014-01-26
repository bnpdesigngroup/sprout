###
 *
 *  jQuery ResponsiveText by Gary Hepting
 *  https://github.com/ghepting/jquery-responsive-text
 *
 *  Open source under the MIT License.
 *
 *  Copyright © 2013 Gary Hepting. All rights reserved.
 *
###

$ = jQuery

GroundworkCSS.delayedAdjustText = []
GroundworkCSS.responsiveTextIndex = 0
GroundworkCSS.responsiveTextElements = []


class GroundworkCSS.ResponsiveText

  constructor: (el) ->
    @index = GroundworkCSS.responsiveTextIndex++
    @el = el
    @compression = $(@el).data('compression') || 10
    @minFontSize = $(@el).data('min') || Number.NEGATIVE_INFINITY
    @maxFontSize = $(@el).data('max') || Number.POSITIVE_INFINITY
    @scrollable = $(@el).data('scrollable') || false
    @scrollSpeed = $(@el).data('scrollspeed') || 650
    @scrollReset = $(@el).data('scrollreset') || 200
    @init()

  init: ->
    $(@el).wrapInner('<span class="responsiveText-wrapper" />')
    @adjustOnLoad()
    @adjustOnResize()
    @scrollOnHover() if @scrollable

  resizeText: ->
    calculatedFontSize = $(@el).width() / @compression
    fontSize = Math.max(Math.min(calculatedFontSize,@maxFontSize),@minFontSize)
    $(@el).css
      "font-size": Math.floor(fontSize)

  adjustOnLoad: ->
    $(window).on 'load', =>
      @resizeText()

  adjustOnResize: ->
    $(window).on 'resize', =>
      clearTimeout(GroundworkCSS.delayedAdjustText[@index])
      GroundworkCSS.delayedAdjustText[@index] = setTimeout(=>
        @resizeText()
      , 20)

  scrollOnHover: ->
    $(@el).css
      'overflow': 'hidden'
      'text-overflow': 'ellipsis'
      'white-space': 'nowrap'
    $(@el).hover =>
      @difference = @el.scrollWidth - $(@el).width()
      @scrollSpeed = @difference if @difference > @scrollSpeed
      if @difference > 0
        $(@el).css('cursor', 'e-resize')
        $(@el).stop().animate
          "text-indent": -@difference
        , @scrollSpeed
        , =>
          $(@el).css('cursor', 'text')
    , =>
      $(@el).stop().animate
        "text-indent": 0
      , @scrollReset


$.fn.responsiveText = (options) ->

  @each ->
    GroundworkCSS.responsiveTextElements.push( new GroundworkCSS.ResponsiveText(@) )

$(document).ready ->
  $(".responsive").not('table').responsiveText()
