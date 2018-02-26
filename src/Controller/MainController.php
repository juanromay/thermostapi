<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainController extends Controller{

    /**
     * @Route("/")
     */
    public function indexAction(){
        $number = mt_rand(0, 100);

        return $this->render('index.html.twig', array(
            'number' => $number,
        ));
    }

    /**
     * @Route("/admin")
     */
    public function adminAction(AuthorizationCheckerInterface $authChecker){
        if (false === $authChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        return $this->render('admin.html.twig');
    }
}

