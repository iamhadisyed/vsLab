<?php

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;


//Menu::macro('fullsubmenuexample', function () {
//    return Menu::new()->prepend('<a href="#"><span> Multilevel PROVA </span> <i class="fa fa-angle-left pull-right"></i></a>')
//        ->addParentClass('treeview')
//        ->add(Link::to('/link1prova', 'Link1 prova'))->addClass('treeview-menu')
//        ->add(Link::to('/link2prova', 'Link2 prova'))->addClass('treeview-menu')
//        ->url('http://www.google.com', 'Google');
//});

Menu::macro('adminlteSubmenu', function ($submenuName) {
    return Menu::new()->prepend('<a href="#"><span> ' . $submenuName . '</span> <i class="fa fa-angle-left pull-right"></i></a>')
        ->addParentClass('treeview')->addClass('treeview-menu');
});
Menu::macro('adminlteMenu', function () {
    return Menu::new()
        ->addClass('sidebar-menu')->setAttribute('data-widget', 'tree');
});
Menu::macro('adminlteSeparator', function ($title) {
    return Html::raw($title)->addParentClass('header');
});

Menu::macro('adminlteDefaultMenu', function ($content) {
    return Html::raw('<i class="fa fa-link"></i><span>' . $content . '</span>')->html();
});

