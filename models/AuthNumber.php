<?php
/**
 * Created by PhpStorm.
 * User: Semenov
 * Date: 04.12.2018
 * Time: 15:08
 */

namespace app\models;

use yii\base\Model;

class AuthNumber extends Model
{
    public $card;
    public $dob;
    public $phone;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['card'], 'required'],
        ];
    }

}