<?php
namespace AdminBundle\Controller;

use AdminBundle\Entity\Donor;
use AdminBundle\Entity\Exhibit;
use AdminBundle\Entity\ExhibitOwner;
use AdminBundle\Form\DonorType;
use AdminBundle\Form\ExhibitOwnerType;
use AdminBundle\Form\ExhibitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DonorController
 * @package AdminBundle\Controller
 */
class DonorController extends Controller
{
    use ErrorCatcherAwareTrait;

    /**
     * @return Response
     */
    public function listAction(): Response
    {
        $donors = $this->getDoctrine()->getRepository(Donor::class)->findAll();
        return $this->render('@Admin/Donor/list.html.twig', ['donors' => $donors]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function editAction($id): Response
    {
        return $this->render('@Admin/Donor/edit.html.twig', ['donorId' => $id]);
    }

    /**
     * @param int|null $id
     * @return Response
     */
    public function formAction($id): Response
    {
        if ($id !== null) {
            $donor = $this->getDoctrine()->getRepository(Donor::class)->find($id);
        } else {
            $donor = new Donor();
        }
        $form = $this->createForm(DonorType::class, $donor, ['action' => $this->generateUrl('admin_add_donor_save')]);
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
        $donorId = (int)$request->get('adminbundle_donor')['donorId'];
        if ($donorId != 0) {
            $donor = $this->getDoctrine()->getRepository(Donor::class)->find($donorId);
        } else {
            $donor = new Donor();
        }
        $form = $this->createForm(DonorType::class, $donor);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($donor);
            $em->flush();
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(json_encode(['errors' => [], 'result' => 'success', 'id' => $donor->getId(), 'string' => (string)$donor]));
            } else {
                return $this->redirectToRoute('admin_donor_list');
            }
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(json_encode(['errors' => self::getFormErrors($form), 'result' => 'fail']));
        }
        return $this->redirectToRoute('admin_donor_list');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction($id): RedirectResponse
    {
        $donor = $this->getDoctrine()->getRepository(Donor::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($donor);
        $em->flush();
        return $this->redirectToRoute('admin_donor_list');
    }
}
