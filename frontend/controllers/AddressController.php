<?php

namespace frontend\controllers;

use chenkby\region\RegionAction;
use frontend\models\Address;
use frontend\models\Locations;
use yii\helpers\ArrayHelper;

class AddressController extends \yii\web\Controller
{
    public $layout='list';
    public function actionIndex($id=0)
    {
        $model=new Address();
        $models=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            return $this->redirect(['address/index']);
        }
        return $this->render('index',['model'=>$model,'models'=>$models]);
    }
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        $models=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            return $this->redirect(['address/index']);
        }
        return $this->render('index',['model'=>$model,'models'=>$models]);
    }
    public function actionDel($id){
        $model=Address::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['address/index']);
    }
    public function actionAddr($id){
        $model=Address::findOne(['id'=>$id]);
        $model->emptystatus();
        $model->status=1;
        $model->save();
        return $this->redirect(['address/index']);
    }

    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>RegionAction::className(),
            'model'=>Locations::className(),
        ];
        return $actions;
    }

}
