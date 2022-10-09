<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class OAuthFlows
{

	public ?OAuthFlow $implicit;

	public ?OAuthFlow $password;

	public ?OAuthFlow $clientCredentials;

	public ?OAuthFlow $authorizationCode;

}
