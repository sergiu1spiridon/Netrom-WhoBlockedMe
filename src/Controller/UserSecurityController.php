<?php

namespace App\Controller;

use App\Entity\ChangePassword;
use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\UserRegisterFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function Sodium\add;

#[Route('/users')]
class UserSecurityController extends AbstractController
{
    #[Route('/', name:'users_index', methods: ['GET'])]
    public function index():Response {
        return $this->render("base.html.twig");
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, MailerService $mailerService):Response {
        $user = new User();

        $form = $this->createForm(UserRegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $user->getPassword();
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $mailerService->sendRegistrationEmail($user->getEmailAddress(), $plainPassword);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('users/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ch_pass', name: 'app_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordEncoder, MailerService $mailerService):Response
    {
        if (!($this->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        $changePass = new ChangePassword();
        $user = $this->getUser();

        $form = $this->createForm(NewPasswordType::class, $changePass);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $changePass->getNewPassword();
            $password = $passwordEncoder->hashPassword($user, $plainPassword);
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($this->getUser()->getUserIdentifier());

            $user->setPassword($password);
            $entityManager->flush();

            $mailerService->sendRegistrationEmail($user->getEmailAddress(), $plainPassword);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('users/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
