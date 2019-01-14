<?php


namespace Holabs\Printer;

use Latte\Engine;
use Nette\Application\IResponse;
use Nette\Application\UI\Form;
use Nette\Http\IRequest;
use Nette\Http\IResponse as IHttpResponse;
use Nette\SmartObject;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 *
 * @method onReady(Job $sender) Occurs when job is ready to print after user input if any - CHECK isReady before
 */
class Job implements IResponse {

	use SmartObject;

	/** @var \Closure[]|callable[] */
	public $onReady = [];

	/** @var Engine */
	private $engine;

	/** @var Form|null */
	private $form;

	/** @var array */
	private $params;

	/** @var string|null */
	private $source = null;

	/** @var bool */
	private $ready = FALSE;


	public function __construct(Engine $engine, ?Form $form = null, array $params = []) {
		$this->engine = $engine;
		$this->form = $form;
		$this->params = $params;
		if ($form !== null) {
			$this->params['input'] = $form->getValues(); // Prefill user input with form defaults
			$form->onSuccess[] = function(Form $form) {
				$this->params['input'] = $form->getValues();
				$this->ready = TRUE;
				$this->onReady($this);
			};
		} else {
			$this->ready = TRUE;
		}
	}

	/**
	 * @return Engine
	 */
	public function getEngine(): Engine {
		return $this->engine;
	}

	/**
	 * @return array
	 */
	public function getParams(): array {
		return $this->params;
	}

	/**
	 * @return bool
	 */
	public function isReady(): bool {
		return $this->ready;
	}

	/**
	 * @return Form|null
	 */
	public function getForm(): ?Form {
		return $this->form;
	}

	/**
	 * @return bool
	 */
	public function hasForm(): bool {
		return $this->getForm() !== NULL;
	}

	/**
	 * @return bool
	 */
	public function isRendered(): bool {
		return $this->source === NULL;
	}

	/**
	 * @return string
	 */
	public function render(): string {
		if ($this->isRendered()) {
			$this->generate();
		}

		return $this->source;
	}

	/**
	 * Sends response to output.
	 * @param IRequest      $httpRequest
	 * @param IHttpResponse $httpResponse
	 * @return void
	 */
	public function send(IRequest $httpRequest, IHttpResponse $httpResponse) {
		echo $this->render();
	}

	/**
	 * Generate final source code from templates
	 */
	private function generate() {
		$this->source = $this->getEngine()->renderToString('template', $this->getParams());
	}
}