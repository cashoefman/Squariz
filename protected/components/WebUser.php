<?php
/**
 * Web User Class
 */
class WebUser extends CWebUser {
    /**
     * @var object User model of WebUser's assigned DB record
     */
    protected $_model = null;

    /**
     * Makes it easy to access related user model object for currenty logged user like
     * <code>Yii::app()->user->model;</code>
     * @return void
     */
    public function getModel() {
        if ($this->_model === null AND !$this->getIsGuest()) {
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }
}