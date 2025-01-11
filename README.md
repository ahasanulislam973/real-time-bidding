Real-Time Bidding (RTB) Banner Campaign Response

Overview

This PHP project handles bid requests and generates suitable responses for Banner campaigns in RTB scenarios.

Files

rtb.php - Main script to process bid requests. README.md - Documentation file.

Send a POST request to the server with a bid request JSON.

Example: Send Request:

{
    "device": "Android",
    "geo": "GrameenPhone",
    "bid_floor": 0.1
}

Output:

{
    "campaign_name": "Test_Banner_13th-31st_march_Developer",
    "advertiser": "TestGP",
    "creative_type": "1",
    "creative_id": "creative_6782b2ee2804a",
    "dimension": "320x480",
    "attribute": "rich-media",
    "url": "https://adplaytechnology.com/",
    "price": 0.1,
    "bid_type": "CPM",
    "image_url": "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png",
    "html_tag": "",
    "ad_id": "ad_6782b2ee28046",
    "from_hour": "0",
    "to_hour": "23",
    "country": "Bangladesh",
    "city": "",
    "lat": "",
    "lng": "",
    "platform": null,
    "audience_targeting": 0
}

