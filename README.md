# Recipe-Finder
Recipe finder
# Start date
24-08-2021

# Steps :

-- Install Git in your system
-- Install Xampp/Wamp Server
-- Clone repository
-- For phpUnit testing Install 'Composer' and update it
-- Application path 'http://localhost/Recipe-Findar/'

# Validation:

-- If ingredient not found in the list.
-- If no order found.
-- Valid date validation.
-- Items counts in a row validation.

# Requirements
  -- PHP 5+
  -- PHPUnit 3.6+

# How to run:

 -- Browse csv file for first input field
 -- Browse Json file for second input field

# Samples
     (Sample files are in folder uploads/*)

Path : http://localhost/Recipe-Findar/

# Sample Images

 Screenshots are available in Imgaes/* Folder

# Test Cases :

    PhpUnit test is used.
    ex: php .\vendor\bin\phpunit <File path>

Note : Sample files are in uploads/*

## Some useful commands :

1) composer require --dev phpunit/phpunit  //Install/Update PhpUnit
2) php .\vendor\bin\phpunit .\tests\RecipeTest.php --color //Test cases run command
