<?php

session_start();
require_once __DIR__.'/template/header.php';



if (!empty($_SESSION['token'])) {
    $bearer_token = $_SESSION['token'];

    $tweet_id = "1427894873852633093";

    $params = http_build_query([
        "ids" => $tweet_id,
        "tweet.fields" => implode(',', [
            "attachments",
            "author_id",
            "context_annotations",
            "conversation_id",
            "created_at",
            "entities",
            "geo",
            "id",
            "in_reply_to_user_id",
            "lang",
            "public_metrics",
            "possibly_sensitive",
            "referenced_tweets",
            "reply_settings",
            "source",
            "text",
            "withheld"
        ]),
        "expansions" => implode(',', [
            "author_id",
            "attachments.media_keys",
            "attachments.poll_ids",
            "geo.place_id",
            "in_reply_to_user_id",
            "referenced_tweets.id",
            "referenced_tweets.id.author_id"
        ]),
        "user.fields" => implode(',', [
            "id",
            "name",
            "username",
            "created_at",
            "description",
            "location",
            "pinned_tweet_id",
            "profile_image_url",
            "protected",
            "public_metrics",
            "url",
            "verified",
            "withheld"
        ]),
        "media.fields" => implode(',', [
            "media_key",
            "type",
            "url",
            "duration_ms",
            "height",
            "width",
            "preview_image_url",
            "public_metrics",
            "alt_text"
        ]),
        "place.fields" => implode(',', [
            "contained_within",
            "country",
            "country_code",
            "full_name",
            "geo",
            "id",
            "name",
            "place_type"
        ]),
        "poll.fields" => implode(',', [
            "duration_minutes",
            "end_datetime",
            "id",
            "options",
            "voting_status"
        ])
    ]);


    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.twitter.com/2/tweets?$params",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $bearer_token"
        ]
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $data = json_decode($response, true);

    }
    $_SESSION['token'] = '';
}

