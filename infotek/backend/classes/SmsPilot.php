<?php

namespace app\classes;

class SmsPilot
{
    CONST API_KEY = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ'; // toDo убрать в config или env

    CONST SENDER = 'test';

    public function sendSms(string $phone, string $author_name): bool
    {

        $text = 'Новая книга автора $author_name добавлена в библиотеку.';
        $sender = self::SENDER;

        $url = 'https://smspilot.ru/api.php'
            .'?send='.urlencode( $text )
            .'&to='.urlencode( $phone )
            .'&from='.$sender
            .'&apikey='.self::API_KEY
            .'&format=json';

        $json = file_get_contents( $url );
        echo $json.'<br/>';

        $j = json_decode( $json );
        if ( !isset($j->error)) {
            echo 'SMS успешно отправлена server_id='.$j->send[0]->server_id;

            return true;
        } else {
            trigger_error( $j->error->description_ru, E_USER_WARNING );

            return false;
        }
    }
}