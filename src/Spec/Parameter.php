<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\ParameterStyle;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;
use function array_map;
use function implode;
use function in_array;

final class Parameter implements SpecObject
{

	use SpecObjectChecksExampleValue;
	use SpecObjectSupportsExtensions;

	/** @readonly */
	public string $name;

	/** @readonly */
	public ParameterIn $in;

	public ?string $description = null;

	private bool $required;

	public bool $deprecated = false;

	private bool $allowEmptyValue = false;

	private ParameterStyle $style;

	private bool $explode;

	private bool $allowReserved = false;

	public Schema $schema;

	/** @var mixed */
	private $example;

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, MediaType> */
	public array $content = [];

	public function __construct(string $name, ParameterIn $in)
	{
		$this->name = $name;
		$this->in = $in;
		$this->required = $in === ParameterIn::path();
		$this->style = $this->in->getDefaultStyle();
		$this->explode = $this->style->getDefaultExplode();
		$this->schema = new Schema();
		unset($this->example);
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
		$this->checkExampleValue($example);
		$this->example = $example;
	}

	public function hasExample(): bool
	{
		return (new ReflectionProperty($this, 'example'))->isInitialized($this);
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
		//TODO - pro type header a name "Accept", "Content-Type" a "Authorization" má být definice parametru ignorovaná
		//			- v openapi se definují přes media types a security
		//TODO - case unsensitive header
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

		//TODO - If style is used, and if behavior is n/a (cannot be serialized),
		//			the value of allowEmptyValue SHALL be ignored ??
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

		$schemaData = $this->schema->toArray();
		if ($schemaData !== []) {
			$data['schema'] = $schemaData;
		}

		//TODO - musí matchnout schema a encoding, pokud jsou nastavené
		//		- jak se nastavuje encoding?? (asi v media type)
		//TODO - pokud existuje examples, tak nesmí existovat example a naopak
		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		//TODO - musí matchnout encoding, pokud je nastavený (schema ne? jiný popisek než example)
		//		- jak se nastavuje encoding?? (asi v media type)
		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		//TODO - parametr musí obsahovat schema nebo content, ale ne obojí
		//TODO - alespoň jeden content type? zkontrolovat specifikaci
		//TODO - example a examples example musí následovat serializační strategii parametru
		if ($this->content !== []) {
			$data['content'] = SpecUtils::specsToArray($this->content);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
