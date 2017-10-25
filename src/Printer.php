<?php


namespace Holabs;

use Holabs\Printer\IJobFactory;
use Holabs\Printer\Job;
use Nette\Application\AbortException;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Printer {

	use SmartObject;

	/** @var IJobFactory */
	private $jobFactory;

	/** @var Presenter */
	private $presenter;

	/**
	 * Printer constructor.
	 * @param Application $application
	 * @param IJobFactory $jobFactory
	 */
	public function __construct(Application $application, IJobFactory $jobFactory) {
		$this->jobFactory = $jobFactory;
		$application->onPresenter[] = function(Application $sender, Presenter $presenter) {
			$this->presenter = $presenter;
		};
	}

	/**
	 * @param string $id
	 * @param int[]  $objIds
	 * @return Job
	 */
	public function createJob(string $id, int ... $objIds): Job {
		return $this->getJobFactory()->create($id, ... $objIds);
	}

	/**
	 * @param Job $job
	 * @throws AbortException
	 */
	public function printJob(Job $job) {
		$this->getPresenter()->sendResponse($job);
		//echo $job->render();
		//throw new AbortException();
	}

	/**
	 * @return IJobFactory
	 */
	protected function getJobFactory(): IJobFactory {
		return $this->jobFactory;
	}


	/**
	 * @return Presenter
	 */
	protected function getPresenter(): Presenter {
		return $this->presenter;
	}

}