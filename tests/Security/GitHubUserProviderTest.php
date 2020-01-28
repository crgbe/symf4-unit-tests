<?php


namespace App\Tests\Security;


use App\Security\GithubUserProvider;
use PHPUnit\Framework\TestCase;

class GitHubUserProviderTest extends TestCase
{
    public function testLoadUserByUserNameReturningUser(){
        $client = $this
            ->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $serialiser = $this
            ->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        //Returned response from get method of GuzzleHttp\Client class
        $response = $this
            ->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->getMock()
        ;

        $client->method('get')->willReturn($response);

        $streamedReponse = $this
            ->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->getMock()
        ;

        $response->method('getBody')->willReturn($streamedReponse);
        $streamedReponse->method('getContents')->willReturn('');

        $gitHubUserProvider = new GithubUserProvider($client, $serialiser);
        $user = $gitHubUserProvider->loadUserByUsername('user-token-access');
    }
}