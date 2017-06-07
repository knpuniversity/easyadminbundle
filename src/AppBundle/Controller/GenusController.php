<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use AppBundle\Entity\GenusScientist;
use AppBundle\Service\MarkdownTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    /**
     * @Route("/genus/new")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $subFamily = $em->getRepository('AppBundle:SubFamily')
            ->findAny();

        $genus = new Genus();
        $genus->setName('Octopus'.rand(1, 10000));
        $genus->setSubFamily($subFamily);
        $genus->setSpeciesCount(rand(100, 99999));
        $genus->setFirstDiscoveredAt(new \DateTime('50 years'));

        $genusNote = new GenusNote();
        $genusNote->setUsername('AquaWeaver');
        $genusNote->setUserAvatarFilename('ryan.jpeg');
        $genusNote->setNote('I counted 8 legs... as they wrapped around me');
        $genusNote->setCreatedAt(new \DateTime('-1 month'));
        $genusNote->setGenus($genus);

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['email' => 'aquanaut1@example.org']);

        $genusScientist = new GenusScientist();
        $genusScientist->setGenus($genus);
        $genusScientist->setUser($user);
        $genusScientist->setYearsStudied(10);
        $em->persist($genusScientist);

        $em->persist($genus);
        $em->persist($genusNote);
        $em->flush();

        return new Response(sprintf(
            '<html><body>Genus created! <a href="%s">%s</a></body></html>',
            $this->generateUrl('genus_show', ['slug' => $genus->getSlug()]),
            $genus->getName()
        ));
    }

    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAllPublishedOrderedByRecentlyActive();

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/{slug}", name="genus_show")
     */
    public function showAction(Genus $genus, MarkdownTransformer $markdownTransformer)
    {
        $em = $this->getDoctrine()->getManager();

        $funFact = $markdownTransformer->parse($genus->getFunFact());

        $this->get('logger')
            ->info('Showing genus: '.$genus->getName());

        $recentNotes = $em->getRepository('AppBundle:GenusNote')
            ->findAllRecentNotesForGenus($genus);

        $foodArray = ['shrimp', 'clams', 'lobsters', 'shark'];
        $foodObject = new \ArrayObject($foodArray);
        $food = $foodObject->getIterator();

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'funFact' => $funFact,
            'recentNoteCount' => count($recentNotes),
            'recentlyAte' => $genus->feed($food),
        ));
    }

    /**
     * @Route("/genus/{slug}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [];

        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => $note->getUserAvatarUri(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')
            ];
        }

        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/genus/{genusId}/scientists/{userId}", name="genus_scientists_remove")
     * @Method("DELETE")
     */
    public function removeGenusScientistAction($genusId, $userId)
    {
        $em = $this->getDoctrine()->getManager();

        $genusScientist = $em->getRepository('AppBundle:GenusScientist')
            ->findOneBy([
                'user' => $userId,
                'genus' => $genusId
            ]);

        $em->remove($genusScientist);
        $em->flush();

        return new Response(null, 204);
    }
}
