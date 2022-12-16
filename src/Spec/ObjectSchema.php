<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
use Orisai\ObjectMapper\Attributes\Expect\ArrayEnumValue;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\BoolValue;
use Orisai\ObjectMapper\Attributes\Expect\IntValue;
use Orisai\ObjectMapper\Attributes\Expect\ListOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Attributes\Modifiers\FieldName;
use Orisai\OpenAPI\Utils\SpecUtils;

/**
 * @CreateWithoutConstructor()
 */
final class ObjectSchema extends Schema
{

	/** @ArrayEnumValue({"object"}) */
	private string $type;

	/**
	 * @var array<string, Schema|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(AllOfSchema::class),
	 *         @MappedObjectValue(AnyOfSchema::class),
	 *         @MappedObjectValue(ArraySchema::class),
	 *         @MappedObjectValue(BoolSchema::class),
	 *         @MappedObjectValue(FloatSchema::class),
	 *         @MappedObjectValue(IntSchema::class),
	 *         @MappedObjectValue(NotSchema::class),
	 *         @MappedObjectValue(NullSchema::class),
	 *         @MappedObjectValue(ObjectSchema::class),
	 *         @MappedObjectValue(OneOfSchema::class),
	 *         @MappedObjectValue(StringSchema::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue()
	 * )
	 */
	public array $properties = [];

	/**
	 * @var list<string>
	 *
	 * @FieldName("required")
	 * @ListOf(@StringValue())
	 */
	public array $requiredProperties = [];

	/**
	 * @var int<0, max>|null
	 *
	 * @IntValue(min=0)
	 */
	public ?int $minProperties = null;

	/**
	 * @var int<0, max>|null
	 *
	 * @IntValue(min=0)
	 */
	public ?int $maxProperties = null;

	/**
	 * @var Schema|Reference|bool
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
	 *     @MappedObjectValue(Reference::class),
	 *     @BoolValue(),
	 * })
	 */
	public $additionalProperties = true;

	/** @MappedObjectValue(Discriminator::class) */
	public ?Discriminator $discriminator = null;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'object';
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['type'] = $this->type;

		if ($this->properties !== []) {
			$data['properties'] = SpecUtils::specsToArray($this->properties);
		}

		if ($this->requiredProperties !== []) {
			$data['required'] = $this->requiredProperties;
		}

		if ($this->minProperties !== null) {
			$data['minProperties'] = $this->minProperties;
		}

		if ($this->maxProperties !== null) {
			$data['maxProperties'] = $this->maxProperties;
		}

		if ($this->additionalProperties !== true) {
			$data['additionalProperties'] = $this->additionalProperties;
		}

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toRaw();
		}

		return $data;
	}

}
