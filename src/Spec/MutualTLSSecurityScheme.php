<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class MutualTLSSecurityScheme extends SecurityScheme
{

	public function __construct()
	{
		parent::__construct(SecuritySchemeType::mutualTLS());
	}

}
