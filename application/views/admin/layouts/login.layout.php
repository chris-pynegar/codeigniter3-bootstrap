<?php $this->template->component('head'); ?>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="padded">
                    <?php echo $this->template->content(); ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>