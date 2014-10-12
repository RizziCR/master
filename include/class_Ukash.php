<?php
/*
 *
 *	UKASH Sample SSL HTTP POST Redemption.
 *
 */
require_once('nusoap/nusoap.php');

class Ukash {

    const LOG_FILE = '/var/www/ukash_log/ukash_log.txt';

    private $_client = null;
    private $_proxy = null;

    private $_error = null;
    private $_result = null;

    private $_ukash_merchant = 'UKASH_ETS2021';
    private $_ukash_password = 'qwtyerv234789jff';
    private $_ukash_brandid  = 'UKASH11108';

    private $_changeVoucher = null;

    public function __construct() {
        $proxyhost = '';
        $proxyport = '';
        $proxyusername = '';
        $proxypassword = '';

        // LIVE URL:
        $this->_client = new nusoap_client('https://processing.ukash.com/gateway/Ukash.WSDL', true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
        // STAGING URL:
        // $this->_client = new nusoap_client('https://ukashst.mercantrade.com/processing/Ukash.WSDL', true, $proxyhost, $proxyport, $proxyusername, $proxypassword);
        $this->_proxy = $this->_client->getProxy();
    }

    public function makePayment($transaction_id, $voucherNumber, $voucherValue, $ticketValue, $nick) {

        $voucherValue = floatval($voucherValue);
        $ticketValue = floatval($ticketValue);

        if(empty($voucherNumber) || !preg_match('/^\d{16}(\d{3}){0,1}$/', $voucherNumber) || $voucherValue == 0 || $ticketValue == 0)
            return false;

        if(floatval($voucherValue) == floatval($ticketValue))
            $ticketValue = '';

        $params = array('sRequest' =>
        '<UKashTransaction>'.
            '<ukashLogin>'.$this->_ukash_merchant.'</ukashLogin>'.
            '<ukashPassword>'.$this->_ukash_password.'</ukashPassword>'.
            '<transactionId>'.$transaction_id.'</transactionId>'.
            '<brandId>'.$this->_ukash_brandid.'</brandId>'.
            '<ukashNumber></ukashNumber>'.
            '<voucherNumber>'.$voucherNumber.'</voucherNumber>'.
            '<voucherValue>'.number_format($voucherValue, 2, '.', '').'</voucherValue>'.
            '<baseCurr>EUR</baseCurr>'.
            '<ticketValue>'.( $ticketValue=='' ? '' : number_format($ticketValue, 2, '.', '')).'</ticketValue>'.
            '<redemptionType>3</redemptionType>'.
            '<merchDateTime>'.date('Y-m-d H:i:s').'</merchDateTime>'.
            '<merchCustomValue>'.$nick.'</merchCustomValue>'.
            '<storeLocationId></storeLocationId>'.
            '<amountReference></amountReference>'.
        '</UKashTransaction>');

        //Check if an error occured in the connection to the server. If so display error.
        $err = $this->_client->getError();
        if ($err) {
            $this->_error = $err;
            return false;
        }

        //Call the redemption method of the Ukash web service, passing the array that
        //was created above.
        $result = $this->_proxy->Redemption($params);

        //Check if an error occured, if so display it. Otherwise display the successful results.
        if ($this->_client->fault) {
            $this->_error = $result;
            return false;
        } else {
            $err = $this->_client->getError();
            if ($err) {
                $this->_error = $err;
                return false;
            } else {
                $this->_result = $result['RedemptionResult'];
                $result_ukash = simplexml_load_string($this->_result);
                if($result_ukash->txCode == 0) {
                    $this->_changeVoucher = new Ukash_Voucher($result_ukash);
                    $this->writeLog($result_ukash);
                    return true;
                }
                else {
                    $this->_error = ''.$result_ukash->errDescription;
                    return false;
                }
            }
        }
    }

    public function getError() {
        return $this->_error;
    }

    public function getResult() {
        return $this->_result;
    }

    public function getChangeVoucher() {
        return $this->_changeVoucher;
    }

    public function writeLog($xml) {
        if (is_writable(self::LOG_FILE)) {
            if (!$handle = fopen(self::LOG_FILE, 'a')) {
                 echo "Cannot open file (".self::LOG_FILE.")";
                 return false;
            }
            if (fwrite($handle, $xml->asXML()) === FALSE) {
                echo "Cannot write to file ($filename)";
                return false;
            }
            fclose($handle);
            return true;
        } else {
            echo "The file ".self::LOG_FILE." is not writable";
        }
        return false;
    }
}

class Ukash_Voucher {
    private $_voucherNumber = null;
    private $_voucherCurrency = null;
    private $_voucherAmount = null;
    private $_voucherExpiryDate = null;

    /**
     * XML FORAT of ANSWER
     * [RedemptionResult] =>
     * <UKashTransaction>
     * <txCode>0</txCode>
     * <txDescription>Accepted</txDescription>
     * <settleAmount>10.00</settleAmount>
     * <transactionId>Gawa2c484a061</transactionId>
     * <changeIssueVoucherNumber>6337180110432122926</changeIssueVoucherNumber>
     * <changeIssueVoucherCurr>EUR</changeIssueVoucherCurr>
     * <changeIssueAmount>10.00</changeIssueAmount>
     * <changeIssueExpiryDate>2009-02-27</changeIssueExpiryDate>
     * <ukashTransactionId>SV_5869_200802290743</ukashTransactionId>
     * <currencyConversion>FALSE</currencyConversion>
     * <errCode></errCode>
     * <errDescription></errDescription>
     * </UKashTransaction>)
     */
    public function __construct(SimpleXMLElement $xml) {
        $this->_voucherNumber = $xml->changeIssueVoucherNumber;
        $this->_voucherAmount = $xml->changeIssueAmount;
        $this->_voucherCurrency = $xml->changeIssueVoucherCurr;
        $this->_voucherExpiryDate = $xml->changeIssueExpiryDate;
    }

    public function getNumber() {
        return $this->_voucherNumber;
    }

    public function getCurrency() {
        return $this->_voucherCurrency;
    }

    public function getAmount() {
        return $this->_voucherAmount;
    }

    public function getExpiryDate() {
        return $this->_voucherExpiryDate;
    }
}
?>