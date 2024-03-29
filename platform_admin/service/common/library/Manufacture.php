<?php

namespace common\library;

use app\models;
use yii\db\Exception;

class Manufacture
{
    /**
     * 获取所有未删除的厂商
     */
    public static function getManufactureALl()
    {
        return (new \yii\db\Query())
            ->select('id,name,name_en')
            ->from('manufacture')
            ->where(['is_deleted'=>0])
            ->all();
    }

    /**
     * 获取所有未删除的终端
     */
    public static function getDeviceTypeAll()
    {
        return (new \yii\db\Query())
            ->select('id,name,name_en')
            ->from('device_type')
            ->where(['is_deleted'=>0])
            ->all();
    }

    /**
     * 获取所有未删除的大类
     */
    public static function getCategoryAll()
    {
        return (new \yii\db\Query())
            ->select('id,name,name_en,key,code,tag')
            ->from('category')
            ->where(['is_deleted'=>0])
            ->all();
    }

    /**
     * 获取所有未删除的遥控器
     */
    public static function getRemoteTypeAll()
    {
        return (new \yii\db\Query())
            ->select('id,name,name_en')
            ->from('remote_type')
            ->where(['is_deleted'=>0])
            ->all();
    }

    /**
     * 获取所有的按键
     */
    public static function getKeySetAll()
    {
        $keycode = (new \yii\db\Query())
            ->select('parent,type')
            ->from('km_keycode')
            ->groupBy('parent,type')
            ->all();
//        $keycode = Keycode::find()->select('parent,type')->from('km_keycode')->groupBy('parent,type')->all();

        $data = array();
        foreach ($keycode as $val) {
            if (!isset($data[$val['type']])) {
                $data[$val['type']] = array();
            }
            $data[$val['type']][] = $val['parent'];
        }
        return $data;
    }

    /**
     * 获取所有的keycode
     */
    public static function getKeycodeAll()
    {
        return (new \yii\db\Query())
            ->select('*')
            ->from('km_keycode')
            ->all();
//        return Keycode::find()->asArray()->all();
    }

    /**
     * 检查厂商下面是否存在批次
     * @param $relate_id string 关联id
     * @param $relate_type integer 关联类型 1:厂商 2：遥控器 or 终端
     * @return bool
     * @throws \Exception
     */
    public function checkManufactureExistsSn($relate_id, $relate_type = 1)
    {
        if(empty($relate_id)){
            throw new \Exception('relate id is not empty');
        }

        switch ($relate_type) {
            case 1:
                return models\Batch::find()
                    ->where(['m_id' => $relate_id])
                    ->exists();
                break;
            case 2:
                return models\Batch::find()
                    ->where(['h_id' => $relate_id])
                    ->exists();
                break;
            default:
                throw new \Exception('relate type is a invalid param');
        }

    }
}