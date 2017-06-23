<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsAlbum;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;

class GoodsAlbumController extends BackendController
{
    public function actionIndex($id)
    {
        $relationBanners = GoodsAlbum::find()->where(['goods_id' => $id])->asArray()->all();

        $p1 = $p2 = [];
        if ($relationBanners) {
            foreach ($relationBanners as $k => $v) {
                $p1[$k] = $v['img'];
                $p2[$k] = [
                    'url' =>Url::toRoute('/goods-album/del'),
                    'key' => $v['id'],
                ];
            }
        }
        $model = new GoodsAlbum();
        return $this->render('index', [
            'model' => $model,
            'p1' => $p1,
            'p2' => $p2,
            'id' => $id,
        ]);
    }
    public function actionAdd(){
// 商品ID
            $id = \Yii::$app->request->post('goods_id');
            $p1 = $p2 = [];
        if (empty($_FILES['GoodsAlbum']['name']) || empty($_FILES['GoodsAlbum']['name']['imgfiles']) || !$id) {
                echo '{}';
                return;
            }
            for ($i = 0; $i < count($_FILES['GoodsAlbum']['name']['imgfiles']); $i++) {
                $url = '/goods-album/del';
                $model = new GoodsAlbum();
                $model->imgfiles=UploadedFile::getInstance($model,'imgfiles');//实例化要在验证前啊啊啊啊啊啊啊
                if($model->validate()){
                    $filename='/images/goods/'.uniqid().'.'.$model->imgfiles->extension;
                    $model->imgfiles->saveAs(\Yii::getAlias("@webroot").$filename);
                    $model->img=$filename;
                }
// 图片入库操作，此处不可以批量直接入库，因为后面我们还要把key返回 便于图片的删除
                $model->goods_id = $id;
//                $model->img = $imageUrl;
                $key = 0;
                if ($model->save(false)) {
                    $key = $model->id;
                }
// $pathinfo = pathinfo($imageUrl);
// $caption = $pathinfo['basename'];
// $size = $_FILES['Banner']['size']['banner_url'][$i];
                $p1[$i] =$filename;
                $p2[$i] = ['url' => $url, 'key' => $key];

            }
            echo json_encode([
                'initialPreview' => $p1,
                'initialPreviewConfig' => $p2,
                'append' => true,
            ]);
            return;
        }
        public function actionDel(){
            if ($id = \Yii::$app->request->post('key')) {
                $model=GoodsAlbum::findOne(['id'=>$id]);
                $model->delete();
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }

}