$query = $pdo->prepare("SELECT * FROM compt");
$query->execute();
$comt = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT * FROM tweet");
$query->execute();
$tweet = $query->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #FAFBFD;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            color: #1a1a2e;
        }

        .logo img {
            width: 90px;
            height: 90px;
        }

        .tabs {
            display: flex;
            gap: 10px;
        }
        .tabs a {
            text-decoration: none;


        }

        .tab {
            padding: 8px 15px;
            background-color: #ffff;
            width: 130px;
            height: 50px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 15px rgba(13, 0, 255, 0.11);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .tab:hover {
            box-shadow: 0 6px 20px rgba(13, 0, 255, 0.15);
            transform: translateY(-2px);
        }

        .tab.active {
            box-shadow: 0 8px 25px rgba(13, 0, 255, 0.2);
            background-color: #6A80FD;
            color: white;
        }

        .search-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search {
            position: relative;
        }

        .search input {
            padding: 8px 15px 8px 35px;
            border-radius: 20px;
            border: 1px solid #ddd;
            width: 250px;
            height: 50px;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .search input:focus {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border-color: #3102ff4a;
            outline: none;
        }

        .search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #b6bdff80;
        }

        .notifications {
            position: relative;
            cursor: pointer;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .welcome {
            margin-bottom: 20px;
            background-color: white;
            width: 100%;
            height: 70px;
            border-radius: 20px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            padding: 20px;
            position: relative;
        }

        .welcome h2 {
            color: #6A80FD;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .welcome h2 span {
            color: #000;
            font-weight: bold;
        }

        .welcome p {
            font-size: 14px;
            color: #666;
        }

        .date-filters {
            position: absolute;
            right: 20px;
            top: 20px;
            display: flex;
            gap: 15px;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card1 {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 8px 25px rgba(124, 121, 181, 0.2);        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }

        .stat-change.positive {
            color: #4caf50;
        }

        .stat-change.negative {
            color: #f44336;
        }

        .content-area {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .activities-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            height: 520px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .activities-card h3 {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .activity-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .activity-tab {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            background-color: #f0f0f0;
        }

        .activity-tab.active {
            background-color: #6c63ff;
            color: white;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-user-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #eee;
        }

        .activity-user-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .activity-info {
            flex: 1;
        }

        .activity-username {
            font-weight: bold;
            font-size: 14px;
        }

        .activity-action {
            font-size: 14px;
        }

        .activity-time {
            font-size: 12px;
            color: #888;
        }

        .dashboard-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 24px;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            height: 380px;
            margin-top: -28px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .title {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
        }

        .chart-container {
            flex-grow: 1;
            position: relative;
            padding-left: 40px;
            padding-bottom: 30px;
        }

        .y-axis {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #a0aec0;
            font-size: 12px;
        }

        .y-axis div {
            transform: translateY(-50%);
        }

        .x-axis {
            position: absolute;
            left: 40px;
            right: 0;
            bottom: 0;
            display: flex;
            justify-content: space-between;
            color: #a0aec0;
            font-size: 12px;
        }

        .grid-lines {
            position: absolute;
            left: 40px;
            right: 0;
            top: 0;
            bottom: 30px;
            z-index: 0;
        }

        .grid-line {
            position: absolute;
            left: 0;
            right: 0;
            border-top: 1px dashed #edf2f7;
        }

        .grid-line:nth-child(1) { top: 0%; }
        .grid-line:nth-child(2) { top: 20%; }
        .grid-line:nth-child(3) { top: 40%; }
        .grid-line:nth-child(4) { top: 60%; }
        .grid-line:nth-child(5) { top: 80%; }
        .grid-line:nth-child(6) { top: 100%; }

        .chart {
            position: absolute;
            left: 40px;
            right: 0;
            top: 0;
            bottom: 30px;
            z-index: 1;
        }

        .highlight {
            position: absolute;
            background: linear-gradient(to bottom, rgba(31, 34, 83, 0.1), transparent);
            width: 40px;
            height: 100%;
            top: 0;
            left: 40%;
            border-radius: 4px;
            z-index: 2;
            transition: left 0.3s ease;
            display: none;
        }

        .tooltip {
            position: absolute;
            top: 15px;
            left: calc(40% + 20px);
            transform: translateX(-50%);
            background-color: white;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            z-index: 3;
            transition: left 0.3s ease;
            display: none;
        }

        .tooltip-value {
            font-weight: 600;
            color: #2d3748;
        }

        .tooltip-date {
            color: #718096;
            margin-top: 4px;
            font-size: 11px;
        }

        .legend {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #4a5568;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .legend-color.views {
            background-color: #1F2253;
        }

        .legend-color.followers {
            background-color: #FE981C;
        }

        .stats-summary {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 20px;
        }

        .data-point {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
            z-index: 3;
            transition: all 0.2s ease;
        }

        .data-point:hover {
            transform: translate(-50%, -50%) scale(1.5);
        }

        .data-point.views {
            background-color: #1F2253;
            border: 2px solid white;
        }

        .data-point.followers {
            background-color: #FE981C;
            border: 2px solid white;
        }

        .chart-area {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            align-items: start;
        }

        .right-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .metrics-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 20px;
        }

        .metric-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: white;
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            height: 80px;
        }

        .metric-info {
            display: flex;
            font-size: 14.056px;
            align-items: center;
            gap: 16px;
        }

        .icon-container {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .like-icon {
            background-color: #0036e5;
            color: white;
        }

        .comment-icon {
            background-color: #00e052;
            color: white;
        }

        .share-icon {
            background-color: #39c6b6;
            color: white;
        }

        .metric-text {
            display: flex;
            flex-direction: column;
        }

        .metric-label {
            font-weight: 600;
            font-size: 11.713px;
            color: #333;
        }

        .metric-value {
            font-weight: 500;
            font-size: 14.056px;
            color: #555;
        }

        .progress-ring {
            position: relative;
            width: 50px;
            height: 50px;
        }

        .progress-ring-circle {
            transform-origin: center;
            transform: rotate(-90deg);
        }

        .progress-ring-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 14px;
            font-weight: 600;
        }

        .main-grid {
            display: flex;
            gap: 20px;
            flex-direction: column;
        }

        .point {
            position: relative;
            padding-left: 20px;
        }

        .point::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .point-views::before {
            background-color: #1F2253;
        }

        .point-followers::before {
            background-color: #FE981C;
        }

        .post-icon {
            width: 40px;
            height: 40px;
            background-color: #f0f0f0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .post-icon.avail {
            background-color: #444;
        }

        .post-icon.winter {
            background-color: #f5f5f5;
        }

        .post-icon.arrival {
            background-color: #f5f5f5;
        }

        .post-icon.collection {
            background-color: #f5f5f5;
        }

        .status {
            color: #444;
            font-size: 0.9rem;
        }

        .likes {
            color: #444;
            font-size: 0.9rem;
        }

        .impression {
            color: #fc4d3c;
            position: relative;
            font-size: 0.9rem;
        }

        .comments {
            color: #00E923;
            font-size: 0.9rem;
        }

        .gender-icon {
            width: 15px;
            text-align: center;
            margin-right: 5px;
            color: #333;
        }

        .progress-bar {
            height: 100%;
            border-radius: 3px;
        }

        .progress-bar.male {
            background-color: #4a6fff;
            width: 70%;
        }

        .progress-bar.female {
            background-color: #ffa726;
            width: 60%;
        }

        .progress-bar.non-binary {
            background-color: #333;
            width: 10%;
        }

        .progress-bar.ind {
            background-color: #ffa726;
            width: 50%;
        }

        .progress-bar.usa {
            background-color: #4a6fff;
            width: 42%;
        }

        .progress-bar.eur {
            background-color: #ffa726;
            width: 40%;
        }

        .progress-bar.age1 {
            background-color: #ffa726;
            width: 25%;
        }

        .progress-bar.age2 {
            background-color: #ffa726;
            width: 60%;
        }

        .icon-circle {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .icon-circle.blue {
            background-color: #4a6fff;
        }

        .icon-circle.orange {
            background-color: #ffa726;
        }

        .icon-circle.black {
            background-color: #333;
        }

        .dashboard-container2 {
            display: flex;
            width: 100%;
            gap: 15px;
            margin-top: 20px;
        }

        .analytics-panel {
            flex: 3;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 24px;
        }

        .demographics-panel {
            flex: 1;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .analytics-table {
            width: 100%;
            border-collapse: collapse;
        }

        .analytics-table th {
            text-align: left;
            color: #666;
            font-weight: 500;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .analytics-table td {
            padding: 15px;
            border-top: 1px solid #f5f5f7;
        }

        .post-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .post-title {
            font-weight: 500;
            color: #222;
            font-size: 0.9rem;
        }

        .post-header {
            color: #8a56ff;
            font-weight: 500;
        }

        .arrow-icon {
            display: inline-block;
            margin-left: 3px;
            color: #fc4d3c;
        }

        /* Demographics Panel */
        .demographics-panel h3 {
            font-size: 0.95rem;
            color: #525472;
            margin-bottom: 18px;
            font-weight: 500;
        }

        .demographic-group {
            margin-bottom: 20px;
        }

        .demographic-title {
            font-size: 0.8rem;
            color: #525472;
            margin-bottom: 10px;
            font-weight: 400;
            display: flex;
            align-items: center;
        }

        .demographic-title-icon {
            margin-right: 6px;
            font-size: 0.9rem;
        }

        .demographic-item {
            display: flex;
            align-items: center;
            margin-bottom: 17px;
        }

        .item-label {
            width: 65px;
            font-size: 0.8rem;
            color: #555;
            display: flex;
            align-items: center;
        }

        .progress-container {
            flex: 1;
            height: 6px;
            background-color: #f0f0f0;
            border-radius: 3px;
            margin: 0 10px;
            overflow: hidden;
        }

        .progress-bar2 {
            height: 100%;
            border-radius: 3px;
        }

        .progress-bar2.male {
            background-color: #4a6fff;
            width: 70%;
        }

        .progress-bar2.female {
            background-color: #ffa726;
            width: 60%;
        }

        .progress-bar2.non-binary {
            background-color: #333;
            width: 10%;
        }

        .progress-bar2.ind {
            background-color: #ffa726;
            width: 50%;
        }

        .progress-bar2.usa {
            background-color: #4a6fff;
            width: 42%;
        }

        .progress-bar2.eur {
            background-color: #ffa726;
            width: 40%;
        }

        .progress-bar2.age1 {
            background-color: #ffa726;
            width: 25%;
        }

        .progress-bar2.age2 {
            background-color: #ffa726;
            width: 60%;
        }

        .percentage {
            font-size: 0.75rem;
            color: #666;
            width: 30px;
            text-align: right;
        }

        .demographic-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 4px;
            text-align: center;
        }

        .stat-value img {
            width: 25px;
            height: 25px;
            object-fit: contain;
        }

        #icon {
            width: 50px;
            height: 50px;
        }

        .dropdown {
            padding: 8px 12px;
            font-size: 14px;
            width: 90px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            color: #333;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="gray" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            cursor: pointer;
            transition: border 0.3s ease;
        }

        .dropdown:focus {
            border-color: #007bff;
            outline: none;
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="logo">
                <img src="images/Frame 1000010341.png">
            </div>
            <div class="tabs">
                <a href="dashboard.php" class="tab active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1v-7m-6 0a1 1 0 01-1-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Dashboard

                </a>
                <a href="analystic.php" class="tab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Analysis
                </a>
                <a href="ai.php" class="tab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M12 4.354a4 4 0 110 5.292V15M15 21h-6v-6h4v6zm-4 0v-6a4 4 0 00-4-4h12a4 4 0 00-4 4v6h-4z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    AI Twintelli
                </a>
            </div>
        </div>


        <div class="search-profile">
            <div class="search">
                <input type="text" placeholder="Search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                     style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #888;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="currentColor" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="profile">
                <div class="profile-pic">
                    <img src="<?= $data['includes']['users'][0]['profile_image_url'] ?? $comt[0]['image'] ?? '' ?>" alt="Profile">
                </div>
                <div style="font-size: 12px;">
                    <div><?= $data['includes']['users'][0]['name'] ?? $comt[0]['display_name'] ?? '' ?></div>
                    <div style="color: #888;">admin</div>
                </div>
            </div>
        </div>
    </div>


    <div class="welcome">
        <h2>Hello , <span><?= $_SESSION['user_name'] ?? "" ?></span></h2>
        <p>Here's what's happening with your store today.</p>

        <div class="date-filters">
            <select class="dropdown">
                <option>Month </option>
                <option>January</option>
                <option>February</option>
                <option>March</option>
                <option>April</option>
                <option>May</option>
                <option>June</option>
                <option>July</option>
                <option>August</option>
                <option>September</option>
                <option>October</option>
                <option>November</option>
                <option>December</option>
            </select>

            <select class="dropdown">
                <option>Day</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Sunday</option>
            </select>
        </div>
    </div>

    <div class="dashboard-layout">
        <div class="left-column">
            <div class="activities-card">
                <h3>Recent Activities</h3>
                <div class="activity-tabs">
                    <div class="activity-tab active">Likes</div>
                    <div class="activity-tab">Inbox</div>
                    <div class="activity-tab">Followed</div>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-user-pic">
                            <img src="images/iccon.png" alt="User">
                        </div>
                        <div class="activity-info">
                            <div>
                                <span class="activity-username">@Ashtonmorton</span>
                                <span class="activity-action">likes your reels</span>
                            </div>
                            <div class="activity-time">21 minutes ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-user-pic">
                            <img src="images/iccon.png" alt="User">
                        </div>
                        <div class="activity-info">
                            <div>
                                <span class="activity-username">@hreneriley_</span>
                                <span class="activity-action">likes your photo</span>
                            </div>
                            <div class="activity-time">27 minutes ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-user-pic">
                            <img src="images/iccon.png" alt="User">
                        </div>
                        <div class="activity-info">
                            <div>
                                <span class="activity-username">@TatequaJPB</span>
                                <span class="activity-action">likes your photo</span>
                            </div>
                            <div class="activity-time">28 minutes ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-user-pic">
                            <img src="images/iccon.png" alt="User">
                        </div>
                        <div class="activity-info">
                            <div>
                                <span class="activity-username">@Sky_Mann</span>
                                <span class="activity-action">likes your story</span>
                            </div>
                            <div class="activity-time">35 minutes ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="stats-container">
                <div class="stat-card1">
                    <div class="stat-value">
                        <?= $data['includes']['users'][0]['public_metrics']['followers_count'] ?? $comt[0]['followers'] ?? '' ?>
                        <img src="images/users.png">
                    </div>
                    <div class="stat-label">Total Followers</div>
                    <div class="stat-change positive">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        10.2 <span>+1.01% this week</span>
                    </div>
                </div>
                <div class="stat-card1">
                    <div class="stat-value">
                        <?= $data['includes']['users'][0]['public_metrics']['following_count'] ?? $comt[0]['following'] ?? '' ?>
                        <img src="images/3 User.png">
                    </div>
                    <div class="stat-label">Total Following</div>
                    <div class="stat-change positive">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        3.1 <span>+0.49% this week</span>
                    </div>
                </div>
                <div class="stat-card1">
                    <div class="stat-value">
                        <?= $data['includes']['users'][0]['public_metrics']['media_count'] ?? $comt[0]['media'] ?? '' ?>
                        <img src="images/check.png">
                    </div>
                    <div class="stat-label">Total Media</div>
                    <div class="stat-change negative">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 14l-7 7m0 0l-7-7m7 7V3" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        2.56 <span>-0.91% this week</span>
                    </div>
                </div>
                <div class="stat-card1">
                    <div class="stat-value">
                        <?= $data['includes']['users'][0]['public_metrics']['like_count'] ?? $comt[0]['likes'] ?? '' ?>
                        <img src="images/icon (3).png" id="icon">
                    </div>
                    <div class="stat-label">Total Likes</div>
                    <div class="stat-change positive">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        7.2 <span>+1.51% this week</span>
                    </div>
                </div>
            </div>

            <div class="main-grid">
                <div class="dashboard-container">
                    <div class="dashboard-header">
                        <h1 class="title">Orders Analytics</h1>
                        <div class="tabs">
                            <select class="dropdown" id="timeRange">
                                <option value="monthly">Monthly</option>
                                <option value="weekly">Weekly</option>
                                <option value="daily">Daily</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="y-axis">
                            <div>1000</div>
                            <div>800</div>
                            <div>600</div>
                            <div>400</div>
                            <div>200</div>
                            <div>0</div>
                        </div>
                        <div class="grid-lines">
                            <div class="grid-line"></div>
                            <div class="grid-line"></div>
                            <div class="grid-line"></div>
                            <div class="grid-line"></div>
                            <div class="grid-line"></div>
                            <div class="grid-line"></div>
                        </div>
                        <div class="chart">
                            <div class="chart-area" id="chartArea">
                                <svg width="100%" height="100%" viewBox="0 0 460 200" preserveAspectRatio="none">
                                    <!-- Views line (Blue) -->
                                    <path id="viewsLine" class="line views" d="M0,180 C20,160 40,170 60,150 C80,130 100,140 120,120 C140,
                                100 160,110 180,90 C200,80 220,100 240,90 C260,80 280,90 300,60 C320,40 340,20 360,10 C380,15 400,20
                                420,10 C440,5 460,0 460,0" stroke="#1F2253" stroke-width="2.5" fill="none"></path>

                                    <!-- Views area -->
                                    <path id="viewsArea" d="M0,180 C20,160 40,170 60,150 C80,130 100,140 120,120 C140,100 160,110 180,90
                                C200,80 220,100 240,90 C260,80 280,90 300,60 C320,40 340,20 360,10 C380,15 400,20 420,10 C440,5 460,0
                                460,0 L460,200 L0,200 Z" fill="url(#gradient-views)" opacity="0.1"></path>

                                    <!-- Followers line (Orange) -->
                                    <path id="followersLine" class="line followers" d="M0,150 C20,140 40,130 60,120 C80,110 100,100 120,90
                                C140,80 160,70 180,60 C200,50 220,60 240,70 C260,80 280,70 300,60 C320,50 340,60 360,70 C380,80 400,70 420,60
                                 C440,50 460,40 460,40" stroke="#FE981C" stroke-width="2.5" fill="none"></path>

                                    <defs>
                                        <linearGradient id="gradient-views" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stop-color="#1F2253" stop-opacity="0.3"></stop>
                                            <stop offset="100%" stop-color="#1F2253" stop-opacity="0"></stop>
                                        </linearGradient>
                                        <linearGradient id="gradient-followers" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" stop-color="#FE981C" stop-opacity="0.3"></stop>
                                            <stop offset="100%" stop-color="#FE981C" stop-opacity="0"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                        </div>
                        <div class="highlight" id="highlight"></div>
                        <div class="tooltip" id="tooltip">
                            <div class="tooltip-value" id="tooltipValue">$9,492.10</div>
                            <div class="tooltip-date" id="tooltipDate">15 Apr 2025</div>
                        </div>
                        <div class="x-axis" id="xAxis">
                            <div>Jan</div>
                            <div>Feb</div>
                            <div>Mar</div>
                            <div>Apr</div>
                            <div>May</div>
                            <div>Jun</div>
                            <div>Jul</div>
                        </div>
                    </div>
                    <div class="legend">
                        <div class="legend-item" data-series="views">
                            <div class="legend-color views"></div>
                            <div>Total Views</div>
                        </div>
                        <div class="legend-item" data-series="followers">
                            <div class="legend-color followers"></div>
                            <div>New Followers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="metrics-container">
        <div class="metric-card">
            <div class="metric-info">
                <div class="icon-container like-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M7 10v12"></path>
                        <path
                                d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z">
                        </path>
                    </svg>
                </div>
                <div class="metric-text">
                    <span class="metric-label">Likes</span>
                    <span class="metric-value"><?= $data['data'][0]['public_metrics']['like_count'] ?? $tweet[0]['likes'] ?? '' ?></span>
                </div>
            </div>
            <div class="progress-ring">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="20" stroke="#e6e6e6" stroke-width="4" fill="none">
                    </circle>
                    <circle cx="25" cy="25" r="20" stroke="#5a35ea" stroke-width="4" fill="none"
                            stroke-dasharray="125.6" stroke-dashoffset="22.6"></circle>
                </svg>
                <div class="progress-ring-value">82%</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <div class="icon-container comment-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z">
                        </path>
                    </svg>
                </div>
                <div class="metric-text">
                    <span class="metric-label">Comments</span>
                    <span class="metric-value"><?= $data['data'][0]['public_metrics']['reply_count'] ?? $tweet[0]['replies'] ?? '' ?></span>
                </div>
            </div>
            <div class="progress-ring">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="20" stroke="#e6e6e6" stroke-width="4" fill="none">
                    </circle>
                    <circle cx="25" cy="25" r="20" stroke="#fd5a77" stroke-width="4" fill="none"
                            stroke-dasharray="125.6" stroke-dashoffset="40.2"></circle>
                </svg>
                <div class="progress-ring-value">68%</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <div class="icon-container share-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                </div>
                <div class="metric-text">
                    <span class="metric-label">Share</span>
                    <span class="metric-value"><?= $data['data'][0]['public_metrics']['retweet_count'] ?? $tweet[0]['retweets'] ?? '' ?></span>
                </div>
            </div>
            <div class="progress-ring">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="20" stroke="#e6e6e6" stroke-width="4" fill="none">
                    </circle>
                    <circle cx="25" cy="25" r="20" stroke="#fd6a6a" stroke-width="4" fill="none"
                            stroke-dasharray="125.6" stroke-dashoffset="67.8"></circle>
                </svg>
                <div class="progress-ring-value">46%</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <div class="icon-container share-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                </div>
                <div class="metric-text">
                    <span class="metric-label">Share</span>
                    <span class="metric-value"><?= $data['data'][0]['public_metrics']['retweet_count'] ?? $tweet[0]['retweets'] ?? '' ?></span>
                </div>
            </div>
            <div class="progress-ring">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="20" stroke="#e6e6e6" stroke-width="4" fill="none">
                    </circle>
                    <circle cx="25" cy="25" r="20" stroke="#fd6a6a" stroke-width="4" fill="none"
                            stroke-dasharray="125.6" stroke-dashoffset="67.8"></circle>
                </svg>
                <div class="progress-ring-value">46%</div>
            </div>
        </div>
    </div>

    <div class="dashboard-container2">
        <div class="analytics-panel">
            <table class="analytics-table">
                <thead>
                <tr>
                    <th class="post-header">Post</th>
                    <th>Status</th>
                    <th>Likes</th>
                    <th>Impression</th>
                    <th>Comments</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="post-cell">
                            <div class="post-icon avail">
                                <img src="/api/placeholder/40/40" alt="Avail icon">
                            </div>
                            <div class="post-title">Avail 35% off</div>
                        </div>
                    </td>
                    <td class="status">Active</td>
                    <td class="likes">1,807</td>
                    <td class="impression">2,689 <span class="arrow-icon">↘</span></td>
                    <td class="comments">8,707</td>
                </tr>
                <tr>
                    <td>
                        <div class="post-cell">
                            <div class="post-icon winter">
                                <img src="/api/placeholder/40/40" alt="Winter icon">
                            </div>
                            <div class="post-title">Winter Collection</div>
                        </div>
                    </td>
                    <td class="status">Active</td>
                    <td class="likes">3,807</td>
                    <td class="impression">5,689 <span class="arrow-icon">↘</span></td>
                    <td class="comments">8,707</td>
                </tr>
                <tr>
                    <td>
                        <div class="post-cell">
                            <div class="post-icon arrival">
                                <img src="/api/placeholder/40/40" alt="New Arrival icon">
                            </div>
                            <div class="post-title">New Arrival</div>
                        </div>
                    </td>
                    <td class="status">Active</td>
                    <td class="likes">3,807</td>
                    <td class="impression">5,689 <span class="arrow-icon">↘</span></td>
                    <td class="comments">8,707</td>
                </tr>
                <tr>
                    <td>
                        <div class="post-cell">
                            <div class="post-icon collection">
                                <img src="/api/placeholder/40/40" alt="New Collection icon">
                            </div>
                            <div class="post-title">New Collection</div>
                        </div>
                    </td>
                    <td class="status">Active</td>
                    <td class="likes">3,807</td>
                    <td class="impression">5,689 <span class="arrow-icon">↘</span></td>
                    <td class="comments">8,707</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="demographics-panel">
            <h3>Demographic</h3>

            <div class="demographic-group">
                <div class="demographic-title">
                    <span class="demographic-title-icon"></span> Gender
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon"><img src="images/Frame 88.png" alt=""></span> Male
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 male"></div>
                    </div>
                    <div class="percentage">70%</div>
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon"><img src="images/Frame 89.png" alt=""></span> Female
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 female"></div>
                    </div>
                    <div class="percentage">60%</div>
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon"><img src="images/Frame 89(3).png" alt=""></span> Non-Binary
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 non-binary"></div>
                    </div>
                    <div class="percentage">10%</div>
                </div>
            </div>

            <div class="demographic-group">
                <div class="demographic-title">
                    <span class="demographic-title-icon"></span> Geographical
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon">🇮🇳</span> IND
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 ind"></div>
                    </div>
                    <div class="percentage">50%</div>
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon">🇺🇸</span> USA
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 usa"></div>
                    </div>
                    <div class="percentage">42%</div>
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon">🇪🇺</span> EUR
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 eur"></div>
                    </div>
                    <div class="percentage">40%</div>
                </div>
            </div>

            <div class="demographic-group">
                <div class="demographic-title">
                    <span class="demographic-title-icon"></span> Age
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon"></span> 18 - 24
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 age1"></div>
                    </div>
                    <div class="percentage">25%</div>
                </div>

                <div class="demographic-item">
                    <div class="item-label">
                        <span class="demographic-icon"></span> 25 - 35
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar2 age2"></div>
                    </div>
                    <div class="percentage">60%</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>