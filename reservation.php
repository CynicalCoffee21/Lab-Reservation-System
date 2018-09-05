<?php

session_start();

// force login through Shibboleth
//array_key_exists('REMOTE_USER', $_SERVER) && !empty($_SERVER['REMOTE_USER'])
if (1) {
    // show reservation form
    include 'reservation-request.php';
} else { ?>

    <?php//	include_once 'template_top.inc'; ?>

    <div class="configurable text_block">
        <section class="text-mod no-components">
            <div class="container-fluid">
                <div class="section-txt">
                    <div class="alert alert-warning text-center">You must login to reserve a lab</div>
                    <div class="text-center"><a class="btn btn-primary btn-lg" href="/Shibboleth.sso/Login?target=<?php echo urlencode($_SERVER['SCRIPT_URI']); ?>">Login</a></div>
                </div>
            </div>
        </section>
    </div>

    <?php//	include_once 'template_middle.inc'; ?>

    <?php//	include_once 'template_bottom.inc'; ?>
<?php	} 

?></p>


