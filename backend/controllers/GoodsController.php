<?php

namespace backend\controllers;

use app\models\Goods;
use app\models\GoodsCategory;
use app\models\GoodsDayCount;
use app\models\GoodsIntro;
use app\models\Search;
use backend\components\RbacFilter;
use backend\models\Brand;
use kucha\ueditor\UEditorAction;
use xj\uploadify\UploadAction;
use yii\helpers\ArrayHelper;


class GoodsController extends BackendController
{
    public function behaviors(){
    return [
        'accessFilter'=>[
            'class'=>RbacFilter::className(),
            'only'=>['index','add','edit','del']
        ],
    ];
 }
    public function actionIndex()
    {
        $search=new Search();
        $query=Goods::find();
        if ($search->load(\Yii::$app->request->get())&&$search->validate()){
            if($search['name']!=null){
                $query->andWhere(['like','name',$search['name']]);
            }
            if($search['sn']!=null){
                $query->andWhere(['like','sn',$search['sn']]);
            }
            if($search['goods_category_id']!=null){
                $query->andWhere(['like','goods_category_id',$search['goods_category_id']]);
            }
            if($search['brand_id']!=null){
                $query->andWhere(['like','brand_id',$search['brand_id']]);//andWhere(like在前是用的数组,'关键字  Like  字段名)'是用字符串
        }
        }
        $model=$query->all();
        $cates=ArrayHelper::map(GoodsCategory::find()->all(),'id','name');
        $brands=ArrayHelper::map(Brand::find()->all(),'id','name');
        return $this->render('index',['model'=>$model,'search'=>$search,'cates'=>$cates,'brands'=>$brands]);
    }

    public function actionAdd(){
        $model=new Goods();
        $intromodel=new GoodsIntro();
       if($model->load(\Yii::$app->request->post())&&$intromodel->load(\Yii::$app->request->post())&&$model->validate()&&$intromodel->validate()){
            $day=date('Ymd',time());
           $goodsdaycountmodel=GoodsDayCount::findOne(['day'=>$day]);
           if($goodsdaycountmodel){
               $goodsdaycountmodel->count =  $goodsdaycountmodel->count+1;
               $goodsdaycountmodel->save();
           }else{
               $goodsdaycountmodel=new GoodsDayCount();
               $goodsdaycountmodel->day=$day;
               $goodsdaycountmodel->count=1;
               $goodsdaycountmodel->save();
           }
           $model->sn=date('Ymd').sprintf("%05d",$goodsdaycountmodel->count);
           $model->save();
           $intromodel->goods_id=$model->id;
           $intromodel->save();
           \Yii::$app->session->setFlash('success', '添加成功');
           return $this->redirect(['goods/index']);
       }
        $goodscate=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->all());
        $brandcate=ArrayHelper::map(Brand::find()->all(),'id','name');
        return $this->render('add',['model'=>$model,'intromodel'=>$intromodel,'brandcate'=>$brandcate,'goodscate'=>$goodscate]);
    }
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $intromodel=GoodsIntro::findOne(['goods_id'=>$id]);
//        $goodsdaycount=new GoodsDayCount();
       if($model->load(\Yii::$app->request->post())&&$intromodel->load(\Yii::$app->request->post())&&$model->validate()&&$intromodel->validate()){
           $model->save();
           $intromodel->goods_id=$model->id;
           $intromodel->save();
           \Yii::$app->session->setFlash('success', '修改成功');
           return $this->redirect(['goods/index']);
       }
        $goodscate=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->all());
        $brandcate=ArrayHelper::map(Brand::find()->all(),'id','name');
        return $this->render('add',['model'=>$model,'intromodel'=>$intromodel,'brandcate'=>$brandcate,'goodscate'=>$goodscate]);
    }
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['goods/index']);
    }
    public function actions()
    {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {
                },
                'beforeSave' => function (UploadAction $action) {
                },
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            'upload' => [
                'class' =>UEditorAction::className(),
            ]
        ];
    }
}
