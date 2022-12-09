<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\ParameterStyle;
use Orisai\OpenAPI\Utils\Headers;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;
use function array_map;
use function count;
use function implode;
use function in_array;
use function preg_match;

final class Parameter implements SpecObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectHasContent {
		SpecObjectHasContent::addContent as addContentTrait;
	}
	use SpecObjectSupportsExtensions;

	/** @readonly */
	private string $name;

	/** @readonly */
	private ParameterIn $in;

	public ?string $description = null;

	private bool $required;

	public bool $deprecated = false;

	private bool $allowEmptyValue = false;

	private ParameterStyle $style;

	private bool $explode;

	private bool $allowReserved = false;

	public ?Schema $schema = null;

	/** @var mixed */
	private $example;

	/** @var array<string, Example|Reference> */
	private array $examples = [];

	public function __construct(string $name, ParameterIn $in)
	{
		$this->name = $this->processName($name, $in);
		$this->in = $in;
		$this->required = $in === ParameterIn::path();
		$this->style = $this->in->getDefaultStyle();
		$this->explode = $this->style->getDefaultExplode();
		$this->schema = null;
		unset($this->example);
	}

	private function processName(string $name, ParameterIn $in): string
	{
		if ($in === ParameterIn::path()) {
			return $this->processPathName($name);
		}

		if ($in === ParameterIn::header()) {
			return $this->processHeaderName($name);
		}

		return $name;
	}

	private function processPathName(string $name): string
	{
		if (preg_match('#[{}/]#', $name) === 1) {
			$message = Message::create()
				->withContext("Creating Parameter with name '$name'.")
				->withProblem("Characters '{}/' are not allowed in Parameter in=path.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		return $name;
	}

	private function processHeaderName(string $name): string
	{
		if (!Headers::isNameValid($name)) {
			$message = Message::create()
				->withContext("Creating a Parameter with name '$name' in 'header'.")
				->withProblem('Name is not valid HTTP header name.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc7230',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		return Headers::formatName($name);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getIn(): ParameterIn
	{
		return $this->in;
	}

	public function setRequired(bool $required = true): void
	{
		if (!$required && $this->in === ParameterIn::path()) {
			$message = Message::create()
				->withContext('Setting Parameter required to false.')
				->withProblem('Parameter is in path and as such must be required.');

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->required = $required;
	}

	public function setStyle(ParameterStyle $style, ?bool $explode = null): void
	{
		$allowed = $this->in->getAllowedStyles();

		if (!in_array($style, $allowed, true)) {
			$allowedInline = implode(
				"', '",
				array_map(static fn (ParameterStyle $style): string => $style->value, $allowed),
			);

			$message = Message::create()
				->withContext("Setting Parameter style to '$style->value'.")
				->withProblem("Allowed styles for parameter in '{$this->in->value}' are '$allowedInline'.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->style = $style;
		$this->explode = $explode ?? $style->getDefaultExplode();
	}

	public function getStyle(): ParameterStyle
	{
		return $this->style;
	}

	public function getExplode(): bool
	{
		return $this->explode;
	}

	public function setAllowReserved(bool $allow = true): void
	{
		if ($this->in !== ParameterIn::query()) {
			$message = Message::create()
				->withContext('Setting Parameter allowReserved.')
				->withProblem('Parameter is not in query and only query parameters can have allowReserved.');

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->allowReserved = $allow;
	}

	public function setAllowEmptyValue(bool $allow = true): void
	{
		if ($this->in !== ParameterIn::query()) {
			$message = Message::create()
				->withContext('Setting Parameter allowEmptyValue.')
				->withProblem('Parameter is not in query and only query parameters can have allowEmptyValue.');

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->allowEmptyValue = $allow;
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'Parameter example');
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
				->withContext('Getting the Parameter example.')
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
				->withContext("Adding content with media type '$name' to a Parameter.")
				->withProblem('Parameter content can contain only one entry, given one is second.');

			throw InvalidState::create()
				->withMessage($message);
		}

		$this->addContentTrait($name, $mediaType);
	}

	public function toArray(): array
	{
		$data = [
			'name' => $this->name,
			'in' => $this->in->value,
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

		if ($this->style !== $this->in->getDefaultStyle()) {
			$data['style'] = $this->style->value;
		}

		if ($this->explode !== $this->style->getDefaultExplode()) {
			$data['explode'] = $this->explode;
		}

		if ($this->allowReserved) {
			$data['allowReserved'] = $this->allowReserved;
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
