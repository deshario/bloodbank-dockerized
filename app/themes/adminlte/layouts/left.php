<?php
use yii\helpers\Html;
use app\models\Managers;
use yii\bootstrap\Modal;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->

        <?php if (Yii::$app->user->isGuest) { ?>

            <?php

            $menuItems = [
                ['label' => 'Cloud Bloodbank', 'options' => ['class' => 'header']],
                ['label' => 'Login', 'icon' => 'sign-in', 'url' => ['site/login']],
            ];

            ?>

        <?php } else { ?>
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= $directoryAsset ?>/img/doctor.png" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p><?=Yii::$app->user->identity->username ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <?php
            $Maccess = Yii::$app->user->identity->roles;
            if($Maccess == Managers::ROLE_MANAGER){
                $id = Yii::$app->user->identity->id;
                $key = Yii::$app->user->identity->manager_key;
                //$params = '{"id":1,"key":2}';
                //$params = '{"id":'.$id.',"key":"'.$key.'"}';
                $params = '{"key":"'.$key.'"}';
                $menuItems = [
                    ['label' => 'Cloud Bloodbank', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Requests',
                        //'active' => true,
                        'icon' => 'arrow-up',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Blood Requests', 'icon' => 'users', 'url' => ['blood-requests/index']],
                            ['label' => 'Branch Requests', 'icon' => 'desktop', 'url' => ['branch-requests/index']],
                        ],
                    ],
                    ['label' => 'Branches', 'icon' => 'hospital-o fa-lg', 'url' => ['branch/index']],
                    ['label' => 'Campaigns', 'icon' => 'bell-o', 'url' => ['campaigns/index']],
                    [
                        'label' => 'Saved',
                        'icon' => 'save',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Blood Requests', 'icon' => 'users', 'url' => ['saved-blood-requests/index'],],
                            ['label' => 'Branch Requests', 'icon' => 'desktop', 'url' => ['saved-branch-requests/index'],],
                        ],
                    ],
                    [
                        'label' => 'Verifications',
                        'icon' => 'key',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Blood Requests Verification', 'icon' => 'users', 'url' => ['blood-requests-verification/index'],],
                            ['label' => 'Branch Requests Verification', 'icon' => 'desktop', 'url' => ['branch-requests-verification/index'],],
                        ],
                    ],
                    ['label' => 'Day Reservation', 'icon' => 'hospital-o fa-lg', 'url' => ['donation-day-reservation/index']],
                    ['label' => 'Android Users', 'icon' => 'user fa-lg', 'url' => ['users/index']],

//                    Yii::$app->user->isGuest ? [
//                        'label' => 'Members Area',
//                        'icon' => 'user',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => 'Sign In', 'icon' => 'sign-in', 'url' => ['site/login'],],
//                            ['label' => 'New Yii2User', 'icon' => 'user-plus', 'url' => ['site/signup'],],
//                        ],
//                    ] :
                        [
                            'label' => 'Tools',
                            //'active' => true,
                            'icon' => 'cog',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Create Blood Request', 'icon' => 'plus', 'url' => ['blood-requests/create']],
                                ['label' => 'Create Branch Request', 'icon' => 'plus', 'url' => ['branch-requests/create']],
                                ['label' => 'Create Branch', 'icon' => 'plus', 'url' => ['branch/create']],
                                ['label' => 'Create Campaign', 'icon' => 'plus', 'url' => ['campaigns/create']],
                                ['label' => 'Profile', 'url' => ['managers/profile'],
                                    'template' => '<a href="{url}" data-method="post" data-params='.$params.'><i class="fa fa-user"></i>{label}</a>'],
                                ['label' => 'Logout', 'url' => ['site/logout'], 'template' => '<a href="{url}" data-method="post"><i class="fa fa-sign-out"></i>{label}</a>'],
                            ],
                        ]
                ];
            }elseif ($Maccess == Managers::ROLE_ADMIN){
                $menuItems = [
                    ['label' => 'Cloud Bloodbank', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Managers',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Create Manager', 'icon' => 'plus', 'url' => ['managers/create'],],
                            ['label' => 'Modify Manager', 'icon' => 'edit', 'url' => ['managers/index'] ],
                        ],
                    ],
                    //['label' => 'Profile', 'icon' => 'heart-o', 'url' => ['managers/admin']],
                    ['label' => 'Logout', 'url' => ['site/logout'], 'template' => '<a href="{url}" data-method="post"><i class="fa fa-sign-out"></i>{label}</a>'],
                ];
            }else{

            }

        } ?>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => $menuItems
        ]); ?>

    </section>

</aside>
