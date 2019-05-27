<?php

namespace app\models;

use Yii;
use yii\base\Model;

class BillingForm extends Model
{
    public $name;
    public $isIP;
    public $inn;
    public $kpp;
    public $email;
    public $password;
    
    public function rules()
    {
        return [
            [ [ 'name', 'email', 'password' ], 'required', 'message' => 'Please choose a username.' ],
            [ 'name', 'checkName' ],
            [ 'isIP', 'boolean' ],
            [ 'email', 'email'],
            [ 'password', 'checkPassword' ],

            [ 'kpp', 'required', 'when' => function($model) {
                return $model->isIP == 1;
            }  ],
            [ 'kpp', 'checkKPP', 'when' => function($model) {
                return $model->isIP == 1;
            } ],

            [ 'inn', 'required', 'when' => function($model) {
                return $model->isIP == 0;
            }  ],
            [ 'inn', 'checkINN', 'when' => function($model) {
                return $model->isIP == 0;
            } ]
        ];
    }

    //Проверка КПП
    public function checkKPP( $attribute, $params ) {
		$kpp = $this->$attribute;
        
        if (strlen($kpp) !== 9) {
            $this->addError($attribute, 'КПП может состоять только из 9 знаков (цифр или заглавных букв латинского алфавита от A до Z)');
		} elseif (!preg_match('/^[0-9]{4}[0-9A-Z]{2}[0-9]{3}$/', $kpp)) {
            $this->addError($attribute, 'Неправильный формат КПП');
		} else {
			$result = true;
		}
	}

    //Проверка ИНН
    public function checkINN( $attribute, $params ) {
        $inn = $this->$attribute;

        if (preg_match('/[^0-9]/', $inn)) {
            $this->addError($attribute, 'ИНН может состоять только из цифр');
		} elseif (!in_array($inn_length = strlen($inn), [10, 12])) {
            $this->addError($attribute, 'ИНН может состоять только из 10 или 12 цифр');
		} else {
			$check_digit = function($inn, $coefficients) {
				$n = 0;
				foreach ($coefficients as $i => $k) {
					$n += $k * (int) $inn{$i};
				}
				return $n % 11 % 10;
			};
			switch ($inn_length) {
				case 10:
					$n10 = $check_digit($inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
					if ($n10 === (int) $inn{9}) {
						$result = true;
					}
					break;
				case 12:
					$n11 = $check_digit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
					$n12 = $check_digit($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
					if (($n11 === (int) $inn{10}) && ($n12 === (int) $inn{11})) {
						$result = true;
					}
					break;
			}
			if (!$result) {
                $this->addError($attribute, 'Неправильное контрольное число');
			}
		}
	}

    //Проверка имени
    public function checkName( $attribute, $params )
    {
        if ( strlen($this->$attribute) < 3 ) {
            $this->addError($attribute, 'Имя должно быть больше 3 символов');
        }
    }

    //Проверка пароля
    public function checkPassword(  $attribute, $params )
    {
        $regex = '/^\S*(?=\S*[a-zа-яё])(?=\S*[A-ZА-ЯЁ])(?=\S*[\d])\S*$/';

        if ( !preg_match( $regex, $this->$attribute ) ){
            $this->addError($attribute, 'Пароль должен содержать цифры, буквы и как минимум одну строчную букву');
        }
    }    
}
