<?php

use yii\rbac\DbManager;
use yii\db\Migration;

class m250310_000006_rbac_init extends Migration
{
    public function safeUp()
    {
        $auth = new DbManager();
        $auth->init();

        // Создаем роли
        $guest = $auth->createRole('guest');
        $user = $auth->createRole('user');

        $auth->add($guest);
        $auth->add($user);

        // Создаем разрешения
        $viewBooks = $auth->createPermission('viewBooks');
        $viewBooks->description = 'Просмотр книг';

        $subscribeAuthors = $auth->createPermission('subscribeAuthors');
        $subscribeAuthors->description = 'Подписка на авторов';

        $manageBooks = $auth->createPermission('manageBooks');
        $manageBooks->description = 'Создание, редактирование и удаление книг';

        $auth->add($viewBooks);
        $auth->add($subscribeAuthors);
        $auth->add($manageBooks);

        // Назначаем разрешения ролям
        $auth->addChild($guest, $viewBooks);
        // $auth->addChild($guest, $subscribeAuthors); как гость может быть на что то подписан?

        $auth->addChild($user, $viewBooks);
        $auth->addChild($user, $subscribeAuthors);
        $auth->addChild($user, $manageBooks);
    }

    public function safeDown()
    {
        $auth = new DbManager();
        $auth->removeAll();
    }
}