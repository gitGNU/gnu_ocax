<?php

/**
 * Wrapper function for Yii::t()
 */
//http://www.yiiframework.com/forum/index.php/topic/14542-gettext-and-yii/page__p__147482#entry147482
function __($string, $params = array(), $category = "") {
        return Yii::t($category, $string, $params);
}

?>
