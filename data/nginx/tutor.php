<?php

class m150101_185401_create_news_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
        ]);

        $this->insert('news', [
            'title' => 'test 1',
            'content' => 'content 1',
        ]);
    }

    public function safeDown()
    {
        $this->delete('news', ['id' => 1]);
        $this->dropTable('news');
    }
}



if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    // valid data received in $model

    // do something meaningful here about $model ...

    return $this->render('hourns', ['model' => $model]);
} else {
    // either the page is initially displayed or there is some validation error
    return $this->render('gate', ['model' => $model]);
}


yii migrate/create add_position_column_to_post_table --fields="position:integer"


use yii\base\Security;
use yii\db\Migration;

class WhateverMigrationName extends Migration
{
    private Security $security;
    public function __construct(Security $security, $config = [])
    {
        parent::__construct($config);
        $this->security = $security;
    }

    public function safeUp()
    {
        // ...
        $this->batchInsert(
            'users',
            ['first_name', 'last_name', 'password', ...],
            [
                ['John', 'Doe', $this->security->generatePasswordHash('mySecretPassword'), ...],
                ['Jane', 'Doe', $this->security->generatePasswordHash('anotherPassword'), ...],
            ]
        );
        // ...
    }

    // ...
}

// returns an active customer whose ID is 123
// SELECT * FROM `customer` WHERE `id` = 123 AND `status` = 1
$customer = Customer::findOne([
    'id' => 123,
    'status' => Customer::STATUS_ACTIVE,
]);

book
+------------------+--------------+------+-----+---------+----------------+
	  | Field            | Type         | Null | Key | Default | Extra          |
+------------------+--------------+------+-----+---------+----------------+
	  | id               | int(11)      | NO   | PRI | NULL    | auto_increment |
	  | title            | varc har(200)| YES  |     | NULL    |                |
	  | publication_date | varchar(200) | YES  |     | NULL    |                |
+------------------+--------------+------+-----+---------+----------------+
	    author
-----+---------+--------------+------+-----+---------+----------------+
	  | Field   | Type         | Null | Key | Default | Extra          |
+---------+--------------+------+-----+---------+----------------+
	  | id      | int(11)      | NO   | PRI | NULL    | auto_increment |
	  | book_id | int(11)      | YES  | MUL | NULL    |                |
	  | country | varchar(200) | YES  |     | NULL    |                |
	  | name    | varchar(200) | YES  |     | NULL    |                |
+---------+--------------+------+-----+---------+----------------+
	    genre;
+----------+--------------+------+-----+---------+----------------+
	  | Field    | Type         | Null | Key | Default | Extra          |
+----------+--------------+------+-----+---------+----------------+
	  | id       | int(11)      | NO   | PRI | NULL    | auto_increment |
	  | title    | varchar(200) | YES  |     | NULL    |                |
	  | gbook_id | int(11)      | YES  | MUL | NULL    |                |
+----------+--------------+------+-----+---------+----------------+


	    public function behaviors()
{
    return [
        [
            'class' => TimestampBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
            // if you're using datetime instead of UNIX timestamp:
            // 'value' => new Expression('NOW()'),
        ],
    ];
}

$user = new User;
$user->name = 'Foo';
$user->save();

$market = new Market;
$market->name = 'Bar';
$market->save();

$user->link('markets', $market);


return $this->redirect(['view', 'id' => $model->id]);


<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'columns'=>[
	[
            'attribute'=>'author.name',
            'value'=>function ($model, $key, $index, $column) {
		return $model->getAuthor();
            },
	],
	//...other columns
]);
		    ?>


    public function search($params){
	$query = MyModel::find();

	$dataProvider = new ActiveDataProvider([
            'query' => $query,
	]);

	if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
	}

	$this->addCondition($query, 'att1');
	$this->addCondition($query, 'att1', true);
	$this->addCondition($query, 'att2');
	$this->addCondition($query, 'att2', true);

	return $dataProvider;
    }

    $command = Yii::app()->db->createCommand();

    $last_id = Yii::app()->db->getLastInsertID(();

	$posts = Yii::$app->db->createCommand('SELECT * FROM post')->queryAll();

	$post = Yii::$app->db->createCommand('SELECT * FROM post WHERE id=:id AND status=:status')
		   ->bindValue(':id', $_GET['id'])
		   ->bindValue(':status', 1)
		   ->queryOne();


	SELECT book.id FROM book INNER JOIN genre ON genre.book_id = book.id WHERE genre.title like '%oror';

        
	select * from book where book.title = 'Dark Knight';

	$url = Url::toRoute(['product/view', 'id' => 42]);


	$session = Yii::$app->session;

	// check if a session is already open
	if ($session->isActive) ...

	// open a session
	$session->open();

	// close a session
	$session->close();

	// destroys all data registered to a session.
	$session->destroy();

	$session = Yii::$app->session;

	// get a session variable. The following usages are equivalent:
	$language = $session->get('language');
	$language = $session['language'];
	$language = isset($_SESSION['language']) ? $_SESSION['language'] : null;

	// set a session variable. The following usages are equivalent:
	$session->set('language', 'en-US');
	$session['language'] = 'en-US';
	$_SESSION['language'] = 'en-US';

	// remove a session variable. The following usages are equivalent:
	$session->remove('language');
	unset($session['language']);
	unset($_SESSION['language']);

	// check if a session variable exists. The following usages are equivalent:
	if ($session->has('language')) ...
	if (isset($session['language'])) ...
	if (isset($_SESSION['language'])) ...

	// traverse all session variables. The following usages are equivalent:
	foreach ($session as $name => $value) ...
	foreach ($_SESSION as $name => $value) ...


  $query = Country::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $countries = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'countries' => $countries,
            'pagination' => $pagination,
        ]);                                               
