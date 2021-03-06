<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsAlbum;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\Search;
use backend\models\Brand;
use kucha\ueditor\UEditorAction;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $search=new Search();
        if($search->load(\Yii::$app->request->get())&&$search->validate()){
            $class=$search->tablename;
            $modelName = $search->tablename=='Brand'?"\\backend\\models\\{$search->tablename}":"\\app\\models\\{$search->tablename}";
            $result=$modelName::find()->where(
                ['like',$search->condition,$search->key]
            );
            if($result){
            $filed=$search->tablename=='Brand'?'brand_id':'goods_category_id';
            $smodel= $search->tablename=='Goods'?$result:Goods::find()->where([$filed=>$result->all()[0]['id']]);
            }
        }
        $query=isset($smodel)?$smodel:Goods::find();
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>'1'
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'search'=>$search,'page'=>$page]);
    }

    public function actionAdd(){
        $model=new Goods();
        $intromodel=new GoodsIntro();
//        $goodsdaycount=new GoodsDayCount();
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
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
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
