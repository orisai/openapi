<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\StringValue;

/**
 * @internal
 */
abstract class OAuthFlow implements SpecObject, MappedObject
{

	/**
	 * @var array<string, string>
	 *
	 * @ArrayOf(
	 *     item=@StringValue(),
	 *     key=@StringValue(),
	 * )
	 */
	protected array $scopes = [];

	/** @StringValue() */
	public ?string $refreshUrl = null;

	public function addScope(string $name, string $description): void
	{
		$this->scopes[$name] = $description;
	}

	/**
	 * @return array<string, string>
	 */
	public function getScopes(): array
	{
		return $this->scopes;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [
			'scopes' => $this->scopes,
		];

		if ($this->refreshUrl !== null) {
			$data['refreshUrl'] = $this->refreshUrl;
		}

		return $data;
	}

}
