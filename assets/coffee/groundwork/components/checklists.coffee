# Checklists

$ = jQuery

window.GroundworkCSS ||= {}

$ ->

  $body = $('body')

  $body.on 'click', '.checklist:not([readonly]) li:not([readonly])', ->
    $item = $(@)
    if $item.attr('aria-checked') == "true" or $item.attr('data-checked') == "true" or $item.attr('checked') == "checked" or $item.hasClass('checked') or $item.hasClass('completed')
      $item.attr('aria-checked', "false")
    else
      $item.attr('aria-checked', "true")
    $item.removeClass('checked completed').removeAttr('data-checked checked')
