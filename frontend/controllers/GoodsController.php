<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsAlbum;
use backend\models\GoodsIntro;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
{
    public $layout='list';
    public function actionIndex($id)
    {
        $images=GoodsAlbum::find()->where(['goods_id'=>$id])->all();
        $model=Goods::findOne(['id'=>$id]);
        if(!$model){
            throw new NotFoundHttpException('没有此商品');
        }
        $detail=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('index',['model'=>$model,'images'=>$images,'detail'=>$detail]);
    }

}
