#Settings

A super basic class to create WordPress settings pages.

###Example Usage

```

include 'settings.php';

add_action( 'init', function() {

	$options = [
		[
			'id'    => 'basic',
			'title' => __( 'Basic', 'lambry' ),
			'description' => __( 'The basic field options.', 'lambry' ),
			'fields' => [
				[
					'id'    => 'text',
					'label' => __( 'Text', 'lambry' ),
					'description' => __( 'A sample description.', 'lambry' ),
					'type'  => 'text'
				], [
					'id'    => 'select',
					'label' => __( 'Select', 'lambry' ),
					'type'  => 'select',
					'choices' => [
						'one' => 'One',
						'two' => 'Two',
						'three' => 'Three',
					]
				], [
					'id'    => 'radio',
					'label' => __( 'Radio', 'lambry' ),
					'type'  => 'radio',
					'choices' => [
						'one' => 'One',
						'two' => 'Two',
						'three' => 'Three',
					]
				], [
					'id'    => 'checkbox',
					'label' => __( 'Checkbox', 'lambry' ),
					'type'  => 'checkbox',
					'choices' => [
						'one' => 'One',
						'two' => 'Two',
						'three' => 'Three',
					]
				], [
					'id'    => 'textarea',
					'label' => __( 'Textarea', 'lambry' ),
					'type'  => 'textarea'
				], [
					'id'    => 'editor',
					'label' => __( 'Editor', 'lambry' ),
					'type'  => 'editor'
				]
			]
		], [
			'id'          => 'extra',
			'title'       => __( 'Extra', 'lambry' ),
			'description' => __( 'Some more feild options.', 'lambry' ),
			'fields'      => [
				[
					'id'    => 'on_off',
					'label' => __( 'On Off', 'lambry' ),
					'type'  => 'on_off'
				], [
					'id'    => 'upload',
					'label' => __( 'Upload', 'lambry' ),
					'type'  => 'upload'
				], [
					'id'    => 'color',
					'label' => __( 'Color', 'lambry' ),
					'type'  => 'color'
				], [
					'id'    => 'block',
					'label' => __( 'Block', 'lambry' ),
					'content' => __( 'The block field is for display text only.', 'lambry' ),
					'type'  => 'block'
				]
			]
		]
	];

	new Lambry\Settings( 'menu', $options, __( 'Options', 'lambry' ) );

} );

```
