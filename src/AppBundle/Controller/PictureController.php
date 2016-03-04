<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Picture;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PictureController extends Controller
{

    /**
     * @Route("/admin/picture", name="picture_index")
     */
    public function indexAction(){
        $pictures=$this->getDoctrine()->getRepository("AppBundle:Picture")->findAll();
        dump($pictures);exit;
        return $this->render("picture/index.html.twig", array('pictures'=>$pictures));
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     *
     * @Route("/admin/picture/upload", name="upload_picture")
     *
     */
    public function uploadAction(Request $request)
    {
       $picture=new Picture();

        $form=$this->createFormBuilder($picture)
            ->add('name')
            ->add('file')
            ->getForm();
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $picture->upload();
            $em->persist($picture);
            $em->flush();

            return $this->redirectToRoute("admin");
        }
        return $this->render('picture/upload.html.twig',
            array('form' => $form->createView(), 'picture'=>$picture));
    }


    /**
     *
     * @Route("/admin/picture/{id}", name="delete_picture")
     * @Method("DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Picture $picture){
        $form = $this->createDeleteForm($picture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $picture->removeFile();
            $em->remove($picture);
            $em->flush();

            $message=$this->get('translator')->trans('Picture deleted!');
            $this->addFlash(
                'success',
                $message
            );
        }

        return $this->redirectToRoute('admin');


    }

    /**
     * Creates a form to delete a Picture entity.
     * @param Picture $picture The Picture entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Picture $picture)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('page_delete', array('id' => $picture->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


}
