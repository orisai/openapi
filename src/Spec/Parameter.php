<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Callbacks\After;
use Orisai\ObjectMapper\Exception\ValueDoesNotMatch;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\BoolValue;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\ObjectMapper\Types\EnumType;
use Orisai\ObjectMapper\Processing\Value;
use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\ParameterStyle;
use Orisai\OpenAPI\Utils\Headers;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;
use function array_map;
use function count;
use function implode;
use function in_array;
use function is_string;
use function preg_match;

/**
 * @CreateWithoutConstructor()
 */
final class Parameter implements SpecObject, MappedObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectHasContent {
		SpecObjectHasContent::addContent as addContentTrait;
	}
	use SpecObjectSupportsExtensions;

	/**
	 * @readonly
	 *
	 * @StringValue()
	 */
	private string $name;

	/**
	 * @readonly
	 *
	 * @MixedValue()
	 * @After("afterIn")
	 */
	private ParameterIn $in;

	/** @StringValue() */
	public ?string $description = null;

	/** @BoolValue() */
	private bool $required;

	/** @BoolValue() */
	public bool $deprecated = false;

	/** @BoolValue() */
	private bool $allowEmptyValue = false;

	/**
	 * @MixedValue()
	 * @After("afterStyle")
	 */
	private ParameterStyle $style;

	/** @BoolValue() */
	private bool $explode;

	/** @BoolValue() */
	private bool $allowReserved = false;

	/**
	 * @AnyOf({
	 *     @MappedObjectValue(AllOfSchema::class),
	 *     @MappedObjectValue(AnyOfSchema::class),
	 *     @MappedObjectValue(ArraySchema::class),
	 *     @MappedObjectValue(BoolSchema::class),
	 *     @MappedObjectValue(FloatSchema::class),
	 *     @MappedObjectValue(IntSchema::class),
	 *     @MappedObjectValue(NotSchema::class),
	 *     @MappedObjectValue(NullSchema::class),
	 *     @MappedObjectValue(ObjectSchema::class),
	 *     @MappedObjectValue(OneOfSchema::class),
	 *     @MappedObjectValue(StringSchema::class),
	 * })
	 */
	public ?Schema $schema = null;

	/**
	 * @var mixed
	 *
	 * @MixedValue()
	 * @todo - tenhle a ostatní $example a Example $value
	 *       - hodnota je mixed a potřebuju rozlišit null a unitialized
	 */
	private $example;

	/**
	 * @var array<string, Example|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Example::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 */
	private array $examples = [];

	public function __construct(string $name, ParameterIn $in)
	{
		//TODO - object mapper
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

	/**
	 * @param mixed $value
	 * @throws ValueDoesNotMatch
	 */
	protected static function afterIn($value): ParameterIn
	{
		if (is_string($value) && ($in = ParameterIn::tryFrom($value)) !== null) {
			return $in;
		}

		$cases = [];
		foreach (ParameterIn::cases() as $case) {
			$cases[] = $case->value;
		}

		throw ValueDoesNotMatch::create(new EnumType($cases), Value::of($value));
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

	/**
	 * @param mixed $value
	 * @throws ValueDoesNotMatch
	 */
	protected static function afterStyle($value): ParameterStyle
	{
		if (is_string($value) && ($style = ParameterStyle::tryFrom($value)) !== null) {
			return $style;
		}

		$cases = [];
		foreach (ParameterStyle::cases() as $case) {
			$cases[] = $case->value;
		}

		throw ValueDoesNotMatch::create(new EnumType($cases), Value::of($value));
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
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
			$data['schema'] = $this->schema->toRaw();
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
