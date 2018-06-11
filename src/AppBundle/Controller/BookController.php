<?php
/**
 * Created by PhpStorm.
 * User: AbdalovAR
 * Date: 09.06.2018
 * Time: 15:29
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * Список книг
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $bookList = $this->getDoctrine()->getRepository('AppBundle:Book')->findAll();

        return $this->render('book/index.html.twig', ['bookList' => $bookList]);
    }

    /**
     * Добавление книги
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid())
        {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            if (($file = $book->getCover()) !== null)
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('cover_directory'), $fileName);
                $book->setCover($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_update', ['id' => $book->getId()]);
        }

        return $this->render('book/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Просмотр книги
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);

        return $this->render('book/view.html.twig', ['book' => $book]);
    }

    /**
     * Редактирвоание книги
     *
     * @param         $id
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id, Request $request)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if ($book === null)
            return $this->redirectToRoute('book_list');

        if ($book->getCover())
        {
            /** @var File $cover */
            $cover = new File($this->getParameter('cover_directory').'/'.$book->getCover());
            $book->setCover($cover);
        }
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid())
        {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            if (($file = $book->getCover()) !== null)
            {
                // Если уже был загруженный файл
                $fs = new Filesystem();
                $filePath = $this->getParameter('cover_directory').'/'.$book->getCover();
                $fs->remove($filePath);
                // Если не удалось удалить файл
                if ($fs->exists($filePath))
                    return $this->redirectToRoute('book_update', ['id' => $book->getId()]);

                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('cover_directory'), $fileName);
                $book->setCover($fileName);
            }
            elseif (isset($cover))
                $book->setCover($cover->getFilename());

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_update', ['id' => $book->getId()]);
        }

        if (isset($cover))
            $book->setCover($cover->getFilename());

        return $this->render('book/update.html.twig', ['book' => $book,
                                                       'form' => $form->createView()]);
    }

    /**
     * Удаление книги
     *
     * @param      $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if ($book !== null)
        {
            $fs = new Filesystem();
            $filePath = $this->getParameter('cover_directory').'/'.$book->getCover();
            $fs->remove($filePath);
            // Если получилось удалить файл
            if (!$fs->exists($filePath))
            {
                $em = $this->getDoctrine()->getManager();
                $em->remove($book);
                $em->flush();
            }
        }

        return $this->redirectToRoute('book_list');
    }
}