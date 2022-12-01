<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
use Orisai\ObjectMapper\Attributes\Expect\ListOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\OpenAPI\Utils\SpecUtils;

/**
 * @CreateWithoutConstructor()
 */
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

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['allOf'] = SpecUtils::specsToArray($this->allOf);

		return $data;
	}

}
