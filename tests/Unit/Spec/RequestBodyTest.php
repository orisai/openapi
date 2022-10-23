<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\RequestBody;
use PHPUnit\Framework\TestCase;

final class RequestBodyTest extends TestCase
{

	public function test(): void
	{
		$rb1 = new RequestBody([]);

		self::assertSame(
			[
				'content' => [],
			],
			$rb1->toArray(),
		);

		$rb2mt1 = new MediaType();
		$rb2mt1->example = 'json';

		$rb2mt2 = new MediaType();
		$rb2mt2->example = 'xml';

		$rb2 = new RequestBody([
			'application/json' => $rb2mt1,
			'application/xml' => $rb2mt2,
		]);
		$rb2->description = 'description';
		$rb2->required = true;
		$rb2->addExtension('x-a', null);

		self::assertSame(
			[
				'content' => [
					'application/json' => $rb2mt1->toArray(),
					'application/xml' => $rb2mt2->toArray(),
				],
				'description' => 'description',
				'required' => true,
				'x-a' => null,
			],
			$rb2->toArray(),
		);
	}

}
