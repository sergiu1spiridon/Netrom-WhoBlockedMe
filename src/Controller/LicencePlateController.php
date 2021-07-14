<?php

namespace App\Controller;

use App\Entity\LicencePlate;
use App\Entity\User;
use App\Form\LicencePlateType;
use App\Repository\LicencePlateRepository;
use App\Service\ActivitiesService;
use App\Service\MailerService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/licence/plate')]
class LicencePlateController extends AbstractController
{
    #[Route('/', name: 'licence_plate_index', methods: ['GET'])]
    public function index(LicencePlateRepository $licencePlateRepository): Response
    {
        return $this->render('licence_plate/index.html.twig', [
            'licence_plates' => $licencePlateRepository->findAll(),
        ]);
    }

    #[Route('/my_cars', name: 'licence_plates_of_user', methods: ['GET'])]
    public function licencePlatesOfUser(LicencePlateRepository $licencePlateRepository): Response
    {
        if ($this->getUser()) {
            return $this->render('licence_plate/index.html.twig', [
                'licence_plates' => $licencePlateRepository->findAllByUser($this->getUser()->getUserIdentifier()),
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/new', name: 'licence_plate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActivitiesService $activitiesService, MailerService $mailerService, UserService $userService): Response
    {
        $licencePlate = new LicencePlate();
        $form = $this->createForm(LicencePlateType::class, $licencePlate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $licencePlate->setUserIds($this->getUser()->getUserIdentifier());
            $initialLicencePlate = $licencePlate->getPlateNumber();

            $finalLicencePlate = preg_replace('/[^0-9a-zA-Z]/', '', $initialLicencePlate);

            $licencePlate->setPlateNumber(strtoupper($finalLicencePlate));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($licencePlate);
            $entityManager->flush();

            $this->addFlash('success', "added car " . $licencePlate->getPlateNumber());
            $activityByBlocker = $activitiesService->findActionByBlocker($licencePlate->getPlateNumber());

            if ($activityByBlocker != null) {
                $mailerService->sendRegistrationEmail($userService->getCurrentUserMail(), $activityByBlocker->getBlockee());
                $activityByBlocker->setStatus(1);
                $this->addFlash("warning", $licencePlate->getPlateNumber());
            }

            return $this->redirectToRoute('licence_plates_of_user');
        }

        return $this->render('licence_plate/new.html.twig', [
            'licence_plate' => $licencePlate,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'licence_plate_show', methods: ['GET'])]
    public function show(LicencePlate $licencePlate): Response
    {
        return $this->render('licence_plate/show.html.twig', [
            'licence_plate' => $licencePlate,
        ]);
    }

    #[Route('/{id}/edit', name: 'licence_plate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LicencePlate $licencePlate, ActivitiesService $activitiesService): Response
    {

        if ($activitiesService->findActionByBlocker($licencePlate->getPlateNumber()) != null) {
            $this->addFlash('notice', "there is an action with this car. Can't edit");

            return $this->show($licencePlate);
        } else {
            $form = $this->createForm(LicencePlateType::class, $licencePlate);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('licence_plates_of_user');
            }
            return $this->render('licence_plate/edit.html.twig', [
                'licence_plate' => $licencePlate,
                'form' => $form->createView(),
            ]);
        }
    }

    #[Route('/{id}', name: 'licence_plate_delete', methods: ['POST'])]
    public function delete(Request $request, LicencePlate $licencePlate, ActivitiesService $activitiesService): Response
    {
        if ($activitiesService->findActionByBlocker($licencePlate->getPlateNumber()) != null) {
            $this->addFlash('notice', "there is an action with this car. Can't edit");

            return $this->show($licencePlate);
        } else {

            if ($this->isCsrfTokenValid('delete' . $licencePlate->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($licencePlate);
                $entityManager->flush();

                $this->addFlash('success', "deleted car " . $licencePlate->getPlateNumber());
            }

            return $this->redirectToRoute('licence_plates_of_user');
        }
    }
}
