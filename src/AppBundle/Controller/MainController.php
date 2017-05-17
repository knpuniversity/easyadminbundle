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

    /**
     * @Route("/crazy-dave")
     */
    public function cookiesAction()
    {
        try {
            if (random_int(0, 1)) {
                throw new NoCookieForYou();
            }

            throw new NoCookiesLeft();
        } catch (NoCookieForYou | NoCookiesLeft $e) {
            $whisper = sprintf('Crazy Dave whispered "%s"', $e->getMessage());
        }

        return new Response('<html><body>'.$whisper.'</body></html>');
    }
}
