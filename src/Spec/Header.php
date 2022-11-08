<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\HeaderStyle;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class Header implements SpecObject
{

	use SpecObjectChecksExampleValue;

	public ?string $description = null;

	public bool $required = false;

	public bool $deprecated = false;

	public bool $allowEmptyValue = false;

	private HeaderStyle $style;

	public bool $explode = false;

	public bool $allowReserved = false;

	public Schema $schema;

	/** @var mixed */
	private $example;

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, MediaType> */
	public array $content = [];

	public function __construct()
	{
		$this->style = HeaderStyle::simple();
		$this->schema = new Schema();
		unset($this->example);
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkExampleValue($example);
		$this->example = $example;
	}

	public function hasExample(): bool
	{
		return (new ReflectionProperty($this, 'example'))->isInitialized($this);
	}

	public function getStyle(): HeaderStyle
	{
		return $this->style;
	}

	/**
	 * @return mixed
	 */
	public function getExample()
	{
		if (!$this->hasExample()) {
			$message = Message::create()
				->withContext('Getting the example value.')
				->withProblem('Example value is not set and so cannot be get.')
				->withSolution('Check with hasExample().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->example;
	}

	public function toArray(): array
	{
		//TODO - stejná logika jako u Parameter + omezení, která existují pro Header (např. pro style)
		$data = [];

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

		/**
		 * Style is always simple
		 *
		 * @codeCoverageIgnore
		 * @infection-ignore-all
		 */
		if ($this->style !== HeaderStyle::simple()) {
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

		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->content !== []) {
			$data['content'] = SpecUtils::specsToArray($this->content);
		}

		return $data;
	}

}
