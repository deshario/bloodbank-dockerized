<?php $baseurl = "http://localhost/THESIS/bloodbank/web/"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CBB REST API</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='shortcut icon' type='image/x-icon' href='http://localhost/THESIS/bloodbank/views/site/favicon.ico'/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .MyBody {
            background: url(https://mgcreativedesign.files.wordpress.com/2014/11/website-design-background.png) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .tab-content {
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            border-radius: 0px 0px 5px 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .nav-tabs {margin-bottom: 0;}
        .Mlink:hover {text-decoration: underline;}
    </style>
    <script>
        $(document).ready(function () {
            activateTab('blood_req_tab');
        });

        function activateTab(tab) {
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        };
    </script>
</head>

<!--<body style="background: #e1e1e1;">-->
<body class="MyBody">
<div class="container" style="margin-top:10px; background: white; border-radius:10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
    <h3><span class="fa fa-code-fork fa-lg"></span> CBB REST API</h3>
    <hr/>
    <ul class="nav nav-tabs">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-bell-o"></span> Branch & BTypes <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#all_branches_tab" data-toggle="tab">All Branches</a></li>
                <li><a href="#all_bloodtypes_tab" data-toggle="tab">All BloodTypes</a></li>
                <li><a href="#create_bloodtypes_tab" data-toggle="tab">Create BloodType</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-envelope-o"></span> Requests <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#blood_req_tab" data-toggle="tab">Blood Requests</a></li>
                <li><a href="#branch_req_tab" data-toggle="tab">Branch Requests</a></li>
                <li><a href="#create_blood_req_tab" data-toggle="tab">Create Blood Request</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-bell-o"></span> Campaigns <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#all_campaigns_tab" data-toggle="tab">All Campaigns</a></li>
                <li><a href="#single_campaign_tab" data-toggle="tab">Single Campaigns</a></li>
                <li><a href="#campaign_subscribe_tab" data-toggle="tab">Create Subscriptions</a></li>
                <li><a href="#campaign_unsubscribe_tab" data-toggle="tab">Remove Subscriptions</a></li>
                <li><a href="#campaign_subscriptions" data-toggle="tab">My Subscriptions</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-file-text-o"></span> Records <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#all_donations_tab" data-toggle="tab">All Donations</a></li>
                <li><a href="#get_user_donations_tab" data-toggle="tab">User Donations</a></li>
                <li><a href="#get_user_requests_tab" data-toggle="tab">User Requests</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-key"></span> Verifications <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#pending_bloodreq_verification" data-toggle="tab">Pending Blood Request Verification</a></li>
                <li><a href="#pending_branchreq_verification" data-toggle="tab">Pending Branch Request Verification</a></li>
                <li><a href="#blood_donation_verification" data-toggle="tab">Create Blood Donation Verification</a></li>
                <li><a href="#branch_donation_verification" data-toggle="tab">Create Branch Donation Verification</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-calendar"></span> Reservation <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#create_reservation" data-toggle="tab">Create Reservation</a></li>
                <li><a href="#user_reservation" data-toggle="tab">User Reservation</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-user"></span> Save <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#save_blood_req" data-toggle="tab">Save Blood Request</a></li>
                <li><a href="#get_saved_bloodreq" data-toggle="tab">Get Saved Blood Request</a></li>
                <li><a href="#save_branch_req" data-toggle="tab">Save Branch Request</a></li>
                <li><a href="#get_saved_branchreq" data-toggle="tab">Get Saved Branch Request</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-user"></span> Member <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#login_tab" data-toggle="tab">Login</a></li>
                <li><a href="#register_tab" data-toggle="tab">Register</a></li>
                <li><a href="#update_token" data-toggle="tab">Update Token</a></li>
                <li><a href="#remove_token" data-toggle="tab">Remove Token</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="fa fa-line-chart"></span> Analysis <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="#analysis_bloodreq_tab" data-toggle="tab">Blood Request</a></li>
                <li><a href="#analysis_bloodgroup_tab" data-toggle="tab">Blood Group</a></li>
            </ul>
        </li>
        <!--    <li><a href="#1a" data-toggle="tab">Menu 2</a></li>-->
    </ul>

    <div class="tab-content clearfix">

        <div class="tab-pane" id="all_branches_tab">
            <div class="alert alert-danger" role="alert">
                <strong>Branches</strong> |
                <small>All Branches that had been registered.</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/branches/fetchAll'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/branches/fetchAll'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="all_bloodtypes_tab">
            <div class="alert alert-danger" role="alert">
                <strong>Blood Types</strong> |
                <small>All Blood Types that was available in the system</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-types/fetchAll'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-types/fetchAll'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="create_bloodtypes_tab">
            <div class="alert alert-info" role="alert">
                <strong>Create BloodType</strong> |
                <small>Add New BloodType to system</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-types/create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-types/create'; ?></a>
                </code><br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : blood_name:str</code>

            </h4>
        </div>

        <div class="tab-pane" id="blood_req_tab">
            <div class="alert alert-danger" role="alert">
                <strong>Blood Requests</strong> |
                <small>Requests that has been created by users and had been <b>approved {waiting for donors}</b></small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests/fetchAll'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests/fetchAll'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="branch_req_tab">
            <div class="alert alert-danger" role="alert">
                <strong>Branch Requests</strong> |
                <small>Requests that has been created by branch,hospital,staff</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/branch-requests/fetchAll'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/branch-requests/fetchAll'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="create_blood_req_tab">
            <div class="alert alert-info" role="alert">
                <strong>Create Blood Request</strong> |
                <small>Create Individual Blood Request</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests/create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests/create'; ?></a>
                </code><br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : requester_id:int, blood_group:int, blood_amount:int, lat_long:latlong, location_name:str, full_address:text(opt), reason:str(opt), postal_code:str(opt)</code>

            </h4>
        </div>

        <div class="tab-pane" id="all_campaigns_tab">
            <div class="alert alert-info" role="alert">
                <strong>All Campaigns</strong> |
                <small>A tool that helps to gather more donors at a short time</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/campaigns/fetchAll'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/campaigns/fetchAll'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="single_campaign_tab">
            <div class="alert alert-info" role="alert">
                <strong>Single Campaign</strong> |
                <small>Get Single Campaign</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/campaigns/fetchSingle/campaign_key_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/campaigns/fetchSingle/'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : campaign_key:str</code>
            </h4>
        </div>

        <div class="tab-pane" id="campaign_subscribe_tab">
            <div class="alert alert-info" role="alert">
                <strong>Create Subscriptions</strong> |
                <small>Subscribe to Specific Campaign</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/campaigns/subscribe'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/campaigns/subscribe'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : subscribed_by:int, subscribed_campaign:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="campaign_unsubscribe_tab">
            <div class="alert alert-info" role="alert">
                <strong>Un-subscribe Campaign</strong> |
                <small>Un-Subscribe to Specific Campaign</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/campaigns/unsubscribe'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/campaigns/unsubscribe'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : subscribed_by:int, subscribed_campaign:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="campaign_subscriptions">
            <div class="alert alert-info" role="alert">
                <strong>Campaign Subscriptions</strong> |
                <small>Check My Campaign Subscriptions</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/campaigns/checkSubscriptions/user_id/camp_id'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/campaigns/checkSubscriptions/user_id/camp_id'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int, camp_id:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="all_donations_tab">
            <div class="alert alert-info" role="alert">
                <strong>All Donations</strong> |
                <small>Get All Completed Donations</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests-verification/fetchAll'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests-verification/fetchAll'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="get_user_donations_tab">
            <div class="alert alert-info" role="alert">
                <strong>User Donations</strong> |
                <small>Get All Donation Records of Specific User</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests-verification/fetchAllByUserID/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests-verification/fetchAllByUserID/'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="get_user_requests_tab">
            <div class="alert alert-info" role="alert">
                <strong>User Requests</strong> |
                <small>Get All Request Records of Specific User</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests/fetchAllByUserID/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests/fetchAllByUserID/'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="pending_bloodreq_verification">
            <div class="alert alert-info" role="alert">
                <strong>Blood Request Verification Pending</strong> |
                <small>Fetch All Pending Blood Request By Specific User</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests-verification/fetchAllPendingsBySavedUser/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests-verification/fetchAllPendingsBySavedUser/'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int</code>

            </h4>
        </div>

        <div class="tab-pane" id="pending_branchreq_verification">
            <div class="alert alert-info" role="alert">
                <strong>Branch Request Verification Pending</strong> |
                <small>Fetch All Pending Branch Request By Specific User</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/branch-requests-verification/fetchAllPendingsBySavedUser/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/branch-requests-verification/fetchAllPendingsBySavedUser/'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int</code>

            </h4>
        </div>

        <div class="tab-pane" id="blood_donation_verification">
            <div class="alert alert-info" role="alert">
                <strong>Blood Donation Verification</strong> |
                <small>Create Blood Donation Verification</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/blood-requests-verification/create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/blood-requests-verification/create'; ?></a>
                </code><br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : request_id:int, donated_by:int, donated_to:int, paid_amount:int</code>

            </h4>
        </div>

        <div class="tab-pane" id="branch_donation_verification">
            <div class="alert alert-info" role="alert">
                <strong>Branch Donation Verification</strong> |
                <small>Create Branch Donation Verification</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/branch-requests-verification/create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/branch-requests-verification/create'; ?></a>
                </code><br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : branch_requests_id:int, donor_id:int, paid_amount:int</code>

            </h4>
        </div>

        <div class="tab-pane" id="create_reservation">
            <div class="alert alert-info" role="alert">
                <strong>Day Reservation</strong> |
                <small>Create Reservation for your upcoming donation</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/day-reservation/create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/day-reservation/create'; ?></a>
                </code><br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int, branch_id:int, reserved_date:datetime, user_notes:str</code>

            </h4>
        </div>

        <div class="tab-pane" id="save_blood_req">
            <div class="alert alert-info" role="alert">
                <strong>Save Blood Request</strong> |
                <small>Save Blood Request For User</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/saved-blood-requests/Create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/saved-blood-requests/Create'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : request_id:int, saved_by:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="get_saved_bloodreq">
            <div class="alert alert-success" role="alert">
                <strong>Get Saved Blood Request</strong> |
                <small>Get Saved Blood Request of each user</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/saved-blood-requests/fetchAllByUserID/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/saved-blood-requests/fetchAllByUserID/'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="save_branch_req">
            <div class="alert alert-info" role="alert">
                <strong>Save Branch Request</strong> |
                <small>Save Branch Request For User</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/saved-branch-requests/Create'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/saved-branch-requests/Create'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : requests_id:int, saved_by:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="get_saved_branchreq">
            <div class="alert alert-success" role="alert">
                <strong>Get Saved Branch Request</strong> |
                <small>Get Saved Branch Request of each user</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/saved-branch-requests/fetchAllByUserID/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/saved-branch-requests/fetchAllByUserID/'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id:int</code>
            </h4>
        </div>

        <div class="tab-pane" id="login_tab">
                    <div class="alert alert-info" role="alert">
                        <strong>User Login</strong> |
                        <small>Access to the application</small>
                    </div>
                    <h4>
                        <code>API URL : <a href="<?= $baseurl . 'API/users/login'; ?>" target="_blank" class="Mlink">
                                <?= $baseurl . 'API/users/login'; ?></a>
                        </code>
                        <br/><br/>
                        <code>METHOD : POST</code>
                        <br/><br/>
                        <code>PARAMETERS : virtual_user:str, virtual_password:str, profile_token:str</code>
                    </h4>
                </div>

        <div class="tab-pane" id="register_tab">
            <div class="alert alert-info" role="alert">
                <strong>User Register</strong> |
                <small>Create a new user to access application</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/users/register'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/users/register'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : username:str, blood_group:int, virtual_password:str, profile_token:token</code>
            </h4>
        </div>

        <div class="tab-pane" id="update_token">
            <div class="alert alert-info" role="alert">
                <strong>Update Token</strong> |
                <small>Update device token in database</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/users/updateToken'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/users/updateToken'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : virtual_user:str, profile_token:str</code>
            </h4>
        </div>

        <div class="tab-pane" id="remove_token">
            <div class="alert alert-info" role="alert">
                <strong>Remove Token</strong> |
                <small>Remove token of device from database</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/users/removeToken'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/users/removeToken'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : POST</code>
                <br/><br/>
                <code>PARAMETERS : phone:number</code>
            </h4>
        </div>

        <div class="tab-pane" id="user_reservation">
            <div class="alert alert-info" role="alert">
                <strong>User Reservation</strong> |
                <small>View All User Reservations</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/day-reservation/fetchAllByUserID/user_id_here'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/day-reservation/fetchAllByUserID/'; ?></a>
                </code>
                <br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : user_id</code>
            </h4>
        </div>

        <div class="tab-pane" id="analysis_bloodreq_tab">
            <div class="alert alert-success" role="alert">
                <strong>Blood Request Analysis</strong> |
                <small>Get the total amount of each request status</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/analysis/bloodRequest'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/analysis/bloodRequest'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

        <div class="tab-pane" id="analysis_bloodgroup_tab">
            <div class="alert alert-success" role="alert">
                <strong>Blood Group Analysis</strong> |
                <small>Get the total amount of each bloodgroup that had been requested</small>
            </div>
            <h4>
                <code>API URL : <a href="<?= $baseurl . 'API/analysis/bloodGroup'; ?>" target="_blank" class="Mlink">
                        <?= $baseurl . 'API/analysis/bloodGroup'; ?></a>
                </code><br/><br/>
                <code>METHOD : GET</code>
                <br/><br/>
                <code>PARAMETERS : NOT REQUIRED</code>
            </h4>
        </div>

    </div>

    <footer style="text-align: center">
        <p><strong>Copyright &copy; 2018 <a href="https://github.com/deshario" target="_blank">Deshario Cloud</a>.</strong> All rights
            reserved.</p>
    </footer>

</div>


</body>
</html>