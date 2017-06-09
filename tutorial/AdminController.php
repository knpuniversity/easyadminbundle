<?php

namespace AppBundle\Controller\EasyAdmin;

use AppBundle\Entity\Genus;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BaseAdminController
{
    public function exportAction()
    {
        throw new \RuntimeException('Action for exporting an entity not defined');
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        $genusRepository = $em->getRepository(Genus::class);

        return $this->render('easy_admin/dashboard.html.twig', [
            'genusCount' => $genusRepository->getGenusCount(),
            'publishedGenusCount' => $genusRepository->getPublishedGenusCount(),
            'randomGenus' => $genusRepository->findRandomGenus()
        ]);
    }
}
