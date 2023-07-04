<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ArrayEnumValue;
use Orisai\ObjectMapper\Rules\BoolValue;
use Orisai\ObjectMapper\Rules\IntValue;
use Orisai\ObjectMapper\Rules\MappedObjectValue;

/**
 * @CreateWithoutConstructor()
 */
final class ArraySchema extends Schema
{

	/** @ArrayEnumValue({"array"}) */
	private string $type;

	/**
	 * @var Schema|Reference
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
	public object $items;

	/**
	 * @var int<0, max>|null
	 *
	 * @IntValue(min=0)
	 */
	public ?int $minItems = null;

	/**
	 * @var int<0, max>|null
	 *
	 * @IntValue(min=0)
	 */
	public ?int $maxItems = null;

	/** @BoolValue() */
	public bool $uniqueItems = false;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'array';
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['type'] = $this->type;

		if ($this->minItems !== null) {
			$data['minItems'] = $this->minItems;
		}

		if ($this->maxItems !== null) {
			$data['maxItems'] = $this->maxItems;
		}

		if ($this->uniqueItems !== false) {
			$data['uniqueItems'] = $this->uniqueItems;
		}

		return $data;
	}

}