Menu::macro('sidebar', function () {
    return Menu::adminlteMenu()
        ->add(Html::raw('NAVIGATION BAR')->addParentClass('header'))
        ->action('HomeController@index', '<i class="fa fa-home"></i><span>Home</span>')
//        ->action('HomeController@dashboard', '<i class="fa fa-wrench"></i><span>Dashboard</span>')

//        ->link('http://www.acacha.org', Menu::adminlteDefaultMenu('Another link'))
//        ->url('http://www.google.com', 'Google')
        ->addIfCan('role_edit', Menu::adminlteSeparator('System Admin Functions'))
        ->addIfCan('role_edit', Menu::new()->prepend('<a href="#"><i class="fa fa-cog"></i><span>System Management</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->add(Link::to('/sites', 'Sites'))->addClass('treeview-menu')
            ->add(Link::to('/usermanagement', 'Users'))
            ->add(Menu::new()->prepend('<a href="#"><span>System Configuration</span> <i class="fa fa-angle-left pull-right"></i></a>')
                ->addParentClass('treeview')
                ->add(Link::to('/sysadmin/cloud-config', 'Cloud System Parameters'))->addClass('treeview-menu')
                ->add(Link::to('/roles', 'Roles'))
                ->add(Link::to('/permissions', 'Permissions'))
                ->add(Link::to('/oauth-settings', 'OAuth Settings'))
            )
            ->add(Menu::new()->prepend('<a href="#"><span>System Resources</span> <i class="fa fa-angle-left pull-right"></i></a>')
                ->addParentClass('treeview')
                ->add(Link::to('/sysadmin/instances', 'Instances'))->addClass('treeview-menu')
                ->add(Link::to('/', 'Images'))
                ->add(Link::to('/', 'Flavors'))
                ->add(Link::to('/', 'Volumes'))
            )
        )
        ->addIfCan('site_view', Menu::adminlteSeparator('Site Admin Functions'))
        ->addIfCan('site_view', Menu::new()->prepend('<a href="#"><i class="fa fa-building"></i><span>Site Management</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->add(Link::to('/mysites/mysite', 'My Sites'))->addClass('treeview-menu')
            ->add(Link::to('/mysites/groups/0', 'Site Groups'))
            ->add(Link::to('/mysites/users/0', 'Site Users'))
        )
        ->addIfCan('group_add', Menu::adminlteSeparator('Instructor Functions'))
        ->addIfCan('group_add', Menu::new()->prepend('<a href="#"><i class="fa fa-users"></i><span>Groups Management</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
//            ->add(Link::to('/', 'Apply a New Group'))
            ->add(Link::to('/groups', 'My Groups'))->addClass('treeview-menu')
//            ->add(Link::to('/resource', 'Join Other Groups'))
        )
        ->addIfCan('lab-design_add', Menu::new()->prepend('<a href="#"><i class="fa fa-pencil-square"></i><span>Lab Design</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->add(Link::to('/labrepo/2', 'Lab Repository'))->addClass('treeview-menu')
            ->add(Link::to('/labenv', 'My Lab Environment'))
            ->add(Link::to('/labcontents', 'My Lab Content'))
        )
        ->addIfCan('lab-deploy_add', Menu::new()->prepend('<a href="#"><i class="fa fa-cubes"></i><span>Lab Management</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->add(Link::to('/subgroups/0', 'Team & Lab Assignment'))->addClass('treeview-menu')
            ->add(Link::to('/labsdeploy/0', 'Lab Deployment'))
        )
        ->addIfCan('group_add', Menu::new()->prepend('<a href="/grade/0/0"><i class="fa fa-users"></i><span>Grading Center</span> </a>')

//            ->add(Link::to('/resource', 'Join Other Groups'))
        )
        #adminlte_menu
        ->add(Menu::adminlteSeparator('User Lab Functions'))
        ->add(Menu::new()->prepend('<a href="#"><i class="fa fa-cube"></i><span>My Class</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->add(Menu::build($this->userGroups(), function ($menu, $group) {
                if ($group->name == 'cse548-advanced-computer-network-security') {
                    $menu->link('/mylabs/' . $group->id, 'CSE 548');
                }elseif($group->name == 'CSE-468-Computer-Network-Security-Fall-2020'){
                    $menu->link('/mylabs/' . $group->id, 'CSE-468-Computer-Network-Se<br>curity-Fall-2020');
                }else{
                    $menu->link('/mylabs/' . $group->id, $group->name);
                }
            }))->addClass('treeview-menu')
        )
        ->add(Menu::adminlteSeparator('Lab Content'))
        ->add(Menu::new()->prepend('<a href="#"><i class="fa fa-cube"></i><span>Lab Content</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->add(Menu::build($this->userGroups(), function ($menu, $group) {
                if($group->name == 'CSE-468-Computer-Network-Security-Fall-2020'){
                    $menu->link('/content/' . "CNS-10003", 'Network and Security Tool:<br/> Hping');
                    $menu->link('/content/' . "CNS-10001", 'Network and Security Tool:<br/> Wireshark and Tshark');
                    $menu->link('/content/' . "CNS-10002", 'Network and Security Tool:<br/> TCPdump');
                    $menu->link('/content/' . "CNS-00001", 'Packet Filter Firewall');
                    $menu->link('/content/' . "CNS-00003", 'Network Intrusion Detection<br/> (Snort)');
                    $menu->link('/content/' . "SYS-00008", 'Syslog (rsyslogd) Local<br/> Logging on Linux');
                    $menu->link('/content/' . "SYS-00009", 'Syslog (rsyslogd) Remote<br/> Logging on Linux');
                    $menu->link('/content/' . "CNS-20001", 'Network and Security Tool:<br/> Nmap');
                    $menu->link('/content/' . "CNS-20002", 'Network and Security:<br/> Testing Firewall and IDS');
                    $menu->link('/content/' . "CNS-20010", 'Network and Security Tool:<br/> Metasploit (CLI)');
                }
            }))->addClass('treeview-menu')
        )
        ->add(Menu::adminlteSeparator('Help'))
        ->add(Menu::new()->prepend('<a href="#"><i class="fa fa-question-circle"></i><span>User Guides</span> <i class="fa fa-angle-left pull-right"></i></a>')
            ->addParentClass('treeview')
            ->addIfCan('group_add',Link::to('/doc/lab-instructor-guide.pdf', 'Guidance for Instructors')->setAttribute('target', '_blank'))->addClass('treeview-menu')
            ->add(Link::to('/doc/lab-student-guide.pdf', 'Guidance for Students')->setAttribute('target', '_blank'))->addClass('treeview-menu')
            ->add(Link::to('https://submissions.storage.mobicloud.asu.edu/submissions/1133/FAQ.pdf', 'FAQ')->setAttribute('target', '_blank'))->addClass('treeview-menu')
//            ->add(Link::to('/', 'Active Tickets'))
//            ->add(Link::to('/', 'Completed Tickets'))
//            ->add(Link::to('/', 'Settings'))
        )
//        ->add(Menu::adminlteSeparator('Social Functions'))
//        ->add(Menu::new()->prepend('<a href="#"><i class="fa fa-share"></i><span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>')
//            ->addParentClass('treeview')
//            ->add(Link::to('/link1', 'Link1'))->addClass('treeview-menu')
//            ->add(Link::to('/link2', 'Link2'))
//            ->url('http://www.google.com', 'Google')
//            ->add(Menu::new()->prepend('<a href="#"><span>Multilevel 2</span> <i class="fa fa-angle-left pull-right"></i></a>')
//                ->addParentClass('treeview')
//                ->add(Link::to('/link21', 'Link21'))->addClass('treeview-menu')
//                ->add(Link::to('/link22', 'Link22'))
//                ->url('http://www.google.com', 'Google')
//            )
//        )
//        ->add(
//            Menu::fullsubmenuexample()
//        )
//        ->add(
//            Menu::adminlteSubmenu('Best menu')
//                ->add(Link::to('/acacha', 'acacha'))
//                ->add(Link::to('/profile', 'Profile'))
//                ->url('http://www.google.com', 'Google')
//        )
        ->setActiveFromRequest();
});