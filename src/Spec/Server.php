<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Utils\SpecUtils;

/**
 * @CreateWithoutConstructor()
 */
final class Server implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	private string $url;

	/** @StringValue() */
	public ?string $description = null;

	/**
	 * @var array<string, ServerVariable>
	 *
	 * @ArrayOf(
	 *     item=@MappedObjectValue(ServerVariable::class),
	 *     key=@StringValue(),
	 * )
	 */
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
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
