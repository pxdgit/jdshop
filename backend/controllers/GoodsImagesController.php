<?php

namespace backend\controllers;


use backend\models\Goods;
use backend\models\GoodsImages;
use yii\helpers\Url;
use yii\web\UploadedFile;

class GoodsImagesController extends BackendController
{
    public function actionAdd($id){
        //获取商品id
        $goods_id=$id-0;
        //找到对应的商品名称
        $goods=Goods::findOne(['id'=>$goods_id]);
        //判断商品是否存在
        if($goods==null){
            \Yii::$app->session->setFlash('danger','非法操作,我们已经报警啦');
            return $this->redirect(['goods/index']);
        }
        //获取货号,为保存在指定货号的文件夹下做准备
        $sn=$goods->sn;
        //创建图片模型
        $model=new GoodsImages();
        //接收数据并且验证
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //获取到所有上传的文件
            $model->imgfiles=UploadedFile::getInstances($model,'imgfiles');
            //循环遍历保存
            foreach($model->imgfiles as $imgfile){
                //获取到保存的目标文件夹
                $filedir=\Yii::getAlias('@webroot').'/images/'.$sn;
                //var_dump($filedir);exit;
                //判断文件夹是否存在,不存在就创建文件夹
                if(!is_dir($filedir)){
                    mkdir($filedir,0777,true);
                }
                //获取文件路径打算保存到数据库中
                $filename='/images/'.$sn.'/'.uniqid().'.'.$imgfile->extension;
                //var_dump($filename);exit;
                //保存图片
                $imgfile->saveAs(\Yii::getAlias('@webroot').'/'.$filename);
                //新建立模型好保存全部的图片;
                $add_img=new GoodsImages();
                //定义文件路径
                $add_img->img=$filename;
                //定义对应的商品id
                $add_img->goods_id=$goods_id;
                $add_img->save(false);
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-images/index','id'=>$goods_id]);
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionIndex($id){
        $models = GoodsImages::find()->where(['goods_id' => $id])->asArray()->all();
        $model=new GoodsImages();
// @param $p1 Array 需要预览的商品图，是商品图的一个集合
// @param $p2 Array 对应商品图的操作属性，我们这里包括商品图删除的地址和商品图的id
        $p1 = $p2 = [];
        if ($models) {
            foreach ($models as $k => $v) {
                //这里改成你数据表中对应的字段名称
                $p1[$k] = $v['img'];
                $p2[$k] = [
                    // 要删除商品图的地址
                    'url' => Url::toRoute('/goods-images/delete'),
                    // 商品图对应的商品图id
                    'key' => $v['id'],
                ];
            }
        }
        return $this->render('index', [
            // other params
            'model'=>$model,
            'p1' => $p1,
            'p2' => $p2,
            // 商品id
            'id' => $id,
        ]);
    }
    public function actionDelete()
    {
        // 前面我们已经为成功上传的banner图指定了key,此处的key也即时banner图的id
        if ($id = \Yii::$app->request->post('key')) {
            //var_dump($id);exit;
            $model = GoodsImages::findOne(['id'=>$id]);
            $model->delete();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }
}
