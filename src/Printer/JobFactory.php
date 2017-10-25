<?php


namespace Holabs\Printer;

use Latte\Loaders\FileLoader;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class JobFactory implements IJobFactory {

	use SmartObject;

	/** @var Configuration */
	private $config;

	/** @var IEntityStorage */
	private $entityStorage;

	/** @var ITemplateStorage */
	private $templateStorage;

	/**
	 * Printer constructor.
	 * @param Configuration    $config
	 * @param IEntityStorage   $entityStorage
	 * @param ITemplateStorage $templateStorage
	 */
	public function __construct(
		Configuration $config,
		IEntityStorage $entityStorage,
		ITemplateStorage $templateStorage
	) {
		$this->entityStorage = $entityStorage;
		$this->templateStorage = $templateStorage;
		$this->config = $config;
	}

	/**
	 * @return IEntityStorage
	 */
	public function getEntityStorage(): IEntityStorage {
		return $this->entityStorage;
	}

	/**
	 * @return ITemplateStorage
	 */
	public function getTemplateStorage(): ITemplateStorage {
		return $this->templateStorage;
	}

	/**
	 * @param string $id
	 * @param int[]  $objIds
	 * @return Job
	 */
	public function create(string $id, int ... $objIds): Job {

		$objIds = array_unique($objIds);

		$layout = new FileLoader();

		$template = $this->getTemplateStorage()->getTemplate($id);
		$preview = count($objIds) ? new PreviewEntity($template->getClass()) : FALSE;
		$entities = $preview
			? ArrayHash::from([$preview])
			: $this->getEntityStorage()->findEntities($template->getClass(), ArrayHash::from($objIds));
		$params = [];
		$options = Helpers::validateOptions($this->getConfig()->getDefaultParams(), $template->getOptions());
		$params['background'] = $options['background'];
		$params['margin'] = $options['margin'];
		$params['size'] = $options['size'];
		$params['isAutoPrintEnabled'] = $this->getConfig()->isAutoPrintEnabled();
		$params['template'] = $template;
		$params['name'] = $template->getName();
		$params['entities'] = $entities;
		$params['options'] = $options;


		return new Job($template, $layout->getContent($this->getConfig()->getLayoutPath()), $params);
	}

	/**
	 * @return Configuration
	 */
	protected function getConfig(): Configuration {
		return $this->config;
	}

}