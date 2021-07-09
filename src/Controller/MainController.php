<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityBlockeeType;
use App\Form\ActivityType;
use App\Repository\UserRepository;
use App\Service\LicencePlateService;
use App\Service\ActivitiesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'main')]
    public function index(LicencePlateService $licencePlateService, ActivitiesService $activitiesService): Response
    {

        if ($this->getUser()) {
            $arrayOfCars = $licencePlateService->findLicencePlatesByUserId();
            if (empty($arrayOfCars)) {
                return $this->redirectToRoute('licence_plate_new');
            } else {
                foreach ($arrayOfCars as $car) {
                    $whoBlockedMe = $activitiesService->whoBlockedMe($car);

                    if ($whoBlockedMe != null) {
                        echo ("you are blocked by " . $whoBlockedMe . "<br>");

                        $usersArrayOfBlockerCar = $licencePlateService->findAllUsersByLicencePlate($whoBlockedMe);

                        foreach ($usersArrayOfBlockerCar as $user) {
                            echo ("user id " . $user . "<br>");
                        }
                    }
                }

                echo ("<br><br>");

                foreach ($arrayOfCars as $car) {
                    $whoIBlocked = $activitiesService->iveBlockedSomebody($car);

                    if ($whoIBlocked != null) {
                        echo ("you have blocked " . $whoIBlocked . "<br>");

                        $usersArrayOfBlockedCar = $licencePlateService->findAllUsersByLicencePlate($whoIBlocked);

                        foreach ($usersArrayOfBlockedCar as $user) {
                            echo ("user id " . $user . "<br>");
                        }
                    }
                }
            }

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
