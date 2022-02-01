<?php

namespace App\Services;


class AramexRateDetails
{
   public static function getRateDetails($query){
        $quantity = 0 ;
        $weight = 0 ;
        $unit = '';



       foreach($query['payload']['checkout']['purchases'] as $purchase){
            $weight += $purchase['dimensions']['weight']['value'];
            $quantity += $purchase['quantity'];
            $unit = strToUpper($purchase['dimensions']['weight']['unit']);
        }

        return ([
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
            "DestinationAddress" => [
                "Line1" => $query['payload']['checkout']['shipping']['destination']['line1'],
                "Line2" => $query['payload']['checkout']['shipping']['destination']['line2'],
                "Line3" => "",
                "City" => $query['payload']['checkout']['shipping']['destination']['city'],
                "StateOrProvinceCode" => "",
                "PostCode" => $query['payload']['checkout']['shipping']['destination']['postcode'],
                "CountryCode" => $query['payload']['checkout']['shipping']['destination']['country'],
                "Longitude" => $query['payload']['checkout']['shipping']['destination']['lng'],
                "Latitude" => $query['payload']['checkout']['shipping']['destination']['lat'],
                "BuildingNumber" => null,
                "BuildingName" => $query['payload']['checkout']['shipping']['destination']['name'],
                "Floor" => null,
                "Apartment" => null,
                "POBox" => null,
                "Description" => null,
            ],
            "OriginAddress" => [
                "Line1" => $query['payload']['checkout']['shipping']['source']['line1'],
                "Line2" => $query['payload']['checkout']['shipping']['source']['line2'],
                "Line3" => "",
                "City" => $query['payload']['checkout']['shipping']['source']['city'],
                "StateOrProvinceCode" => "",
                "PostCode" => $query['payload']['checkout']['shipping']['source']['postcode'],
                "CountryCode" => $query['payload']['checkout']['shipping']['source']['country'],
                "Longitude" => $query['payload']['checkout']['shipping']['source']['lng'],
                "Latitude" => $query['payload']['checkout']['shipping']['source']['lat'],
                "BuildingNumber" => null,
                "BuildingName" => $query['payload']['checkout']['shipping']['source']['name'],
                "Floor" => null,
                "Apartment" => null,
                "POBox" => null,
                "Description" => null,
            ],
            "PreferredCurrencyCode" => "USD",
            "ShipmentDetails" => [
                "Dimensions" => null,
                "ActualWeight" => [
                    "Unit" => $unit,
                    "Value" => $weight,
                ],
                "ChargeableWeight" => null,
                "DescriptionOfGoods" => null,
                "GoodsOriginCountry" => null,
                "NumberOfPieces" => $quantity,
                "ProductGroup" => "DOM",
                "ProductType" => "OND",
                "PaymentType" => "P",
                "PaymentOptions" => "",
                "CustomsValueAmount" => null,
                "CashOnDeliveryAmount" => null,
                "InsuranceAmount" => null,
                "CashAdditionalAmount" => null,
                "CashAdditionalAmountDescription" => null,
                "CollectAmount" => null,
                "Services" => "",
                "Items" => null,
                "DeliveryInstructions" => null,
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
