<?php

class CategoryController extends Zend_Controller_Action {

    private $sess = null;
    private $user_data = null;

    public function init() {
        $cats = new Application_Model_Category();
        $this->view->cats = $cats->listAllCategory();
        $this->user_data = Zend_Auth::getInstance()->getStorage()->read();
        $this->sess = new Zend_Session_Namespace("Zend_Auth");
        $authorization = Zend_Auth::getInstance();
        $action = $this->getRequest()->getActionName();   
        $_SESSION['action'] = $action;
        if ($this->user_data->admin == 'false') {
            $this->redirect("/user/");
        } else if (!$authorization->hasIdentity()) {
            $this->redirect("/user/login");
        }
    }

    public function indexAction() {
        // action body
    }

    public function addcategoryAction() {
        $addCatForm = new Application_Form_Addcategory();
        $this->view->addCatForm = $addCatForm;

        if ($this->getRequest()->isPost()) {
            if ($addCatForm->isValid($this->getRequest()->getParams())) {
                try {

                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $adapter->setDestination("/var/www/bondo2Loza/public/images");
                
                    $files = $adapter->getFileInfo();
                    $img = array();
                    foreach ($files as $file => $fileInfo) {
                        if ($adapter->isUploaded($file)) {
                            if ($adapter->isValid($file)) {
                                array_push($img, $fileInfo['name']);
                            }
                        }
                    }

                    if (!$adapter->receive()) {
                        echo 'دخل كل البيانات';
                    }
                    
                    else{
                        $category = new Application_Model_Category();
                        $category->addcategory($this->_request->getParams(),$img[0]);
                        $this->redirect("user/");
                    }
                } catch (Exception $e) {
                    echo '<font color=red>هذا التصنيف موجود بالفعل</font>';
                }
            }
        }
    }
}

