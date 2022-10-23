<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class HttpSecurityScheme extends SecurityScheme
{

	use SupportsSpecExtensions;

	public string $scheme;

	public ?string $bearerFormat = null;

	public function __construct(string $scheme)
	{
		parent::__construct(SecuritySchemeType::http());
		$this->scheme = $scheme;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['scheme'] = $this->scheme;

		if ($this->bearerFormat !== null) {
			$data['bearerFormat'] = $this->bearerFormat;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
