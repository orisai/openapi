<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

/**
 * @internal
 */
abstract class OAuthFlow implements SpecObject
{

	/** @var array<string, string> */
	private array $scopes = [];

	public ?string $refreshUrl = null;

	public function addScope(string $name, string $description): void
	{
		$this->scopes[$name] = $description;
	}

	public function toArray(): array
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
