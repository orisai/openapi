<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\MixedValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

/**
 * @CreateWithoutConstructor()
 */
final class MediaType implements SpecObject, MappedObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	/**
	 * @var Schema|Reference|null
	 *
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
	 *     @MappedObjectValue(Reference::class),
	 * })
	 */
	public ?object $schema = null;

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

	/**
	 * @var array<string, Encoding>
	 *
	 * @ArrayOf(
	 *     item=@MappedObjectValue(Encoding::class),
	 *     key=@StringValue(),
	 * )
	 */
	private array $encoding = [];

	public function __construct()
	{
		//TODO - call with object mapper
		unset($this->example);
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'MediaType example');
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
				->withContext('Getting the MediaType example.')
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

	public function addEncoding(string $key, Encoding $encoding): void
	{
		$this->encoding[$key] = $encoding;
	}

	/**
	 * @return array<string, Encoding>
	 */
	public function getEncodings(): array
	{
		return $this->encoding;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		if ($this->schema !== null) {
			$data['schema'] = $this->schema->toRaw();
		}

		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->encoding !== []) {
			$data['encoding'] = SpecUtils::specsToArray($this->encoding);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
