<?php
namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use AdminBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @package AdminBundle\Controller
 */
class CategoryController extends Controller
{
    use ErrorCatcherAwareTrait;

    /**
     * @return Response
     */
    public function listAction(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('@Admin/Category/list.html.twig', ['categories' => $categories]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function editAction($id): Response
    {
        return $this->render('@Admin/Category/edit.html.twig', ['categoryId' => $id]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function formAction($id): Response
    {
        if ($id !== null) {
            $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        } else {
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category, ['action' => $this->generateUrl('admin_add_category_save')]);
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
    public function saveAction(Request $request) : Response
    {
        $formDataArray = $request->get('adminbundle_category');
        $categoryId = (int)$formDataArray['categoryId'];
        if ($categoryId != 0) {
            $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);
        } else {
            $category = new Category();
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(json_encode([
                    'errors' => [],
                    'result' => 'success',
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'alias' => $category->getAlias(),
                ]));
            }
            return $this->redirectToRoute('admin_category_list');
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(json_encode(['errors' => self::getFormErrors($form), 'result' => 'fail']));
        }
        return $this->redirectToRoute('admin_category_list');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id): RedirectResponse
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if (!$category) {
            throw $this->createNotFoundException('No hire found for id ' . $id);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $url = $this->generateUrl('admin_category_list');
        return $this->redirect($url);
    }

}
