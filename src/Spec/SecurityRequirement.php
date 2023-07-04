<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Callbacks\Before;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\ListOf;
use Orisai\ObjectMapper\Rules\StringValue;
use stdClass;
use function is_array;

/**
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
