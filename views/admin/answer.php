<?php
/** @var \rabint\ticket\models\TicketHead $newTicket */
use yii\helpers\Html;

/** @var \rabint\ticket\models\TicketBody $thisTicket */
?>
<div class="panel page-block">
    <div class="container-fluid row">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="<?= \yii\helpers\Url::toRoute(['admin/index']) ?>"
               style="margin-bottom: 10px">بازگشت</a>
            <a class="btn btn-primary" style="width: 100%" role="button" data-toggle="collapse" href="#collapseExample"
               aria-expanded="false" aria-controls="collapseExample">
                <i class="glyphicon glyphicon-pencil pull-left"></i><span>پاسخ</span>
            </a>
            <div class="collapse" id="collapseExample">
                <div class="well">
                    <?php $form = \yii\widgets\ActiveForm::begin() ?>
                    <?= $form->field($newTicket,
                        'text')->textarea(['style' => 'height: 150px; resize: none;'])->label('پیام')->error() ?>
                    <div class="text-center">
                        <button class='btn btn-primary'>ارسال پیام</button>
                    </div>
                    <?= $form->errorSummary($newTicket) ?>
                    <?php $form->end() ?>
                </div>
            </div>
            <div class="clearfix" style="margin-bottom: 20px"></div>
            <?php foreach ($thisTicket as $ticket) : ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <span><?= $ticket['name_user'] ?>&nbsp;<span
                                    style="font-size: 12px">(<?= ($ticket['client'] == 1) ? 'کارمند' : 'مشتری' ?>
                                )</span></span>
                        <span class="pull-right"><?= $ticket['date'] ?></span>
                    </div>
                    <div class="panel-body">
                        <?= nl2br(Html::encode($ticket['text'])) ?>
                        <?php if (!empty($ticket['file'])) : ?>
                            <hr>
                            <?php foreach ($ticket['file'] as $file) : ?>
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/'])?>fileTicket/<?= $file['fileName'] ?>" target="_blank"><img
                                            src="/fileTicket/reduced/<?= $file['fileName'] ?> " alt="..."
                                            class="img-thumbnail"></a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>