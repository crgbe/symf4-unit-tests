<?php


namespace App\Tests\Security;


use App\Entity\User;
use App\Security\GithubUserProvider;
use PHPUnit\Framework\TestCase;

class GitHubUserProviderTest extends TestCase
{
    public function testLoadUserByUserNameReturningUser(){
        $userData = [
            'login' => 'user_login',
            'name' => 'username',
            'email' => 'user_email',
            'avatar_url' => 'user_avatar_url',
            'html_url' => 'user_html_url',
        ];

        $client = $this
            ->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $serialiser = $this
            ->getMockBuilder('JMS\Serializer\Serializer')
            ->setMethods(['deserialize'])
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

        $serialiser->method('deserialize')->willReturn($userData);

        $gitHubUserProvider = new GithubUserProvider($client, $serialiser);
        $user = $gitHubUserProvider->loadUserByUsername('user-token-access');

        $this->assertInstanceOf(User::class, $user);
    }
}