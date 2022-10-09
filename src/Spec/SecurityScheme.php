<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class SecurityScheme
{

	public string $type;

	public ?string $description;

	public string $name;

	public string $in;

	public string $scheme;

	public ?string $bearerFormat;

	public OAuthFlows $flows;

	public string $openIdConnectUrl;

}
