<?php

namespace Toto\TotalizerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Toto\TotalizerBundle\Entity\Bid;
use Toto\TotalizerBundle\Form\BidType;

/**
 * Bid controller.
 *
 * @Route("/bid")
 */
class BidController extends Controller
{

    /**
     * Lists all Bid entities.
     *
     * @Route("/", name="bid")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TotoTotalizerBundle:Bid')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Bid entity.
     *
     * @Route("/", name="bid_create")
     * @Method("POST")
     * @Template("TotoTotalizerBundle:Bid:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Bid();
        $form = $this->createForm(new BidType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bid_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Bid entity.
     *
     * @Route("/new", name="bid_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Bid();
        $form   = $this->createForm(new BidType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Bid entity.
     *
     * @Route("/{id}", name="bid_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Bid')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bid entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Bid entity.
     *
     * @Route("/{id}/edit", name="bid_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Bid')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bid entity.');
        }

        $editForm = $this->createForm(new BidType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Bid entity.
     *
     * @Route("/{id}", name="bid_update")
     * @Method("PUT")
     * @Template("TotoTotalizerBundle:Bid:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Bid')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bid entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BidType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bid_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Bid entity.
     *
     * @Route("/{id}", name="bid_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TotoTotalizerBundle:Bid')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bid entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bid'));
    }

    /**
     * Creates a form to delete a Bid entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}