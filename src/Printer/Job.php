<?php


namespace Holabs\Printer;

use Latte\Engine;
use Latte\Loaders\StringLoader;
use Nette\Application\IResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse as IHttpResponse;


/**
 * @author       Tomáš Holan <mail@tomasholan.eu>
 * @package      holabs/printer
 * @copyright    Copyright © 2017, Tomáš Holan [www.tomasholan.eu]
 */
class Job implements IResponse {

	/** @var Engine */
	private $engine;

	/** @var string */
	private $layout;

	/** @var array */
	private $params;

	/** @var string|null */
	private $source = NULL;

	/** @var ITemplate */
	private $template;


	public function __construct(ITemplate $template, string $layout, array $params = []) {
		$this->engine = new Engine();
		$this->layout = $layout;
		$this->template = $template;
		$this->params = $params;
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
	 * @return string
	 */
	public function getLayout(): string {
		return $this->layout;
	}

	/**
	 * @return ITemplate
	 */
	public function getTemplate(): ITemplate {
		return $this->template;
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

		$latte = $this->getEngine();
		$latte->setLoader(
			new StringLoader(
				[
					'template' => '{extends layout}' . $this->getTemplate()->getSource(),
					'layout'   => $this->getLayout()
				]
			)
		);


		$this->source = $latte->renderToString('template', $this->getParams());
	}
}