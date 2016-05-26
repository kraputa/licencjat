<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Puzzle;
use AppBundle\Form\PuzzleType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
/**
 * Puzzle controller.
 *
 *
 */
class PuzzleController extends Controller
{

    /**
     * Lists all Puzzle entities.
     *
     * @Route("puzzle/", name="puzzle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $puzzles = $em->getRepository('AppBundle:Puzzle')->findAll();

        return $this->render('puzzle/index.html.twig', array(
            'puzzles' => $puzzles,
        ));
    }
    /**
     * Lists all Puzzle entities.
     *
     * @Route("admin/puzzle", name="admin_puzzle_index")
     * @Method("GET")
     */
    public function adminIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $puzzles = $em->getRepository('AppBundle:Puzzle')->findAll();

        return $this->render('puzzle/adminIndex.html.twig', array(
            'puzzles' => $puzzles,
        ));
    }

    /**
     * Creates a new Puzzle entity.
     *
     * @Route("admin/puzzle/new", name="puzzle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $puzzle = new Puzzle();
        $form = $this->createForm('AppBundle\Form\PuzzleType', $puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($puzzle);
            $em->flush();

            return $this->redirectToRoute('puzzle_show', array('id' => $puzzle->getId()));
        }

        return $this->render('puzzle/new.html.twig', array(
            'puzzle' => $puzzle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Puzzle entity.
     *
     * @Route("puzzle/{id}", name="puzzle_show")
     * @Method("GET")
     */
    public function showAction(Puzzle $puzzle)
    {
        $deleteForm = $this->createDeleteForm($puzzle);
$view=array('puzzle'=>$puzzle);
        if( $this->checkAvailability($puzzle)){
            $view['access']=true;
            $deleteForm = $this->createDeleteForm($puzzle);
            $view['delete_form'] =$deleteForm->createView();
        }else{
            $view['access']=false;
            $unlockForm=$this->createUnlockForm($puzzle);
            $view['unlock_form']=$unlockForm->createView();
        }

        return $this->render('puzzle/show.html.twig',$view
        );
    }

    /**
     * Add access to a Puzzle entity.
     *
     * @Route("/unlock_puzzle/{id}", name="puzzle_unlock")
     * @Method("POST")
     */
    public function unlockAction(Request $request, Puzzle $puzzle)
    {
        $form = $this->createUnlockForm($puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer=$form->get('answer')->getData();
            if($answer==$puzzle->getUnlockAnswer()){
                $em = $this->getDoctrine()->getManager();
                $user=$this->getUser();
                if(!$user->getUnlockedPuzzles()->contains($puzzle)){
                    $user->addUnlockedPuzzle($puzzle);
                    $em->persist($user);
                    $em->flush();

                    $message=$this->get('translator')->trans('Puzzle unlocked!');
                    $this->addFlash(
                        'success',
                        $message
                    );
                }
                return $this->redirectToRoute("puzzle_show", array('id'=>$puzzle->getId()));
            }else{
                $message=$this->get("translator")->trans("Błędna odpowiedź. Spróbuj jeszcze raz!");
                $form->get('answer')->addError(new FormError($message));
            }
        }

        return $this->render('puzzle/show.html.twig', array(
            'puzzle' => $puzzle,
            'unlock_form' => $form->createView(),
            'access'=>false,
        ));
    }

    /**
     * Creates a form to unlock a Puzzle entity.
     *
     * @param Puzzle $puzzle The Puzzle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUnlockForm(Puzzle $puzzle)
    {
        return $this->createFormBuilder()
            ->add('answer',null, array(
                'label'=>"Twoja odpowiedź:"
            ))
            ->setAction($this->generateUrl('puzzle_unlock', array('id' => $puzzle->getId(), 'user' => $this->getUser())))
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save',),'label'=>'Odpowiedź')
            )
            ->setMethod('POST')
            ->getForm()
            ;
    }

    /**
     * Displays a form to edit an existing Puzzle entity.
     *
     * @Route("admin/puzzle/{id}/edit", name="puzzle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Puzzle $puzzle)
    {
        $deleteForm = $this->createDeleteForm($puzzle);
        $editForm = $this->createForm('AppBundle\Form\PuzzleType', $puzzle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($puzzle);
            $em->flush();

            return $this->redirectToRoute('puzzle_edit', array('id' => $puzzle->getId()));
        }

        return $this->render('puzzle/edit.html.twig', array(
            'puzzle' => $puzzle,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Puzzle entity.
     *
     * @Route("admin/puzzle/{id}", name="puzzle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Puzzle $puzzle)
    {
        $form = $this->createDeleteForm($puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($puzzle);
            $em->flush();
        }

        return $this->redirectToRoute('puzzle_index');
    }

    /**
     * Creates a form to delete a Puzzle entity.
     *
     * @param Puzzle $puzzle The Puzzle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Puzzle $puzzle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('puzzle_delete', array('id' => $puzzle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param $puzzle Puzzle
     * @return bool
     */
    private function checkAvailability(Puzzle $puzzle){
        if(!$puzzle->getUnlockQuestion()
            || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            || $this->getUser()->getUnlockedPuzzles()->contains($puzzle)
        )
        {
            return true;
        }
        else return false;
    }
}
