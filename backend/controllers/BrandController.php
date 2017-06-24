<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;


class BrandController extends BackendController
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
        $query=Brand::find();
        $total=$query->count();
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>'10'
        ]);
        $model=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }

    public function actionAdd(){
    $model = new Brand();
    $request = new Request();
    if ($request->isPost) {
        $model->load($request->post());
        if ($model->validate()) {
            $model->save();
            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect(['brand/index']);
        }
    }
    return $this->render('add', ['model' => $model]);
}


    public function actionEdit($id)
    {
        $model = Brand::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }
    public function actionDel($id){
        $model = Brand::findOne(['id' => $id]);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['brand/index']);
    }

    public function actions() {
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
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl= $action->getWebUrl();//1
//                    $imgUrl=$action->getSavePath();//2
//                    $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛云组件，将图片上传到七牛云
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取该图片在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] =$url;
//                    $action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
//        $ak = '43g5nYcengHvUx2sdNOsfCg_0qK4SOQmy4dckGoI';
//        $sk = 'byhCSvjH2e_3B4jamWLfRu6FSY0YPFFLKE16igdE';
//        $domain = 'http://or9r19axb.bkt.clouddn.com/';
//        $bucket = 'jxshop';
//        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
//        //要上传的文件
//        $filename=\Yii::getAlias('@webroot').'/images/brand/5938ff87b7219.png';
//        $key = '5938ff87b7219.png';
//        $re=$qiniu->uploadFile($filename,$key);
//        var_dump($re);
//        $url = $qiniu->getLink($key);

        $imgUrl='./images/brand/5938ff87b7219.png';
//                    $action->output['fileUrl'] = $action->getWebUrl();
        //调用七牛云组件，将图片上传到七牛云
        $qiniu=\Yii::$app->qiniu;
        $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
        //获取该图片在七牛云的地址
        $url = $qiniu->getLink($imgUrl);
        var_dump($url);
//        $action->output['fileUrl'] =$url;
    }
}