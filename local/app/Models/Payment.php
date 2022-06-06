<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoapClient;

class Payment extends Model
{
    private $TerminalId;
    private $Username;
    private $UserPassword;
    private $OrderId;
    private $SaleOrderId;
    private $LocalDate;
    private $LocalTime;
    private $AdditionalData;
    private $PayerId;
    private $SaleReferenceId;

    public function __construct($url = null, $amount = null, $description = null, $param1 = null, $param2 = null)
    {
        $carbon = new Carbon();
        $localdate = $carbon->format('Ymd');
        $localtime = $carbon->format('His');
        $this->TerminalId = config('app.terminalId');
        $this->Username = config('app.user_bank');
        $this->UserPassword = config('app.password_bank');
        $this->OrderId = $param1;
        $this->SaleOrderId = $param1;
        $this->SaleReferenceId = $param2;
        $this->Amount = $amount;
        $this->LocalDate = $localdate;
        $this->LocalTime = $localtime;
        $this->AdditionalData = ' ';
        $this->PayerId = 0;
        if ($url != null) {
            $this->CallbackURL = config('app.url') . '' . $url . '?data=' . $param2; // Required
        }
    }

    public function doPayment()
    {
        $client = new SoapClient(config('app.bank_uri'), ['encoding' => 'UTF-8']);
        $result = $client->bpPayrequest(
            [
                'terminalId' => $this->TerminalId,
                'userName' => $this->Username,
                'userPassword' => $this->UserPassword,
                'orderId' => $this->OrderId,
                'amount' => $this->Amount,
                'localDate' => $this->LocalDate,
                'localTime' => $this->LocalTime,
                'additionalData' => $this->AdditionalData,
                'callBackUrl' => $this->CallbackURL,
                'payerId' => $this->PayerId,
            ]
        );
        return $result;
    }

    public function verifyPayment()
    {
        $client = new SoapClient(config('app.bank_uri'), ['encoding' => 'UTF-8']);

        $result = $client->bpVerifyRequest(
            [
                'terminalId' => $this->TerminalId,
                'userName' => $this->Username,
                'userPassword' => $this->UserPassword,
                'orderId' => $this->OrderId,
                'saleOrderId' => $this->SaleOrderId,
                'saleReferenceId' => $this->SaleReferenceId,

            ]
        );

        return $result;
    }

    public function settleRequest()
    {
        $client = new SoapClient(config('app.bank_uri'), ['encoding' => 'UTF-8']);

        $result = $client->bpSettleRequest(
            [
                'terminalId' => $this->TerminalId,
                'userName' => $this->Username,
                'userPassword' => $this->UserPassword,
                'orderId' => $this->OrderId,
                'saleOrderId' => $this->SaleOrderId,
                'saleReferenceId' => $this->SaleReferenceId,

            ]
        );

        return $result;
    }

}
