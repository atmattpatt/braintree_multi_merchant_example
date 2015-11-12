<?php

require __DIR__ . '/vendor/autoload.php';

$sandboxAccounts = require 'credentials.php';

foreach ($sandboxAccounts as $sandboxAccount => $credentials) {
    printf("Creating a transaction on sandbox account %s\n", $sandboxAccount);

    $credentials['environment'] = 'sandbox';
    $configuration = new Braintree\Configuration($credentials);

    $gateway = new Braintree\Gateway($configuration);

    $result = $gateway->transaction()->sale([
        'amount' => '100.00',
        'creditCard' => [
            'number' => '4111111111111111',
            'expirationMonth' => '01',
            'expirationYear' => '2020',
        ],
        'options' => [
            'submitForSettlement' => true,
        ],
    ]);

    if ($result->success) {
        $transaction = $result->transaction;
        printf("\tSuccess! Created transaction %s for %0.2f %s\n", $transaction->id, $transaction->amount, $transaction->currencyIsoCode);
    } else {
        printf("\tError!\n");
        print_r($result->errors->deepAll());
    }
}
