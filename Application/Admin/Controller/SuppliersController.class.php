<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Controller\CommondController;

class SuppliersController extends CommondController
{
    /**
     * 企业列表
     */
    public function passSuppliers()
    {
        $suppliersBD = D("Suppliers");
        $allSuppliers = $suppliersBD->getMessage($_GET['status']);
        $this->assign("suppliers",$allSuppliers);
        $this->display();
    }
    /**
     * 未审核企业列表
     */
    public function UntreatedSuppliers()
    {
        $suppliersBD = D("Suppliers");
        $allSuppliers = $suppliersBD->getMessage(1);
        $this->assign("suppliers",$allSuppliers);
        if (IS_POST){
            $map['id'] = $_POST['id'];
            $save['status'] = $_POST['status'];
            $suppliersBD = D("Suppliers");
            $sql = $suppliersBD->updateMessage($map,$save);
            $this->ajaxReturn($sql);
        }
        $this->display();
    }
}
    