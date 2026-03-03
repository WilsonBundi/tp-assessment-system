<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tp_report}}".
 *
 * @property int $id
 * @property int $assessment_id
 * @property string $report_type
 * @property string|null $report_title
 * @property string|null $report_file
 * @property string|null $file_type
 * @property string|null $generated_at
 * @property string|null $downloaded_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TpReport extends ActiveRecord
{
    const TYPE_STUDENT = 'student';
    const TYPE_OFFICE = 'office';

    const FILE_TYPE_PDF = 'pdf';
    const FILE_TYPE_DOCX = 'docx';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tp_report}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['assessment_id', 'report_type'], 'required'],
            [['assessment_id'], 'integer'],
            [['report_title', 'report_file'], 'string', 'max' => 255],
            [['report_type'], 'in', 'range' => [self::TYPE_STUDENT, self::TYPE_OFFICE]],
            [['file_type'], 'in', 'range' => [self::FILE_TYPE_PDF, self::FILE_TYPE_DOCX]],
            [['generated_at', 'downloaded_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assessment_id' => 'Assessment ID',
            'report_type' => 'Report Type',
            'report_title' => 'Report Title',
            'report_file' => 'Report File',
            'file_type' => 'File Type',
            'generated_at' => 'Generated At',
            'downloaded_at' => 'Downloaded At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->generated_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    /**
     * Get assessment
     */
    public function getAssessment()
    {
        return $this->hasOne(TpAssessment::class, ['id' => 'assessment_id']);
    }

    /**
     * Get report type label
     */
    public static function getReportTypeLabel($type)
    {
        $labels = [
            self::TYPE_STUDENT => 'Student Copy',
            self::TYPE_OFFICE => 'TP Office Copy',
        ];
        return $labels[$type] ?? $type;
    }
}
