<?php
namespace AdminBundle\Controller\Security;

use AdminBundle\Controller\ErrorCatcherAwareTrait;
use AdminBundle\Entity\User;
use AdminBundle\Form\ChangePasswordType;
use AdminBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class SecurityController
 * @package AdminBundle\Controller
 */
class SecurityController extends Controller
{
    use ErrorCatcherAwareTrait;
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $authUtils = $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render(
            '@Admin/Security/form.html.twig',
            ['error' => $error, 'lastUsername' => $lastUsername]
        );
    }

    /**
     * @return JsonResponse
     */
    public function authorizeAction()
    {
        $authUtils = $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $result = ['result' => 'ok'];
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $result['result'] = 'fail';
            $result['error'] = $error->getMessage();
        }
        return new JsonResponse(json_encode($result));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction()
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        return $this->render(
            '@Admin/Security/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function validateRegisterAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'Tylko Ajax!'), 400);
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $newUser = $this->getDoctrine()->getRepository(User::class)->findBy(['username' => $user->getUsername()]);

        if ($newUser) {
            $form->get('username')->addError(new FormError('Podana nazwa użytkownika jest już zajęta.'));
        }
        if ($form->isValid()) {
            $passwordString =  $this->generatePassword();
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $passwordString);
            $user->setPassword($password);
            $user->setIsActive(true);
            $user->setAdmin(false);
            $user->setNew(true);

            $em->persist($user);
            $em->flush();
            $userData = [
                'login' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password' => $passwordString
            ];

            $this->sendRegisterEmail($userData, $this->get('swiftmailer.mailer'));
            return new JsonResponse(json_encode([
                'errors' => [],
                'result' => 'success',
                'user' => $userData
            ]));
        }
        return new JsonResponse(json_encode(['errors' => self::getFormErrors($form), 'result' => 'fail']));
    }

    /**
     * @param int $length
     * @return string
     */
    private function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = mt_rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newPasswordAction()
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        return $this->render('@Admin/Security/new_password.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validateNewPasswordAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'Tylko Ajax!'), 400);
        }
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        $encoder = $this->get('security.password_encoder');
        if (!$encoder->isPasswordValid($user, $user->getOldPassword())) {
            $form->addError(new FormError('Poprzednie hasło jest niepoprawne.'));
        }
        if ($form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setNew(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->reloadUserPermissions($user);
            return new JsonResponse(json_encode(['errors' => false, 'result' => 'success']));
        }
        $errors = self::getFormErrors($form);
        return new JsonResponse(json_encode(['errors' => $errors, 'result' => 'fail']));
    }


    /**
     * @param User $user
     */
    protected function reloadUserPermissions(User $user)
    {
        $token = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $this->get('security.token_storage')->setToken($token);
    }

    /**
     * @param $userData
     * @param \Swift_Mailer $mailer
     */
    private function sendRegisterEmail($userData, \Swift_Mailer $mailer)
    {
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return;
        }
        $message = (new \Swift_Message($this->getParameter('sitetitle') . ' - Nowe konto w systemie'))
            ->setFormat($this->getParameter('mailer_user'))
            ->setTo($userData['email'])
            ->setBody(
                $this->renderView(
                    'AdminBundle:Security:register_email.html.twig',
                    ['userData' => $userData]
                ),
                'text/html'
            );
        $mailer->send($message);
    }
}
