<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class SecurityRequirement implements SpecObject
{

	/** @var array<string, list<string>> */
	public array $requirements = [];

	public function toArray(): array
	{
		//TODO - prázdné requirements se musí vracet jako stdClass
		//		- aby se zakódovaly v jsonu jako objekt a ne pole
		//		- toArray() -> toData()? toRaw()?
		//TODO - název musí odpovídat SecurityScheme v rámci Components
		//TODO - pokud obsahuje více schemat, tak musí odpovídat všechny
		//TODO - pouze jeden SecurityRequirement list (na OpenAPI nebo Operation) musí odpovídat aby uživatel byl autorizován
		//		- přetěžuje operace OpenAPI?
		return $this->requirements;
	}

}
