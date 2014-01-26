# Forms

$ = jQuery

window.GroundworkCSS ||= {}

GroundworkCSS.formSelectors = [
  '.error input'
  '.error textarea'
  '.invalid input'
  '.invalid textarea'
  'input.error'
  'textarea.error'
  'input.invalid'
  'textarea.invalid'
  'input[aria-invalid="true"]'
  'textarea[aria-invalid="true"]'
].join(',')

$ ->

  $body = $('body')

  $body.on 'click', GroundworkCSS.formSelectors, ->
    $(@).focus()
    $(@).select()
