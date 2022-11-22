<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class MutualTLSSecurityScheme extends SecurityScheme
{

	use SpecObjectSupportsExtensions;

	public function __construct()
	{
		parent::__construct(SecuritySchemeType::mutualTLS());
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$this->addExtensionsToData($data);

		return $data;
	}

}
