<?php
use rabint\helpers\user;
/** @var \rabint\ticket\models\TicketHead $dataProvider */
?>

<div class="panel page-block">
    <div class="container-fluid row">
    <a href="<?= \yii\helpers\Url::toRoute(['admin/open']) ?>" class="btn btn-primary" style="margin-left: 15px">ثبت جدید</a>
    <br><br>
    <div class="container-fluid">
        <div class="col-md-12">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns'      => [
                    [
                        'label' =>'نام کاربری',
                        'attribute' => 'username',
                        'value'     => function($model){
                            return $model->userName->displayName;
//                            'userName.username'
                        },
                    ],
                    [
                        'label' => 'واحد گیرنده پیام',
                        'attribute' => 'department',
                        'value'     => 'department',
                    ],
                    [
                        'label'=>'عنوان',
                        'attribute' => 'topic',
                        'value'     => 'topic',
                    ],
                    [
                        'label' => 'وضعیت',
                        'attribute' => 'status',
                        'value'     => function ($model) {
                            $background = ($model->status == 0 || $model->status == 1) ? 'text-danger' :'' ;
                            switch ($model->body['client']) {
                                case 0 :
                                    if ($model->status == \rabint\ticket\models\TicketHead::CLOSED) {
                                        return '<div class="label label-success '.$background.'">مشتری</div>&nbsp;<div class="label label-default">بسته شده</div>';
                                    }

                                    return '<div class="label label-success '.$background.'">مشتری</div>';
                                case 1 :
                                    if ($model->status == \rabint\ticket\models\TicketHead::CLOSED) {
                                        return '<div class="label label-primary '.$background.'">مدیر</div>&nbsp;<div class="label label-default">بسته شده</div>';
                                    }

                                    return '<div class="label label-primary '.$background.'">مدیر</div>';
                            }
                        },
                        'format'    => 'html',
                    ],
                    [
                        'label' => 'بروزرسانی',
                        'attribute' => 'date_update',
                        'value'     => 'date_update',
                    ],
                    [
                        'class'         => 'yii\grid\ActionColumn',
                        'template'      => '{update}&nbsp;{delete}&nbsp;{closed}',
                        'headerOptions' => [
                            'style' => 'width:230px',
                        ],
                        'buttons'       => [
                            'update' => function ($url, $model) {
                                return \yii\helpers\Html::a('پاسخ دادن',
                                    \yii\helpers\Url::toRoute(['admin/answer', 'id' => $model['id']]),
                                    ['class' => 'btn-xs btn-info']);
                            },
                            'delete' => function ($url, $model) {
                                return \yii\helpers\Html::a('حذف',
                                    \yii\helpers\Url::to(['admin/delete', 'id' => $model['id']]),
                                    [
                                        'class'   => 'btn-xs btn-danger',
                                        'onclick' => 'return confirm("آیا از حذف آیتم اطمینان دارید؟")',
                                    ]
                                );
                            },
                            'closed' => function ($url, $model) {
                                return \yii\helpers\Html::a('بستن',
                                    \yii\helpers\Url::to(['admin/closed', 'id' => $model['id']]),
                                    [
                                        'class'   => 'btn-xs btn-primary',
                                        'onclick' => 'return confirm("مطمئن هستید که می خواهید تیکت را ببندید؟")',
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>