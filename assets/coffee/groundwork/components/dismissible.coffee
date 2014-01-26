# Dismissible Message Boxes

$ = jQuery

window.GroundworkCSS ||= {}

$ ->

  $body = $('body')

  $body.on 'click', '.dismissible', ->
    $box = $(@)
    $box.addClass('dismiss animated')
    setTimeout( ->
      $box.hide 250, ->
        $box.remove()
    , 1000)
