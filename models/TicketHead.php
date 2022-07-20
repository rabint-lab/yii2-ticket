<?php

namespace rabint\ticket\models;

use \rabint\ticket\models;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use rabint\ticket\Module;

/**
 * This is the model class for table "ticket_head".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $department
 * @property string $topic
 * @property integer $status
 * @property string $date_update
 */
class TicketHead extends \yii\db\ActiveRecord
{

    public $user = false;

    /** @var  Module */
    private $module;

    /**
     * ticket status
     */
    const OPEN = 0;
    const WAIT = 1;
    const ANSWER = 2;
    const CLOSED = 3;
    const VIEWED = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ticket_head}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->module = Module::getInstance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'topic'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['date_update'], 'safe'],
            [['department', 'topic'], 'string', 'max' => 255],
            [['department', 'topic'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_update',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('rabint','شناسه'),
            'user_id'     => Yii::t('rabint','شناسه کاربر'),
            'department'  => Yii::t('rabint','واحد'),
            'topic'       => Yii::t('rabint','عنوان'),
            'status'      => Yii::t('rabint','وضعیت'),
            'date_update' => Yii::t('rabint','تاریخ بروزرسانی'),
        ];
    }

    /**
     *
     * @return ActiveDataProvider
     */
    public function dataProviderUser()
    {
        $query = TicketHead::find()->where("user_id = " . Yii::$app->user->id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_update' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => $this->module->pageSize
            ]
        ]);

        return $dataProvider;
    }

    /**
     *
     * @return ActiveDataProvider
     */
    public function dataProviderAdmin()
    {
        $query = TicketHead::find()->joinWith('userName');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_update' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => $this->module->pageSize
            ]
        ]);

        return $dataProvider;
    }

    public function getUserName()
    {
        $userModel = \Yii::$app->user->identityClass;
        return $this->hasOne($userModel, ['id' => 'user_id']);
    }

    public function getBody()
    {
        return $this->hasOne(TicketBody::className(), ['id_head' => 'id'])->orderBy('date DESC');
    }

    /**
     * @return int|string gect count new tickets
     */
    public static function getNewTicketCount()
    {
        return TicketHead::find()->where('status = 0 OR status = 1')->count();
    }

    /**
     *
     * @param int $status int get new ticket per user
     * @return int|string
     */
    public static function getNewTicketCountUser($status = 0)
    {
        return TicketHead::find()->where("status = $status AND user_id = " . Yii::$app->user->id . " ")->count();
    }

    /**
     *
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->user_id = ($this->user === false) ? Yii::$app->user->id : $this->user;
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $files = TicketFile::find()
            ->joinWith('idBody', false)
            ->where(['id_head' => $this->id])
            ->all();
        foreach($files as $file) {
            @unlink(Yii::getAlias($this->module->uploadFilesDirectory).'/'.$file->fileName);
            @unlink(Yii::getAlias($this->module->uploadFilesDirectory).'/reduced/'.$file->fileName);
        }

        return parent::beforeDelete();
    }

}
