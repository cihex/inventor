<?php
namespace AdminBundle\Controller;

use AdminBundle\Entity\ExhibitOwner;
use AdminBundle\Form\ExhibitOwnerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OwnerController
 * @package AdminBundle\Controller
 */
class OwnerController extends Controller
{
    use ErrorCatcherAwareTrait;

    /**
     * @return Response
     */
    public function listAction(): Response
    {
        $owners = $this->getDoctrine()->getRepository(ExhibitOwner::class)->findAll();
        return $this->render('@Admin/Owner/list.html.twig', ['owners' => $owners]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function editAction($id): Response
    {
        return $this->render('@Admin/Owner/edit.html.twig', ['ownerId' => $id]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function formAction($id): Response
    {
        if ($id != null) {
            $owner = $this->getDoctrine()->getRepository(ExhibitOwner::class)->find($id);
        } else {
            $owner = new ExhibitOwner();
        }

        $form = $this->createForm(ExhibitOwnerType::class, $owner, ['action' => $this->generateUrl('admin_add_owner_save')]);
        return $this->render(
            'AdminBundle:Category:form.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function saveAction(Request $request): Response
    {
        $ownerId = (int)$request->get('adminbundle_exhibitowner')['ownerId'];
        if ($ownerId != null) {
            $owner = $this->getDoctrine()->getRepository(ExhibitOwner::class)->find($ownerId);
        } else {
            $owner = new ExhibitOwner();
        }
        $form = $this->createForm(ExhibitOwnerType::class, $owner);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($owner);
            $em->flush();
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(json_encode(['errors' => [], 'result' => 'success', 'id' => $owner->getId(), 'string' => (string)$owner]));
            } else {
                return $this->redirectToRoute('admin_owner_list');
            }
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(json_encode(['errors' => self::getFormErrors($form), 'result' => 'fail']));
        }
        return $this->redirectToRoute('admin_owner_list');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction($id): RedirectResponse
    {
        $owner = $this->getDoctrine()->getRepository(ExhibitOwner::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($owner);
        $em->flush();
        return $this->redirectToRoute('admin_owner_list');
    }
}
