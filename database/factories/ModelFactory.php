<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-13 07:38:14
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:25
 */

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

use Domain\Billet\Billet;
use Domain\Billet\BilletAssignor;
use Domain\Classroom\Classroom;
use Domain\Classroom\ClassroomMatter;
use Domain\Config\Config;
use Domain\Employee\Employee;
use Domain\Lesson\Lesson;
use Domain\Matter\Matter;
use Domain\Schedule\Schedule;
use Domain\Student\Student;
use Domain\Teacher\Teacher;
use Domain\User\User;

$factory->define(Billet::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\DateTime($faker));

    $student = factory(Student::class)->create();
    $recipient = BilletAssignor::first();

    if (is_null($recipient)) {
        $recipient = factory(BilletAssignor::class)->create();
    }

    return [
        'student_id' => $student->id,

        'due_date' => $faker->date,
        'amount' => $faker->randomDigit(3),
        'number' => $faker->unique()->randomDigit(10), //numero //
        'document_number' => $faker->unique()->randomDigit(10), //numeroDocumento //
        'statement01' => $faker->sentence(5), //descricaoDemonstrativo //  máximo de 5
        'statement02' => $faker->sentence(5), //descricaoDemonstrativo //  máximo de 5
        'statement03' => $faker->sentence(5), //descricaoDemonstrativo //  máximo de 5
        'statement04' => $faker->sentence(5), //descricaoDemonstrativo //  máximo de 5
        'statement05' => $faker->sentence(5), //descricaoDemonstrativo //  máximo de 5
        'instruction01' => $faker->sentence(5), //instrucoes // máximo de 5
        'instruction02' => $faker->sentence(5), //instrucoes // máximo de 5
        'instruction03' => $faker->sentence(5), //instrucoes // máximo de 5
        'instruction04' => $faker->sentence(5), //instrucoes // máximo de 5
        'instruction05' => $faker->sentence(5), //instrucoes // máximo de 5
        'acceptance' => $faker->randomElement([0, 1]), //aceite //
        'kind_document' => $recipient->kind_document, //especieDoc //

        'payer' => json_encode([
            'nome' => $student->responsible,
            'endereco' => $student->street.', '.$student->number.' '.$student->complement,
            'cep' => $student->postcode,
            'uf' => $student->stateAbbr,
            'cidade' => $student->city,
            'documento' => $student->cpf_responsible,
        ]),

        'recipient' => json_encode([
            'nome' => $recipient->name,
            'endereco' => $recipient->street.', '.$recipient->number.' '.$recipient->complement,
            'cep' => $recipient->postcode,
            'uf' => $recipient->stateAbbr,
            'cidade' => $recipient->city,
            'documento' => $recipient->cnpj,
        ]),

        'bank' => $recipient->bank,
        'agency' => $recipient->agency,
        'digit_agency' => $recipient->digit_agency,
        'account' => $recipient->account,
        'digit_account' => $recipient->digit_account,
        'wallet' => $recipient->wallet,
        'agreement' => $recipient->agreement,
        'acceptance' => $recipient->acceptance,
        'payment_of_fine' => $recipient->payment_of_fine,
        'interest' => $recipient->interest, //juros // porcento ao mes
        'is_interest' => $recipient->is_interest, //juros_apos //
        'days_protest' => $recipient->days_protest, //diasProtesto // protestar após, se for necessário

        'refer' => date_format($faker->dateTimeBetween('now', '+1 year'), 'Ym'),
        'note' => $faker->sentence(5),
    ];
});

