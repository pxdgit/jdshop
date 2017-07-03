<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $Addressee
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $Address
 * @property string $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['addressee','province','area','city','tel','address'], 'required'],
            [['province', 'city', 'area', 'status'], 'integer'],
            [['addressee', 'address'], 'string', 'max' => 255],
            [['tel'],'match','pattern'=>'/^1[3578]\d{9}$/','message'=>'格式不正确'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'addressee' => '收货人：',
            'province' => '省市区',
            'city' => '市',
            'area' => '地区',
            'address' => '详细地址：',
            'tel' => '手机号码：',
            'status' => '状态',
        ];
    }
    public function beforeSave($insert){
        if($insert){
            $this->member_id=\Yii::$app->user->id;
            if($this->status){
               if( $old=Address::findOne(['member_id'=> $this->member_id,'status'=>1])){
                   $old->status=0;
                   $old->save();
               }
            }else{
            }
        }else{
        }
        return parent::beforeSave($insert);
    }
    public function emptystatus(){
        $old=Address::findOne(['member_id'=>$this->member_id,'status'=>1]);
        if($old && $old->id!=$this->id){
        $old->status=0;
        $old->save();}
    }
    public function getpro(){
        return $this->hasOne(Locations::className(),['id'=>'province']);
    }
    public function getcit(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }
    public function getare(){
        return $this->hasOne(Locations::className(),['id'=>'area']);
    }
}
