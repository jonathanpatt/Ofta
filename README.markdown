# Ofta

A small collection of PHP 5 classes that simplify oft-repeated tasks.

# Installation

Copy the Ofta directory into the directory of choice on your PHP 5-enabled web server and require it in your PHP file(s).

	require_once('/path/to/Ofta/Ofta.php');

If you don't need all of Ofta's classes in a particular file, you can require classes individually and they will automatically handle dependencies (if there are any).

	require_once('/path/to/Ofta/OftaFormValidator.php');

It's necessary that you use `require_once()`, especially if you're using more than one Ofta class in a given file.

# Usage

For usage documentation, see the wiki.