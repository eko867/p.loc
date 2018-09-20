<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.09.18
 * Time: 9:43
 */

namespace Gallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Gallery\Form\AlbumForm;
use Gallery\Form\PhotoForm;
use Gallery\Entity\Album;
use Gallery\Entity\Author;


class AlbumController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Post manager.
     * @var Gallery\Service\AlbumManager
     */
    private $albumManager;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $albumManager)
    {
        $this->entityManager = $entityManager;
        $this->albumManager = $albumManager;
    }

    public function indexAction()
    {
        //экшн демонстрации списка альбомов
        //$albums = $this->entityManager->getRepository(Album::class)->findBy(['lastModifiedAt'=>'DateTime']);
        $albums = $this->entityManager->getRepository(Album::class)->findBy([],['id'=>'DESC']);
        return new ViewModel(['albums'=>$albums]);
    }

    public function createAction()
    {
        //экшн создания нового альбома
        //? не требует своего view
        $form = new AlbumForm();

        // Проверяем, отправил ли пользователь форму.
        if ($this->getRequest()->isPost()) {
            //выгружаем данные отправленные пользователем
            $request = $this->getRequest();
            //вытаскиваем данные отправленные через POST
            $data = $request->getPost();

            //Передаем данные в объект класса формы
            $form->setData($data);

            if ($form->isValid()) {
                //если форма валидная, то помещаем файл в каталог назначения
                //создаем объект через менеджера объектов
                //TODO : через bind & hydrator //

                $this->albumManager->createAlbum($data);

                return $this->redirect()->toRoute('albums');
            }
        }

        //если POST-отправки не было, то отдаем пользователю пустую форму
        return new ViewModel(['form'=>$form]);

    }

    public function editAction()
    {
        //экшн редактирования описания к альбому
        $form=new AlbumForm();

        //узнаем idAlbum для редактирования
        $idAlbum=$this->params()->fromRoute('idAlbum',-1);

        // Находим существующий альбом в базе данных.
        $album = $this->entityManager->getRepository(Album::class)->findOneById($idAlbum);
        if ($album == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // отправил ли юзер POST-запрос с формы
        if ($this->getRequest()->isPost()) {
            // Получаем POST-данные.
            $data = $this->params()->fromPost();
            // Заполняем форму данными.
            $form->setData($data);
            if ($form->isValid()) {
                // Получаем валидированные данные формы.
                $data = $form->getData();
                // Используем менеджер постов, чтобы добавить новый пост в базу данных.
                $this->albumManager->editAlbum($album, $data);

                // Перенаправляем пользователя на страницу c альбомами.
                return $this->redirect()->toRoute('albums');
            }
        } else { //иначе готовим форму для юзера, чтобы ее поля содержали данные альбома
            $data = [
                'label' => $album->getLabel(),
                'note' => $album->getNote(),
                'authorId' => $album->getAuthorName(),
            ];
            $form->setData($data);
        }

        // Визуализируем шаблон представления.
        return new ViewModel([
            'form' => $form,
            'post' => $album
        ]);
    }

    public function viewAction()
    {
        //экшн просмотра альбома или фотографии в альбоме
    }

    public function addPhotoAction() //TODO
    {
        //экшн добавления фотографии с первоначальным выбором альбома с пом.селектора
        //? не требует своего view
        $form=new PhotoForm();

        // Проверяем, отправил ли пользователь форму.
        if($this->getRequest()->isPost()){
            //выгружаем данные отправленные пользователем
            $request = $this->getRequest();
            //объединяем данные отправленные через POST и сам загруженный файл (_POST_ и _FILES_)
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            //Передаем данные в объект класса формы
            $form->setData($data);

            if ($form->isValid()){
                //если форма валидная, то помещаем файл в каталог назначения

                //потому что метод getData() запустит  для формы фильтр RenameUpload,
                // который перемещает выгруженный на сервер файл в его постоянный каталог.
                $form->getData();

                return $this->redirect()->toRoute('albums');
            }
        }

        //если POST-отправки не было, то отдаем пользователю пустую форму
        return new ViewModel(['form'=>$form]);
    }

    public function newPhotoAction() //TODO
    {
        //экшн добавления фотографии в выбранный альбом
        //узнаем idAlbum для редактирования
        $idAlbum=$this->params()->fromRoute('idAlbum',-1);
        // Находим существующий альбом в базе данных.
        $album = $this->entityManager->getRepository(Album::class)->findOneById($idAlbum);

        $form=new PhotoForm();

        // Проверяем, отправил ли пользователь форму.
        if($this->getRequest()->isPost()){
            //выгружаем данные отправленные пользователем
            $request = $this->getRequest();
            //объединяем данные отправленные через POST и сам загруженный файл (_POST_ и _FILES_)
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            //Еще можно получить один конкретный элемент массива $_FILES.
            //        $files = $this->params()->fromFiles('file');

            //Передаем данные в объект класса формы
            $form->setData($data);
            $form->isValid();

            if ($form->isValid()){//запускаем валидацию данных
                //если форма валидная, то помещаем файл в каталог назначения

                //потому что метод getData() запустит  для формы фильтр RenameUpload,
                // который перемещает выгруженный на сервер файл в его постоянный каталог.
                $data1=$form->getData();
                dump($data1);//мб там будет имя файлика после аплода?
                $this->albumManager->addPhoto($album,$data1);

                return $this->redirect()->toRoute('albums');
            }
        }
        //если POST-отправки не было, то отдаем пользователю пустую форму
        return new ViewModel(['form'=>$form]);
    }

    public function deleteAction()
    {
        //экшн удаления альбома или фотографии в альбоме
        //? не требует своего view
    }


}