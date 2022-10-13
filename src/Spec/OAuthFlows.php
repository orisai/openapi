<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class OAuthFlows implements SpecObject
{

	public ?ImplicitOAuthFlow $implicit = null;

	public ?PasswordOAuthFlow $password = null;

	public ?ClientCredentialsOAuthFlow $clientCredentials = null;

	public ?AuthorizationCodeOAuthFlow $authorizationCode = null;

	public function toArray(): array
	{
		$data = [];

		if ($this->implicit !== null) {
			$data['implicit'] = $this->implicit->toArray();
		}

		if ($this->password !== null) {
			$data['password'] = $this->password->toArray();
		}

		if ($this->clientCredentials !== null) {
			$data['clientCredentials'] = $this->clientCredentials->toArray();
		}

		if ($this->authorizationCode !== null) {
			$data['authorizationCode'] = $this->authorizationCode->toArray();
		}

		return $data;
	}

}
