<?php

namespace AppBundle\Controller;

use AppBundle\Exception\NoCookieForYou;
use AppBundle\Exception\NoCookiesLeft;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    public function homepageAction()
    {
        return $this->render('main/homepage.html.twig');
    }
}
