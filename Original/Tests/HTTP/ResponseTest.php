<?php

use Stratum\Original\HTTP\Response;
use PHPUnit\Framework\TestCase;

Class ResponseTest extends TestCase
{
    public function test_correct_content_is_set_as_the_http_body()
    {
        (object) $response = $this->getMockBuilder(Response::class)
                                    ->setMethods([
                                        'contentType',
                                        'body'
                                        ])
                                    ->setConstructorArgs([new \Symfony\Component\HttpFoundation\Response])
                                    ->getMock();

        $response->expects($this->any())
                 ->method('contentType')
                 ->willReturn('text/plain');

        $response->expects($this->any())
                ->method('body')
                ->willReturn('The contents of the response!');

        ob_start();

        $response->send();

        (string) $responseContent = ob_get_contents();
        
        ob_end_clean();

        $this->assertEquals('The contents of the response!', $responseContent);
    }
}