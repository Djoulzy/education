<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Asset\Packages;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\BSMenuGenerator;

use App\Form\AccountValidationType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
        TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mailer): Response
    {
        if ($request->isMethod('POST')) {
            try {
                $token = $tokenGenerator->generateToken();

                $user = new User();
                $user->setEmail($request->request->get('email'));
                $user->setFullname($request->request->get('fullname'));
                $user->setNickname($request->request->get('nickname'));
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $user->setRoles(['ROLE_USER']);
                $user->setResetToken($token);
                $user->setDisabled(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } 
            catch(DBALException $e){
                $this->addFlash('danger', 'Ce compte existe déjà');
                return $this->redirectToRoute('app_register');
            }    
            catch(\Exception $e){
                $this->addFlash('danger', 'Une erreur est survenue, veuillez réessayer ultérieurement.<br/>'.$e->getMessage());
                return $this->redirectToRoute('app_register');
            }

            $url = $this->generateUrl('activate_account', array('id' => $user->getId(),'token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Activation de votre compte'))
                ->setFrom($_ENV['MAILER_SENDER'])
                ->setTo($user->getEmail());
            $content = $this->renderView('security/mailhtml.html.twig', [
                'lien' => $url,
                'passwd' => $request->request->get('password')
            ]);
            $content2 = $this->renderView('security/mailplain.html.twig', [
                'lien' => $url,
                'passwd' => $request->request->get('password')
            ]);
            // $message->setBody($content, 'text/html');
            $message->setBody($content, 'text/plain');

            $message->setBody($content, 'text/html');
            $message->addPart($content2, 'text/plain');

            $mailer->send($message);
            
            $this->addFlash('info', 'Un mail vous a été envoyé pour activer votre compte.');

            return $this->redirectToRoute('home');
        }
        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/activate/{id}/{token}", name="activate_account")
     */
    public function activate(Request $request, int $id, string $token, \Swift_Mailer $mailer)
    {
        jlog(var_export($id, true));
        jlog(var_export($token, true));
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneByResetToken($token);

        if (($user === null) || ($user->getId() != $id)) {
            $this->addFlash('warning', 'Token Expiré');
            return $this->redirectToRoute('home');
        }

        $user->setResetToken(null);
        $user->setDisabled(false);
        $em->persist($user);
        $em->flush();

        $this->addFlash('info', 'Votre compte est activé !');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }
}
