<?php
include("header.php");
$getStore = $store;

// Total plans
$stmt = $db->prepare("SELECT COUNT(*) FROM membership_plans WHERE store = ? AND plan_status = 'enable'");
$stmt->execute([$getStore]);
$totalPlans = $stmt->fetchColumn();

// Membership orders sale
$stmt = $db->prepare("SELECT * FROM contract_sale WHERE store_id = ?");
$stmt->execute([$store_id]);
$membership_orders_sale = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Store currency
// $stmt = $db->prepare("SELECT * FROM store_details WHERE store = ?");
// $stmt->execute([$getStore]);
// $getStoreCurrency = $stmt->fetch(PDO::FETCH_ASSOC);

// Active Plans - Status A
$stmt = $db->prepare("SELECT COUNT(*) FROM subscriptionOrderContract WHERE store_id = ? AND contract_status = 'A' AND plan_type = 'membership'");
$stmt->execute([$store_id]);
$activePlansA = $stmt->fetchColumn();

// Active Plans - Status P
$stmt = $db->prepare("SELECT COUNT(*) FROM subscriptionOrderContract WHERE store_id = ? AND contract_status = 'P' AND plan_type = 'membership'");
$stmt->execute([$store_id]);
$activePlansP = $stmt->fetchColumn();

// Active Plans - Status C
$stmt = $db->prepare("SELECT COUNT(*) FROM subscriptionOrderContract WHERE store_id = ? AND contract_status = 'C' AND plan_type = 'membership'");
$stmt->execute([$store_id]);
$activePlansC = $stmt->fetchColumn();

?>

<input type="hidden" value='<?php echo json_encode($membership_orders_sale); ?>' id="membership_details_array" data-attr="membership">
<input type="hidden" value="<?php echo '(' . ($currency_code ?? '') . ')' . ($currency ?? ''); ?>" id="membershipStoreCurrency">

