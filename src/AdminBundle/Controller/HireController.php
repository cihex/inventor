<?php
namespace AdminBundle\Controller;

use AdminBundle\Entity\Hire;
use AdminBundle\Form\HireType;
use AdminBundle\Form\ReturnHireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

/**
 * Class HireController
 * @package AdminBundle\Controller
 */
class HireController extends Controller
{

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render(
            'AdminBundle:Hire:index.html.twig',
            ['hires' => $this->getDoctrine()->getRepository(Hire::class)->findBy([], ['hireDate' => 'DESC'])]
        );
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    public function formAction(Request $request, $id = null): Response
    {
        if ($id !== null) {
            $hire = $this->getDoctrine()->getRepository(Hire::class)->find($id);
        } else {
            $hire = new Hire();
        }
        $form = $this->createForm(HireType::class, $hire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->doSave($hire);
        }
        return $this->render('AdminBundle:Hire:form.html.twig', ['form' => $form->createView(), 'hireId' => $id]);
    }

    /**
     * @param int $id
     * @return Response
     * @todo dokończyć
     */
    public function cancelAction($id): Response
    {
        return new Response();
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function returnAction(Request $request, $id): Response
    {
        $hire = $this->getDoctrine()->getRepository(Hire::class)->find($id);
        $form = $this->createForm(ReturnHireType::class, $hire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->doSave($hire);
        }
        return $this->render('AdminBundle:Hire:return-form.html.twig', ['form' => $form->createView(), 'hireId' => $id]);
    }

    /**
     * @param Hire $hire
     * @return RedirectResponse
     * @internal param FormInterface $form
     */
    private function doSave(Hire $hire): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($hire);
        $em->flush();
        return $this->redirectToRoute('admin_hire_edit', ['id' => $hire->getId()]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id): Response
    {
        $hire = $this->getDoctrine()->getRepository(Hire::class)->find($id);
        if (!$hire) {
            throw $this->createNotFoundException('No hire found for id ' . $id);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($hire);
        $em->flush();
        $url = $this->generateUrl('admin_hire_index');
        return $this->redirect($url);
    }
}
