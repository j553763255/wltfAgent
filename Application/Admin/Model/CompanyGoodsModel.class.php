<?php
/**
 * Created by PhpStorm.
 * User: ju
 * Date: 2017/2/27
 * Time: 11:12
 */
namespace Admin\Model;
use Think\Model;

class CompanyGoodsModel extends Model{

    protected $tableName =  'daili_company_goods';

    public function addMall($data)
    {
        return $this->add($data);
    }

    public function getMall($id)
    {
        if (empty($id)){
            $goodsObj = $this->select();
            foreach ($goodsObj as $k=>$item) {
                if ($item['type'] == 1){
                    $goodsObj[$k]['type'] = "上架";
                }else{
                    $goodsObj[$k]['type'] = "下架";
                }
            }
        }else{
            $goodsObj = $this->where("id =" .$id)->find();
        }
        return $goodsObj;
    }

    public function modifyGoods($data)
    {
        $bool = $this->where("id =" . $data['id'])->save($data);
        return $bool;
    }

}