<?php

namespace App\Jobs;

use App\Mail\WaybillMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class GenerateWaybillAndEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $query;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function dateFormatter($date)
    {

        $tz = 2 * 100;
        $tz = $tz > 0 ? '-' . $tz : '+' . $tz;
        return '/Date(' . (\Carbon\Carbon::parse($date)->timestamp) * 1000 . $tz . ')/';
    }

    public function handle()
    {
        // Logger('ho');
        $weight = 0;
        $quantity = 0;
        $unit = '';
        $width = 0;
        $height = 0;
        $length = 0;
        $itemUnit = '';

        foreach ($this->query['payload']['order']['purchases'] as $purchase) {
            $weight += $purchase['dimensions']['weight']['value'];
            $width += $purchase['dimensions']['width']['value'];
            $height += $purchase['dimensions']['height']['value'];
            $length += $purchase['dimensions']['length']['value'];

            $unit = strToUpper($purchase['dimensions']['weight']['unit']);
            $itemUnit = strToUpper($purchase['dimensions']['width']['unit']);

            $quantity += $purchase['quantity'];
        }

        $shipment = Http::post('https://ws.dev.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments',  [
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
                            "Line1" => $this->query['payload']['order']['billing_address']['line1'],
                            "Line2" => $this->query['payload']['order']['billing_address']['line2'],
                            "Line3" => "",
                            "City" => $this->query['payload']['order']['billing_address']['city'],
                            "StateOrProvinceCode" => "",
                            "PostCode" => $this->query['payload']['order']['billing_address']['postcode'],
                            "CountryCode" => $this->query['payload']['order']['billing_address']['country'],
                            "Longitude" => $this->query['payload']['order']['billing_address']['lng'],
                            "Latitude" => $this->query['payload']['order']['billing_address']['lat'],
                            "BuildingNumber" => null,
                            "BuildingName" => null,
                            "Floor" => null,
                            "Apartment" => null,
                            "POBox" => null,
                            "Description" => null,
                        ],
                        "Contact" => [
                            "Department" => "",
                            "PersonName" => $this->query['payload']['order']['billing_address']['name'],
                            "Title" => "",
                            "CompanyName" => $this->query['meta']['tenant']['name'],
                            "PhoneNumber1" => $this->query['payload']['order']['billing_address']['telephone'],
                            "PhoneNumber1Ext" => "",
                            "PhoneNumber2" => "",
                            "PhoneNumber2Ext" => "",
                            "FaxNumber" => "",
                            "CellPhone" => $this->query['payload']['order']['billing_address']['telephone'],
                            "EmailAddress" => $this->query['payload']['order']['billing_address']['email'],
                            "Type" => "",
                        ],
                    ],
                    "Consignee" => [
                        "Reference1" => "",
                        "Reference2" => "",
                        "AccountNumber" => "",
                        "PartyAddress" => [
                            "Line1" => $this->query['payload']['order']['shipping_address']['line1'],
                            "Line2" => $this->query['payload']['order']['shipping_address']['line2'],
                            "Line3" => "",
                            "City" => $this->query['payload']['order']['shipping_address']['city'],
                            "StateOrProvinceCode" => "",
                            "PostCode" => $this->query['payload']['order']['shipping_address']['postcode'],
                            "CountryCode" => $this->query['payload']['order']['shipping_address']['country'],
                            "Longitude" => $this->query['payload']['order']['shipping_address']['lng'],
                            "Latitude" => $this->query['payload']['order']['shipping_address']['lat'],
                            "BuildingNumber" => "",
                            "BuildingName" => "",
                            "Floor" => "",
                            "Apartment" => "",
                            "POBox" => null,
                            "Description" => "",
                        ],
                        "Contact" => [
                            "Department" => "",
                            "PersonName" => $this->query['payload']['order']['shipping_address']['name'],
                            "Title" => "",
                            "CompanyName" => $this->query['payload']['details']['company_name'],
                            "PhoneNumber1" => $this->query['payload']['order']['shipping_address']['telephone'],
                            "PhoneNumber1Ext" => "",
                            "PhoneNumber2" => "",
                            "PhoneNumber2Ext" => "",
                            "FaxNumber" => "",
                            "CellPhone" => $this->query['payload']['order']['shipping_address']['telephone'],
                            "EmailAddress" => $this->query['payload']['order']['shipping_address']['email'],
                            "Type" => "",
                        ],
                    ],
                    "ShippingDateTime" => $this->dateFormatter(now()),
                    "DueDate" => $this->dateFormatter(now()),
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
                        "DescriptionOfGoods" => $this->query['payload']['details']['goods_description'],
                        "GoodsOriginCountry" => $this->query['payload']['details']['country_of_origin'],
                        "NumberOfPieces" => $quantity,
                        "ProductGroup" => $this->query['payload']['order']['shipping_address']['country'] === $this->query['payload']['order']['billing_address']['country'] ? 'DOM' : 'EXP',
                        "ProductType" => $this->query['payload']['order']['shipping_address']['country'] === $this->query['payload']['order']['billing_address']['country'] ? 'OND' : 'PDX',
                        "PaymentType" => $this->query['payload']['order']['payment_method'] === "cash_on_delivery" ? 'P' : 'P',
                        "PaymentOptions" => $this->query['payload']['order']['payment_method'] === "cash_on_delivery" ? "" : "",
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



        $pickupLabel = Http::post('https://ws.dev.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreatePickup', [
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
                    "Line1" => $this->query['payload']['order']['billing_address']['line1'],
                    "Line2" => "",
                    "Line3" => "",
                    "City" => $this->query['payload']['order']['billing_address']['city'],
                    "StateOrProvinceCode" => "",
                    "PostCode" => $this->query['payload']['order']['billing_address']['postcode'],
                    "CountryCode" => $this->query['payload']['order']['billing_address']['country'],
                    "Longitude" => $this->query['payload']['order']['billing_address']['lng'],
                    "Latitude" => $this->query['payload']['order']['billing_address']['lat'],
                    "BuildingNumber" => null,
                    "BuildingName" => null,
                    "Floor" => null,
                    "Apartment" => null,
                    "POBox" => null,
                    "Description" => null,
                ],
                "PickupContact" => [
                    "Department" => "",
                    "PersonName" => $this->query['payload']['order']['billing_address']['name'],
                    "Title" => "",
                    "CompanyName" => $this->query['meta']['tenant']['name'],
                    "PhoneNumber1" => $this->query['payload']['order']['billing_address']['telephone'],
                    "PhoneNumber1Ext" => "",
                    "PhoneNumber2" => "",
                    "PhoneNumber2Ext" => "",
                    "FaxNumber" => "",
                    "CellPhone" => $this->query['payload']['order']['billing_address']['telephone'],
                    "EmailAddress" => $this->query['payload']['order']['billing_address']['email'],
                    "Type" => "",
                ],
                "PickupLocation" => "test",
                "PickupDate" => $this->dateFormatter(now()->addDay()),
                "ReadyTime" => $this->dateFormatter(now()),
                "LastPickupTime" => $this->dateFormatter(now()->addDay()),
                "ClosingTime" => $this->dateFormatter(now()->addDay()),
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
                                "Line1" => $this->query['payload']['order']['billing_address']['line1'],
                                "Line2" => "",
                                "Line3" => "",
                                "City" => $this->query['payload']['order']['billing_address']['city'],
                                "StateOrProvinceCode" => "",
                                "PostCode" => $this->query['payload']['order']['billing_address']['postcode'],
                                "CountryCode" => $this->query['payload']['order']['billing_address']['country'],
                                "Longitude" => $this->query['payload']['order']['billing_address']['lng'],
                                "Latitude" => $this->query['payload']['order']['billing_address']['lat'],
                                "BuildingNumber" => null,
                                "BuildingName" => null,
                                "Floor" => null,
                                "Apartment" => null,
                                "POBox" => null,
                                "Description" => null,
                            ],
                            "Contact" => [
                                "Department" => "",
                                "PersonName" => $this->query['payload']['order']['billing_address']['name'],
                                "Title" => "",
                                "CompanyName" => $this->query['meta']['tenant']['name'],
                                "PhoneNumber1" => $this->query['payload']['order']['billing_address']['telephone'],
                                "PhoneNumber1Ext" => "",
                                "PhoneNumber2" => "",
                                "PhoneNumber2Ext" => "",
                                "FaxNumber" => "",
                                "CellPhone" => $this->query['payload']['order']['billing_address']['telephone'],
                                "EmailAddress" => $this->query['payload']['order']['billing_address']['email'],
                                "Type" => "",
                            ],
                        ],
                        "Consignee" => [
                            "Reference1" => "",
                            "Reference2" => "",
                            "AccountNumber" => "",
                            "PartyAddress" => [
                                "Line1" => $this->query['payload']['order']['shipping_address']['line1'],
                                "Line2" => "",
                                "Line3" => "",
                                "City" => $this->query['payload']['order']['shipping_address']['city'],
                                "StateOrProvinceCode" => "",
                                "PostCode" => $this->query['payload']['order']['shipping_address']['postcode'],
                                "CountryCode" => $this->query['payload']['order']['shipping_address']['country'],
                                "Longitude" => $this->query['payload']['order']['shipping_address']['lng'],
                                "Latitude" => $this->query['payload']['order']['shipping_address']['lat'],
                                "BuildingNumber" => "",
                                "BuildingName" => "",
                                "Floor" => "",
                                "Apartment" => "",
                                "POBox" => null,
                                "Description" => "",
                            ],
                            "Contact" => [
                                "Department" => "",
                                "PersonName" => $this->query['payload']['order']['shipping_address']['name'],
                                "Title" => "",
                                "CompanyName" => $this->query['payload']['details']['company_name'],
                                "PhoneNumber1" => $this->query['payload']['order']['shipping_address']['telephone'],
                                "PhoneNumber1Ext" => "",
                                "PhoneNumber2" => "",
                                "PhoneNumber2Ext" => "",
                                "FaxNumber" => "",
                                "CellPhone" => $this->query['payload']['order']['shipping_address']['telephone'],
                                "EmailAddress" => $this->query['payload']['order']['shipping_address']['email'],
                                "Type" => "",
                            ],
                        ],
                        "ShippingDateTime" => $this->dateFormatter(now()),
                        "DueDate" => $this->dateFormatter(now()),
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
                            "DescriptionOfGoods" => $this->query['payload']['details']['goods_description'],
                            "GoodsOriginCountry" => $this->query['payload']['details']['country_of_origin'],
                            "NumberOfPieces" => $quantity,
                            "ProductGroup" => $this->query['payload']['order']['shipping_address']['country'] === $this->query['payload']['order']['billing_address']['country'] ? 'DOM' : 'EXP',
                            "ProductType" => $this->query['payload']['order']['shipping_address']['country'] === $this->query['payload']['order']['billing_address']['country'] ? 'OND' : 'PDX',
                            "PaymentType" => $this->query['payload']['order']['payment_method'] === "cash_on_delivery" ? 'P' : 'P',
                            "PaymentOptions" => $this->query['payload']['order']['payment_method'] === "cash_on_delivery" ? '' : '',
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
                        "ForeignHAWB" => $shipment->json("Shipments")[0]['ID'],
                        "TransportType " => 0,
                        "PickupGUID" => null,
                        "Number" => "",
                        "ScheduledDelivery" => null,
                    ],
                ],
                "PickupItems" => [
                    [
                        "ProductGroup" => $this->query['payload']['order']['shipping_address']['country'] === $this->query['payload']['order']['billing_address']['country'] ? 'DOM' : 'EXP',
                        "ProductType" => $this->query['payload']['order']['shipping_address']['country'] === $this->query['payload']['order']['billing_address']['country'] ? 'OND' : 'PDX',
                        "NumberOfShipments" => 1,
                        "PackageType" => "Box",
                        "Payment" => $this->query['payload']['order']['payment_method'] === "cash_on_delivery" ? 'C' : 'P',
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



        Mail::to($this->query['meta']['tenant']['owner']['email'])
            ->queue(new WaybillMail($pickupLabel->json('ProcessedPickup')['ProcessedShipments'][0]['ShipmentLabel']['LabelURL']));

    }
}
