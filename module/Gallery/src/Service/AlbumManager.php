<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.09.18
 * Time: 9:22
 */

namespace Gallery\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Gallery\Entity\Album;
use Gallery\Entity\Author;
use Gallery\Entity\Photo;
use Zend\Filter\StaticFilter;

class AlbumManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

    /**
     * Директория для загрузки фотографий
     */
    private $uploadDir='./data/upload/';

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAlbum($data)
    {
        /*
         * Дано
         * input - данные из формы
         * Надо
         * Создать автора и альбом
         * Заполнить сушности данными input
         * Указать в альбоме автора
         * Сохраниться
         */
        //создаем новый альбом по данным формы и добавляем его в БД
        $author=new Author();
        $album=new Album();

        $author->setName($data['authorName']);
        $author->setEmail($data['email']);
        $author->setPhone($data['phone']);


        $album->setLabel($data['label']);
        $album->setNote($data['note']);
        $album->setAuthor($author);
        $album->setLastModifiedAt($album->getCreatedAt());

        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($author);
        $this->entityManager->persist($album);

        // Применяем изменения к БД.
        $this->entityManager->flush();
    }

    public function getUploadedFiles()
    {

        // Просматриваем каталог и создаем список выгруженных файлов.
        $files = [];
        $handle  = opendir($this->saveToDir);
        while (($entry = readdir($handle)) !== false) {
            if($entry=='.' || $entry=='..')
                continue; // Пропускаем текущий и родительский каталоги.
            $files[] = $entry; //остальные файлы вносим в список
        }

        //список файлов
        return $files;
    }

    public function getImagePathByFilename($filename)
    {
        //для безопасности удаляем слеши / \ из имени файла
        $filename = str_replace("/", "", $filename);
        $filename = str_replace("\\", "", $filename);
        //возращаем путь для загрузки файла
        return $this->uploadDir.$filename;
    }

    public function getImageFileContent($filepath)
    {
        // Возвращает содержимое файла изображения. При ошибке возвращает булевое false.
        return file_get_contents($filepath);
    }

    public function addPhoto($album,$data)
    {
        $photo=new Photo();
        $photo->setTitle($data['title']);
        $photo->setGeo($data['geo']);
        $photo->setFilepath('srccc');//?
        $photo->setAlbum($album);

        $album->setPhoto($photo);

        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($photo);

        // Применяем изменения к БД.
        $this->entityManager->flush();

    }


    public function editAlbum($album,$data)
    {
        $album->setLabel($data['label']);
        $album->setNote($data['note']);
        $album->setAuthorId('777');
        $album->setLastModifiedAt(date("Y-m-d H:i:s"));

        // Применяем изменения к БД //persist не нужен, т.к. сущность уже существующая
        $this->entityManager->flush();
    }

    public function deleteAlbum()
    {

    }
}