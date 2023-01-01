<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Callbacks\Before;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\ListOf;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use stdClass;
use function is_array;

/**
 * @CreateWithoutConstructor()
 * @Before("beforeClass")
 */
final class SecurityRequirement implements SpecObject, MappedObject
{

	/**
	 * @var array<string, list<string>>
	 *
	 * @ArrayOf(
	 *     item=@ListOf(@StringValue()),
	 *     key=@StringValue(),
	 * )
	 */
	private array $nameAndScopePairs = [];

	/**
	 * @param array<string, list<string>> $nameAndScopePairs
	 */
	public function __construct(array $nameAndScopePairs)
	{
		$this->nameAndScopePairs = $nameAndScopePairs;
	}

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	protected function beforeClass($data)
	{
		if (!is_array($data)) {
			return $data;
		}

		// TODO - kontrolovat, že ve třídě není klíč nameAndScopePairs
		return [
			'nameAndScopePairs' => $data,
		];
	}

	/**
	 * @return array<string, list<string>>
	 */
	public function getNameAndScopePairs(): array
	{
		return $this->nameAndScopePairs;
	}

	/**
	 * @return mixed
	 */
	public function toRaw()
	{
		return $this->nameAndScopePairs === []
			? new stdClass()
			: $this->nameAndScopePairs;
	}

}
