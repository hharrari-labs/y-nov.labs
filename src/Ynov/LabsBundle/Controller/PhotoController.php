<?php

namespace Ynov\LabsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ynov\LabsBundle\Entity\Photo;
use Ynov\LabsBundle\Form\PhotoType;

/**
 * Photo controller.
 *
 */
class PhotoController extends Controller
{

    /**
     * Lists all Photo entities.
     *
     */
    public function indexAction()
    {
        if(!$this->isAdmin()){
             return $this->redirect($this->generateUrl('accueil'));
        }
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('YnovLabsBundle:Photo')->findAll();

        return $this->render('YnovLabsBundle:Photo:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Photo entity.
     *
     */
    public function createAction(Request $request)
    {
        if(!$this->isAdmin()){
             return $this->redirect($this->generateUrl('accueil'));
        }
        $entity = new Photo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('photo_show', array('id' => $entity->getId())));
        }

        return $this->render('YnovLabsBundle:Photo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Photo entity.
    *
    * @param Photo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     */
    public function newAction()
    {
        if(!$this->isAdmin()){
             return $this->redirect($this->generateUrl('accueil'));
        }
        $entity = new Photo();
        $form   = $this->createCreateForm($entity);

        return $this->render('YnovLabsBundle:Photo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Photo entity.
     *
     */
    public function showAction($id)
    {
        if(!$this->isAdmin()){
             return $this->redirect($this->generateUrl('accueil'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YnovLabsBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YnovLabsBundle:Photo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Photo entity.
     *
     */
    public function editAction($id)
    {
        if(!$this->isAdmin()){
             return $this->redirect($this->generateUrl('accueil'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YnovLabsBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('YnovLabsBundle:Photo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Photo entity.
    *
    * @param Photo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Photo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        if(!$this->isAdmin()){
             return $this->redirect($this->generateUrl('accueil'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('YnovLabsBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('photo_edit', array('id' => $id)));
        }

        return $this->render('YnovLabsBundle:Photo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Photo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        
//        $form = $this->createDeleteForm($id);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('YnovLabsBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $em->remove($entity);
        $em->flush();
//        }

        return $this->redirect($this->generateUrl('projet_edit', array('id' => $this->getRequest()->getSession()->get('projet'))));
    }

    /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('photo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
