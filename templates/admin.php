<div class="wrap">
    <h1 class="mb-2">Headlab Theme Utilities</h1>

    <?php settings_errors(); ?>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-general-tab" data-toggle="tab" href="#nav-general">
                <?php _e('General', 'headlab-theme-utilities'); ?>
            </a>
            <a class="nav-item nav-link" id="nav-updates-tab" data-toggle="tab" href="#nav-updates">
                <?php _e('Updates', 'headlab-theme-utilities'); ?>
            </a>
            <a class="nav-item nav-link" id="nav-documentation-tab" data-toggle="tab" href="#nav-documentation">
                <?php _e('Documentation', 'headlab-theme-utilities'); ?>
            </a>
            <a href="<?php echo admin_url( 'admin.php?page=headlab_theme_utilities_contact') ?>" class="nav-item nav-link ml-auto bg-white">Donate</a>
        </div>
    </nav>


    <div class="tab-content shadow" id="nav-tabContent">
        <div class="tab-pane p-4 show active" id="nav-general">
            <form action="options.php" method="post">
                <?php 
                    settings_fields('headlab_theme_utilities_settings');
                    do_settings_sections( 'headlab_theme_utilities' );
                    echo "<hr class='mb-0'>";
                    submit_button();
                ?>
            </form>
        </div>

        <div class="tab-pane p-4" id="nav-updates">
            <h2>Updates manager</h2>
        </div>

        <div class="tab-pane p-4" id="nav-documentation">
            <div class="container-fluid p-0">
                <div class="row m-0">
                    <div class="col-12 pl-0 w-100">
                        <h2 class="m-0">Documentation</h2>
                        <span class="d-block border-bottom mt-3"></span>
                    </div>
                    <div class="col-6 border-right pl-0 pt-3">
                        <?php include plugin_dir_path( __FILE__ ) . '/documentation/docs.php'; ?>
                    </div>
                    
                    <div class="col-4 pt-3">
                        <h2>Links</h2>
                        <ul class="list-style-none documentation-links"></ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div><!-- /.tab-content -->
</div><!-- /.wrap -->
