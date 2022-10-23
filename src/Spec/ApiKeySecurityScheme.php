<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeIn;
use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class ApiKeySecurityScheme extends SecurityScheme
{

	use SupportsSpecExtensions;

	public string $name;

	public SecuritySchemeIn $in;

	public function __construct(string $name, SecuritySchemeIn $in)
	{
		parent::__construct(SecuritySchemeType::apiKey());
		$this->name = $name;
		$this->in = $in;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['name'] = $this->name;
		$data['in'] = $this->in->value;
		$this->addExtensionsToData($data);

		return $data;
	}

}
