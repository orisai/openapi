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
final class AnyOfSchema extends Schema
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
	private array $anyOf;

	/** @MappedObjectValue(Discriminator::class) */
	public ?Discriminator $discriminator = null;

	/**
	 * @param list<Schema|Reference> $anyOf
	 */
	public function __construct(array $anyOf)
	{
		//TODO - min 2
		parent::__construct();
		$this->anyOf = $anyOf;
	}

	/**
	 * @return list<Schema|Reference>
	 */
	public function getAnyOf(): array
	{
		return $this->anyOf;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['anyOf'] = SpecUtils::specsToArray($this->anyOf);

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toArray();
		}

		return $data;
	}

}
