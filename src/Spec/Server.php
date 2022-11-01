<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Server implements SpecObject
{

	use SupportsSpecExtensions;

	private string $url;

	public ?string $description = null;

	/** @var array<string, ServerVariable> */
	private array $variables = [];

	public function __construct(string $url)
	{
		$this->url = $url;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function addVariable(string $name, ServerVariable $variable): void
	{
		$this->variables[$name] = $variable;
	}

	/**
	 * @return array<string, ServerVariable>
	 */
	public function getVariables(): array
	{
		return $this->variables;
	}

	public function toArray(): array
	{
		//TODO - validovat že všechny example/.com/{param} jsou definované ve variables a všechny variables se používají
		//		- ve specifikaci není uvedeno
		$data = [
			'url' => $this->url,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->variables !== []) {
			$data['variables'] = SpecUtils::specsToArray($this->variables);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
