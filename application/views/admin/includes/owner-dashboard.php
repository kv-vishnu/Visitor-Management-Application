<!-- end page title -->

<div class="application-navigation container">
    <ul class="application-navigation__ul">
        <li class="application-navigation__li">
            <a href="<?php echo base_url('admin/dashboard'); ?>"
                class="application-navigation__a <?php echo ($controller == 'dashboard') ? 'application-navigation__a--active' : ''; ?>">Dashboard</a>
        </li>
        <li class="application-navigation__li">
            <a href="<?php echo base_url('admin/enquiry/index/0'); ?>"
                class="application-navigation__a <?php echo ($controller == 'product') ? 'application-navigation__a--active' : ''; ?>">Enquiry</a>
        </li>

        <li class="application-navigation__li">
            <a href="<?php echo base_url('admin/reports'); ?>"
                class="application-navigation__a <?php echo ($controller == 'reports') ? 'application-navigation__a--active' : ''; ?>">Reports</a>
        </li>
        <!-- <li class="application-navigation__li">
            <a href="<?php echo base_url('admin/settings'); ?>"
                class="application-navigation__a <?php echo ($controller == 'settings') ? 'application-navigation__a--active' : ''; ?>">Settings</a>
        </li> -->

        <li class="application-navigation__li">
            <a href="<?php echo base_url('admin/login/logout'); ?>" class="application-navigation__a">Logout</a>
        </li>
    </ul>
</div>