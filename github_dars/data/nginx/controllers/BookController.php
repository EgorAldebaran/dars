<?php

namespace app\controllers;

use Yii;

use app\models\Book;
use app\models\Author;
use app\models\Genre;
use app\models\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\base\Security;
use yii\data\Pagination;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * точка входа для приложения
     * в базе есть два Субъекта - Клиент и Админ  
     * если зарегестрировавшийся Админ - то его отправляет на CRUD
     * если Клиент - то его отправляет на Поисковик 
     * при других вариантах - пишется сообщение об ошибке и повторный ввод  
     */
    public function actionEntry()
    {
        $model = new User();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            /// получаем данные по HTTP с формы
            $data = Yii::$app->request->post();
            /// выделяем емайл
            $email = $data['User']['email'];
            /// выделяем пароль
            $password = $data['User']['password'];
            /// получаем юзера по email данному
            $userbase = User::findOne(['email' => $email]);
            if (!$userbase) {
                $message = 'Такого пользователя в базе не зарегистрировано! Доступ запрещен!';
                return $this->render('entry_form.php', [
                    'model' => $model,
                    'message' => $message
                ]);
            }

            $hash = $userbase->password;
            $message = 'Неправильно введен пароль! Доступ запрещен!';
            /// проверяем хеш пароля из базы и введенный пользователем средствами Yii
            if (!Yii::$app->getSecurity()->validatePassword($password, $hash)) {
                return $this->render('entry_form.php', [
                    'model' => $model,
                    'message' => $message
                ]);
            }

            /// если прошедший все проверки пользователь админ - то
            if ($userbase->roles == 'ADMIN') {

                /// установить сессию для админа
                $sessionAdmin = Yii::$app->session->set('isAdmin', 'admin');

                return $this->redirect(['index']);
            }
            //// в другом случае отправляется к поисковику
            //// значит это клиент установим сессию для клиента
            $sessionClient = Yii::$app->session->set('isAdmin', 'client');

            return $this->redirect(['search']);
            
        } else {
            //// это случаи неправильного доступа
            $message = '';
            
            return $this->render('entry_form', [
                'model' => $model,
                'message' => $message
            ]);
        }
    }

    /**
     * поисковая часть для клиента
     * также отображение всех имеющихся книг с авторами
     * и демонстрация использования пагинации
     */
    public function actionSearch()
    {
        $query = Book::find();

        $pagination = new Pagination([
            'defaultPageSize' => 4,
            'totalCount' => $query->count(),
        ]);

        $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();

        $message = '';
        return $this->render('search', [
            'books' => $books,
            'pagination' => $pagination,
            'message' => $message,
        ]);
    }
    
    /**
     *
     *   поиск по названию книги
     *
     */
    public function actionBook()
    {
        if (Yii::$app->request->get()) {
            /// теперь нужно отправить эти данные в каком-либо виде в базу данных и препарировать их соответственно
            /// сначала данные просто отобразить
            $_data = Yii::$app->request->get();
            $data = $_data['search'];

            ///пусть всегда будет с большой буквы
            $data = ucfirst($data);

            /// если пользователь не ввел никаких данных
            if (!$data) {
                $query = Book::find();

                $pagination = new Pagination([
                    'defaultPageSize' => 4,
                    'totalCount' => $query->count(),
                ]);

                $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
                
                $message = 'нужно ввести что-либо в поле поиска!';
                return $this->render('search', [
                    'books' => $books,
                    'pagination' => $pagination,
                    'message' => $message,
                ]);
            }

            /// проверка есть ли такое значение в базе данных
            if (Yii::$app->db->createCommand("SELECT * FROM book WHERE book.title =:title")->bindValue(':title', $data)->queryOne()) {
                /// в случае нахождения
                $id = Yii::$app->db->createCommand("SELECT * FROM book WHERE book.title =:title")->bindValue(':title', $data)->queryOne();
                /// использую ActiveRecord
                $books = Book::find()->where(['id' => $id])->all();
                
                return $this->render('client_view.php', [
                    'books' => $books,
                ]);
                
            } else {
                $query = Book::find();

                $pagination = new Pagination([
                    'defaultPageSize' => 4,
                    'totalCount' => $query->count(),
                ]);

                $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
                
                $message = 'В базе данных такого нет!';
                return $this->render('search', [
                    'books' => $books,
                    'pagination' => $pagination,
                    'message' => $message,
                ]);
            }
        } else {
            $query = Book::find();

            $pagination = new Pagination([
                'defaultPageSize' => 4,
                'totalCount' => $query->count(),
            ]);

            $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
            /// если пользователь еще не ввел никаких данных
            $message = '';
            return $this->render('search', [
                'books' => $books,
                'pagination' => $pagination,
                'message' => $message,
            ]);
        }
    }


    /**
     *
     *   поиск по Автору
     *
     */
    public function actionAuthor()
    {
        if (Yii::$app->request->get()) {
            /// теперь нужно отправить эти данные в каком-либо виде в базу данных и препарировать их соответственно
            /// сначала данные просто отобразить
            $_data = Yii::$app->request->get();
            $data = $_data['search'];

            ///пусть всегда будет с большой буквы
            $data = ucfirst($data);

            /// если пользователь не ввел никаких данных
            if (!$data) {
                $query = Book::find();

                $pagination = new Pagination([
                    'defaultPageSize' => 4,
                    'totalCount' => $query->count(),
                ]);

                $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
                
                $message = 'нужно ввести что-либо в поле поиска!';
                return $this->render('search', [
                    'books' => $books,
                    'pagination' => $pagination,
                    'message' => $message,
                ]);
            }

            /// проверка есть ли такое значение в базе данных
            if (Yii::$app->db->createCommand("SELECT id FROM author WHERE author.name =:name")->bindValue(':name', $data)->queryOne()) {
                /// в случае нахождения
                $id = Yii::$app->db->createCommand("SELECT id FROM author WHERE author.name =:name")->bindValue(':name', $data)->queryOne();
                /// использую ActiveRecord
                $model = Author::find()->where(['id' => $id])->one();

                /// так как автор всегда один - можно испьзовать виджет для детализации
                return $this->render('author_view.php', [
                    'model' => $model,
                ]);
                
            } else {
                $query = Book::find();

                $pagination = new Pagination([
                    'defaultPageSize' => 4,
                    'totalCount' => $query->count(),
                ]);

                $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();

                $message = 'В базе данных такого нет!';
                return $this->render('search', [
                    'books' => $books,
                    'pagination' => $pagination,
                    'message' => $message,
                ]);
            }
        } else {
            /// если пользователь еще не ввел никаких данных
            $query = Book::find();

            $pagination = new Pagination([
                'defaultPageSize' => 4,
                'totalCount' => $query->count(),
            ]);

            $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
            
            $message = '';
            return $this->render('search', [
                'books' => $books,
                'pagination' => $pagination,
                'message' => $message,
            ]);
        }
    }

    /**
     *
     *   поиск по жанрам
     *
     */
    public function actionGenre()
    {
        if (Yii::$app->request->get()) {
            /// теперь нужно отправить эти данные в каком-либо виде в базу данных и препарировать их соответственно
            /// сначала данные просто отобразить
            $_data = Yii::$app->request->get();
            $data = $_data['search'];

            ///пусть всегда будет с большой буквы
            $data = ucfirst($data);

            /// если пользователь не ввел никаких данных
            if (!$data) {
                $query = Book::find();

                $pagination = new Pagination([
                    'defaultPageSize' => 4,
                    'totalCount' => $query->count(),
                ]);

                $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
                
                $message = 'нужно ввести что-либо в поле поиска!';
                return $this->render('search', [
                    'books' => $books,
                    'pagination' => $pagination,
                    'message' => $message,
                ]);
            }

            /// проверка есть ли такое значение в базе данных
            if (Yii::$app->db->createCommand("SELECT book.id FROM book INNER JOIN genre ON genre.book_id = book.id WHERE genre.title =:title")->bindValue(':title', $data)->queryOne()) {
                /// в случае нахождения
                $id = Yii::$app->db->createCommand("SELECT book.id FROM book INNER JOIN genre ON genre.book_id = book.id WHERE genre.title =:title")->bindValue(':title', $data)->queryAll();
                /// использую ActiveRecord
                $books = Book::find()->where(['id' => $id])->all();
                
                return $this->render('client_view.php', [
                    'books' => $books,
                ]);
                
            } else {
                $query = Book::find();

                $pagination = new Pagination([
                    'defaultPageSize' => 4,
                    'totalCount' => $query->count(),
                ]);

                $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
                
                $message = 'В базе данных такого нет!';
                return $this->render('search', [
                    'books' => $books,
                    'pagination' => $pagination,
                    'message' => $message,
                ]);
            }
        } else {
            $query = Book::find();

            $pagination = new Pagination([
                'defaultPageSize' => 4,
                'totalCount' => $query->count(),
            ]);

            $books = $query->orderBy('title')->offset($pagination->offset)->limit($pagination->limit)->all();
            /// если пользователь еще не ввел никаких данных
            $message = '';
            return $this->render('search', [
                'books' => $books,
                'pagination' => $pagination,
                'message' => $message,
            ]);
        }
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        /**
         * если это не админ то не пускаем - возвращаем в поисковик
         */
        if (Yii::$app->session->get('isAdmin') !== 'admin') {
            $message = 'доступ не администратору запрещен!';
            return $this->render('search', [
                'message' => $message
            ]);
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /**
         * если это не админ то не пускаем - возвращаем в поисковик
         */
        if (Yii::$app->session->get('isAdmin') !== 'admin') {
            $message = 'доступ не администратору запрещен!';
            return $this->render('search', [
                'message' => $message
            ]);
        }
        
        $model = new Book;
        $author = new Author;
        $genre = new Genre;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $data = Yii::$app->request->post();
            $name = $data['Book']['title'];
            $authorName = $data['Book']['authorBook'];
            $bookGenre = $data['Book']['genreBook'];
            $authorCountry = $data['Book']['authorCountry'];

            ///пусть всегда сохраняется с большой буквы - независимо от того как ввели
            $name = ucfirst($name);
            $authorName = ucfirst($authorName);
            $bookGenre = ucfirst($bookGenre);
            $authorCountry = ucfirst($authorCountry);

            /// достаем из базы последний id Author
            $lastIdCommand = Yii::$app->db->createCommand('SELECT MAX(id) FROM author')->queryAll();
            $lastId = $lastIdCommand[0]['MAX(id)'];
            /// инкрементирую id
            $lastId++;

            /**
             * если автор такой не существет (по имени смотрим) то инкрементируем последний id 
             *   и связываем с Book и записываем все в базу
             *
             */
            $isAuthorExists = Book::checkAuthor($authorName);
            if (!$isAuthorExists) {

                /// последовательность важна - так как Автор - это Система, а модель - это зависимые элементы
                $author->name = $authorName;
                $author->country = $authorCountry;
                $author->save();

                $model->title = $name;
                $model->author_id = $lastId;
                $model->save();
                            
                $genreCommand = Yii::$app->db->createCommand('SELECT MAX(id) FROM book')->queryAll();
                $genre->book_id = $genreCommand[0]['MAX(id)']++;
                $genre->title = $bookGenre;
                $genre->save();

                $model->link('author', $model);

                return $this->redirect('index');
            }

            /// если такой автор существует
            $model->title = $name;
            $model->author_id = $isAuthorExists;
            $model->save();

            $genre->book_id = $model->id;
            $genre->title = $bookGenre;
            $genre->save();

            return $this->redirect('index');

        } else {
            $message = '';
            return $this->render('create', [
                'model' => $model,
                'message' => $message,
            ]);
        }
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /**
         * если это не админ то не пускаем - возвращаем в поисковик
         */
        if (Yii::$app->session->get('isAdmin') !== 'admin') {
            $message = 'доступ не администратору запрещен!';
            return $this->render('search', [
                'message' => $message
            ]);
        }
        
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        /**
         * если это не админ то не пускаем - возвращаем в поисковик
         */
        if (Yii::$app->session->get('isAdmin') !== 'admin') {
            $message = 'доступ не администратору запрещен!';
            return $this->render('search', [
                'message' => $message
            ]);
        }
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     *
     *   все содержимое книг
     *
     */
    public function actionIndex()
    {
        /**
         * если это не админ то не пускаем - возвращаем в поисковик
         */
        if (Yii::$app->session->get('isAdmin') !== 'admin') {
            $message = 'доступ не администратору запрещен!';
            return $this->render('search', [
                'message' => $message
            ]);
        }

        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
