<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityBlockeeType;
use App\Form\ActivityType;
use App\Service\LicencePlateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'main')]
    public function index(): Response
    {

        if ($this->getUser()) {
            return $this->render('main_page.html.twig');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/i_was_blocked', name: 'i_was_blocked', methods: ['GET', 'POST'])]
    public function new_blockee(Request $request): Response
    {
        $activity = new Activity();

        $form = $this->createForm(ActivityBlockeeType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $activity->setStatus(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/i_blocked_someone', name: 'i_blocked', methods: ['GET', 'POST'])]
    public function new_blocked(Request $request, LicencePlateService $licencePlateService):Response
    {

        $activity = new Activity();



        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $activity->setStatus(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }


}
