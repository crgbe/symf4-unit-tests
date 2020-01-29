<?php


namespace App\Tests\Security;


use App\Entity\User;
use App\Security\GithubUserProvider;
use PHPUnit\Framework\TestCase;

class GitHubUserProviderTest extends TestCase
{
    private $client;
    private $serializer;
    private $responseInterface;
    private $streamedInterface;
    private $userData;

    public function setUp()
    {
        $this->userData = [
            'login' => 'user_login',
            'name' => 'username',
            'email' => 'user_email',
            'avatar_url' => 'user_avatar_url',
            'html_url' => 'user_html_url',
        ];

        $this->client = $this
            ->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->serializer =$this
            ->getMockBuilder('JMS\Serializer\Serializer')
            ->setMethods(['deserialize'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->responseInterface = $this
            ->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->getMock()
        ;

        $this->streamedInterface = $this
            ->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->getMock()
        ;
    }

    public function tearDown()
    {
        $this->client = null;
        $this->serializer = null;
        $this->responseInterface = null;
        $this->streamedInterface = null;
        $this->userData = null;
    }

    public function testLoadUserByUsernameReturningUser(){
        $this->client
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->responseInterface)
        ;

        $this->responseInterface
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->streamedInterface)
        ;

        $this->streamedInterface
            ->expects($this->once())
            ->method('getContents')
            ->willReturn('')
        ;

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($this->userData)
        ;

        $gitHubUserProvider = new GithubUserProvider($this->client, $this->serializer);
        $user = $gitHubUserProvider->loadUserByUsername('user-token-access');

        $expectedUser = new User(
            $this->userData['login'],
            $this->userData['name'],
            $this->userData['email'],
            $this->userData['avatar_url'],
            $this->userData['html_url']
        );

        $this->assertEquals($expectedUser, $user);
        $this->assertEquals('App\Entity\User', get_class($user));
//        $this->assertInstanceOf(User::class, $user);
    }

    public function testLoadUserByUsernameThrowingLogicalException(){
        $this->client
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->responseInterface)
        ;

        $this->responseInterface
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->streamedInterface)
        ;


        $this->streamedInterface
            ->expects($this->once())
            ->method('getContents')
            ->willReturn('')
        ;

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn([])
        ;


        $this->expectException('LogicException');


        $githubUserProvider = new GithubUserProvider($this->client, $this->serializer);
        $githubUserProvider->loadUserByUsername('user-access-token');
    }
}