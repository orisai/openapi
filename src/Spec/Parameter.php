<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
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

	use SupportsSpecExtensions;

	/** @readonly */
	public string $name;

	/** @readonly */
	public ParameterIn $in;

	public ?string $description = null;

	private bool $required;

	public bool $deprecated = false;

	private bool $allowEmptyValue = false;

	private ParameterStyle $style;

	public bool $explode = false;

	private bool $allowReserved = false;

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
		$this->required = $in === ParameterIn::path();
		$this->style = $this->in->getDefaultStyle();
		//TODO - zapisovat default? jiné defaults do output nezapisuju
		//TODO - když nastavím jiný style, má se změnit explode?
		//		změna na form by měla nastavit explode na true, pokud není nastaven explicitně
		//		- možná společný setter pro obě hodnoty, kdy explode = null bude auto?
		$this->explode = $this->style === ParameterStyle::form();
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

	public function setStyle(ParameterStyle $style): void
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
	}

	public function getStyle(): ParameterStyle
	{
		return $this->style;
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

	public function toArray(): array
	{
		//TODO - pro type header a name "Accept", "Content-Type" a "Authorization" má být definice parametru ignorovaná??
		//			- co to znamená?
		//TODO - ve specifikaci je, že jsou jména case sensitive, ale hlavičky jsou case unsensitive
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

		//TODO - musí matchnout schema a encoding, pokud jsou nastavené
		//		- jak se nastavuje encoding?? (asi v media type)
		//TODO - pokud existuje examples, tak nesmí existovat example a naopak
		$valueRef = new ReflectionProperty($this, 'example');
		if ($valueRef->isInitialized($this)) {
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
