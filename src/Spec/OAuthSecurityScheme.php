<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class OAuthSecurityScheme extends SecurityScheme
{

	public OAuthFlows $flows;

	public function __construct(OAuthFlows $flows)
	{
		parent::__construct(SecuritySchemeType::oAuth2());
		$this->flows = $flows;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['flows'] = $this->flows->toArray();

		return $data;
	}

}
