<div id="searchModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="searchModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <object type="image/svg+xml" data="<?= get_template_directory_uri(); ?>/assets/img/cubecross-close.svg" width="40" height="40">
                    <img src="<?= get_template_directory_uri(); ?>/assets/img/cubecross-close.svg" alt="" width="40" height="40">
                </object>
            </button>
            <div class="modal-container">
                <div class="modal-header">
                    <h3 class="modal-title" id="searchModalTitle">Search for a job!</h3>
                </div>
                <div class="modal-body pt-3">
                    <form class="pt-3" action="">
                        <div class="row">
                            <div class="col-lg-3 dog">
                                <object type="image/svg+xml" data="<?= get_template_directory_uri(); ?>/assets/img/dog-stand-whitebow.svg">
                                    <img src="<?= get_template_directory_uri(); ?>/assets/img/dog-stand-whitebow.svg" alt="">
                                </object>
                            </div>
                            <div class="col-lg-9 searchInput">
                                <div class="triangle-left"></div>
                                <input type="search" placeholder="Let me help you find the perfect job">
                                <button type="submit" class="btn btn__notched"><i class="far fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>