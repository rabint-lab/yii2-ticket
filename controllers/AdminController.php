<?php

namespace rabint\ticket\controllers;

use rabint\ticket\models\TicketBody;
use rabint\ticket\models\TicketHead;
use rabint\ticket\Module;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * @property Module $module
 */
class AdminController extends \rabint\controllers\AdminController
{

//    /**
//     * @return array
//     */
//    public function behaviors()


    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataPrivider = (new TicketHead())->dataProviderAdmin();
        Url::remember();

        return $this->render('index', ['dataProvider' => $dataPrivider]);
    }

    /**
     * @param $id int
     * @return string|\yii\web\Response
     */
    public function actionAnswer($id)
    {
        $thisTicket = TicketBody::find()->where(['id_head' => $id])->joinWith('file')->asArray()->orderBy('date DESC')->all();
        $newTicket = new TicketBody();

        if (\Yii::$app->request->post()) {
            $newTicket->load(\Yii::$app->request->post());
            $newTicket->id_head = $id;

            if ($newTicket->save()) {
                $ticketHead = TicketHead::findOne($newTicket->id_head);
                $ticketHead->status = TicketHead::ANSWER;

                if ($ticketHead->save()) {
                    return $this->redirect(Url::to());
                }
            }
        }

        return $this->render('answer', ['thisTicket' => $thisTicket, 'newTicket' => $newTicket]);
    }

    /**
     *
     * @param $id int id
     * @return \yii\web\Response
     */
    public function actionClosed($id)
    {
        $model = TicketHead::findOne($id);

        $model->status = TicketHead::CLOSED;

        $model->save();

        if ($this->module->mailSend !== false) {
            (new Mailer())
                ->sendMailDataTicket($model->topic, $model->status, $model->id, '')
                ->setDataFrom(Yii::$app->params['adminEmail'], $this->module->subjectAnswer)
                ->senda('closed');
        }

        return $this->redirect(Url::previous());
    }

    /**
     * @param $id int
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        TicketHead::findOne($id)->delete();

        return $this->redirect(Url::to(['/ticket/admin/index']));
    }

    public function actionOpen()
    {
        $ticketHead = new TicketHead();
        $ticketBody = new TicketBody();

        $userModel = Yii::$app->user->identityClass;

        $users = $userModel::find()->select(['username as value', 'username as label', 'id as id'])->asArray()->all();

        if ($post = \Yii::$app->request->post()) {

            $ticketHead->load($post);
            $ticketBody->load($post);

            if ($ticketHead->validate() && $ticketBody->validate()) {

                $ticketHead->user = $post['TicketHead']['user_id'];
                $ticketHead->status = TicketHead::ANSWER;
                if ($ticketHead->save()) {
                    $ticketBody->id_head = $ticketHead->primaryKey;
                    $ticketBody->save();

                    $this->redirect(Url::previous());
                }
            }
        }

        return $this->render('open', [
            'ticketHead' => $ticketHead,
            'ticketBody' => $ticketBody,
            'qq'         => $this->module->qq,
            'users'      => $users,
        ]);
    }
}