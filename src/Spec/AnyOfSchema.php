<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ListOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['anyOf'] = SpecUtils::specsToArray($this->anyOf);

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toRaw();
		}

		return $data;
	}

}
