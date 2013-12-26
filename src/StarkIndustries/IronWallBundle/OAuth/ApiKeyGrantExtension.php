<?php

namespace StarkIndustries\IronWallBundle\OAuth;

use Doctrine\Common\Persistence\ObjectRepository;
use FOS\OAuthServerBundle\Storage\GrantExtensionInterface;
use OAuth2\Model\IOAuth2Client;

/**
 * Make sure there is at least one user with given api_key
 */
class ApiKeyGrantExtension implements GrantExtensionInterface
{

    private $userRepository;

    public function __construct(ObjectRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * You have three possibilities to return from this method.
     * return false means that request was not authorized at all
     * return true means that you have authorized request, but there's no user assigned to it
     * return $user means that request is authrorized and $user is assigned to the session
     */
    public function checkGrantExtension(IOAuth2Client $client, array $inputData, array $authHeaders)
    {
        $user = $this->userRepository->findOneByApiKey($inputData['api_key']);
        if ($user) {
            return array(
                'data' => $user
            );
        }

        return false;
    }
}
