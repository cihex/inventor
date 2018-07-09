<?php
namespace AdminBundle\Controller;

use AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AdminBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('@Admin/User/index.html.twig');
    }

    /**
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render(
            '@Admin/User/list.html.twig',
            ['users' => $this->getDoctrine()->getRepository('AdminBundle:User')->findAll()]
        );
    }

    /**
     * @param int $userId
     * @return RedirectResponse
     */
    public function removeAction($userId): RedirectResponse
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_user');
    }
}
