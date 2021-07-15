<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityBlockeeType;
use App\Form\ActivityType;
use App\Repository\UserRepository;
use App\Service\LicencePlateService;
use App\Service\ActivitiesService;
use App\Service\MailerService;
use App\Service\UserService;
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
        $carsBlocking = array();
        $carsBlocked = array();
        if ($this->getUser()) {
            $arrayOfCars = $licencePlateService->findLicencePlatesByUserId();
            if (empty($arrayOfCars)) {
                return $this->redirectToRoute('licence_plate_new');
            } else {
                foreach ($arrayOfCars as $car) {
                    $actionByBlocker = $activitiesService->findActionByBlocker($car);
                    $actionByBlockee = $activitiesService->findActionByBlockee($car);
                    $whoBlockedMe = $activitiesService->whoBlockedMe($car);

                    if ($actionByBlocker != null) {
                        array_push($carsBlocking, $actionByBlocker);
                    }

                    if ($actionByBlockee != null) {
                        array_push($carsBlocked, $actionByBlockee);
                    }
                }
            }

            return $this->render('main_page.html.twig', [
                'blockedCars' => $carsBlocking,
                'blockedCarsTwo' => $carsBlocked
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/i_was_blocked', name: 'i_was_blocked', methods: ['GET', 'POST'])]
    public function someoneBlockedMe(Request $request, ActivitiesService $activitiesService,
                                LicencePlateService $licencePlateService,
                                UserService $userService, MailerService $mailerService): Response
    {
        $activity = new Activity();

        $form = $this->createForm(ActivityBlockeeType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $initialLicencePlate = $activity->getBlocker();

            $finalLicencePlate = preg_replace('/[^0-9a-zA-Z]/', '', $initialLicencePlate);

            $activity->setBlocker(strtoupper($finalLicencePlate));

            $activityByKey = $activitiesService->findByComposedId($activity->getBlocker(), $activity->getBlockee());
            if ($activityByKey != null) {
                $this->addFlash('notice', "activity already reported");
            } else {

                $this->searchForBlocker($licencePlateService, $mailerService, $userService, $activity);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($activity);
                $entityManager->flush();
            }
            return $this->redirectToRoute('main');
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/i_blocked_someone', name: 'i_blocked', methods: ['GET', 'POST'])]
    public function iBlockedSomeone(Request $request, LicencePlateService $licencePlateService, ActivitiesService $activitiesService,
        MailerService $mailerService, UserService $userService):Response
    {

        $activity = new Activity();



        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $initialLicencePlate = $activity->getBlockee();

            $finalLicencePlate = preg_replace('/[^0-9a-zA-Z]/', '', $initialLicencePlate);

            $activity->setBlockee(strtoupper($finalLicencePlate));

            $activityByKey = $activitiesService->findByComposedId($activity->getBlocker(), $activity->getBlockee());

            if ($activityByKey != null) {
                $this->addFlash("notice", "activity already reported");
            } else {

                $this->searchForBlockee($licencePlateService, $mailerService, $userService, $activity);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($activity);
                $entityManager->flush();
            }

            return $this->redirectToRoute('main');
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    private function searchForBlockee(LicencePlateService $licencePlateService, MailerService $mailerService,
                                      UserService $userService, Activity $activity) {

        $usersOfBlockedCar = $licencePlateService->findAllUsersByLicencePlate($activity->getBlockee());

        if (sizeof($usersOfBlockedCar) != 0) {

            foreach ($usersOfBlockedCar as $userId) {
                $mailerService->sendHaveBeenBlockedEmail($userService->getMailOfUser($userId),
                    $userService->getCurrentUserMail());
            }
            $activity->setStatus(0);
        } else {
            $this->addFlash("warning", "Can't find the user of car " . $activity->getBlockee());
            $activity->setStatus(0);
        }

    }

    private function searchForBlocker(LicencePlateService $licencePlateService, MailerService $mailerService,
                                      UserService $userService, Activity $activity) {

        $usersOfBlockerCar = $licencePlateService->findAllUsersByLicencePlate($activity->getBlocker());

        if (sizeof($usersOfBlockerCar) != 0) {

            foreach ($usersOfBlockerCar as $userId) {
                $mailerService->sendGetCarEmail($userService->getMailOfUser($userId),
                   $activity->getBlocker());
            }
            $activity->setStatus(1);
        } else {
            $this->addFlash("warning", "Can't find the user of car " . $activity->getBlocker());
            $activity->setStatus(0);
        }

    }

    #[Route('/{blocker}/{blockee}/{id}', name: 'call_driver' , methods: ['GET'])]
    public function callForUserOfCar(string $blocker, string $blockee, MailerService $mailerService,
                                     LicencePlateService $licencePlateService, UserService $userService,
                                     ActivitiesService $activitiesService)
    {

        $activity = $activitiesService->findByComposedId($blocker, $blockee);

        if ($activity != null && $activity->getStatus() == 0) {
            $this->searchForBlocker($licencePlateService, $mailerService, $userService, $activity);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('main');
    }

    #[Route('/{blocker}/{blockee}', name: 'activity_delete', methods: ['POST'])]
    public function deleteActivity(Request $request,$blocker, $blockee, ActivitiesService $activitiesService,
                                   MailerService $mailerService, LicencePlateService $licencePlateService,
                                    UserService $userService):Response
    {
        $activity = $activitiesService->findByComposedId($blocker, $blockee);

        if ($this->isCsrfTokenValid('delete' . $activity->getBlocker() . $activity->getBlockee(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activity);
//            $activitiesService->deleteActivity($activity);
            $entityManager->flush();

            $usersOfBlockerCar = $licencePlateService->findAllUsersByLicencePlate($activity->getBlocker());

            if (sizeof($usersOfBlockerCar) != 0) {

                foreach ($usersOfBlockerCar as $userId) {
                    $mailerService->sendActivityHasBeenDeletedMail($userService->getMailOfUser($userId),
                        $activity->getBlocker());
                }

            }
        }

        return $this->redirectToRoute('main');
    }


}
