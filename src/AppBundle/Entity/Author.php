<?php
/**
 * Created by PhpStorm.
 * User: AbdalovAR
 * Date: 08.06.2018
 * Time: 22:23
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class Author
{
    private $id;
    private $fio;
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fio
     *
     * @param string $fio
     *
     * @return Author
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Сокращенный формат фамилия и инициалы
     * @return string
     */
    public function getFioShort()
    {
        $fio = '';
        $tmp = explode(' ', $this->getFio());
        if (isset($tmp[0]))
        {
            $fio .= $tmp[0];
            if (isset($tmp[1]))
            {
                $fio .= ' '.mb_substr($tmp[1], 0 , 1).'.';
                if (isset($tmp[2]))
                    $fio .= ' '.mb_substr($tmp[2], 0, 1).'.';
            }
        }

        return $fio;
    }

    /**
     * Add book
     *
     * @param \AppBundle\Entity\Book $book
     *
     * @return Author
     */
    public function addBook(\AppBundle\Entity\Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \AppBundle\Entity\Book $book
     */
    public function removeBook(\AppBundle\Entity\Book $book)
    {
        $this->books->removeElement($book);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks()
    {
        return $this->books;
    }
}
