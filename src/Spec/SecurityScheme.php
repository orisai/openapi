<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class SecurityScheme implements SpecObject
{

	public string $type;

	public ?string $description;

	public string $name;

	public string $in;

	public string $scheme;

	public ?string $bearerFormat;

	public OAuthFlows $flows;

	public string $openIdConnectUrl;

	public function toArray(): array
	{
		return [
			'type' => $this->type,
			'description' => $this->description,
			'name' => $this->name,
			'in' => $this->in,
			'scheme' => $this->scheme,
			'bearerFormat' => $this->bearerFormat,
			'flows' => $this->flows->toArray(),
			'openIdConnectUrl' => $this->openIdConnectUrl,
		];
	}

}
