# Config Form extension for Contao

Use config files instead of the form generator to define forms. This makes forms more easily portable.

The forms behave the same way Form Generator forms do because they are rendered, validated and processed by the Contao core. This means, for instance:
- Form hooks, like `compileFormFields` and `prepareFormData`, are available;
- Form settings like `sendViaEmail` can be used.

## Example usage
Create a config file `templates/[theme-name/]formconfig_contact.php`:

```
<?php

return [
	'tl_form' => [
		'title' => 'Contact',
		'sendViaEmail' => true,
		'format' => 'email',
		'subject' => 'Contact via website form',
		'tableless' => true
	],
	'tl_form_field' => [
		[
			'type' => 'text',
			'name' => 'email',
			'label' => 'Email',
			'mandatory' => true,
			'rgxp' => 'email'
		],
		[
			'type' => 'text',
			'name' => 'subject',
			'label' => 'Subject',
			'mandatory' => true
		],
		[
			'type' => 'textarea',
			'name' => 'message',
			'label' => 'Message',
			'mandatory' => true,
			'size' => serialize([8,12])
		],
		[
			'type' => 'submit',
			'slabel' => 'Send'
		]
	]
];
```

In the Contao backend, create a front end module of the type `Form from config file` and select the config.

Use the module wherever the form shall be inserted.
