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

            $message=$this->get('translator')->trans('New page created!');
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
        $category=$this->getDoctrine()->getRepository("AppBundle:Category")->findOneBy(array(
            'shortName'=>$category));
        if(!$category) throw new NotFoundHttpException;

        $page=$this->getDoctrine()->getRepository("AppBundle:Page")->findOneBy(array(
            'category'=>$category,
            'shortName'=>$page));
        if(!$page) throw new NotFoundHttpException;

        $view=array('page' => $page);
        if( $this->checkAvailability($page)){
           $view['access']=true;
           $deleteForm = $this->createDeleteForm($page);
           $view['delete_form'] =$deleteForm->createView();
        }else{
           $view['access']=false;
           $unlockForm=$this->createUnlockForm($page);
           $view['unlock_form']=$unlockForm->createView();
       }
        return $this->render('page/show.html.twig', $view);
    }

    /**
     * @param $page Page
     * @return bool
     */
    private function checkAvailability($page){
        if(!$page->getUnlockQuestion()
            || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            || $this->getUser()->getUnlockedPages()->contains($page)
        )
        {
            return true;
        }
        else return false;
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

            $message=$this->get('translator')->trans('Page updated!');
            $this->addFlash(
                'success',
                $message
            );
            return $this->redirectToRoute('page_edit', array('id' => $page->getId()));
        }

        return $this->render('page/edit.html.twig', array(
            'page' => $page,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

            $message=$this->get('translator')->trans('Page deleted!');
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

    /**
     * Add access to a Page entity.
     *
     * @Route("/unlock_page/{id}", name="page_unlock")
     * @Method("POST")
     */
    public function unlockAction(Request $request, Page $page)
    {
        $form = $this->createUnlockForm($page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer=$form->get('answer')->getData();
            if($answer==$page->getUnlockAnswer()){
                $em = $this->getDoctrine()->getManager();
                $user=$this->getUser();
                if(!$user->getUnlockedPages()->contains($page)){
                    $user->addUnlockedPage($page);
                    $em->persist($user);
                    $em->flush();

                    $message=$this->get('translator')->trans('Page unlocked!');
                    $this->addFlash(
                        'success',
                        $message
                    );
                }
            return $this->redirectToRoute("page_show", array("category"=>$page->getCategory()->getShortName(),
                    "page"=>$page->getShortName()));
            }else{
                $message=$this->get("translator")->trans("Błędna odpowiedź. Spróbuj jeszcze raz!");
                $form->get('answer')->addError(new FormError($message));
            }
        }

        return $this->render('page/unlock.html.twig', array(
            'page' => $page,
            'unlock_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to unlock a Page entity.
     *
     * @param Page $page The Page entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUnlockForm(Page $page)
    {
        return $this->createFormBuilder()
            ->add('answer')
            ->setAction($this->generateUrl('page_unlock', array('id' => $page->getId(), 'user' => $this->getUser())))
            ->add('save', ButtonType::class, array(
                'attr' => array('class' => 'save')))
            ->setMethod('POST')
            ->getForm()
            ;
    }
}
