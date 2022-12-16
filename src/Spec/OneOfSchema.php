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
final class OneOfSchema extends Schema
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
	private array $oneOf;

	/** @MappedObjectValue(Discriminator::class) */
	public ?Discriminator $discriminator = null;

	/**
	 * @param list<Schema|Reference> $oneOf
	 */
	public function __construct(array $oneOf)
	{
		//TODO - min 2
		parent::__construct();
		$this->oneOf = $oneOf;
	}

	/**
	 * @return list<Schema|Reference>
	 */
	public function getOneOf(): array
	{
		return $this->oneOf;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['oneOf'] = SpecUtils::specsToArray($this->oneOf);

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toRaw();
		}

		return $data;
	}

}
