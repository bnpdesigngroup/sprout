# Equal Grid Columns

$ = jQuery

window.GroundworkCSS ||= {}

$ ->

  $body = $('body')

  GroundworkCSS.equalizeColumns = ->
    $('.row.equalize').each ->
      $row = $(@)
      tallest = 0
      collapsed = false

      $row.children().each (i) ->
        $col = $(@)
        $col.css('minHeight','1px')
        collapsed = ($col.outerWidth() == $row.outerWidth())
        unless collapsed
          $col.addClass('equal') unless $col.hasClass('equal')
          if $col.outerHeight() > tallest
            tallest = $col.outerHeight()

      $row.children().css('min-height', tallest) unless collapsed

$(window).on 'load resize', ->
  GroundworkCSS.equalizeColumns()
