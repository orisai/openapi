<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\HeaderStyle;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;
use function count;

final class Header implements SpecObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectHasContent {
		SpecObjectHasContent::addContent as addContentTrait;
	}
	use SpecObjectSupportsExtensions;

	public ?string $description = null;

	public bool $required = false;

	public bool $deprecated = false;

	private HeaderStyle $style;

	private bool $explode;

	public ?Schema $schema = null;

	/** @var mixed */
	private $example;

	/** @var array<string, Example|Reference> */
	private array $examples = [];

	public function __construct()
	{
		$this->style = HeaderStyle::simple();
		$this->explode = $this->style->getDefaultExplode();
		unset($this->example);
	}

	public function setRequired(bool $required = true): void
	{
		$this->required = $required;
	}

	public function setStyle(HeaderStyle $style, ?bool $explode = null): void
	{
		$this->style = $style;
		$this->explode = $explode ?? $style->getDefaultExplode();
	}

	public function getStyle(): HeaderStyle
	{
		return $this->style;
	}

	public function getExplode(): bool
	{
		return $this->explode;
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'Header example');
		$this->example = $example;
	}

	public function hasExample(): bool
	{
		$property = new ReflectionProperty($this, 'example');
		$property->setAccessible(true);

		return $property->isInitialized($this);
	}

	/**
	 * @return mixed
	 */
	public function getExample()
	{
		if (!$this->hasExample()) {
			$message = Message::create()
				->withContext('Getting the Header example.')
				->withProblem('Example is not set and so cannot be get.')
				->withSolution('Check with hasExample().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->example;
	}

	/**
	 * @param Example|Reference $example
	 */
	public function addExample(string $key, $example): void
	{
		$this->examples[$key] = $example;
	}

	/**
	 * @return array<string, Example|Reference>
	 */
	public function getExamples(): array
	{
		return $this->examples;
	}

	/**
	 * @param non-empty-string $name
	 */
	public function addContent(string $name, MediaType $mediaType): void
	{
		if (!isset($this->content[$name]) && count($this->content) > 0) {
			$message = Message::create()
				->withContext("Adding content with media type '$name' to a Header.")
				->withProblem('Header content can contain only one entry, given one is second.');

			throw InvalidState::create()
				->withMessage($message);
		}

		$this->addContentTrait($name, $mediaType);
	}

	public function toArray(): array
	{
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

		// Style is always simple
		// if ($this->style !== HeaderStyle::simple()) {
		// 	$data['style'] = $this->style->value;
		// }

		if ($this->explode) {
			$data['explode'] = $this->explode;
		}

		if ($this->schema !== null) {
			$data['schema'] = $this->schema->toArray();
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

		$this->addExtensionsToData($data);

		return $data;
	}

}
