<?php

namespace Toto\TotalizerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Toto\TotalizerBundle\Entity\Competition;
use Toto\TotalizerBundle\Form\CompetitionType;

/**
 * Competition controller.
 *
 * @Route("/competition")
 */
class CompetitionController extends Controller
{

    /**
     * Lists all Competition entities.
     *
     * @Route("/", name="competition")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->get('totalizer.competition_service')->getUserCompetitions();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Competition entity.
     *
     * @Route("/", name="competition_create")
     * @Method("POST")
     * @Template("TotoTotalizerBundle:Competition:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Competition();
        $form = $this->createForm(new CompetitionType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competition_show', array('slug' => $entity->getSlug())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Competition entity.
     *
     * @Route("/new", name="competition_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Competition();
        $form   = $this->createForm(new CompetitionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Competition stats
     *
     * @Route("/{slug}/stats", name="competition_stats")
     * @Method("GET")
     * @Template()
     */
    public function statsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Competition')->findOneBy(['slug' => $slug]);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }
        
       
        $bidService = $this->get('totalizer.bid_service');
        $stats = $bidService->getStats($entity->getId());
        
        return [
            'entity' => $entity,
            'stats' => $stats
        ];
    }
    
    
    /**
     * Competition update points
     *
     * @Route("/updatepoints", name="competition_updatepoints")
     * @Method("GET")
     * @Template()
     */
    public function updatePointsAction()
    {
        $bidService = $this->get('totalizer.bid_service');
        $bids = $bidService->getEmptyBids();
        
        if(!empty($bids)){
            foreach($bids as $bid){
                $points = $bidService->countPoints($bid);
                $bidService->updatePoints($bid['bid_id'], $points);
            }
        }
        
        echo 'done'; die;
    }
    
    /**
     *
     * @Route("/{slug}/{userId}", name="competition_user_stats")
     * @Template("TotoTotalizerBundle:Bid:competitionStats.html.twig")
     */
    public function competitionStatsAction($slug, $userId)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TotoTotalizerBundle:Competition')->findOneBy(['slug' => $slug]);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }

        $bids = $this->get('totalizer.bid_service')->getUserBidsByCompetition($entity->getId(), null, null, $userId);

        $user = $em->getRepository('TotoUserBundle:User')->find($userId);
        
        return array(
            'user' => $user,
            'bids' => $bids,
            'entity'      => $entity
        );
    }
    
    
    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{slug}", name="competition_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($slug)
    {
        $entity = $this->getCompetitionBySlug($slug);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Competition entity.
     *
     * @Route("/{id}/edit", name="competition_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Competition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }

        $editForm = $this->createForm(new CompetitionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Competition entity.
     *
     * @Route("/{id}", name="competition_update")
     * @Method("PUT")
     * @Template("TotoTotalizerBundle:Competition:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Competition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CompetitionType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competition_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Competition entity.
     *
     * @Route("/{id}", name="competition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TotoTotalizerBundle:Competition')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Competition entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competition'));
    }
    
    /**
     * Creates a form to delete a Competition entity by id.
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

    /**
     * Competition rules
     *
     * @Route("/{slug}/rules", name="competition_rules")
     * @Method("GET")
     * @Template()
     */
    public function rulesAction($slug)
    {
        $entity = $this->getCompetitionBySlug($slug);

        return [
            'entity' => $entity
        ];
    }

    /**
     * History
     *
     * @Route("/{slug}/history", name="competition_history")
     * @Method("GET")
     * @Template()
     */
    public function historyAction($slug)
    {
        $entity = $this->getCompetitionBySlug($slug);

        return [
            'entity' => $entity
        ];
    }

    protected function getCompetitionBySlug($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TotoTotalizerBundle:Competition')->findOneBy(['slug' => $slug]);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }

        return $entity;
    }
}
