<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    public function articlesAction()
    {
        $articles = array('article1', 'article2', 'article3');
        return new JsonResponse($articles);
    }

    public function userAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user) {
            return new JsonResponse(array(
                'id' => $user->getId(),
                'username' => $user->getUsername()
            ));
        }

        return new JsonResponse(array(
            'message' => 'User is not identified'
        ));

    }
}
