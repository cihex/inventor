<?php
namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Exhibit;
use AdminBundle\Entity\Hire;
use AdminBundle\Entity\Photo;
use AdminBundle\Form\CategoryType;
use AdminBundle\Form\ExhibitType;
use AdminBundle\Repository\HireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ExhibitController
 * @package AdminBundle\Controller
 */
class ExhibitController extends Controller
{
    use ErrorCatcherAwareTrait;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if (!$this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_login');
        }
        return $this->render('@Admin/Exhibit/index.html.twig', ['search' => false, 'forceExhibits' => null, 'pattern' => '']);
    }

    /**
     * @param bool $search
     * @param array $forceExhibits
     * @param string $pattern
     * @return Response
     */
    public function listAction($search = false, array $forceExhibits = null, $pattern = '')
    {
        /**
         * @var HireRepository
         */
        $repository = $this->getDoctrine()->getManager()->getRepository(Hire::class);
        $exhibits = !$search
            ? $this->getDoctrine()->getRepository('AdminBundle:Exhibit')->findAll()
            : $forceExhibits;
        return $this->render(
            '@Admin/Exhibit/list.html.twig',
            [
                'exhibits' => $exhibits,
                'hireRepository' => $repository,
                'isSearch' => $search,
                'pattern' => $pattern
            ]
        );
    }

    /**
     * @param int|null $id
     * @return Response
     */
    public function formAction($id = null)
    {
        if ($id == null) {
            $exhibit = new Exhibit();
        } else {
            $repository = $this->getDoctrine()->getRepository(Exhibit::class);
            $exhibit = $repository->find($id);
        }

        $category = new Category();
        $form = $this->createForm(ExhibitType::class, $exhibit, ['action' => $this->generateUrl('admin_exhibit_save', ['id' => $id])]);
        $view = $form->createView();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        return $this->render(
            'AdminBundle:Exhibit:form.html.twig',
            [
                'form' => $view,
                'categoryForm' => $categoryForm->createView(),
                'exhibit' => $exhibit
            ]
        );
    }

    /**
     * @param Request $request
     * @param null $id
     * @return JsonResponse
     */
    public function saveAction(Request $request, $id = null)
    {
        if ($id !== null) {
            $exhibit = $this->getDoctrine()->getRepository(Exhibit::class)->find($id);
        } else {
            $exhibit = new Exhibit();
        }
        $form = $this->createForm(ExhibitType::class, $exhibit);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $photosIds = array_map(
                function ($value) {
                    return (int)$value;
                },
                explode(',', $form->get('photosId')->getData())
            );
            $em = $this->getDoctrine()->getManager();
            foreach ($exhibit->getPhotos()->getIterator() as $photo) {
                $exhibit->removePhoto($photo);
            }
            $exhibit->setPhotos(new ArrayCollection());
            $photoRepository = $this->getDoctrine()->getRepository(Photo::class);

            foreach ($photosIds as $photoId) {
                if ($photoId == 0) {
                    continue;
                }
                $newPhoto = $photoRepository->find($photoId);
                $newPhoto->setExhibit($exhibit);
                $exhibit->addPhoto($newPhoto);
                $em->persist($newPhoto);
            }
            $em->persist($exhibit);
            $em->flush();
            return new JsonResponse(json_encode(['errors' => [], 'result' => 'success', 'id' => $exhibit->getId()]));
        }
        return new JsonResponse(json_encode(['errors' => self::getFormErrors($form), 'result' => 'fail']));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $exhibit = $this->getDoctrine()->getRepository(Exhibit::class)->find($id);
        if (!$exhibit) {
            throw $this->createNotFoundException('No exhibit found for id ' . $id);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($exhibit);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_exhibit'));
    }

    /**
     * @param string $id
     * @return BinaryFileResponse
     */
    public function loadPhotoAction($id)
    {
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($id);
        $photoResource = $this->get('kernel')->locateResource('@AdminBundle/Resources/Photos');
        $file = new File($photoResource . '/' . $photo->getName());
        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-type', $file->getMimeType());
        return $response;
    }

    /**
     * @param $photoId
     * @return JsonResponse
     */
    public function deletePhotoAction($photoId)
    {
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($photoId);

        /**
         * @var Exhibit $exhibit
         */
        $exhibit = $photo->getExhibit();
        $filesystem = new Filesystem();
        $result = ['result' => 'ok'];
        try {
            if ($exhibit !== null) {
                $exhibit->removePhoto($photo);
            }
            $em = $this->getDoctrine()->getManager();
            $photoResource = $this->get('kernel')->locateResource('@AdminBundle/Resources/Photos');
            $filesystem->remove($photoResource . '/' . $photo->getName());
            $em->remove($photo);
            if ($exhibit !== null) {
                $em->persist($exhibit);
            }
            $em->persist($photo);
            $em->flush();
        } catch (\Exception $exception) {
            $result = ['result' => 'fail'];
        }
        return new JsonResponse(json_encode($result));
    }

    /**
     * @param int $photoId
     * @param bool $isActive
     * @return JsonResponse
     */
    public function setActivePhotoAction($photoId, $isActive)
    {
        $result = ['result' => 'ok'];
        try {
            $photo = $this->getDoctrine()->getRepository(Photo::class)->find($photoId);
            $photo->setActive($isActive);
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
        } catch (\Exception $exception) {
            $result['result'] = 'fail';
        }
        return new JsonResponse(json_encode($result));
    }

    /**
     * @param Request $request
     * @param int $exhibitId
     * @return JsonResponse
     */
    public function uploadPhotoAction(Request $request, int $exhibitId) 
    {
        /**
         * @var File[] $files
         */
        $files = $request->files->get('files');

        /**
         * @var Photo[]
         */
        $images = [];
        $em = $this->getDoctrine()->getManager();
        $photo = new Photo();
        $exhibit = null;
        if ((int)$exhibitId !== 0) {
            $exhibit = $this->getDoctrine()->getRepository(Exhibit::class)->find($exhibitId);
        }
        $photoPath = $this->get('kernel')->locateResource('@AdminBundle/Resources/Photos');
        foreach ($files as $file) {
            $fileName = md5(uniqid('image', false)) . '.' . $file->guessExtension();
            $file->move(
                $photoPath,
                $fileName
            );
            $this->resizeAndSaveFile($photoPath . '/' . $fileName);

            /**
             * @var Photo $newPhoto
             */
            $newPhoto = clone $photo;
            $newPhoto->setName($fileName);
            $em->persist($newPhoto);
            $images[] = $newPhoto;
            if ($exhibit != null) {
                $exhibit->addPhoto($newPhoto);
                $newPhoto->setExhibit($exhibit);
            }
        }
        if ($exhibit != null) {
            $em->persist($exhibit);
        }
        $em->flush();
        $ids = [];
        $names = [];
        foreach ($images as $image) {
            $ids[] = $image->getId();
            $names[$image->getId()] = $image->getName();
        }
        return new JsonResponse(json_encode([
            'files' => $ids,
            'names' => $names
        ]));
    }



    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function printAction($id)
    {
        $exhibit = $this->getDoctrine()->getRepository(Exhibit::class)->find($id);
        return $this->render('@Admin/Exhibit/print.html.twig', ['exhibit' => $exhibit, 'isPrint' => true]);
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $exhibit = $this->getDoctrine()->getRepository(Exhibit::class)->find($id);
        return $this->render('@Admin/Exhibit/print.html.twig', ['exhibit' => $exhibit, 'isPrint' => false]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $pattern = $request->get('pattern');
        $forceExhibits = $this->getDoctrine()->getRepository('AdminBundle:Exhibit')->findByPattern($pattern);
        return $this->render(
            '@Admin/Exhibit/index.html.twig',
            ['search' => true, 'forceExhibits' => $forceExhibits, 'pattern' => $pattern]
        );
    }

    /**
     * @param string $filePath
     */
    private function resizeAndSaveFile(string $filePath)
    {
        $imageHandler = $this->get('image.handling')->open($filePath);
        if ($imageHandler->width() > $imageHandler->height()) {
            $imageHandler->scaleResize(800, null, 'transparent', true);
        } else {
            $imageHandler->scaleResize(null, 800,'transparent', true);
        }

        $imageHandler->save($filePath);
    }

    /**
     * @param string $photoName
     * @param int $size
     * @param string $container
     * @return Response
     */
    public function getThumbAction(string $photoName, int $size, string $container = '')
    {
        return $this->render(
            'AdminBundle:Exhibit:thumb.html.twig',
            compact('photoName', 'size', 'container'))
        ;
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Endroid\QrCode\Exception\InvalidWriterException
     */
    public function getQrAction(int $id)
    {
        $qrCode = new QrCode($this->generateUrl('admin_exhibit_show', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL));
        $qrCode->setSize('100');
        return new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);
    }
}
