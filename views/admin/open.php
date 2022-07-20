<?php
/** @var \rabint\ticket\models\TicketHead $ticketHead */
use yii\helpers\Html;
use yii\web\JsExpression;

/** @var \rabint\ticket\models\TicketBody $ticketBody */
?>
<div class="panel page-block">
    <div class="container-fluid row">
        <div class="col-md-12">
            <a class="btn btn-primary" href="<?= \yii\helpers\Url::to(['admin/index']) ?>" style="margin-bottom: 10px">بازگشت</a>
            <div class="well">
                <?php $form = \yii\widgets\ActiveForm::begin([]) ?>
                <label for="">نام کاربری</label>
                <?= \yii\jui\AutoComplete::widget([
                    'clientOptions' => [
                        'source'   => $users,
                        'autoFill' => true,
                        'select'   => new JsExpression("function( event, ui ) {
                                     $('#tickethead-user_id').val(ui.item.id);
                            }"),
                    ],
                    'options'       => [
                        'class' => 'form-control',
                    ],

                ]); ?>
                <?= Html::activeHiddenInput($ticketHead, 'user_id') ?>
                <?= $form->field($ticketHead, 'department')
                    ->dropDownList($qq)
                    ->label('پیام')->error() ?>
                <?= $form->field($ticketHead, 'topic')
                    ->textInput() ?>
                <?= $form->field($ticketBody, 'text')
                    ->textarea([
                        'style' => 'height: 150px; resize: none;',
                    ])->label('پیام'); ?>
                <div class="text-center">
                    <button class='btn btn-primary'>ارسال</button>
                </div>
                <?= $form->errorSummary($ticketBody) ?>
                <?php $form->end() ?>
            </div>
        </div>

    </div>
</div>
</div><!-- contentpanel -->
</div><!-- mainpanel -->