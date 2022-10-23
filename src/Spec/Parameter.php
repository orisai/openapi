<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class Parameter implements SpecObject
{

	use SupportsSpecExtensions;

	public string $name;

	public ParameterIn $in;

	public ?string $description = null;

	public bool $required = false;

	public bool $deprecated = false;

	public bool $allowEmptyValue = false;

	public ?string $style = null;

	public bool $explode = false;

	public bool $allowReserved = false;

	public Schema $schema;

	/** @var mixed */
	public $example;

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, MediaType> */
	public array $content = [];

	public function __construct(string $name, ParameterIn $in)
	{
		$this->name = $name;
		$this->in = $in;
		$this->schema = new Schema();
		unset($this->example);

		if ($in->name === ParameterIn::path()->name) {
			$this->required = true;
		}
	}

	public function toArray(): array
	{
		$data = [
			'name' => $this->name,
			'in' => $this->in->name,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->required) {
			$data['required'] = $this->required;
		}

		if ($this->deprecated) {
			$data['deprecated'] = $this->deprecated;
		}

		if ($this->allowEmptyValue) {
			$data['allowEmptyValue'] = $this->allowEmptyValue;
		}

		if ($this->style !== null) {
			$data['style'] = $this->style;
		}

		if ($this->explode) {
			$data['explode'] = $this->explode;
		}

		if ($this->allowReserved) {
			$data['allowReserved'] = $this->allowReserved;
		}

		$schemaData = $this->schema->toArray();
		if ($schemaData !== []) {
			$data['schema'] = $schemaData;
		}

		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
			$data['example'] = $this->example;
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->content !== []) {
			$data['content'] = SpecUtils::specsToArray($this->content);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
