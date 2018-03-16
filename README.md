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

Create an instance of `Kronos()` class in the start of your page, passing the page "alias" name and `optionally` the initial float timestamp (If not passed kronos calculate for you the timestamp).<br />
At the end of page call the method `setMainEnd()` optionally passing the ending float timestamp (If not passed kronos calculate for you the timestamp).<br />
Finally you can call the method `getReport()` to render under your page a final report, or call the method `getReportRaw()` to get the array with all the data.<br />
The `getReport()` accept in input a dateFormat in PHP style, 

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

In the middle of your code (where you want), you can insert a checkpoint, which allows you to create other statistics.<br />
To create a checkpoint use the methods `startCheckPoints()` and `stopCheckPoints()` passing the checkpoint alias (it will be the key of your checkpoint), and optionally the float timestamp (If not passed kronos calculate for you the timestamp).<br />


```php
<?php
	
	use emoretti\kronos\Kronos;

	$myKronos = new Kronos("pageAlias"); 


	[... YOUR CODE ...]
	
	$myKronos->startCheckPoints("FirstCheckPoint",microtime(true));

	usleep(mt_rand(500, 3000));

	$myKronos->stopCheckPoints("FirstCheckPoint",microtime(true));

	[... YOUR CODE ...]

	
	$myKronos->setMainEnd(microtime(true));


	$myKronos->getReport(); // OR $myKronos->getReportRaw();

?>
```

## Kronos Results

Kronos results can be:

 1. Rendered in bottom of the examined page
	```php
		$myKronos->getReport();
	```

 2. Returned in array structure (or json if you pass the first argument true)
 	```php
 		\\Array structure output
		$myKronos->getReportRaw();
		\\Json structure output
		$myKronos->getReportRaw(true);
	```

 3. Saved in a file (the file will have a json inside)
	```php
		$myKronos->saveReportData(__DIR__ . "/report.json");
	```
	<br /><br />If you save data for get the report later, you can render that use the static method `Kronos::renderReport()` passing $reportName and $reportData	
	```php
		use emoretti\kronos\Kronos;
		require_once("src/Kronos.php"); 
		Kronos::renderReport("TestReport", file_get_contents(__DIR__."/report.json"));
	```

## Kronos Render 

Kronos will render the data through is template engine `KronosTemplate()`, by default the template is: `src/template/KronosTemplate.php` (it use Bootstrap.min.css) , you can modify it as you want (
Be careful not to change the variable names in the template)

You can specify your prefered date format for those methods:

1. getReport ( $dateFormat = "d/m/Y H:i:s:u" )
2. getReportRaw ( $json= false , $dateFormat = "d/m/Y H:i:s:u" ) 
3. renderReport( $name , $data  , $dateFormat='d/m/Y H:i:s:u' )


## Kronos time execution

Kronos will try automatically to determine his execution time, it will be presented automatically in the final report, may be usefull to determine how time it is used by the class itself.<br />
N.B. Kronos attempts to calculate its execution time. But these times are to be considered as APPROXIMATE. Remember that : 0.001 ms == 1 µs (In 1 μs the light runs exactly 299.792458 meters).

### Author

Ettore Moretti - <info@ettoremoretti.com> - <https://twitter.com/emoretticom> - <https://www.facebook.com/emoretticom/>

### License

href-count is licensed under the MIT License - see the `LICENSE` file for details