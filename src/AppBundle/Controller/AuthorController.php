<?php
/**
 * Created by PhpStorm.
 * User: AbdalovAR
 * Date: 11.06.2018
 * Time: 23:54
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller
{
    /**
     * Список авторов
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $authorList = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();

        return $this->render('author/index.html.twig', ['authorList' => $authorList]);
    }

    /**
     * Добавление автора
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('author_update', ['id' => $author->getId()]);
        }

        return $this->render('author/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Редактирование автора
     *
     * @param         $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id, Request $request)
    {
        /** @var Author $author */
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($id);
        if ($author === null)
            return $this->redirectToRoute('author_list');

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('author_update', ['id' => $author->getId()]);
        }

        return $this->render('author/update.html.twig', ['author' => $author,
                                                         'form'   => $form->createView()]);
    }

    /**
     * Удаление автора
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        /** @var Author $author */
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($id);
        if ($author !== null)
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($author);
            $em->flush();
        }

        return $this->redirectToRoute('author_list');
    }
}