<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class OpenIDConnectSecurityScheme extends SecurityScheme
{

	public string $openIdConnectUrl;

	public function __construct(string $openIdConnectUrl)
	{
		parent::__construct(SecuritySchemeType::openIdConnect());
		$this->openIdConnectUrl = $openIdConnectUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['openIdConnectUrl'] = $this->openIdConnectUrl;

		return $data;
	}

}
