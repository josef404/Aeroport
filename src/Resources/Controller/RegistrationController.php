<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Controller;

use App\Entity\Magasiniers;
use App\Entity\Techniciens;
use App\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller managing the registration.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends Controller
{
    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    private $tokenStorage;

    public function __construct(EventDispatcherInterface $eventDispatcher, FactoryInterface $formFactory, UserManagerInterface $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request )
    {
        #$user = $this->userManager->createUser();
        $user=new User();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);

        if($this->isGranted('ROLE_SUPER_ADMIN')){
            $form->add('userRoles', ChoiceType::class, array(
                'choices' => array(
                    'Chef' => 'ROLE_CHEF',
                    'Magasinier' => 'ROLE_MAG',
                    'Technicien' => 'ROLE_TECH'
                ),
                'translation_domain' => 'FOSUserBundle',
                'multiple' => false,
                'required' => true,
                'mapped' => false,
            ));
        }elseif ($this->isGranted('ROLE_CHEF')){
            $form->add('userRoles', ChoiceType::class, array(
                'choices' => array(

                    'Magasinier' => 'ROLE_MAG',
                    'Technicien' => 'ROLE_TECH'
                ),
                'translation_domain' => 'FOSUserBundle',
                'multiple' => false,
                'required' => true,
                'mapped' => false,
            ));
        }





        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $role = array($form->get('userRoles')->getData());

                $user->setRoles($role);
                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $this->userManager->updateUser($user);
$source=0;
                if($form->get('userRoles')->getData()=='ROLE_TECH'){
                    $tech=new Techniciens();
                    $tech->setNom($user->getNom());
                    $tech->setPrenom($user->getPrenom());
                    $tech->setAdress($user->getAdress());
                    $tech->setTelephone($user->getTelephone());
                    $tech->setDateNaissance($user->getDateNaissance());
                    $tech->setDateRecrutement($user->getDateRecrutement());
                    $tech->setChef($this->getUser());
                    $tech->setUser($user);
                    $source=2;
                    $this->getDoctrine()->getManager()->persist($tech);

                }elseif ($form->get('userRoles')->getData()=='ROLE_MAG'){
                    $mag=new Magasiniers();
                    $mag->setNom($user->getNom());
                    $mag->setPrenom($user->getPrenom());
                    $mag->setAdress($user->getAdress());
                    $mag->setTelephone($user->getTelephone());
                    $mag->setDateNaissance($user->getDateNaissance());
                    $mag->setDateRecrut($user->getDateRecrutement());
                    $mag->setUser($user);
                    $source=1;
                    $this->getDoctrine()->getManager()->persist($mag);
                }

                if (null === $response = $event->getResponse()) {
                    if($this->isGranted('ROLE_SUPER_ADMIN')){
                        $url = $this->generateUrl('user_table');
                    }elseif ($source==1){
                        $url = $this->generateUrl('liste_mag');
                    }elseif ($source==2){
                        $url = $this->generateUrl('liste_tech');
                    }

                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($this->getUser(), $request, $response));
                return $response;

            }

            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Tell the user to check their email provider.
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->getSession()->get('fos_user_send_confirmation_email/email');

        if (empty($email)) {
            return new RedirectResponse($this->generateUrl('fos_user_registration_register'));
        }

        $request->getSession()->remove('fos_user_send_confirmation_email/email');
        $user = $this->userManager->findUserByEmail($email);

        if (null === $user) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
        }

        return $this->render('@FOSUser/Registration/check_email.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function confirmAction(Request $request, $token)
    {
        $userManager = $this->userManager;

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('base');
            $response = new RedirectResponse($url);
        }

        $this->eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed.
     */
    public function confirmedAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('@FOSUser/Registration/confirmed.html.twig', array(
            'user' => $user,
            'targetUrl' => $this->getTargetUrlFromSession($request->getSession()),
        ));
    }

    /**
     * @return string|null
     */
    private function getTargetUrlFromSession(SessionInterface $session)
    {
        $key = sprintf('_security.%s.target_path', $this->tokenStorage->getToken()->getProviderKey());

        if ($session->has($key)) {
            return $session->get($key);
        }

        return null;
    }
}
