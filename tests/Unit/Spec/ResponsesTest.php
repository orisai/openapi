<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Response;
use Orisai\OpenAPI\Spec\Responses;
use PHPUnit\Framework\TestCase;

final class ResponsesTest extends TestCase
{

	public function test(): void
	{
		$rs1 = new Responses();
		self::assertSame([], $rs1->toArray());

		$rs2 = new Responses();

		$rs1rsd = new Response('default');
		$rs2->addResponse('default', $rs1rsd);

		$rs2r1 = new Response('deleted');
		$rs2->addResponse(204, $rs2r1);

		$rs2r2 = new Response('not found');
		$rs2->addResponse(404, $rs2r2);

		$rs2->addExtension('x-a', null);

		self::assertSame(
			[
				204 => $rs2r1->toArray(),
				404 => $rs2r2->toArray(),
				'default' => $rs1rsd->toArray(),
				'x-a' => null,
			],
			$rs2->toArray(),
		);
	}

}
