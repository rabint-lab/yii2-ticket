<?php

namespace rabint\ticket;

use rabint\ticket\models\TicketHead;
use Yii;

/**
 * ticket module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rabint\ticket\controllers';

    /** @var bool Уведомление на почту о тикетах */
    public $mailSend = false;

    /** @var string Тема email сообщения когда пользователю приходит ответ */
    public $subjectAnswer = 'پاسخ به تیکت پشتیبانی ';

    public $userModel = false;

    public $qq = [
        'support' => 'واحد پشتیبانی',
    ];

    /** @var array ID администраторов */
    public $adminId = [];

    /** @var string  */
    public $uploadFilesDirectory = '@webroot/fileTicket';

    /** @var string  */
    public $uploadFilesExtensions = 'png, jpg';

    /** @var int  */
    public $uploadFilesMaxFiles = 5;

    /** @var null|int */
    public $uploadFilesMaxSize = null;

    /** @var bool|int */
    public $pageSize = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    public static function adminMenu()
    {
        $count = TicketHead::find()->where(['status'=>0])->count();
        return [
            'label' => Yii::t('rabint', 'تیکت ها').'<span class="badge '.($count == 0?'badge-success':'badge-warning').' ">'.$count.'</span>',
            'url' => '#',
            'visible' => \rabint\helpers\user::can('manager'),
            'icon' => '<i class="fa fa-credit-card"></i>',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => Yii::t('rabint', 'لیست تیکت ها'),
                    'url' => ['/ticket/admin'],
                    'icon' => '<i class="far fa-circle"></i>',
                ]
            ]
        ];
    }
    
    public static function dashboardMenu()
    {
        $cash = \rabint\finance\models\FinanceWallet::cash(\rabint\helpers\user::id());
        return [
            'label' => Yii::t('rabint', 'پشتیبانی'),
            'url' => '#',
            'hit' => \Yii::t('app', 'جهت ارتباط با بخش فروش و پشتیبانی از این قسمت استفاده نمایید.'),

            'icon' => '<i class="fa fa-credit-card"></i>',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => Yii::t('rabint', 'فهرست تیکت ها'),
                    'url' => ['/ticket/ticket'],
                    'icon' => '<i class="far fa-circle"></i>',
                ],
                [
                    'label' => Yii::t('rabint', 'ایجاد تیکت جدید'),
                    'url' => ['/ticket/ticket/open'],
                    'icon' => '<i class="far fa-circle"></i>',
                ],
            ]
        ];
    }
    
}
