# Ofta

A small collection of PHP 5 classes that simplify oft-repeated tasks.

# Installation

Copy the Ofta directory into the directory of choice on your PHP 5-enabled web server and require it in your PHP file(s).

	require_once('/path/to/Ofta/Ofta.php');

If you don't need all of Ofta's classes in a particular file, you can require classes individually and they will automatically handle dependencies (if there are any).

	require_once('/path/to/Ofta/OftaTagBuilder.php');

It's necessary that you use `require_once()`, especially if you're using more than one Ofta class in a given file.

# Usage


A basic overview of the major features of each class will be outlined here. If Ofta grows past a certain point, more detailed explanations will be available on the wiki.

## OftaNotificationCenter

A class to hold notification messages of some sort for eventual output.

	$messages = new OftaNotificationCenter();
	$messages->add('Success!');
	$messages->format();
	
	Returns: <li>Success!</li>

To specify message types, you can optionally provide a type parameter:

	$messages->add('Invalid username.', 'error');
	$messages->add('Free pie!', 'special-offers');
	$messages->format('error');
	
	Returns: <li>Invalid username.</i>

You can also override the default format type with optional prefix and suffix parameters:

	$messages->format('all', '<p>', '</p>');
	
	Returns: <p>Message goes here!</p>

## OftaTagBuilder

A simple HTML tag builder that can be used to programmatically build and return HTML tags without mixing HTML directly in with your PHP.

	$t = new OftaTagBuilder();
	$t->tag('p', 'I am a paragraph!');
	
	Returns: <p>I am a paragraph!</p>

To add attributes to your tag, you can optionally provide an array as the third parameter:

	$t->tag('p', 'I am a fancy paragraph!', array('style' => 'fancyParagraph'));
	
	Returns: <p style="fancyParagraph">I am a fancy paragraph!</p>

If you want an empty, self-closing tag:

	$t->emptyTag('img', array('src' => 'image.gif'));
	
	Returns: <img src="image.gif" />

If, for some reason, you aren't working with XHTML, you can disable the closing slash in empty tags:

	$tNoXHTML = new oftaTagBuilder(false);
	$tNoXHTML->emptyTag('br');
	
	Returns: <br>

For more specialized circumstances, you may want to build start and end tags separately.

	$t->startTag('div', array('class' => 'box')).'Who would want to do this?'.$t->endTag('div');
	
	
	Returns: <div class="box">Who would want to do this?</div>

## OftaTemplate

A very simple, but very versatile template class.

Template variable tags are strings surrounded by curly brackets. Case does not matter, but uppercase is preferable as it makes them more visible.

	<title>{TITLE}</title>

	$frame = new OftaTemplate('/path/to/template');
	$frame->set('title', 'Templates are cool!);
	$frame->publish();
	
	Outputs: <title>Templates are cool!</title>

To use templates within templates, use the `template()` method to return its final value to a variable:

	$frame = new OftaTemplate('/path/to/awesome/template');
	$content = new OftaTemplate('/path/to/content');
	$content->set('date', date('M d, Y'));
	$frame->set('content', $content->template());
	$frame->publish();