<div class="sd-prefix-main">
    <div class="Polaris-Page__Content sd-home-cards">
        <div class="Polaris-Card salesOverview">

            <div class="sd_advertising_banner" style="display: flex; align-items: center; justify-content: space-between; padding: 10px;">
                <h2 class="Polaris-Heading" style="margin: 0;">Sales Overview</h2>

            </div>

            <div class="sd-home-innerCards">
                <div class="Polaris-Card dashboardCard">
                    <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <h4 class="Polaris-Heading">Revenue from memberships & subscriptions</h4>
                    </div>
                    <div class="Polaris-Card__Section dashboardCard-innner">
                        <span class="Polaris-TextStyle--variationSubdued spanclass" id="sd_total_sales"></span>

                        <svg id="SvgjsSvg4706" width="60" height="36" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                            <foreignObject x="0" y="0" width="60" height="36">
                                <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 18px;"></div>
                            </foreignObject>
                            <g id="SvgjsG4794" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                            <g id="SvgjsG4708" class="apexcharts-inner apexcharts-graphical" transform="translate(12.022222222222222, 0)">
                                <defs id="SvgjsDefs4707">
                                    <linearGradient id="SvgjsLinearGradient4711" x1="0" y1="0" x2="0" y2="1">
                                        <stop id="SvgjsStop4712" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                        <stop id="SvgjsStop4713" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                        <stop id="SvgjsStop4714" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                    </linearGradient>
                                    <clipPath id="gridRectMaskwqsb2fmck">
                                        <rect id="SvgjsRect4716" width="64" height="36" x="-10.022222222222222" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <clipPath id="forecastMaskwqsb2fmck"></clipPath>
                                    <clipPath id="nonForecastMaskwqsb2fmck"></clipPath>
                                    <clipPath id="gridRectMarkerMaskwqsb2fmck">
                                        <rect id="SvgjsRect4717" width="47.955555555555556" height="40" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <linearGradient id="SvgjsLinearGradient4723" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4724" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4725" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4728" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4729" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4730" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4733" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4734" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4735" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4738" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4739" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4740" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4743" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4744" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4745" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4748" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4749" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4750" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4753" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4754" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4755" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4758" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4759" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4760" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4763" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4764" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4765" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient4768" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop4769" stop-opacity="1" stop-color="#5be49b" offset="0"></stop>
                                        <stop id="SvgjsStop4770" stop-opacity="1" stop-color="#00a76f" offset="1"></stop>
                                    </linearGradient>
                                </defs>
                                <g id="SvgjsG4773" class="apexcharts-grid">
                                    <g id="SvgjsG4774" class="apexcharts-gridlines-horizontal" style="display: none;">
                                        <line id="SvgjsLine4777" x1="-8.022222222222222" y1="0" x2="51.977777777777774" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine4778" x1="-8.022222222222222" y1="9" x2="51.977777777777774" y2="9" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine4779" x1="-8.022222222222222" y1="18" x2="51.977777777777774" y2="18" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine4780" x1="-8.022222222222222" y1="27" x2="51.977777777777774" y2="27" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine4781" x1="-8.022222222222222" y1="36" x2="51.977777777777774" y2="36" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                    </g>
                                    <g id="SvgjsG4775" class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                    <line id="SvgjsLine4783" x1="0" y1="36" x2="43.955555555555556" y2="36" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                    <line id="SvgjsLine4782" x1="0" y1="1" x2="0" y2="36" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                </g>
                                <g id="SvgjsG4776" class="apexcharts-grid-borders" style="display: none;"></g>
                                <g id="SvgjsG4718" class="apexcharts-bar-series apexcharts-plot-series">
                                    <g id="SvgjsG4719" class="apexcharts-series" rel="1" seriesname="series-1" data:realindex="0">
                                        <path id="SvgjsPath4727" d="M -1.6605432098765431 35.751 L -1.6605432098765431 35.751 C -1.6605432098765431 34.751 -0.6605432098765431 33.751 0.33945679012345686 33.751 L 0.33945679012345686 33.751 C 1 33.751 1.6605432098765431 34.751 1.6605432098765431 35.751 L 1.6605432098765431 35.751 C 1.6605432098765431 35.876 0.6605432098765431 36.001 -0.33945679012345686 36.001 L -0.33945679012345686 36.001 C -1 36.001 -1.6605432098765431 35.876 -1.6605432098765431 35.751 Z " fill="url(#SvgjsLinearGradient4723)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M -1.6605432098765431 35.751 L -1.6605432098765431 35.751 C -1.6605432098765431 34.751 -0.6605432098765431 33.751 0.33945679012345686 33.751 L 0.33945679012345686 33.751 C 1 33.751 1.6605432098765431 34.751 1.6605432098765431 35.751 L 1.6605432098765431 35.751 C 1.6605432098765431 35.876 0.6605432098765431 36.001 -0.33945679012345686 36.001 L -0.33945679012345686 36.001 C -1 36.001 -1.6605432098765431 35.876 -1.6605432098765431 35.751 Z " pathfrom="M -1.6605432098765431 36.001 L -1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L -1.6605432098765431 36.001 Z" cy="33.75" cx="1.6605432098765431" j="0" val="5" barheight="2.25" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4732" d="M 3.2234074074074073 34.001 L 3.2234074074074073 29.901 C 3.2234074074074073 28.901 4.223407407407407 27.901 5.223407407407407 27.901 L 5.223407407407407 27.901 C 5.883950617283951 27.901 6.544493827160494 28.901 6.544493827160494 29.901 L 6.544493827160494 34.001 C 6.544493827160494 35.001 5.544493827160494 36.001 4.544493827160494 36.001 L 4.544493827160494 36.001 C 3.8839506172839506 36.001 3.2234074074074073 35.001 3.2234074074074073 34.001 Z " fill="url(#SvgjsLinearGradient4728)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 3.2234074074074073 34.001 L 3.2234074074074073 29.901 C 3.2234074074074073 28.901 4.223407407407407 27.901 5.223407407407407 27.901 L 5.223407407407407 27.901 C 5.883950617283951 27.901 6.544493827160494 28.901 6.544493827160494 29.901 L 6.544493827160494 34.001 C 6.544493827160494 35.001 5.544493827160494 36.001 4.544493827160494 36.001 L 4.544493827160494 36.001 C 3.8839506172839506 36.001 3.2234074074074073 35.001 3.2234074074074073 34.001 Z " pathfrom="M 3.2234074074074073 36.001 L 3.2234074074074073 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 3.2234074074074073 36.001 Z" cy="27.9" cx="6.544493827160494" j="1" val="18" barheight="8.1" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4737" d="M 8.107358024691358 34.001 L 8.107358024691358 32.601 C 8.107358024691358 31.601 9.107358024691358 30.601000000000003 10.107358024691358 30.601000000000003 L 10.107358024691358 30.601000000000003 C 10.767901234567901 30.601000000000003 11.428444444444445 31.601 11.428444444444445 32.601 L 11.428444444444445 34.001 C 11.428444444444445 35.001 10.428444444444445 36.001 9.428444444444445 36.001 L 9.428444444444445 36.001 C 8.767901234567901 36.001 8.107358024691358 35.001 8.107358024691358 34.001 Z " fill="url(#SvgjsLinearGradient4733)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 8.107358024691358 34.001 L 8.107358024691358 32.601 C 8.107358024691358 31.601 9.107358024691358 30.601000000000003 10.107358024691358 30.601000000000003 L 10.107358024691358 30.601000000000003 C 10.767901234567901 30.601000000000003 11.428444444444445 31.601 11.428444444444445 32.601 L 11.428444444444445 34.001 C 11.428444444444445 35.001 10.428444444444445 36.001 9.428444444444445 36.001 L 9.428444444444445 36.001 C 8.767901234567901 36.001 8.107358024691358 35.001 8.107358024691358 34.001 Z " pathfrom="M 8.107358024691358 36.001 L 8.107358024691358 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 8.107358024691358 36.001 Z" cy="30.6" cx="11.428444444444445" j="2" val="12" barheight="5.3999999999999995" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4742" d="M 12.991308641975309 34.001 L 12.991308641975309 15.051 C 12.991308641975309 14.051 13.991308641975309 13.051 14.991308641975309 13.051 L 14.991308641975309 13.051 C 15.651851851851852 13.051 16.312395061728395 14.051 16.312395061728395 15.051 L 16.312395061728395 34.001 C 16.312395061728395 35.001 15.312395061728395 36.001 14.312395061728395 36.001 L 14.312395061728395 36.001 C 13.651851851851852 36.001 12.991308641975309 35.001 12.991308641975309 34.001 Z " fill="url(#SvgjsLinearGradient4738)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 12.991308641975309 34.001 L 12.991308641975309 15.051 C 12.991308641975309 14.051 13.991308641975309 13.051 14.991308641975309 13.051 L 14.991308641975309 13.051 C 15.651851851851852 13.051 16.312395061728395 14.051 16.312395061728395 15.051 L 16.312395061728395 34.001 C 16.312395061728395 35.001 15.312395061728395 36.001 14.312395061728395 36.001 L 14.312395061728395 36.001 C 13.651851851851852 36.001 12.991308641975309 35.001 12.991308641975309 34.001 Z " pathfrom="M 12.991308641975309 36.001 L 12.991308641975309 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 12.991308641975309 36.001 Z" cy="13.05" cx="16.312395061728395" j="3" val="51" barheight="22.95" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4747" d="M 17.87525925925926 34.001 L 17.87525925925926 7.4010000000000025 C 17.87525925925926 6.4010000000000025 18.87525925925926 5.4010000000000025 19.87525925925926 5.4010000000000025 L 19.87525925925926 5.4010000000000025 C 20.535802469135803 5.4010000000000025 21.196345679012346 6.4010000000000025 21.196345679012346 7.4010000000000025 L 21.196345679012346 34.001 C 21.196345679012346 35.001 20.196345679012346 36.001 19.196345679012346 36.001 L 19.196345679012346 36.001 C 18.535802469135803 36.001 17.87525925925926 35.001 17.87525925925926 34.001 Z " fill="url(#SvgjsLinearGradient4743)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 17.87525925925926 34.001 L 17.87525925925926 7.4010000000000025 C 17.87525925925926 6.4010000000000025 18.87525925925926 5.4010000000000025 19.87525925925926 5.4010000000000025 L 19.87525925925926 5.4010000000000025 C 20.535802469135803 5.4010000000000025 21.196345679012346 6.4010000000000025 21.196345679012346 7.4010000000000025 L 21.196345679012346 34.001 C 21.196345679012346 35.001 20.196345679012346 36.001 19.196345679012346 36.001 L 19.196345679012346 36.001 C 18.535802469135803 36.001 17.87525925925926 35.001 17.87525925925926 34.001 Z " pathfrom="M 17.87525925925926 36.001 L 17.87525925925926 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 17.87525925925926 36.001 Z" cy="5.400000000000002" cx="21.196345679012346" j="4" val="68" barheight="30.599999999999998" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4752" d="M 22.75920987654321 34.001 L 22.75920987654321 33.051 C 22.75920987654321 32.051 23.75920987654321 31.051000000000002 24.75920987654321 31.051000000000002 L 24.75920987654321 31.051000000000002 C 25.419753086419753 31.051000000000002 26.080296296296297 32.051 26.080296296296297 33.051 L 26.080296296296297 34.001 C 26.080296296296297 35.001 25.080296296296297 36.001 24.080296296296297 36.001 L 24.080296296296297 36.001 C 23.419753086419753 36.001 22.75920987654321 35.001 22.75920987654321 34.001 Z " fill="url(#SvgjsLinearGradient4748)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 22.75920987654321 34.001 L 22.75920987654321 33.051 C 22.75920987654321 32.051 23.75920987654321 31.051000000000002 24.75920987654321 31.051000000000002 L 24.75920987654321 31.051000000000002 C 25.419753086419753 31.051000000000002 26.080296296296297 32.051 26.080296296296297 33.051 L 26.080296296296297 34.001 C 26.080296296296297 35.001 25.080296296296297 36.001 24.080296296296297 36.001 L 24.080296296296297 36.001 C 23.419753086419753 36.001 22.75920987654321 35.001 22.75920987654321 34.001 Z " pathfrom="M 22.75920987654321 36.001 L 22.75920987654321 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 22.75920987654321 36.001 Z" cy="31.05" cx="26.080296296296297" j="5" val="11" barheight="4.95" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4757" d="M 27.64316049382716 34.001 L 27.64316049382716 20.451 C 27.64316049382716 19.451 28.64316049382716 18.451 29.64316049382716 18.451 L 29.64316049382716 18.451 C 30.303703703703704 18.451 30.964246913580247 19.451 30.964246913580247 20.451 L 30.964246913580247 34.001 C 30.964246913580247 35.001 29.964246913580247 36.001 28.964246913580247 36.001 L 28.964246913580247 36.001 C 28.303703703703704 36.001 27.64316049382716 35.001 27.64316049382716 34.001 Z " fill="url(#SvgjsLinearGradient4753)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 27.64316049382716 34.001 L 27.64316049382716 20.451 C 27.64316049382716 19.451 28.64316049382716 18.451 29.64316049382716 18.451 L 29.64316049382716 18.451 C 30.303703703703704 18.451 30.964246913580247 19.451 30.964246913580247 20.451 L 30.964246913580247 34.001 C 30.964246913580247 35.001 29.964246913580247 36.001 28.964246913580247 36.001 L 28.964246913580247 36.001 C 28.303703703703704 36.001 27.64316049382716 35.001 27.64316049382716 34.001 Z " pathfrom="M 27.64316049382716 36.001 L 27.64316049382716 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 27.64316049382716 36.001 Z" cy="18.45" cx="30.964246913580247" j="6" val="39" barheight="17.55" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4762" d="M 32.52711111111111 34.001 L 32.52711111111111 21.351000000000003 C 32.52711111111111 20.351000000000003 33.52711111111111 19.351000000000003 34.52711111111111 19.351000000000003 L 34.52711111111111 19.351000000000003 C 35.187654320987654 19.351000000000003 35.8481975308642 20.351000000000003 35.8481975308642 21.351000000000003 L 35.8481975308642 34.001 C 35.8481975308642 35.001 34.8481975308642 36.001 33.8481975308642 36.001 L 33.8481975308642 36.001 C 33.187654320987654 36.001 32.52711111111111 35.001 32.52711111111111 34.001 Z " fill="url(#SvgjsLinearGradient4758)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 32.52711111111111 34.001 L 32.52711111111111 21.351000000000003 C 32.52711111111111 20.351000000000003 33.52711111111111 19.351000000000003 34.52711111111111 19.351000000000003 L 34.52711111111111 19.351000000000003 C 35.187654320987654 19.351000000000003 35.8481975308642 20.351000000000003 35.8481975308642 21.351000000000003 L 35.8481975308642 34.001 C 35.8481975308642 35.001 34.8481975308642 36.001 33.8481975308642 36.001 L 33.8481975308642 36.001 C 33.187654320987654 36.001 32.52711111111111 35.001 32.52711111111111 34.001 Z " pathfrom="M 32.52711111111111 36.001 L 32.52711111111111 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 32.52711111111111 36.001 Z" cy="19.35" cx="35.8481975308642" j="7" val="37" barheight="16.65" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4767" d="M 37.41106172839506 34.001 L 37.41106172839506 25.851000000000003 C 37.41106172839506 24.851000000000003 38.41106172839506 23.851000000000003 39.41106172839506 23.851000000000003 L 39.41106172839506 23.851000000000003 C 40.071604938271605 23.851000000000003 40.73214814814815 24.851000000000003 40.73214814814815 25.851000000000003 L 40.73214814814815 34.001 C 40.73214814814815 35.001 39.73214814814815 36.001 38.73214814814815 36.001 L 38.73214814814815 36.001 C 38.071604938271605 36.001 37.41106172839506 35.001 37.41106172839506 34.001 Z " fill="url(#SvgjsLinearGradient4763)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 37.41106172839506 34.001 L 37.41106172839506 25.851000000000003 C 37.41106172839506 24.851000000000003 38.41106172839506 23.851000000000003 39.41106172839506 23.851000000000003 L 39.41106172839506 23.851000000000003 C 40.071604938271605 23.851000000000003 40.73214814814815 24.851000000000003 40.73214814814815 25.851000000000003 L 40.73214814814815 34.001 C 40.73214814814815 35.001 39.73214814814815 36.001 38.73214814814815 36.001 L 38.73214814814815 36.001 C 38.071604938271605 36.001 37.41106172839506 35.001 37.41106172839506 34.001 Z " pathfrom="M 37.41106172839506 36.001 L 37.41106172839506 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 37.41106172839506 36.001 Z" cy="23.85" cx="40.73214814814815" j="8" val="27" barheight="12.149999999999999" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath4772" d="M 42.29501234567901 34.001 L 42.29501234567901 29.001 C 42.29501234567901 28.001 43.29501234567901 27.001 44.29501234567901 27.001 L 44.29501234567901 27.001 C 44.955555555555556 27.001 45.6160987654321 28.001 45.6160987654321 29.001 L 45.6160987654321 34.001 C 45.6160987654321 35.001 44.6160987654321 36.001 43.6160987654321 36.001 L 43.6160987654321 36.001 C 42.955555555555556 36.001 42.29501234567901 35.001 42.29501234567901 34.001 Z " fill="url(#SvgjsLinearGradient4768)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskwqsb2fmck)" pathto="M 42.29501234567901 34.001 L 42.29501234567901 29.001 C 42.29501234567901 28.001 43.29501234567901 27.001 44.29501234567901 27.001 L 44.29501234567901 27.001 C 44.955555555555556 27.001 45.6160987654321 28.001 45.6160987654321 29.001 L 45.6160987654321 34.001 C 45.6160987654321 35.001 44.6160987654321 36.001 43.6160987654321 36.001 L 43.6160987654321 36.001 C 42.955555555555556 36.001 42.29501234567901 35.001 42.29501234567901 34.001 Z " pathfrom="M 42.29501234567901 36.001 L 42.29501234567901 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 42.29501234567901 36.001 Z" cy="27" cx="45.6160987654321" j="9" val="20" barheight="9" barwidth="3.3210864197530863"></path>
                                        <g id="SvgjsG4721" class="apexcharts-bar-goals-markers">
                                            <g id="SvgjsG4726" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4731" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4736" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4741" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4746" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4751" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4756" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4761" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4766" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                            <g id="SvgjsG4771" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskwqsb2fmck)"></g>
                                        </g>
                                        <g id="SvgjsG4722" class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g>
                                    </g>
                                    <g id="SvgjsG4720" class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realindex="0"></g>
                                </g>
                                <g id="SvgjsG4786" class="apexcharts-xaxis" transform="translate(0, 0)">
                                    <g id="SvgjsG4787" class="apexcharts-xaxis-texts-g" transform="translate(0, 4)"></g>
                                </g>
                                <g id="SvgjsG4795" class="apexcharts-yaxis-annotations"></g>
                                <g id="SvgjsG4796" class="apexcharts-xaxis-annotations"></g>
                                <g id="SvgjsG4797" class="apexcharts-point-annotations"></g>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="Polaris-Card dashboardCard">
                    <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <h4 class="Polaris-Heading">Total membership plans</h4>

                    </div>
                    <div class="Polaris-Card__Section dashboardCard-innner">
                        <span
                            class="Polaris-TextStyle--variationSubdued spanclass"> <?php echo $totalPlans ?? '0'; ?></span>

                        <svg id="SvgjsSvg5302" width="60" height="36" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                            <foreignObject x="0" y="0" width="60" height="36">
                                <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 18px;"></div>
                            </foreignObject>
                            <g id="SvgjsG5390" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                            <g id="SvgjsG5304" class="apexcharts-inner apexcharts-graphical" transform="translate(12.022222222222222, 0)">
                                <defs id="SvgjsDefs5303">
                                    <linearGradient id="SvgjsLinearGradient5307" x1="0" y1="0" x2="0" y2="1">
                                        <stop id="SvgjsStop5308" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                        <stop id="SvgjsStop5309" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                        <stop id="SvgjsStop5310" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                    </linearGradient>
                                    <clipPath id="gridRectMaskhxrlyj6t">
                                        <rect id="SvgjsRect5312" width="64" height="36" x="-10.022222222222222" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <clipPath id="forecastMaskhxrlyj6t"></clipPath>
                                    <clipPath id="nonForecastMaskhxrlyj6t"></clipPath>
                                    <clipPath id="gridRectMarkerMaskhxrlyj6t">
                                        <rect id="SvgjsRect5313" width="47.955555555555556" height="40" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <linearGradient id="SvgjsLinearGradient5319" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5320" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5321" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5324" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5325" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5326" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5329" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5330" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5331" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5334" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5335" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5336" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5339" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5340" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5341" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5344" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5345" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5346" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5349" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5350" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5351" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5354" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5355" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5356" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5359" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5360" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5361" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient5364" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop5365" stop-opacity="1" stop-color="#61f3f3" offset="0"></stop>
                                        <stop id="SvgjsStop5366" stop-opacity="1" stop-color="#00b8d9" offset="1"></stop>
                                    </linearGradient>
                                </defs>
                                <g id="SvgjsG5369" class="apexcharts-grid">
                                    <g id="SvgjsG5370" class="apexcharts-gridlines-horizontal" style="display: none;">
                                        <line id="SvgjsLine5373" x1="-8.022222222222222" y1="0" x2="51.977777777777774" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine5374" x1="-8.022222222222222" y1="9" x2="51.977777777777774" y2="9" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine5375" x1="-8.022222222222222" y1="18" x2="51.977777777777774" y2="18" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine5376" x1="-8.022222222222222" y1="27" x2="51.977777777777774" y2="27" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine5377" x1="-8.022222222222222" y1="36" x2="51.977777777777774" y2="36" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                    </g>
                                    <g id="SvgjsG5371" class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                    <line id="SvgjsLine5379" x1="0" y1="36" x2="43.955555555555556" y2="36" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                    <line id="SvgjsLine5378" x1="0" y1="1" x2="0" y2="36" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                </g>
                                <g id="SvgjsG5372" class="apexcharts-grid-borders" style="display: none;"></g>
                                <g id="SvgjsG5314" class="apexcharts-bar-series apexcharts-plot-series">
                                    <g id="SvgjsG5315" class="apexcharts-series" rel="1" seriesname="series-1" data:realindex="0">
                                        <path id="SvgjsPath5323" d="M -1.6605432098765431 34.001 L -1.6605432098765431 29.001 C -1.6605432098765431 28.001 -0.6605432098765431 27.001 0.33945679012345686 27.001 L 0.33945679012345686 27.001 C 1 27.001 1.6605432098765431 28.001 1.6605432098765431 29.001 L 1.6605432098765431 34.001 C 1.6605432098765431 35.001 0.6605432098765431 36.001 -0.33945679012345686 36.001 L -0.33945679012345686 36.001 C -1 36.001 -1.6605432098765431 35.001 -1.6605432098765431 34.001 Z " fill="url(#SvgjsLinearGradient5319)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M -1.6605432098765431 34.001 L -1.6605432098765431 29.001 C -1.6605432098765431 28.001 -0.6605432098765431 27.001 0.33945679012345686 27.001 L 0.33945679012345686 27.001 C 1 27.001 1.6605432098765431 28.001 1.6605432098765431 29.001 L 1.6605432098765431 34.001 C 1.6605432098765431 35.001 0.6605432098765431 36.001 -0.33945679012345686 36.001 L -0.33945679012345686 36.001 C -1 36.001 -1.6605432098765431 35.001 -1.6605432098765431 34.001 Z " pathfrom="M -1.6605432098765431 36.001 L -1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L -1.6605432098765431 36.001 Z" cy="27" cx="1.6605432098765431" j="0" val="20" barheight="9" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5328" d="M 3.2234074074074073 34.001 L 3.2234074074074073 19.551000000000002 C 3.2234074074074073 18.551000000000002 4.223407407407407 17.551000000000002 5.223407407407407 17.551000000000002 L 5.223407407407407 17.551000000000002 C 5.883950617283951 17.551000000000002 6.544493827160494 18.551000000000002 6.544493827160494 19.551000000000002 L 6.544493827160494 34.001 C 6.544493827160494 35.001 5.544493827160494 36.001 4.544493827160494 36.001 L 4.544493827160494 36.001 C 3.8839506172839506 36.001 3.2234074074074073 35.001 3.2234074074074073 34.001 Z " fill="url(#SvgjsLinearGradient5324)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 3.2234074074074073 34.001 L 3.2234074074074073 19.551000000000002 C 3.2234074074074073 18.551000000000002 4.223407407407407 17.551000000000002 5.223407407407407 17.551000000000002 L 5.223407407407407 17.551000000000002 C 5.883950617283951 17.551000000000002 6.544493827160494 18.551000000000002 6.544493827160494 19.551000000000002 L 6.544493827160494 34.001 C 6.544493827160494 35.001 5.544493827160494 36.001 4.544493827160494 36.001 L 4.544493827160494 36.001 C 3.8839506172839506 36.001 3.2234074074074073 35.001 3.2234074074074073 34.001 Z " pathfrom="M 3.2234074074074073 36.001 L 3.2234074074074073 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 3.2234074074074073 36.001 Z" cy="17.55" cx="6.544493827160494" j="1" val="41" barheight="18.45" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5333" d="M 8.107358024691358 34.001 L 8.107358024691358 9.651000000000002 C 8.107358024691358 8.651000000000002 9.107358024691358 7.6510000000000025 10.107358024691358 7.6510000000000025 L 10.107358024691358 7.6510000000000025 C 10.767901234567901 7.6510000000000025 11.428444444444445 8.651000000000002 11.428444444444445 9.651000000000002 L 11.428444444444445 34.001 C 11.428444444444445 35.001 10.428444444444445 36.001 9.428444444444445 36.001 L 9.428444444444445 36.001 C 8.767901234567901 36.001 8.107358024691358 35.001 8.107358024691358 34.001 Z " fill="url(#SvgjsLinearGradient5329)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 8.107358024691358 34.001 L 8.107358024691358 9.651000000000002 C 8.107358024691358 8.651000000000002 9.107358024691358 7.6510000000000025 10.107358024691358 7.6510000000000025 L 10.107358024691358 7.6510000000000025 C 10.767901234567901 7.6510000000000025 11.428444444444445 8.651000000000002 11.428444444444445 9.651000000000002 L 11.428444444444445 34.001 C 11.428444444444445 35.001 10.428444444444445 36.001 9.428444444444445 36.001 L 9.428444444444445 36.001 C 8.767901234567901 36.001 8.107358024691358 35.001 8.107358024691358 34.001 Z " pathfrom="M 8.107358024691358 36.001 L 8.107358024691358 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 8.107358024691358 36.001 Z" cy="7.650000000000002" cx="11.428444444444445" j="2" val="63" barheight="28.349999999999998" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5338" d="M 12.991308641975309 34.001 L 12.991308641975309 23.151 C 12.991308641975309 22.151 13.991308641975309 21.151 14.991308641975309 21.151 L 14.991308641975309 21.151 C 15.651851851851852 21.151 16.312395061728395 22.151 16.312395061728395 23.151 L 16.312395061728395 34.001 C 16.312395061728395 35.001 15.312395061728395 36.001 14.312395061728395 36.001 L 14.312395061728395 36.001 C 13.651851851851852 36.001 12.991308641975309 35.001 12.991308641975309 34.001 Z " fill="url(#SvgjsLinearGradient5334)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 12.991308641975309 34.001 L 12.991308641975309 23.151 C 12.991308641975309 22.151 13.991308641975309 21.151 14.991308641975309 21.151 L 14.991308641975309 21.151 C 15.651851851851852 21.151 16.312395061728395 22.151 16.312395061728395 23.151 L 16.312395061728395 34.001 C 16.312395061728395 35.001 15.312395061728395 36.001 14.312395061728395 36.001 L 14.312395061728395 36.001 C 13.651851851851852 36.001 12.991308641975309 35.001 12.991308641975309 34.001 Z " pathfrom="M 12.991308641975309 36.001 L 12.991308641975309 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 12.991308641975309 36.001 Z" cy="21.15" cx="16.312395061728395" j="3" val="33" barheight="14.85" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5343" d="M 17.87525925925926 34.001 L 17.87525925925926 25.401 C 17.87525925925926 24.401 18.87525925925926 23.401 19.87525925925926 23.401 L 19.87525925925926 23.401 C 20.535802469135803 23.401 21.196345679012346 24.401 21.196345679012346 25.401 L 21.196345679012346 34.001 C 21.196345679012346 35.001 20.196345679012346 36.001 19.196345679012346 36.001 L 19.196345679012346 36.001 C 18.535802469135803 36.001 17.87525925925926 35.001 17.87525925925926 34.001 Z " fill="url(#SvgjsLinearGradient5339)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 17.87525925925926 34.001 L 17.87525925925926 25.401 C 17.87525925925926 24.401 18.87525925925926 23.401 19.87525925925926 23.401 L 19.87525925925926 23.401 C 20.535802469135803 23.401 21.196345679012346 24.401 21.196345679012346 25.401 L 21.196345679012346 34.001 C 21.196345679012346 35.001 20.196345679012346 36.001 19.196345679012346 36.001 L 19.196345679012346 36.001 C 18.535802469135803 36.001 17.87525925925926 35.001 17.87525925925926 34.001 Z " pathfrom="M 17.87525925925926 36.001 L 17.87525925925926 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 17.87525925925926 36.001 Z" cy="23.4" cx="21.196345679012346" j="4" val="28" barheight="12.6" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5348" d="M 22.75920987654321 34.001 L 22.75920987654321 22.251 C 22.75920987654321 21.251 23.75920987654321 20.251 24.75920987654321 20.251 L 24.75920987654321 20.251 C 25.419753086419753 20.251 26.080296296296297 21.251 26.080296296296297 22.251 L 26.080296296296297 34.001 C 26.080296296296297 35.001 25.080296296296297 36.001 24.080296296296297 36.001 L 24.080296296296297 36.001 C 23.419753086419753 36.001 22.75920987654321 35.001 22.75920987654321 34.001 Z " fill="url(#SvgjsLinearGradient5344)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 22.75920987654321 34.001 L 22.75920987654321 22.251 C 22.75920987654321 21.251 23.75920987654321 20.251 24.75920987654321 20.251 L 24.75920987654321 20.251 C 25.419753086419753 20.251 26.080296296296297 21.251 26.080296296296297 22.251 L 26.080296296296297 34.001 C 26.080296296296297 35.001 25.080296296296297 36.001 24.080296296296297 36.001 L 24.080296296296297 36.001 C 23.419753086419753 36.001 22.75920987654321 35.001 22.75920987654321 34.001 Z " pathfrom="M 22.75920987654321 36.001 L 22.75920987654321 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 22.75920987654321 36.001 Z" cy="20.25" cx="26.080296296296297" j="5" val="35" barheight="15.75" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5353" d="M 27.64316049382716 34.001 L 27.64316049382716 15.501 C 27.64316049382716 14.501 28.64316049382716 13.501 29.64316049382716 13.501 L 29.64316049382716 13.501 C 30.303703703703704 13.501 30.964246913580247 14.501 30.964246913580247 15.501 L 30.964246913580247 34.001 C 30.964246913580247 35.001 29.964246913580247 36.001 28.964246913580247 36.001 L 28.964246913580247 36.001 C 28.303703703703704 36.001 27.64316049382716 35.001 27.64316049382716 34.001 Z " fill="url(#SvgjsLinearGradient5349)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 27.64316049382716 34.001 L 27.64316049382716 15.501 C 27.64316049382716 14.501 28.64316049382716 13.501 29.64316049382716 13.501 L 29.64316049382716 13.501 C 30.303703703703704 13.501 30.964246913580247 14.501 30.964246913580247 15.501 L 30.964246913580247 34.001 C 30.964246913580247 35.001 29.964246913580247 36.001 28.964246913580247 36.001 L 28.964246913580247 36.001 C 28.303703703703704 36.001 27.64316049382716 35.001 27.64316049382716 34.001 Z " pathfrom="M 27.64316049382716 36.001 L 27.64316049382716 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 27.64316049382716 36.001 Z" cy="13.5" cx="30.964246913580247" j="6" val="50" barheight="22.5" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5358" d="M 32.52711111111111 34.001 L 32.52711111111111 17.301000000000002 C 32.52711111111111 16.301000000000002 33.52711111111111 15.301 34.52711111111111 15.301 L 34.52711111111111 15.301 C 35.187654320987654 15.301 35.8481975308642 16.301000000000002 35.8481975308642 17.301000000000002 L 35.8481975308642 34.001 C 35.8481975308642 35.001 34.8481975308642 36.001 33.8481975308642 36.001 L 33.8481975308642 36.001 C 33.187654320987654 36.001 32.52711111111111 35.001 32.52711111111111 34.001 Z " fill="url(#SvgjsLinearGradient5354)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 32.52711111111111 34.001 L 32.52711111111111 17.301000000000002 C 32.52711111111111 16.301000000000002 33.52711111111111 15.301 34.52711111111111 15.301 L 34.52711111111111 15.301 C 35.187654320987654 15.301 35.8481975308642 16.301000000000002 35.8481975308642 17.301000000000002 L 35.8481975308642 34.001 C 35.8481975308642 35.001 34.8481975308642 36.001 33.8481975308642 36.001 L 33.8481975308642 36.001 C 33.187654320987654 36.001 32.52711111111111 35.001 32.52711111111111 34.001 Z " pathfrom="M 32.52711111111111 36.001 L 32.52711111111111 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 32.52711111111111 36.001 Z" cy="15.3" cx="35.8481975308642" j="7" val="46" barheight="20.7" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5363" d="M 37.41106172839506 34.001 L 37.41106172839506 33.051 C 37.41106172839506 32.051 38.41106172839506 31.051000000000002 39.41106172839506 31.051000000000002 L 39.41106172839506 31.051000000000002 C 40.071604938271605 31.051000000000002 40.73214814814815 32.051 40.73214814814815 33.051 L 40.73214814814815 34.001 C 40.73214814814815 35.001 39.73214814814815 36.001 38.73214814814815 36.001 L 38.73214814814815 36.001 C 38.071604938271605 36.001 37.41106172839506 35.001 37.41106172839506 34.001 Z " fill="url(#SvgjsLinearGradient5359)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 37.41106172839506 34.001 L 37.41106172839506 33.051 C 37.41106172839506 32.051 38.41106172839506 31.051000000000002 39.41106172839506 31.051000000000002 L 39.41106172839506 31.051000000000002 C 40.071604938271605 31.051000000000002 40.73214814814815 32.051 40.73214814814815 33.051 L 40.73214814814815 34.001 C 40.73214814814815 35.001 39.73214814814815 36.001 38.73214814814815 36.001 L 38.73214814814815 36.001 C 38.071604938271605 36.001 37.41106172839506 35.001 37.41106172839506 34.001 Z " pathfrom="M 37.41106172839506 36.001 L 37.41106172839506 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 37.41106172839506 36.001 Z" cy="31.05" cx="40.73214814814815" j="8" val="11" barheight="4.95" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath5368" d="M 42.29501234567901 34.001 L 42.29501234567901 26.301000000000002 C 42.29501234567901 25.301000000000002 43.29501234567901 24.301000000000002 44.29501234567901 24.301000000000002 L 44.29501234567901 24.301000000000002 C 44.955555555555556 24.301000000000002 45.6160987654321 25.301000000000002 45.6160987654321 26.301000000000002 L 45.6160987654321 34.001 C 45.6160987654321 35.001 44.6160987654321 36.001 43.6160987654321 36.001 L 43.6160987654321 36.001 C 42.955555555555556 36.001 42.29501234567901 35.001 42.29501234567901 34.001 Z " fill="url(#SvgjsLinearGradient5364)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskhxrlyj6t)" pathto="M 42.29501234567901 34.001 L 42.29501234567901 26.301000000000002 C 42.29501234567901 25.301000000000002 43.29501234567901 24.301000000000002 44.29501234567901 24.301000000000002 L 44.29501234567901 24.301000000000002 C 44.955555555555556 24.301000000000002 45.6160987654321 25.301000000000002 45.6160987654321 26.301000000000002 L 45.6160987654321 34.001 C 45.6160987654321 35.001 44.6160987654321 36.001 43.6160987654321 36.001 L 43.6160987654321 36.001 C 42.955555555555556 36.001 42.29501234567901 35.001 42.29501234567901 34.001 Z " pathfrom="M 42.29501234567901 36.001 L 42.29501234567901 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 42.29501234567901 36.001 Z" cy="24.3" cx="45.6160987654321" j="9" val="26" barheight="11.7" barwidth="3.3210864197530863"></path>
                                        <g id="SvgjsG5317" class="apexcharts-bar-goals-markers">
                                            <g id="SvgjsG5322" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5327" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5332" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5337" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5342" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5347" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5352" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5357" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5362" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                            <g id="SvgjsG5367" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMaskhxrlyj6t)"></g>
                                        </g>
                                        <g id="SvgjsG5318" class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g>
                                    </g>
                                    <g id="SvgjsG5316" class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realindex="0"></g>
                                </g>
                                <line id="SvgjsLine5381" x1="-8.022222222222222" y1="0" x2="51.977777777777774" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                <g id="SvgjsG5382" class="apexcharts-xaxis" transform="translate(0, 0)">
                                    <g id="SvgjsG5383" class="apexcharts-xaxis-texts-g" transform="translate(0, 4)"></g>
                                </g>
                                <g id="SvgjsG5391" class="apexcharts-yaxis-annotations"></g>
                                <g id="SvgjsG5392" class="apexcharts-xaxis-annotations"></g>
                                <g id="SvgjsG5393" class="apexcharts-point-annotations"></g>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="Polaris-Card dashboardCard">
                    <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <h4 class="Polaris-Heading">Active membership plans</h4>

                    </div>
                    <div class="Polaris-Card__Section dashboardCard-innner">
                        <span
                            class="Polaris-TextStyle--variationSubdued spanclass"><?php echo  $activePlansA ?? '0'; ?></span>

                        <svg id="SvgjsSvg1734" width="60" height="36" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                            <foreignObject x="0" y="0" width="60" height="36">
                                <div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 18px;"></div>
                            </foreignObject>
                            <g id="SvgjsG1823" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                            <g id="SvgjsG1736" class="apexcharts-inner apexcharts-graphical" transform="translate(12.022222222222222, 0)">
                                <defs id="SvgjsDefs1735">
                                    <linearGradient id="SvgjsLinearGradient1739" x1="0" y1="0" x2="0" y2="1">
                                        <stop id="SvgjsStop1740" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                        <stop id="SvgjsStop1741" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                        <stop id="SvgjsStop1742" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                    </linearGradient>
                                    <clipPath id="gridRectMask9cc6xa3ll">
                                        <rect id="SvgjsRect1744" width="64" height="36" x="-10.022222222222222" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <clipPath id="forecastMask9cc6xa3ll"></clipPath>
                                    <clipPath id="nonForecastMask9cc6xa3ll"></clipPath>
                                    <clipPath id="gridRectMarkerMask9cc6xa3ll">
                                        <rect id="SvgjsRect1745" width="47.955555555555556" height="40" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <linearGradient id="SvgjsLinearGradient1751" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1752" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1753" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1756" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1757" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1758" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1761" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1762" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1763" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1766" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1767" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1768" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1771" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1772" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1773" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1776" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1777" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1778" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1781" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1782" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1783" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1786" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1787" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1788" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1791" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1792" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1793" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                    <linearGradient id="SvgjsLinearGradient1796" x1="0" y1="1" x2="1" y2="1">
                                        <stop id="SvgjsStop1797" stop-opacity="1" stop-color="#ffd666" offset="0"></stop>
                                        <stop id="SvgjsStop1798" stop-opacity="1" stop-color="#ffab00" offset="1"></stop>
                                    </linearGradient>
                                </defs>
                                <g id="SvgjsG1801" class="apexcharts-grid">
                                    <g id="SvgjsG1802" class="apexcharts-gridlines-horizontal" style="display: none;">
                                        <line id="SvgjsLine1805" x1="-8.022222222222222" y1="0" x2="51.977777777777774" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine1806" x1="-8.022222222222222" y1="7.2" x2="51.977777777777774" y2="7.2" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine1807" x1="-8.022222222222222" y1="14.4" x2="51.977777777777774" y2="14.4" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine1808" x1="-8.022222222222222" y1="21.6" x2="51.977777777777774" y2="21.6" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine1809" x1="-8.022222222222222" y1="28.8" x2="51.977777777777774" y2="28.8" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                        <line id="SvgjsLine1810" x1="-8.022222222222222" y1="36" x2="51.977777777777774" y2="36" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                    </g>
                                    <g id="SvgjsG1803" class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                    <line id="SvgjsLine1812" x1="0" y1="36" x2="43.955555555555556" y2="36" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                    <line id="SvgjsLine1811" x1="0" y1="1" x2="0" y2="36" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                </g>
                                <g id="SvgjsG1804" class="apexcharts-grid-borders" style="display: none;"></g>
                                <g id="SvgjsG1746" class="apexcharts-bar-series apexcharts-plot-series">
                                    <g id="SvgjsG1747" class="apexcharts-series" rel="1" seriesname="series-1" data:realindex="0">
                                        <path id="SvgjsPath1755" d="M -1.6605432098765431 34.001 L -1.6605432098765431 32.241 C -1.6605432098765431 31.241 -0.6605432098765431 30.241000000000003 0.33945679012345686 30.241000000000003 L 0.33945679012345686 30.241000000000003 C 1 30.241000000000003 1.6605432098765431 31.241 1.6605432098765431 32.241 L 1.6605432098765431 34.001 C 1.6605432098765431 35.001 0.6605432098765431 36.001 -0.33945679012345686 36.001 L -0.33945679012345686 36.001 C -1 36.001 -1.6605432098765431 35.001 -1.6605432098765431 34.001 Z " fill="url(#SvgjsLinearGradient1751)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M -1.6605432098765431 34.001 L -1.6605432098765431 32.241 C -1.6605432098765431 31.241 -0.6605432098765431 30.241000000000003 0.33945679012345686 30.241000000000003 L 0.33945679012345686 30.241000000000003 C 1 30.241000000000003 1.6605432098765431 31.241 1.6605432098765431 32.241 L 1.6605432098765431 34.001 C 1.6605432098765431 35.001 0.6605432098765431 36.001 -0.33945679012345686 36.001 L -0.33945679012345686 36.001 C -1 36.001 -1.6605432098765431 35.001 -1.6605432098765431 34.001 Z " pathfrom="M -1.6605432098765431 36.001 L -1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L 1.6605432098765431 36.001 L -1.6605432098765431 36.001 Z" cy="30.240000000000002" cx="1.6605432098765431" j="0" val="8" barheight="5.76" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1760" d="M 3.2234074074074073 34.001 L 3.2234074074074073 31.521 C 3.2234074074074073 30.521 4.223407407407407 29.521 5.223407407407407 29.521 L 5.223407407407407 29.521 C 5.883950617283951 29.521 6.544493827160494 30.521 6.544493827160494 31.521 L 6.544493827160494 34.001 C 6.544493827160494 35.001 5.544493827160494 36.001 4.544493827160494 36.001 L 4.544493827160494 36.001 C 3.8839506172839506 36.001 3.2234074074074073 35.001 3.2234074074074073 34.001 Z " fill="url(#SvgjsLinearGradient1756)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 3.2234074074074073 34.001 L 3.2234074074074073 31.521 C 3.2234074074074073 30.521 4.223407407407407 29.521 5.223407407407407 29.521 L 5.223407407407407 29.521 C 5.883950617283951 29.521 6.544493827160494 30.521 6.544493827160494 31.521 L 6.544493827160494 34.001 C 6.544493827160494 35.001 5.544493827160494 36.001 4.544493827160494 36.001 L 4.544493827160494 36.001 C 3.8839506172839506 36.001 3.2234074074074073 35.001 3.2234074074074073 34.001 Z " pathfrom="M 3.2234074074074073 36.001 L 3.2234074074074073 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 6.544493827160494 36.001 L 3.2234074074074073 36.001 Z" cy="29.52" cx="6.544493827160494" j="1" val="9" barheight="6.48" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1765" d="M 8.107358024691358 34.001 L 8.107358024691358 15.681 C 8.107358024691358 14.681 9.107358024691358 13.681 10.107358024691358 13.681 L 10.107358024691358 13.681 C 10.767901234567901 13.681 11.428444444444445 14.681 11.428444444444445 15.681 L 11.428444444444445 34.001 C 11.428444444444445 35.001 10.428444444444445 36.001 9.428444444444445 36.001 L 9.428444444444445 36.001 C 8.767901234567901 36.001 8.107358024691358 35.001 8.107358024691358 34.001 Z " fill="url(#SvgjsLinearGradient1761)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 8.107358024691358 34.001 L 8.107358024691358 15.681 C 8.107358024691358 14.681 9.107358024691358 13.681 10.107358024691358 13.681 L 10.107358024691358 13.681 C 10.767901234567901 13.681 11.428444444444445 14.681 11.428444444444445 15.681 L 11.428444444444445 34.001 C 11.428444444444445 35.001 10.428444444444445 36.001 9.428444444444445 36.001 L 9.428444444444445 36.001 C 8.767901234567901 36.001 8.107358024691358 35.001 8.107358024691358 34.001 Z " pathfrom="M 8.107358024691358 36.001 L 8.107358024691358 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 11.428444444444445 36.001 L 8.107358024691358 36.001 Z" cy="13.68" cx="11.428444444444445" j="2" val="31" barheight="22.32" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1770" d="M 12.991308641975309 34.001 L 12.991308641975309 32.241 C 12.991308641975309 31.241 13.991308641975309 30.241000000000003 14.991308641975309 30.241000000000003 L 14.991308641975309 30.241000000000003 C 15.651851851851852 30.241000000000003 16.312395061728395 31.241 16.312395061728395 32.241 L 16.312395061728395 34.001 C 16.312395061728395 35.001 15.312395061728395 36.001 14.312395061728395 36.001 L 14.312395061728395 36.001 C 13.651851851851852 36.001 12.991308641975309 35.001 12.991308641975309 34.001 Z " fill="url(#SvgjsLinearGradient1766)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 12.991308641975309 34.001 L 12.991308641975309 32.241 C 12.991308641975309 31.241 13.991308641975309 30.241000000000003 14.991308641975309 30.241000000000003 L 14.991308641975309 30.241000000000003 C 15.651851851851852 30.241000000000003 16.312395061728395 31.241 16.312395061728395 32.241 L 16.312395061728395 34.001 C 16.312395061728395 35.001 15.312395061728395 36.001 14.312395061728395 36.001 L 14.312395061728395 36.001 C 13.651851851851852 36.001 12.991308641975309 35.001 12.991308641975309 34.001 Z " pathfrom="M 12.991308641975309 36.001 L 12.991308641975309 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 16.312395061728395 36.001 L 12.991308641975309 36.001 Z" cy="30.240000000000002" cx="16.312395061728395" j="3" val="8" barheight="5.76" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1775" d="M 17.87525925925926 34.001 L 17.87525925925926 26.481 C 17.87525925925926 25.481 18.87525925925926 24.481 19.87525925925926 24.481 L 19.87525925925926 24.481 C 20.535802469135803 24.481 21.196345679012346 25.481 21.196345679012346 26.481 L 21.196345679012346 34.001 C 21.196345679012346 35.001 20.196345679012346 36.001 19.196345679012346 36.001 L 19.196345679012346 36.001 C 18.535802469135803 36.001 17.87525925925926 35.001 17.87525925925926 34.001 Z " fill="url(#SvgjsLinearGradient1771)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 17.87525925925926 34.001 L 17.87525925925926 26.481 C 17.87525925925926 25.481 18.87525925925926 24.481 19.87525925925926 24.481 L 19.87525925925926 24.481 C 20.535802469135803 24.481 21.196345679012346 25.481 21.196345679012346 26.481 L 21.196345679012346 34.001 C 21.196345679012346 35.001 20.196345679012346 36.001 19.196345679012346 36.001 L 19.196345679012346 36.001 C 18.535802469135803 36.001 17.87525925925926 35.001 17.87525925925926 34.001 Z " pathfrom="M 17.87525925925926 36.001 L 17.87525925925926 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 21.196345679012346 36.001 L 17.87525925925926 36.001 Z" cy="24.48" cx="21.196345679012346" j="4" val="16" barheight="11.52" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1780" d="M 22.75920987654321 34.001 L 22.75920987654321 11.360999999999999 C 22.75920987654321 10.360999999999999 23.75920987654321 9.360999999999999 24.75920987654321 9.360999999999999 L 24.75920987654321 9.360999999999999 C 25.419753086419753 9.360999999999999 26.080296296296297 10.360999999999999 26.080296296296297 11.360999999999999 L 26.080296296296297 34.001 C 26.080296296296297 35.001 25.080296296296297 36.001 24.080296296296297 36.001 L 24.080296296296297 36.001 C 23.419753086419753 36.001 22.75920987654321 35.001 22.75920987654321 34.001 Z " fill="url(#SvgjsLinearGradient1776)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 22.75920987654321 34.001 L 22.75920987654321 11.360999999999999 C 22.75920987654321 10.360999999999999 23.75920987654321 9.360999999999999 24.75920987654321 9.360999999999999 L 24.75920987654321 9.360999999999999 C 25.419753086419753 9.360999999999999 26.080296296296297 10.360999999999999 26.080296296296297 11.360999999999999 L 26.080296296296297 34.001 C 26.080296296296297 35.001 25.080296296296297 36.001 24.080296296296297 36.001 L 24.080296296296297 36.001 C 23.419753086419753 36.001 22.75920987654321 35.001 22.75920987654321 34.001 Z " pathfrom="M 22.75920987654321 36.001 L 22.75920987654321 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 26.080296296296297 36.001 L 22.75920987654321 36.001 Z" cy="9.36" cx="26.080296296296297" j="5" val="37" barheight="26.64" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1785" d="M 27.64316049382716 34.001 L 27.64316049382716 32.241 C 27.64316049382716 31.241 28.64316049382716 30.241000000000003 29.64316049382716 30.241000000000003 L 29.64316049382716 30.241000000000003 C 30.303703703703704 30.241000000000003 30.964246913580247 31.241 30.964246913580247 32.241 L 30.964246913580247 34.001 C 30.964246913580247 35.001 29.964246913580247 36.001 28.964246913580247 36.001 L 28.964246913580247 36.001 C 28.303703703703704 36.001 27.64316049382716 35.001 27.64316049382716 34.001 Z " fill="url(#SvgjsLinearGradient1781)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 27.64316049382716 34.001 L 27.64316049382716 32.241 C 27.64316049382716 31.241 28.64316049382716 30.241000000000003 29.64316049382716 30.241000000000003 L 29.64316049382716 30.241000000000003 C 30.303703703703704 30.241000000000003 30.964246913580247 31.241 30.964246913580247 32.241 L 30.964246913580247 34.001 C 30.964246913580247 35.001 29.964246913580247 36.001 28.964246913580247 36.001 L 28.964246913580247 36.001 C 28.303703703703704 36.001 27.64316049382716 35.001 27.64316049382716 34.001 Z " pathfrom="M 27.64316049382716 36.001 L 27.64316049382716 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 30.964246913580247 36.001 L 27.64316049382716 36.001 Z" cy="30.240000000000002" cx="30.964246913580247" j="6" val="8" barheight="5.76" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1790" d="M 32.52711111111111 34.001 L 32.52711111111111 14.240999999999998 C 32.52711111111111 13.240999999999998 33.52711111111111 12.240999999999998 34.52711111111111 12.240999999999998 L 34.52711111111111 12.240999999999998 C 35.187654320987654 12.240999999999998 35.8481975308642 13.240999999999998 35.8481975308642 14.240999999999998 L 35.8481975308642 34.001 C 35.8481975308642 35.001 34.8481975308642 36.001 33.8481975308642 36.001 L 33.8481975308642 36.001 C 33.187654320987654 36.001 32.52711111111111 35.001 32.52711111111111 34.001 Z " fill="url(#SvgjsLinearGradient1786)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 32.52711111111111 34.001 L 32.52711111111111 14.240999999999998 C 32.52711111111111 13.240999999999998 33.52711111111111 12.240999999999998 34.52711111111111 12.240999999999998 L 34.52711111111111 12.240999999999998 C 35.187654320987654 12.240999999999998 35.8481975308642 13.240999999999998 35.8481975308642 14.240999999999998 L 35.8481975308642 34.001 C 35.8481975308642 35.001 34.8481975308642 36.001 33.8481975308642 36.001 L 33.8481975308642 36.001 C 33.187654320987654 36.001 32.52711111111111 35.001 32.52711111111111 34.001 Z " pathfrom="M 32.52711111111111 36.001 L 32.52711111111111 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 35.8481975308642 36.001 L 32.52711111111111 36.001 Z" cy="12.239999999999998" cx="35.8481975308642" j="7" val="33" barheight="23.76" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1795" d="M 37.41106172839506 34.001 L 37.41106172839506 4.880999999999995 C 37.41106172839506 3.880999999999995 38.41106172839506 2.8809999999999953 39.41106172839506 2.8809999999999953 L 39.41106172839506 2.8809999999999953 C 40.071604938271605 2.8809999999999953 40.73214814814815 3.880999999999995 40.73214814814815 4.880999999999995 L 40.73214814814815 34.001 C 40.73214814814815 35.001 39.73214814814815 36.001 38.73214814814815 36.001 L 38.73214814814815 36.001 C 38.071604938271605 36.001 37.41106172839506 35.001 37.41106172839506 34.001 Z " fill="url(#SvgjsLinearGradient1791)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 37.41106172839506 34.001 L 37.41106172839506 4.880999999999995 C 37.41106172839506 3.880999999999995 38.41106172839506 2.8809999999999953 39.41106172839506 2.8809999999999953 L 39.41106172839506 2.8809999999999953 C 40.071604938271605 2.8809999999999953 40.73214814814815 3.880999999999995 40.73214814814815 4.880999999999995 L 40.73214814814815 34.001 C 40.73214814814815 35.001 39.73214814814815 36.001 38.73214814814815 36.001 L 38.73214814814815 36.001 C 38.071604938271605 36.001 37.41106172839506 35.001 37.41106172839506 34.001 Z " pathfrom="M 37.41106172839506 36.001 L 37.41106172839506 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 40.73214814814815 36.001 L 37.41106172839506 36.001 Z" cy="2.8799999999999955" cx="40.73214814814815" j="8" val="46" barheight="33.120000000000005" barwidth="3.3210864197530863"></path>
                                        <path id="SvgjsPath1800" d="M 42.29501234567901 34.001 L 42.29501234567901 15.681 C 42.29501234567901 14.681 43.29501234567901 13.681 44.29501234567901 13.681 L 44.29501234567901 13.681 C 44.955555555555556 13.681 45.6160987654321 14.681 45.6160987654321 15.681 L 45.6160987654321 34.001 C 45.6160987654321 35.001 44.6160987654321 36.001 43.6160987654321 36.001 L 43.6160987654321 36.001 C 42.955555555555556 36.001 42.29501234567901 35.001 42.29501234567901 34.001 Z " fill="url(#SvgjsLinearGradient1796)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9cc6xa3ll)" pathto="M 42.29501234567901 34.001 L 42.29501234567901 15.681 C 42.29501234567901 14.681 43.29501234567901 13.681 44.29501234567901 13.681 L 44.29501234567901 13.681 C 44.955555555555556 13.681 45.6160987654321 14.681 45.6160987654321 15.681 L 45.6160987654321 34.001 C 45.6160987654321 35.001 44.6160987654321 36.001 43.6160987654321 36.001 L 43.6160987654321 36.001 C 42.955555555555556 36.001 42.29501234567901 35.001 42.29501234567901 34.001 Z " pathfrom="M 42.29501234567901 36.001 L 42.29501234567901 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 45.6160987654321 36.001 L 42.29501234567901 36.001 Z" cy="13.68" cx="45.6160987654321" j="9" val="31" barheight="22.32" barwidth="3.3210864197530863"></path>
                                        <g id="SvgjsG1749" class="apexcharts-bar-goals-markers">
                                            <g id="SvgjsG1754" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1759" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1764" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1769" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1774" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1779" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1784" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1789" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1794" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                            <g id="SvgjsG1799" classname="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMask9cc6xa3ll)"></g>
                                        </g>
                                        <g id="SvgjsG1750" class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g>
                                    </g>
                                    <g id="SvgjsG1748" class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realindex="0"></g>
                                </g>
                                <line id="SvgjsLine1814" x1="-8.022222222222222" y1="0" x2="51.977777777777774" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                <g id="SvgjsG1815" class="apexcharts-xaxis" transform="translate(0, 0)">
                                    <g id="SvgjsG1816" class="apexcharts-xaxis-texts-g" transform="translate(0, 4)"></g>
                                </g>
                                <g id="SvgjsG1824" class="apexcharts-yaxis-annotations"></g>
                                <g id="SvgjsG1825" class="apexcharts-xaxis-annotations"></g>
                                <g id="SvgjsG1826" class="apexcharts-point-annotations"></g>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="Polaris-Card dashboardCard">
                    <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <h2 class="Polaris-Heading">Paused membership plans</h2>

                    </div>
                    <div class="Polaris-Card__Section dashboardCard-innner">
                        <span
                            class="Polaris-TextStyle--variationSubdued spanclass"><?php echo $activePlansP ??  '0'; ?></span>

                        <svg class="MuiBox-root css-uwwqev" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path fill="url(#a)" d="M134.926 133.612c-1.2-3.2-5.8-4.1-8.1-1.6-1.3-7.8 5-32.3-8.9-32-72.8-.5-48.2-8-52.4 77.7-.1 4.8 4.1 9 8.9 8.9h43.5c14 .4 7.6-24.5 8.9-32.4 2.9 3.2 8.7.8 8.5-3.5-.1-1 .4-16.5-.4-17.1zm-2.5 18.3h-5.6v-17.4h5.6v17.4z"></path>
                            <path fill="url(#b)" d="M74.426 190.212c.1 5.9-9.2 5.9-9.1 0-.1-5.9 9.2-5.9 9.1 0zm46.4-4.5c-5.9-.1-5.9 9.2 0 9.1 5.9.1 5.9-9.2 0-9.1z"></path>
                            <path fill="#007867" d="M73.126 99.513h2.5v87.499h-2.5v-87.5zm7.1 87.399h2.5v-87.4h-2.5v87.4zm7 0h2.5v-87.4h-2.5v87.4zm7.1 0h2.5v-87.4h-2.5v87.4zm7 0h2.5v-87.4h-2.5v87.4zm7.1 0h2.5v-87.4h-2.5v87.4zm7-87.4v87.5h2.5v-87.5h-2.5z"></path>
                            <path fill="#00A76F" d="M140.026 60.912l-1.8 6.7c-3.3-.9-7.3-.7-10.6.3.2-3.2 0-6.3-.6-9.4l-17.3-7.5c-2 14.2 1.4 21.6 7.4 32.8l-10.3 4.8c-1.7 4.1-4.1 4-7.1-.2-6.3 8.4-15.3-6.8-24.7-.3l-1.5-6.6-6.2.2c-5-10.2-13-36.6-1.2-43.9 0 0 0 .1.1.1 3-2 6.6-3.4 10.1-4.4l8.5 10.6c5.6-11.1 7.4-14.8 21.6-12.8l28.6 16.6c4.4 2.3 6.7 8 5 13z"></path>
                            <path fill="#C8FAD6" fill-rule="evenodd" d="M107.626 52.712h.1v2.3h-.1v12.4c0 1-.9 1.9-1.9 1.9-1.7-.5-9.1 1.4-8.9-1.9v-18.1c0-1 .9-1.9 1.9-1.9.3 0 7.7-.1 7.7.1 1.9.6 1.1 3.8 1.2 5.2zm-4 .1c0 2.2-3.3 2.2-3.3 0 0-2.1 3.3-2.1 3.3 0z" clip-rule="evenodd"></path>
                            <path fill="#004B50" d="M130.826 91.813l-12.9 56.5c1.3 1.2 6.9 11.4 6.9 13.2-1.8 5.8-12.9 0-15.9-3.3-1.6-6.9 6-48.8 5.5-56.6-3.9-.3-10.4.4-14.1.8-6.6.2-15.7-1.3-22.4-.8l-.2 1.3 5.4 45.2h-.2c.3.2.7.5 1.1.8 0 1.9 1.5 7.7.3 9.4-3.4 3.7-9.2 5.9-13.5 5.7-1.7-.1-2.9-2-2.2-3.6.1-.1 6.1-12 6.3-11.5h-.4l-13-57.1c-1-4.3 1.5-9 5.7-10.4 0 .1.1.2.1.3l6.2-.2 1.5 6.6c.4-.2.7-.3 1.1-.5.9-.3 1.8-.6 2.7-.8 7.4-2.8 14.8 9.4 20.9 1.5 3 4.2 5.4 4.3 7.1.2l7.6-3.5c.6-1 1.5-1.9 2.4-2.5.2.5.5 1 .8 1.3 4.6-.3 8.2-1.4 12.5 2.3.9 1.7 1.1 3.8.7 5.7zm-58.1-68.2c0-.8.1-1.5.2-2.2-1-.9-.2-5.2 1.3-3.3.5-.9 1.1-1.6 1.9-2.3 2.8 6.1 11.8 2.8 16.5 2.1 1.5 2.3 2 5.4 1.4 8.1 9.6-28.2-30.8-27.7-21.1-.4-.1-.6-.2-1.3-.2-2z"></path>
                            <path fill="#FBCDBE" d="M84.826 43.913l-8.5-10.6c-.1 0-.2.1-.3.1l-.2-2.3c-2.4-2.4-3.6-6.3-2.8-9.7-1-.9-.2-5.2 1.3-3.3.5-.9 1.1-1.6 1.9-2.3 2.8 6.1 11.8 2.8 16.5 2.1 2.9 4.3 1.8 10.9-2.2 14l.2 1.7c-.2-.1-5.9 10.3-5.9 10.3zm42.8 23.9v-.7c-.2 3.4-1 6.8-2.2 10.1-1.4.3-7.5-.3-8.4 1.1-1.5 1.4-.2 5.3 1.5 5.9 4.5-1.6 7.7-1.2 11.7 1.9l7.2-18.6c-3.2-.8-6.8-.6-9.8.3zm-20.2-9.8c-3.8-2.8-20.3 9.5-25.7 10.2-2.6 3.7-1.8 7.4 1 11.2 5.4-2.3 11.6-6.5 17.8-11.4l5.8-1.5c3.9-.9 4.7-6.7 1.1-8.5z"></path>
                            <defs>
                                <linearGradient id="a" x1="64.751" x2="64.751" y1="99.643" y2="186.617" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#5BE49B"></stop>
                                    <stop offset="1" stop-color="#007867"></stop>
                                </linearGradient>
                                <linearGradient id="b" x1="95.286" x2="95.286" y1="280.421" y2="185.693" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#5BE49B"></stop>
                                    <stop offset="1" stop-color="#007867"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
                <div class="Polaris-Card dashboardCard">
                    <div class="Polaris-Stack__Item Polaris-Stack__Item--fill">
                        <h2 class="Polaris-Heading">Cancelled membership plans</h2>

                    </div>
                    <div class="Polaris-Card__Section dashboardCard-innner">
                        <span
                            class="Polaris-TextStyle--variationSubdued spanclass"><?php echo  $activePlansC ?? '0'; ?></span>

                        <svg class="MuiBox-root css-uwwqev" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path fill="url(#a)" d="M85.6 134.7c-3.7-5.5-33-10.5-35.3-12 0 0-5.4-2-7.8 3.4-2.4 5.4-16.6 49.8-16.6 49.8s27.8 14.9 36 16.5c3.3.4 6.9-1.1 9.7-2.9l15.8-47.2c-.1 0 2.1-5.2-1.8-7.6z"></path>
                            <path fill="#004B50" d="M85.6 134.7c2.6 4.5-17.3 52.2-17.2 55.4v.1c2.5 4-4.6 6.9-5.6 2.3-.3 0-.6 0-.9-.1.2-.1.5-.2.8-.4-.1-1.8 1.2-3.2 2.9-3.3 1.6-4.7 17-51 17.3-51.6 0 0-.5-3.5-1.8-5.7 1.8.7 3.4 1.7 4.5 3.3zm2.4-29.4c-.3.5-17.4-7.9-14.5.4l-8 21.2c1 .3 2 .6 3 .8 1.7-5.2 6.9-20.9 7.4-22.2.5-1.5 6 .8 8.1 1.4 1.6.4 1.1 2 1.1 2l-7.4 21.4c1 .3 1.8.5 2.5.7l6.7-21.2c4.2-4.2 1.1-4.5 1.1-4.5zm-61.9 70.5c-4 0-4 6.2 0 6.2s4-6.2 0-6.2zm30.1 13c-4 0-4 6.2 0 6.2s4-6.2 0-6.2zm116.3 5.4v.2h-19.1v-7.7c6.1.3 19.4-2.3 19.1 7.5zm-65-11.4l-9.8-6.3-4.1 6.5 16.1 10.3c2.3-3.5 1.4-8.3-2.2-10.5zm56.3-2.1L156.7 94l-13.2-24.5-22.5 3.9c.8 15.9-1.6 50.8-3.2 66.8l-16.1 29.9 7.7 5.5 21.9-28.5 8.7-28.3 13.6 62.5 10.2-.6z"></path>
                            <path fill="#5BE49B" d="M138 57.5s4.6-5.5-.5-10.6c0 0-6.5-10.5-8.4-12.4 0 0 0 .2-10.9.6l-.2.2c12.8 2.3 18 22.9 12 38.7l14-3.5-6-13z"></path>
                            <path fill="#00A76F" d="M161.4 93.8c2.4 8.2 3.9 14.1 3.9 14.1s-2.9.2-7.4.9L156.7 94c-4.4-7.8-15.8-28.3-18.8-36.4 0 0 4.7-5.5-.4-10.6 0 0-6.4-10.5-8.4-12.4 2.1-.3 4.4 1.5 6.8 4.1v-.2c10.1 13.8 23.7 37.9 33.3 51.8l-7.8 3.5zm-38.6-57c-6.8-4.5-16.7 2.2-15 10.2L80.7 92.9l8.8 3.6 23.8-31.3c.9 12.1-9.7 32.4-16.2 36.9-4.6 4.4 2 12.3 5.5 13.7v.1s7.7 3.6 17 3.5c.4-5.3 1.9-21.2 1.5-26.2 11.8-18.9 18.1-42.4 1.7-56.4z"></path>
                            <path fill="#004B50" d="M109.6 29.8c2.1-.4 11.1 1.4 13.3 1.8 1.1.1 2.1-.7 2-1.8 12.4.5 8.6-.2 9.2-10.7h3.1c.2-4.6-3.1-8.2-7-10.2-7.7-8.4-22.8-2-22.5 9.3.5 1.6-1.5 12 1.9 11.6z"></path>
                            <path fill="#FBCDBE" d="M154.9 181.2l6.2-.4-.2 5.8h-6v-5.4zM99 177.3l5.2 3.3 4.6-5.5-5.8-4.1-4 6.3zM80.7 92.9l.9-1.6-5.2 6.4c-10 9.6 11.4 15.2 7.6 2.1l3.8-4-7.1-2.9zm95.5 4.9c-1-2.3-6.9-7.5-8.4-9.7l1.4 2.2-6.9 3.1 4 3.9c-3.5 9.4 12.5 10.2 9.9.5zm-52.9-80.6l-.7 2h-.7c0 3.9 1.9 7.5 3.1 10.3 5-.6 8.7-5.2 8.4-10.3h-10l-.1-2z"></path>
                            <defs>
                                <linearGradient id="a" x1="25.9" x2="25.9" y1="122.338" y2="192.465" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#5BE49B"></stop>
                                    <stop offset="1" stop-color="#007867"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="salesOverview-sale">
        <h2 class="Polaris-Heading h2heading">Total sales</h2>
        <div class="Polaris-Card__Section">
            <div id="reportrange">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div><br><br>
            <div id="HighContainer"></div>
        </div>
    </div>
</div>
</div>

<?php include("footer.php"); ?>