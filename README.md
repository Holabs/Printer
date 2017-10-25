Holabs/Printer
===============

Printer package is for easy printing in Nette framework

Installation
------------

**Requirements:**
 - php 7.1+
 - [Nette Framework](https://github.com/nette/nette)
 
```sh
composer require holabs/printer
```

Using
-----

```yaml
extensions:
	holabs.printer: Holabs\Printer\Bridges\Nette\PrinterExtension
	
# OPTIONAL
holabs.printer:
	auto-print: FALSE # Disable JS which call browser printing dialog
	layout: '%appDir%/presenters/template/printing/layout.latte' # Define own printing layout
	params: # Default parameters. Can be overwritten by job template options
		background: '#ffffff'
		margin: 2cm
		size: A4
		
# !!! You have to create your implementation of ITemplateStorage and IEntityStorage as service
```

```php
<?php

namespace App\Presenters;

use Holabs\Printer;

class PrinterPresenter extends Presenter {

	/** @var string @persistent */
	public $id = NULL;

	/** @var Printer @inject */
	public $printer;

	/**
	 * @param int[]|array $eid Entity ids
	 */
	public function actionPrint(array $eid = []){
		$job = $this->printer->createJob($this->id, ... $eid);

		// Send to output from printer
		$this->printer->printJob($job);
		
		// Or using presenter method - Job implement IResponse
		$this->sendResponse($job);
	}

}
```



