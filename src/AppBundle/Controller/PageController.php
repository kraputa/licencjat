<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Page;
use AppBundle\Form\PageType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Page controller.
 *
 *
 */
class PageController extends Controller
{
    /**
     * Creates a new Page entity.
     *
     * @Route("admin/page/new", name="page_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $page = new Page();
        $form = $this->createForm('AppBundle\Form\PageType', $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $message=$this->get('translator')->trans('Dodano artykuł!');
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('page_show', array('page' => $page->getShortName(),
                'category' =>$page->getCategory()->getShortName()));
        }

        return $this->render('page/new.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{category}/{page}", name="page_show", requirements={
     *     "category" = "^(?!admin|user|unlock_page).+" })
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($category, $page)
    {
        $categoryRep=$this->getDoctrine()->getRepository("AppBundle:Category");
        $category=$categoryRep->findOneBy(array(
            'shortName'=>$category));
        if(!$category) throw new NotFoundHttpException;

        $allCategories=$categoryRep->findAll();
        $page=$this->getDoctrine()->getRepository("AppBundle:Page")->findOneBy(array(
            'category'=>$category,
            'shortName'=>$page));
        if(!$page) throw new NotFoundHttpException;

        $view=array('page' => $page,
            'categories'=>$allCategories,
            'currentCategory'=>$category,
        'deleteForm' => $this->createDeleteForm($page)->createView()
        );
        return $this->render('page/show.html.twig', $view);
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/admin/page", name="admin_page_index")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminIndexAction()
    {
        $pages=$this->getDoctrine()->getRepository("AppBundle:Page")->findAll();
        $view=array('pages' => $pages,
        );
        return $this->render('page/adminIndex.html.twig', $view);
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("admin/page/{id}/edit", name="page_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Page $page)
    {
        $deleteForm = $this->createDeleteForm($page);
        $editForm = $this->createForm('AppBundle\Form\PageType', $page);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $message=$this->get('translator')->trans('Zmiany zotały zapisane!');
            $this->addFlash(
                'success',
                $message
            );
            return $this->redirectToRoute('page_show', array('page' => $page->getShortName(),
                'category' =>$page->getCategory()->getShortName()));
        }

        return $this->render('page/edit.html.twig', array(
            'page' => $page,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("admin/page/{id}", name="page_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Page $page)
    {
        $form = $this->createDeleteForm($page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();

            $message=$this->get('translator')->trans('Artykuł został usunięty!');
            $this->addFlash(
                'success',
                $message
            );
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * Creates a form to delete a Page entity.
     *
     * @param Page $page The Page entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Page $page)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('page_delete', array('id' => $page->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    
}
