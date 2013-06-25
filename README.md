ExtractNodePro.php
==================

PHP function for extracting fields from Drupal Nodes into a simple and clean array

Usage
==================
Insert extractNodePro function in template.php in your Drupal theme
```php
$data = extractNodePro($node, array('title', 'body', 'custom_thing'));
```

OUTPUT: (if field_custom_thing is a text field)
```php
$data = array(
	'title' => 'Title of the content type',
	'body' => 'Body of the content type',
	'custom_thing' => 'value of custom thing'
);
```

If field_custom_thing is something else, let's say an image, you would get:
```php
$data = array(
  'title' => 'Title of the content type',
	'body' => 'Body of the content type',
	'custom_thing' => array(
		'uri' => 'public://image.jpg',
		'width' => 500,
		'height' => 500
	)
);
```
