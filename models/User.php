<?php

namespace app\models;

use nodge\eauth\ErrorException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class User extends ActiveRecord  implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    //    public $id;
    //    public $phone;
    //    public $email;
    //    public $username;
    //    public $password;
        public $authKey;
//        public $paid_to;
    //    public $accessToken;
//    public $f_name;

    /**
     * @var array EAuth attributes
     */
    public $profile;


//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return 'user';
//    }

    public static function findIdentity($id) {
        if (Yii::$app->getSession()->has('user-'.$id)) {
            return new self(Yii::$app->getSession()->get('user-'.$id));
        }
        else {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
//            return isset(self::$users[$id]) ? new self(self::$users[$id]) : null;
        }
    }

    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return User
     * @throws ErrorException
     */
    public static function findByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $id = $service->getServiceName().'-'.$service->getId();
        $attributes = [
            'id' => $id,
            'username' => $service->getAttribute('name'),
            'authKey' => md5($id),
            'profile' => $service->getAttributes(),
        ];
        $attributes['profile']['service'] = $service->getServiceName();
        Yii::$app->getSession()->set('user-'.$id, $attributes);
        return new self($attributes);
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
//            ['paid_to', 'integer']
        ];
    }

//    public static function findIdentity($id)
//    {
//        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
//    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Проверка оплаты
     *
     * @return bool
     */
    public static function isPay()
    {
        $paidTo = Yii::$app->user->identity->paid_to;
        if ($paidTo!=null){
            $true = $paidTo - time();
            if ($true>=0){
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Проверка пробного периода
     *
     * @return bool
     */
    private static function trialPeriod($numDay=3)
    {
        $created = Yii::$app->user->identity->created_at;
        $D = time() - $created;
        $days = round($D/(3600*24));
        if ($days>$numDay){
            return false;
        }
        return true;
    }

    /**
     * Права доступа
     *
     * @return bool
     */
    public static function accessPermission()
    {
        if (Yii::$app->user->identity->payment_status==0 and self::trialPeriod()){
            return true;
        }elseif (Yii::$app->user->identity->payment_status!=0){
            return true;
        }elseif (self::trialPeriod()){
            return true;
        }elseif (self::isPay()){
            return true;
        }

        return false;
    }


    /**
     * Заполнен ли профиль пользователя информацией
     *
     *
     * @return int|string
     */
    public static function isProfileComplete()
    {
        $email = Yii::$app->user->identity->email;
        $phone = Yii::$app->user->identity->phone;

        if (!empty($email) and !empty($phone)){

            return 1;
        }elseif (empty($email) and empty($phone)){

            return 'заполнить E-mail и телефон!';
        }elseif(empty($email)){

            return 'заполнить E-mail!';
        }elseif(empty($phone)){

            return 'заполнить телефон!';
        }

    }

}
