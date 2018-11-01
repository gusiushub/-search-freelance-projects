<?php

namespace app\models;


use app\components\helpers\FunctionHelper;
use Yii;


/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property int source
 * @property string $number
 * @property string $create
 * @property int $active
 * @property string $activeTo
 * @property int $authorId
 * @property int $projectType
 * @property string $name
 * @property string $budget
 * @property string $currency
 * @property int $settlementType
 * @property string $generalDescription
 * @property int $categoryId
 * @property string $labels
 * @property int $desiredCountryId
 * @property int $desiredCityId
 * @property int $onlyInsured
 * @property int $permanentDistantWork
 * @property int $competition
 * @property string $application_date
 * @property string $deadline
 * @property int $payment_method
 * @property string $inner_budget
 * @property string $inner_currency
 * @property string $prepayment
 * @property string $debts
 * @property string $office
 * @property int $managerId
 * @property int $status
 * @property int $published
 * @property int $parent
 * @property int $is_part
 * @property string $notes
 * @property string $url
 */
class Projects extends \yii\db\ActiveRecord
{
    const SCENARIO_USER_CREATE = 'create';
    const PROJECT_ACTIVE = 1;
    const PROJECT_NO_ACTIVE = 0;
    const DEFAULT_AUTHOR = 1;
    const PROJECT_JOB = 1;
    public $statuses = [
        0 => 'Заказчик думает',
        1 => 'Заказчик отказался',
        2 => 'Недостаточно информации',
        3 => 'Отклонена',
        4 => 'В работе',
        5 => 'Выполнена',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['projectType', 'name', 'budget', 'settlementType', 'generalDescription', 'create', 'office', 'managerId', 'status', 'settlementType'],
                'required', 'on' => self::SCENARIO_USER_CREATE
            ],
            [
                ['active', 'authorId', 'projectType', 'settlementType', 'categoryId', 'desiredCountryId',
                    'desiredCityId', 'onlyInsured', 'permanentDistantWork', 'competition', 'payment_method',
                    'managerId', 'status', 'published', 'parent', 'is_part', 'source'],
                'integer'
            ],
            [
                ['budget', 'inner_budget', 'prepayment', 'debts'],
                'number'
            ],
            [
                ['name', 'labels', 'office', 'url'],
                'string', 'max' => 255
            ],
            [
                ['number'],
                'string', 'max' => 50
            ],
            [
                ['currency', 'inner_currency'],
                'string', 'max' => 3
            ],
            [
                ['create', 'activeTo', 'application_date', 'deadline'],
                'safe'
            ],
            [
                ['generalDescription'],
                'string', 'max' => 10000
            ],
            [
                ['notes'],
                'string'
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create' => 'Create',
            'active' => 'Active',
            'activeTo' => 'Active To',
            'authorId' => 'Author ID',
            'projectType' => 'Project Type',
            'name' => 'Name',
            'budget' => 'Budget',
            'currency' => 'Currency',
            'settlementType' => 'Settlement Type',
            'generalDescription' => 'Описание проекта',
            'categoryId' => 'Category ID',
            'labels' => 'Labels',
            'desiredCountryId' => 'Desired Country ID',
            'desiredCityId' => 'Desired City ID',
            'onlyInsured' => 'Только для флилансеров со страховкой',
            'permanentDistantWork' => 'Постоянная удаленная работа',
            'competition' => 'Опубликовать как конкурс',
            'office' => 'Офис обслуживания',
            'notes' => 'Блокнот'
        ];
    }
    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statuses;
    }
    /**
     * @return null|string
     */
    public function getNumber()
    {
        $number = null;
        $date = date('Y-m-d');
        if ($this->officeModel && $this->officeModel->countryModel && $this->officeModel->cityModel) {
            $pref1 = $this->officeModel->countryModel
                ? strtoupper(substr(FunctionHelper::translit($this->officeModel->countryModel->name), 0, 1))
                : null;
            $pref2 = $this->officeModel->cityModel
                ? strtoupper(substr(FunctionHelper::translit($this->officeModel->cityModel->name), 0, 1))
                : null;
            $today = self::find()->where([
                    'between',
                    'create',
                    $date . ' 00:00:01',
                    $date . ' 23:59:59',
                ])->count() + 1;
            $number = $pref1 . $pref2 . substr(date('Y', strtotime($date)), 2) . date('md', strtotime($date)) . $today;
        }
        return $number;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::className(), ['id' => 'authorId']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorDetailsModel()
    {
        return $this->hasOne(UsersFl24::className(), ['userId' => 'authorId']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeModel()
    {
        return $this->hasOne(Offices::className(), ['id' => 'office']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManagerModel()
    {
        return $this->hasOne(User::className(), ['id' => 'managerId']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartsModel()
    {
        return $this->hasMany(ProjectParts::className(), ['projectId' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentModel()
    {
        return $this->hasOne(self::className(), ['id' => 'parent']);
    }


    /**
     * @return int|string
     */
    public function getCountMessagesByProject()
    {
        return Messages::find()->where(['toId' => Yii::$app->user->id, 'projectId' => $this->id])->count();
    }
}