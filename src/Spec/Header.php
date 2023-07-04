<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Callbacks\After;
use Orisai\ObjectMapper\Exception\ValueDoesNotMatch;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Processing\Value;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\BoolValue;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\ObjectMapper\Types\EnumType;
use Orisai\OpenAPI\Enum\HeaderStyle;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;
use function count;
use function is_string;

final class Header implements SpecObject, MappedObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectHasContent {
		SpecObjectHasContent::addContent as addContentTrait;
	}
	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public ?string $description = null;

	/** @BoolValue() */
	public bool $required = false;

	/** @BoolValue() */
	public bool $deprecated = false;

	/**
	 * @MixedValue()
	 * @After("afterStyle")
	 */
	private HeaderStyle $style;

	/** @BoolValue() */
	private bool $explode;

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

	public function __construct()
	{
		// TODO - nastavit hodnoty s object mapperem
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

	/**
	 * @param mixed $value
	 * @throws ValueDoesNotMatch
	 */
	protected static function afterStyle($value): HeaderStyle
	{
		if (is_string($value) && ($style = HeaderStyle::tryFrom($value)) !== null) {
			return $style;
		}

		$cases = [];
		foreach (HeaderStyle::cases() as $case) {
			$cases[] = $case->value;
		}

		throw ValueDoesNotMatch::create(new EnumType($cases), Value::of($value));
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
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
