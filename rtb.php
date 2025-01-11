<?php

$campaigns = [
    [
        "campaignname" => "Test_Banner_13th-31st_march_Developer",
        "advertiser" => "TestGP",
        "code" => "118965F12BE33FB7E",
        "appid" => "20240313103027",
        "tld" => "https://adplaytechnology.com/",
        "creative_type" => "1",
        "creative_id" => 167629,
        "dimension" => "320x480",
        "attribute" => "rich-media",
        "url" => "https://adplaytechnology.com/",
        "price" => 0.1,
        "bidtype" => "CPM",
        "image_url" => "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png",
        "htmltag" => "",
        "from_hour" => "0",
        "to_hour" => "23",
        "hs_os" => "Android,iOS,Desktop",
        "operator" => "Banglalink,GrameenPhone,Robi,Teletalk,Airtel,Wi-Fi",
        "device_make" => "No Filter",
        "country" => "Bangladesh",
        "city" => "",
        "lat" => "",
        "lng" => "",
        "app_name" => null,
        "user_list_id" => "0",
        "adplay_logo" => 1,
        "vast_video_duration" => null,
        "logo_placement" => 1,
        "hs_model" => null,
        "is_rewarded_inventory" => 0,
        "pixel_tag" => null,
        "dmp_campaign_audience" => 0,
        "platform" => null,
        "open_publisher" => 1,
        "audience_targeting" => 0,
        "native_title" => null,
        "native_type" => null,
        "native_data_value" => null,
        "native_data_cta" => null,
        "native_data_rating" => null,
        "native_data_price" => null,
        "native_img_icon" => null
    ]
];

function handleBidRequest($requestJson, $campaigns)
{

    $bidRequest = json_decode($requestJson, true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($bidRequest)) {
        return jsonResponse([], 400);
    }

    $device = $bidRequest['device'] ?? null;
    $geo = $bidRequest['geo'] ?? null;
    $bidFloor = $bidRequest['bid_floor'] ?? 0;

    if (!$device || !$geo || $bidFloor <= 0) {
        return jsonResponse([], 400);
    }

    $eligibleCampaigns = array_filter($campaigns, function ($campaign) use ($device, $geo, $bidFloor) {
        $devices = explode(',', $campaign['hs_os']);
        $countries = explode(',', $campaign['operator']);

        return in_array($device, $devices) &&
            in_array($geo, $countries) &&
            $campaign['price'] >= $bidFloor;
    });

    if (empty($eligibleCampaigns)) {
        return jsonResponse([
            "error" => "No eligible campaigns found."
        ], 400);
    }

    usort($eligibleCampaigns, function ($a, $b) {
        return $b['price'] <=> $a['price'];
    });

    $selectedCampaign = $eligibleCampaigns[0];

    $response = [
        "campaign_name" => $selectedCampaign['campaignname'],
        "advertiser" => $selectedCampaign['advertiser'],
        "creative_type" => $selectedCampaign['creative_type'],
        "creative_id" => $selectedCampaign['creative_id'],
        "dimension" => $selectedCampaign['dimension'],
        "attribute" => $selectedCampaign['attribute'],
        "url" => $selectedCampaign['url'],
        "price" => $selectedCampaign['price'],
        "bid_type" => $selectedCampaign['bidtype'],
        "image_url" => $selectedCampaign['image_url'],
        "html_tag" => $selectedCampaign['htmltag'],
        "ad_id" => uniqid("ad_"),
        "creative_id" => uniqid("creative_"),
        "from_hour" => $selectedCampaign['from_hour'],
        "to_hour" => $selectedCampaign['to_hour'],
        "country" => $selectedCampaign['country'],
        "city" => $selectedCampaign['city'],
        "lat" => $selectedCampaign['lat'],
        "lng" => $selectedCampaign['lng'],
        "platform" => $selectedCampaign['platform'],
        "audience_targeting" => $selectedCampaign['audience_targeting'],
    ];

    return jsonResponse($response, 200);
}

function jsonResponse($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

$requestJson = file_get_contents('php://input');

handleBidRequest($requestJson, $campaigns);
