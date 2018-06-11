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
     */
    public function addAction(Request $request)
    {

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
        /** @var Book $bookInfo */
        $bookInfo = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if ($bookInfo === null)
            return $this->redirectToRoute('book_list');

        /** @var File $cover */
        $cover = new File($this->getParameter('cover_directory').'/'.$bookInfo->getCover());
        $bookInfo->setCover($cover);

        $form = $this->createForm(BookType::class, $bookInfo);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid())
        {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            if (($file = $bookInfo->getCover()) !== null)
            {
                $fs = new Filesystem();
                $filePath = $this->getParameter('cover_directory').'/'.$bookInfo->getCover();
                $fs->remove($filePath);
                // Если не удалось удалить файл
                if ($fs->exists($filePath))
                    return $this->redirectToRoute('book_update', ['id' => $bookInfo->getId()]);

                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('cover_directory'), $fileName);
                $bookInfo->setCover($fileName);
            }
            else
                $bookInfo->setCover($cover->getFilename());

            $em = $this->getDoctrine()->getManager();
            $em->persist($bookInfo);
            $em->flush();

            return $this->redirectToRoute('book_update', ['id' => $bookInfo->getId()]);
        }
        $bookInfo->setCover($cover->getFilename());

        return $this->render('book/update.html.twig', ['bookInfo' => $bookInfo,
                                                       'form'     => $form->createView()]);
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
        /** @var Book $bookInfo */
        $bookInfo = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if ($bookInfo !== null)
        {
            $fs = new Filesystem();
            $filePath = $this->getParameter('cover_directory').'/'.$bookInfo->getCover();
            $fs->remove($filePath);
            // Если получилось удалить файл
            if (!$fs->exists($filePath))
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($bookInfo);
                $em->flush();
            }
        }

        return $this->redirectToRoute('book_list');
    }
}