<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends Controller
{
    /**
     * @Route("/", name="todo_list")
     */
    public function listAction()
    {
        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAll();

        return $this->render('todo/index.html.twig', array(
            'todos' => $todos
        ));
    }

    /**
     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo();

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin: 0 0 0 15px'
                )
            ))
            ->add('category', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin: 0 0 0 15px'
                )
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin: 0 0 0 15px'
                )
            ))
            ->add('priority', ChoiceType::class, array(
                'choices' => array(
                    'Low' => 'Low',
                    'Normal' => 'Normal',
                    'High' => 'High'
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin: 0 0 0 15px'
                )
            ))
            ->add('due_date', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'formcontrol',
                    'style' => 'margin: 0 0 0 15px'
                )
            ))
            ->add('Save', SubmitType::class, array(
                'label' => 'Create ToDo',
                'attr' => array(
                    'class' => 'btn btn-primary',
                    'style' => 'margin: 0 0 0 15px'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();

            $now = new\DateTime('now');

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash('notice', 'ToDo Added');

            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction(Todo $todo, Request $request)
    {
        $form = $this->createFormBuilder($todo)

        ->add('name', TextType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'style' => 'margin: 0 0 0 15px'
            )
        ))
        ->add('category', TextType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'style' => 'margin: 0 0 0 15px'
            )
        ))
        ->add('description', TextareaType::class, array(
            'attr' => array(
                'class' => 'form-control',
                'style' => 'margin: 0 0 0 15px'
            )
        ))
        ->add('priority', ChoiceType::class, array(
            'choices' => array(
                'Low' => 'Low',
                'Normal' => 'Normal',
                'High' => 'High'
            ),
            'attr' => array(
                'class' => 'form-control',
                'style' => 'margin: 0 0 0 15px'
            )
        ))
        ->add('due_date', DateTimeType::class, array(
            'attr' => array(
                'class' => 'formcontrol',
                'style' => 'margin: 0 0 0 15px'
            )
        ))
        ->add('Save', SubmitType::class, array(
            'label' => 'Update ToDo',
            'attr' => array(
                'class' => 'btn btn-primary',
                'style' => 'margin: 0 0 0 15px'
            )
        ))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();

            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em->flush();

            $this->addFlash('notice', 'ToDo Updated');

            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/todo/details/{id}", name="todo_details")
     */
    public function detailsAction(Todo $todo)
    {
        return $this->render('todo/details.html.twig', array(
            'todo' => $todo
        ));
    }

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction(Todo $todo)
    {
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($todo);
//        $em->flush();

        $this->addFlash('notice', 'ToDo Removed');

        return $this->redirectToRoute('todo_list');
    }
}
