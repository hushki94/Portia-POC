<?php

namespace App\Services;


class AramexShipmentDetails {

    public static function getAramexShipmentDetails($query){

        function dateFormatter($date){
            $tz = 2 * 100;
            $tz = $tz > 0 ? '-' . $tz : '+' . $tz;
            return '/Date(' . (\Carbon\Carbon::parse($date)->timestamp) * 1000 . $tz . ')/';
        }

        $weight = 0;
        $quantity = 0;
        $unit = '';
        $width = 0;
        $height = 0;
        $length = 0;
        $itemUnit = '';

        foreach ($query['payload']['order']['purchases'] as $purchase) {
            $weight += $purchase['dimensions']['weight']['value'];
            $width += $purchase['dimensions']['width']['value'];
            $height += $purchase['dimensions']['height']['value'];
            $length += $purchase['dimensions']['length']['value'];

            $unit = strToUpper($purchase['dimensions']['weight']['unit']);
            $itemUnit = strToUpper($purchase['dimensions']['width']['unit']);

            $quantity += $purchase['quantity'];
        }

       return  ([
            "ClientInfo" => [
                "UserName" => "reem@reem.com",
                "Password" => "123456789",
                "Version" => "v1",
                "AccountNumber" => "20016",
                "AccountPin" => "331421",
                "AccountEntity" => "AMM",
                "AccountCountryCode" => "JO",
                "Source" => 24,
            ],
            "LabelInfo" => null,
            "Shipments" => [
                [
                    "Reference1" => "",
                    "Reference2" => "",
                    "Reference3" => "",
                    "Shipper" => [
                        "Reference1" => "",
                        "Reference2" => "",
                        "AccountNumber" => "20016",
                        "PartyAddress" => [
                            "Line1" => $query['payload']['order']['billing_address']['line1'],
                            "Line2" => $query['payload']['order']['billing_address']['line2'],
                            "Line3" => "",
                            "City" => $query['payload']['order']['billing_address']['city'],
                            "StateOrProvinceCode" => "",
                            "PostCode" => $query['payload']['order']['billing_address']['postcode'],
                            "CountryCode" => $query['payload']['order']['billing_address']['country'],
                            "Longitude" => $query['payload']['order']['billing_address']['lng'],
                            "Latitude" => $query['payload']['order']['billing_address']['lat'],
                            "BuildingNumber" => null,
                            "BuildingName" => null,
                            "Floor" => null,
                            "Apartment" => null,
                            "POBox" => null,
                            "Description" => null,
                        ],
                        "Contact" => [
                            "Department" => "",
                            "PersonName" => $query['payload']['order']['billing_address']['name'],
                            "Title" => "",
                            "CompanyName" => $query['meta']['tenant']['name'],
                            "PhoneNumber1" => $query['payload']['order']['billing_address']['telephone'],
                            "PhoneNumber1Ext" => "",
                            "PhoneNumber2" => "",
                            "PhoneNumber2Ext" => "",
                            "FaxNumber" => "",
                            "CellPhone" => $query['payload']['order']['billing_address']['telephone'],
                            "EmailAddress" => $query['payload']['order']['billing_address']['email'],
                            "Type" => "",
                        ],
                    ],
                    "Consignee" => [
                        "Reference1" => "",
                        "Reference2" => "",
                        "AccountNumber" => "",
                        "PartyAddress" => [
                            "Line1" => $query['payload']['order']['shipping_address']['line1'],
                            "Line2" => $query['payload']['order']['shipping_address']['line2'],
                            "Line3" => "",
                            "City" => $query['payload']['order']['shipping_address']['city'],
                            "StateOrProvinceCode" => "",
                            "PostCode" => $query['payload']['order']['shipping_address']['postcode'],
                            "CountryCode" => $query['payload']['order']['shipping_address']['country'],
                            "Longitude" => $query['payload']['order']['shipping_address']['lng'],
                            "Latitude" => $query['payload']['order']['shipping_address']['lat'],
                            "BuildingNumber" => "",
                            "BuildingName" => "",
                            "Floor" => "",
                            "Apartment" => "",
                            "POBox" => null,
                            "Description" => "",
                        ],
                        "Contact" => [
                            "Department" => "",
                            "PersonName" => $query['payload']['order']['shipping_address']['name'],
                            "Title" => "",
                            "CompanyName" => $query['payload']['details']['company_name'],
                            "PhoneNumber1" => $query['payload']['order']['shipping_address']['telephone'],
                            "PhoneNumber1Ext" => "",
                            "PhoneNumber2" => "",
                            "PhoneNumber2Ext" => "",
                            "FaxNumber" => "",
                            "CellPhone" => $query['payload']['order']['shipping_address']['telephone'],
                            "EmailAddress" => $query['payload']['order']['shipping_address']['email'],
                            "Type" => "",
                        ],
                    ],
                    "ShippingDateTime" => dateFormatter(now()),
                    "DueDate" => dateFormatter(now()),
                    "Comments" => "",
                    "PickupLocation" => "",
                    "OperationsInstructions" => "",
                    "AccountingInstrcutions" => "",
                    "Details" => [
                        "Dimensions" => null,
                        "ActualWeight" => [
                            "Unit" => $unit,
                            "Value" => $weight,
                        ],
                        "ChargeableWeight" => null,
                        "DescriptionOfGoods" => $query['payload']['details']['goods_description'],
                        "GoodsOriginCountry" => $query['payload']['details']['country_of_origin'],
                        "NumberOfPieces" => $quantity,
                        "ProductGroup" => $query['payload']['order']['shipping_address']['country'] === $query['payload']['order']['billing_address']['country'] ? 'DOM' : 'EXP',
                        "ProductType" => $query['payload']['order']['shipping_address']['country'] === $query['payload']['order']['billing_address']['country'] ? 'OND' : 'PDX',
                        "PaymentType" => $query['payload']['order']['payment_method'] === "cash_on_delivery" ? 'P' : 'P',
                        "PaymentOptions" => $query['payload']['order']['payment_method'] === "cash_on_delivery" ? "" : "",
                        "CustomsValueAmount" => null,
                        "CashOnDeliveryAmount" => null,
                        "InsuranceAmount" => null,
                        "CashAdditionalAmount" => null,
                        "CashAdditionalAmountDescription" => "",
                        "CollectAmount" => null,
                        "Services" => "",
                        "Items" => [],
                    ],
                    "Attachments" => [],
                    "ForeignHAWB" => "",
                    "TransportType " => 0,
                    "PickupGUID" => "",
                    "Number" => null,
                    "ScheduledDelivery" => null,
                ],
            ],
            "Transaction" => [
                "Reference1" => "",
                "Reference2" => "",
                "Reference3" => "",
                "Reference4" => "",
                "Reference5" => "",
            ],
        ]);
    }
}
