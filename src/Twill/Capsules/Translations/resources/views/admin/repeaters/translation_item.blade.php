@twillRepeaterTitle('Translation')
@twillRepeaterTrigger('Add translation')

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
