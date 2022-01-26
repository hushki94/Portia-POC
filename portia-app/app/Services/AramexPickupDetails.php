<?php

namespace App\Services;

class AramexPickupDetails
{

    public static function getAramexPickupDetails($shipmentId,$query)
    {


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

        return ([
            "ClientInfo" => [
                "UserName" => "testingapi@aramex.com",
                "Password" => 'R123456789$r',
                "Version" => "v1",
                "AccountNumber" => "20016",
                "AccountPin" => "331421",
                "AccountEntity" => "AMM",
                "AccountCountryCode" => "JO",
                "Source" => 24,
            ],
            "LabelInfo" => [
                "ReportID" => 9201,
                "ReportType" => "URL",
            ],
            "Pickup" => [
                "PickupAddress" => [
                    "Line1" => $query['payload']['order']['billing_address']['line1'],
                    "Line2" => "",
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
                "PickupContact" => [
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
                "PickupLocation" => "test",
                "PickupDate" => dateFormatter(now()->addDay()),
                "ReadyTime" => dateFormatter(now()),
                "LastPickupTime" => dateFormatter(now()->addDay()),
                "ClosingTime" => dateFormatter(now()->addDay()),
                "Comments" => "",
                "Reference1" => "001",
                "Reference2" => "",
                "Vehicle" => "",
                "Shipments" => [
                    [
                        "Reference1" => "123",
                        "Reference2" => "",
                        "Reference3" => "",
                        "Shipper" => [
                            "Reference1" => "",
                            "Reference2" => "",
                            "AccountNumber" => "20016",
                            "PartyAddress" => [
                                "Line1" => $query['payload']['order']['billing_address']['line1'],
                                "Line2" => "",
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
                                "Line2" => "",
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
                            "PaymentOptions" => $query['payload']['order']['payment_method'] === "cash_on_delivery" ? '' : '',
                            "CustomsValueAmount" => null,
                            "CashOnDeliveryAmount" => null,
                            "InsuranceAmount" => null,
                            "CashAdditionalAmount" => null,
                            "CashAdditionalAmountDescription" => "",
                            "CollectAmount" => null,
                            "Services" => "",
                            "Items" => [],
                            "DeliveryInstructions" => null,
                        ],
                        "Attachments" => [],
                        "ForeignHAWB" => $shipmentId->json("Shipments")[0]['ID'],
                        "TransportType " => 0,
                        "PickupGUID" => null,
                        "Number" => "",
                        "ScheduledDelivery" => null,
                    ],
                ],
                "PickupItems" => [
                    [
                        "ProductGroup" => $query['payload']['order']['shipping_address']['country'] === $query['payload']['order']['billing_address']['country'] ? 'DOM' : 'EXP',
                        "ProductType" => $query['payload']['order']['shipping_address']['country'] === $query['payload']['order']['billing_address']['country'] ? 'OND' : 'PDX',
                        "NumberOfShipments" => 1,
                        "PackageType" => "Box",
                        "Payment" => $query['payload']['order']['payment_method'] === "cash_on_delivery" ? 'C' : 'P',
                        "ShipmentWeight" => [
                            "Unit" => $unit,
                            "Value" => $weight,
                        ],
                        "ShipmentVolume" => null,
                        "NumberOfPieces" => $quantity,
                        "CashAmount" => null,
                        "ExtraCharges" => null,
                        "ShipmentDimensions" => [
                            "Length" => $length,
                            "Width" => $width,
                            "Height" => $height,
                            "Unit" => $itemUnit,
                        ],
                        "Comments" => "",
                    ],
                ],
                "Status" => "Ready",
                "ExistingShipments" => null,
                "Branch" => "",
                "RouteCode" => "",
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
