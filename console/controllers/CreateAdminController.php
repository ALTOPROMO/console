<?php
namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

use admin\models\Admin;

/**
 * Контроллер управляет администраторами.
 */
class CreateAdminController extends Controller
{
    public $username;
    public $email;
    public $password;

    /**
     * Метод для создания нового администратора.
     * 
     * Данный метод вызывается из консоли, в качестве параметров принимает данные администратора и создает нового администратора в базе данных.
     * 
     * @param string $username Имя пользователя
     * @param string $email Адрес электронной почты
     * @param string $password Пароль администратора
     * @return array
     */
    public function actionIndex($username, $email, $password)
    {
    	echo 'Создание нового аккаунта администратора с именем ' . $username .  PHP_EOL;
    	echo "\r\n";

    	$admin = new Admin();
    	$admin->username = $username;
        $admin->email = $email;
        $admin->status = 10;
        $admin->setPassword($password);
        $admin->generateAuthKey();
        $isSaveAdmin = $admin->save();

        if($isSaveAdmin) {
            echo "Аккаунт успешно создан! Не забудьте сохранить данные для входа.\r\n";
        } else {
            echo "Fail\r\n";
        }
    }
}
