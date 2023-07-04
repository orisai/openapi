<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ListOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\OpenAPI\Utils\SpecUtils;

final class AllOfSchema extends Schema
{

	/**
	 * @var list<Schema|Reference>
	 *
	 * @ListOf(
	 *     @AnyOf({
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
	 *     })
	 * )
	 */
	private array $allOf;

	/**
	 * @param list<Schema|Reference> $allOf
	 */
	public function __construct(array $allOf)
	{
		//TODO - min 2
		parent::__construct();
		$this->allOf = $allOf;
	}

	/**
	 * @return list<Schema|Reference>
	 */
	public function getAllOf(): array
	{
		return $this->allOf;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['allOf'] = SpecUtils::specsToArray($this->allOf);

		return $data;
	}

}
