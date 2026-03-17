@twillRepeaterTitle('Translation')
@twillRepeaterTitleField('key', false)
@twillRepeaterTrigger('')

@formField('input', [
    'name' => 'key',
    'label' => 'Key',
    'disabled' => true,
])

@formField('input', [
    'name' => 'value',
    'label' => 'Value',
    'translated' => true,
    'type' => 'textarea',
])
