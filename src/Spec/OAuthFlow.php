<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

/**
 * @internal
 */
abstract class OAuthFlow implements SpecObject
{

	/** @var array<string, string> */
	public array $scopes;

	public ?string $refreshUrl = null;

	/**
	 * @param array<string, string> $scopes
	 */
	public function __construct(array $scopes)
	{
		$this->scopes = $scopes;
	}

	public function toArray(): array
	{
		//TODO - this + overrides
		$data = [
			'scopes' => $this->scopes,
		];

		if ($this->refreshUrl !== null) {
			$data['refreshUrl'] = $this->refreshUrl;
		}

		return $data;
	}

}
