<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Data;

/**
 * Age controller.
 *
 * @Route("/data")
 */
class DataController extends Controller
{

    /**
     * Lists all Age entities.
     *
     * @Route("/", name="admin_data")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        //Flashbag: value 1 is stored just after user logged in in LoginLister.php
        //1 - just logged in
        //2 - already logged in (or to not repeat Welcome message again and again)
        if(isset($_SESSION['login_success'])) {
            if($_SESSION["login_success"] == "1") {
                $this->get('session')->getFlashBag()->add('success', 'Welcome!');
                $_SESSION["login_success"] = "2";
            }
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Data')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Age entity.
     *
     * @Route("/store", name="data_create")
     * @Method("POST")
     * @Template("AppBundle:Data:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        //Save File
        $file = $request->files->get('file');
        $fileName = time() ."-". $file->getClientOriginalName();
        $fileName = str_replace(' ', '-', $fileName);
        $image = '/data_files/' .$fileName;
        $upload_success= $file->move('data_files', $fileName);

        //Save to database
        $data = new Data();
        $data->setTitle($request->request->get('title'));
        $data->setDescription($request->request->get('description'));
        $data->setFile($fileName);
        $data->setYear($request->request->get('year'));
        $data->setMonth($request->request->get('month'));

        // tell Doctrine you want to (eventually) save the data (no queries yet)
        $entityManager->persist($data);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirect($this->generateUrl('admin_data'));
    }

    /**
     * Creates a form to create a Age entity.
     *
     * @param Age $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Data $entity)
    {
        $form = $this->createForm(new Data(), $entity, array(
            'action' => $this->generateUrl('data_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create',
            'attr' => array( 'class' => 'btn btn-xs btn-success' )
            ));

        return $form;
    }

    /**
     * Displays a form to create a new Data entity.
     *
     * @Route("/new", name="data_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Data();
        // $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            // 'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Age entity.
     *
     * @Route("/{id}", name="age_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Age')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Age entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Age entity.
     *
     * @Route("/{id}/edit", name="data_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Data')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Data entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
    * Creates a form to edit a Age entity.
    *
    * @param Data $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Data $entity)
    {
        $form = $this->createForm(new AgeType(), $entity, array(
            'action' => $this->generateUrl('age_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update',
            'attr' => array( 'class' => 'btn btn-xs btn-success' )
            ));

        return $form;
    }
    /**
     * Edits an existing Data entity.
     *
     * @Route("/{id}", name="data_update")
     * @Method("PUT")
     * @Template("AppBundle:Data:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = $entityManager->getRepository(Data::class)->find($id);

        if (!$data) {
            throw $this->createNotFoundException(
                'No data found for id '.$id
            );
        }

        //Save File
        $file = $request->files->get('file');
        if(!empty($file)) {
            $fileName = time() ."-". $file->getClientOriginalName();
            $fileName = str_replace(' ', '-', $fileName);
            $image = '/data_files/' .$fileName;
            $upload_success= $file->move('data_files', $fileName);

            $data->setFile($fileName);
        }

        //Save data
        $data->setTitle($request->request->get('title'));
        $data->setDescription($request->request->get('description'));
        $data->setYear($request->request->get('year'));
        $data->setMonth($request->request->get('month'));
        $entityManager->flush();

        return $this->redirect($this->generateUrl('admin_data'));

    }

    /**
     * Deletes a Age entity.
     *
     * @Route("/{id}", name="data_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Data')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Data entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_data'));
    }

}
