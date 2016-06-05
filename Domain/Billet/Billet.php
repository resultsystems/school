<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 08:36:29
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:18
 */

namespace Domain\Billet;

use Carbon\Carbon;
use Domain\BaseModel;
use Domain\Student\Student;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billet extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['student_id', 'due_date', 'new_due_date', 'amount', 'number', 'document_number', 'statement01',
        'statement02', 'statement03', 'statement04', 'statement05', 'instruction01', 'instruction02',
        'instruction03', 'instruction04', 'instruction05', 'acceptance', 'kind_document', 'refer', 'payer',
        'recipient', 'note', ];

    protected $dates = ['due_date', 'discharge_date', 'deleted_at', 'new_due_date'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $student = Student::find($model->student_id);
            $recipient = BilletAssignor::first();

            $model->payer = $student->getPayer();
            $model->recipient = $recipient->getRecipient();
            $model->new_due_date = $model->due_date->toDateString();

            $model->payment_of_fine = $recipient->payment_of_fine;
            $model->interest = $recipient->interest;
            $model->is_interest = $recipient->is_interest;
            $model->days_protest = $recipient->days_protest;

            $model->bank = $recipient->bank;
            $model->agency = $recipient->agency;
            $model->digit_agency = $recipient->digit_agency;
            $model->account = $recipient->account;
            $model->digit_account = $recipient->digit_account;
            $model->wallet = $recipient->wallet;
            $model->agreement = $recipient->agreement;
            $model->portfolio_change = $recipient->portfolio_change;
            $model->range = $recipient->range;
            $model->client_id = $recipient->client_id;
            $model->ios = $recipient->ios;
            $model->kind_document = $recipient->kind_document;
        });

        static::updating(function ($model) {
            //
        });
    }

    public function geStatements()
    {
        return [$this->statement01, $this->statement02, $this->statement03, $this->statement04, $this->statement05];
    }

    public function getInstructions()
    {
        return [$this->instruction01, $this->instruction02, $this->instruction03, $this->instruction04, $this->instruction05];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Discharge from.
     *
     * @param  Carbon|null $date
     * @return Carbon|bool
     */
    public function dischargeFrom(Carbon $date = null)
    {
        if (!is_null($this->discharge_date)) {
            return false;
        }

        if (is_null($date)) {
            $date = Carbon::now();
        }
        $this->discharge_date = $date;
        $this->save();

        return $this->discharge_date;
    }
}