$factory->define(BilletAssignor::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Address($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Company($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));

    $bank = $faker->randomElement(['bb', 'bradesco', 'caixa', 'hsbc', 'itau', 'santander']);

    switch ($bank) {
        case 'bb':
            $wallet = ['11', '12', '15', '16', '17', '18', '31', '51'];
            break;
        case 'bradesco':
            $wallet = ['09', '28'];
            break;
        case 'caixa':
            $wallet = ['RG'];
            break;
        case 'hsbc':
            $wallet = ['CSB'];
            break;
        case 'itau':
            $wallet = ['112', '115', '188', '109', '121', '180', '175'];
            break;
        case 'santander':
            $wallet = ['101', '201'];
            break;
    }

    return [
        'name' => $faker->unique()->name,
        'cnpj' => $faker->cnpj(false),

        'postcode' => str_replace('-', '', $faker->postcode()),
        'street' => $faker->streetName,
        'number' => $faker->randomDigit(2, 3),
        'district' => $faker->name,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,

        'bank' => $bank,
        'agency' => rand(1, 9999),
        'digit_agency' => rand(1, 9),
        'account' => rand(1, 99999999),
        'digit_account' => rand(0, 9),
        'wallet' => $faker->randomElement($wallet),
        'agreement' => 4545,
        'acceptance' => $faker->randomElement([1, 0]),
        'days_protest' => rand(0, 10),
        'client_id' => $faker->randomDigit(2, 3),
        'range' => $faker->randomDigit(2, 3),
        'kind_document' => 'DM',
        'portfolio_change' => $faker->randomDigit(2, 3),
    ];
});

$factory->define(Classroom::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->name,
        'teacher_id' => factory(Teacher::class)->create()->id,
        'schedule_id' => factory(Schedule::class)->create()->id,
    ];
});

$factory->define(ClassroomMatter::class, function (Faker\Generator $faker) {
    return [
        'classroom_id' => factory(Classroom::class)->create()->id,
        'matter_id' => factory(Matter::class)->create()->id,
    ];
});

$factory->define(Config::class, function (Faker\Generator $faker) {
    return [
        'field' => $faker->unique()->name,
        'value' => $faker->name,
    ];
});

$factory->define(Employee::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));

    $sex = $faker->randomElement(['male', 'female']);

    return [
        'name' => $faker->name($sex),
        'sex' => $sex,
    ];
});

$factory->define(Lesson::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->name,
        'description' => $faker->sentence,
    ];
});

$factory->define(Matter::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->name,
        'workload' => $faker->randomDigit(2),
    ];
});

$factory->define(Schedule::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->name,
        'start' => $faker->time,
        'end' => $faker->time,
    ];
});

$factory->define(Student::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Address($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker));

    $sex = $faker->randomElement(['male', 'female']);

    return [
        'name' => $faker->name($sex),
        'sex' => $sex,

        'father' => $faker->name('male'),
        'mother' => $faker->name('female'),
        'responsible' => $faker->name,
        'cpf_responsible' => $faker->cpf(false),
        'phone_father' => $faker->cellphoneNumber(false),
        'phone_mother' => $faker->cellphoneNumber(false),
        'phone_responsible' => $faker->cellphoneNumber(false),
        'postcode' => str_replace('-', '', $faker->postcode()),
        'street' => $faker->streetName,
        'number' => $faker->randomDigit(2, 3),
        'district' => $faker->name,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'phone' => $faker->landlineNumber(false),
        'cellphone' => $faker->cellphoneNumber(false),
        'monthly_payment' => $faker->randomDigit(3),
        'day_of_payment' => $faker->randomDigit(2),
        'installments' => $faker->randomDigit(1),
        'status' => $faker->randomElement([0, 1]),
    ];
});

$factory->define(Teacher::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Address($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker));

    $sex = $faker->randomElement(['male', 'female']);

    return [
        'name' => $faker->name($sex),
        'sex' => $sex,

        'cpf' => $faker->cpf(false),
        'rg' => $faker->rg(false),
        'postcode' => str_replace('-', '', $faker->postcode()),
        'street' => $faker->streetName,
        'number' => $faker->randomDigit(2, 3),
        'district' => $faker->name,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'phone' => $faker->landlineNumber(false),
        'cellphone' => $faker->cellphoneNumber(false),

        'salary' => $faker->randomFloat(3, 1),
        'type_salary' => $faker->randomElement(['commission', 'class_time']),

        'status' => $faker->randomElement([0, 1]),
    ];
});

$factory->define(User::class, function (Faker\Generator $faker) {
    $type = $faker->randomElement($array = ['Domain\Student\Student', 'Domain\Teacher\Teacher', 'Domain\Employee\Employee']);

    $owner = factory($type)->create();

    return [
        'email' => $faker->safeEmail,
        'username' => $faker->username,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'owner_id' => $owner->id,
        'owner_type' => $type,
    ];
});
