# Kronos - calculate the execution time of a page and code blocks for PHP 
Kronos is a PHP library, which allows to calculate the execution time of a page and code blocks (called CheckPoint), with an accuracy up to 1 μs

## Installation
Install the latest version with

```bash
$ composer require emoretti/kronos ^dev-master
```

## Configuration

`no configuration needed`

## Basic Usage

Create an instance of `Kronos()` class in the start of your page, passing the page "alias" name and `optionally` the initial float timestamp (If not passed kronos calculate for you the timestamp).<br /><br />
At the end of page call the method `setMainEnd()` optionally passing the ending float timestamp (If not passed kronos calculate for you the timestamp).<br /><br />
At the end you can call the method `getReport()` to render under your page a final report, or call the method `getReportRaw()` to get the array with all the data.<br />

```php
<?php
	
	use emoretti\kronos\Kronos;

	$myKronos = new Kronos("pageAlias"); 


	[... YOUR CODE ...]


	[... YOUR CODE ...]

	
	$myKronos->setMainEnd(microtime(true));


	$myKronos->getReport(); // OR $myKronos->getReportRaw();
?>

```

## Use of checkpoints 

After declare the instance of `Kronos()`<br /><br />

In the middle of your code (where you want), you can enter a checkpoint, which allows you to create other statistics.<br /><br />
To create a checkpoint use the methods `startCheckPoints()` and `stopCheckPoints()` passing the checkpoint alias (it will be the key of your checkpoint), and optionally the float timestamp (If not passed kronos calculate for you the timestamp).<br />


```php
<?php
	
	use emoretti\kronos\Kronos;

	$myKronos = new Kronos("pageAlias"); 


	[... YOUR CODE ...]
	
	$myKronos->startCheckPoints("FirstCheckPoint",microtime(true));

		[... YOUR CHECKPOINT CODE ...]

	$myKronos->stopCheckPoints("FirstCheckPoint",microtime(true));

	[... YOUR CODE ...]

	
	$myKronos->setMainEnd(microtime(true));


	$myKronos->getReport(); // OR $myKronos->getReportRaw();

?>
```

## Kronos time execution

Kronos will try automatically to determine his execution time, it will be presented automatically in the final report, may be usefull to determine how time it is used by the class itself.<br /><br />
N.B. Kronos attempts to calculate its execution time. But these times are to be considered as APPROXIMATE. Remember that : 0.001 ms == 1 µs (In 1 μs the light runs exactly 299.792458 meters).

### Author

Ettore Moretti - <info@ettoremoretti.com> - <https://twitter.com/emoretticom> - <https://www.facebook.com/emoretticom/>

### License

href-count is licensed under the MIT License - see the `LICENSE` file for details
