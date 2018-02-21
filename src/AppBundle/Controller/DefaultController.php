<?php

namespace AppBundle\Controller;

use AppBundle\Service\ZoomClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    public function index(ZoomClient $zoomClient)
    {
        $users = $zoomClient->getUsers();

        return $this->render('default/index.html.twig', [
            'users' => $users,
        ]);
    }

    public function readUser(ZoomClient $zommClient, $userId)
    {
        $user = $zommClient->getUser($userId);
        $user->meetings = $zommClient->getMeetings($user->id);

        return $this->render('default/user.html.twig', [
            'user' => $user,
        ]);
    }

    public function createMeeting(ZoomClient $zoomClient, string $userId)
    {
        $zoomClient->createMeeting($userId);

        return new RedirectResponse($this->generateUrl('user.read', ['userId' => $userId]));
    }
}
