<div class="wrap">
    <h1 class="wp-heading-inline">Import coupon CSV</h1>
    <hr class="wp-header-end">

    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h2>Add New</h2>
                    <form id="addtag" enctype="multipart/form-data" method="post"
                          action="/wp-admin/admin.php?page=coupon-import" class="validate">
                        <div class="form-field form-required term-name-wrap">
                            <label for="url_csv">URL File Import</label>
                            <input type="file" class="form-field" name="url_csv" accept=".csv">
                        </div>

                        <?php if (!empty($error)) { ?>
                            <div class="error"><?php echo esc_attr($error); ?></div>
                        <?php } ?>

                        <?php if (!empty($success)) { ?>
                            <div class="success"><?php echo esc_attr($success); ?></div>
                        <?php } ?>

                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="Import data">
                            <span class="spinner"></span>
                        </p>

                        <a target="_top" download=""
                           href="<?php echo plugins_url('public/csv/template-wp-import-coupon.csv', SIMPLE_COUPON_IMPORT_PLUGIN_FILE) ?>">
                            Download template import
                        </a>
                    </form>
                </div>
            </div>
        </div><!-- /col-left -->

        <div id="col-right">
            <div class="col-wrap">
                <h2>History import</h2>
                <table class="wp-list-table widefat fixed striped bordered table-view-list tags">
                    <thead>
                    <tr>
                        <th style="width: 100px" scope="col" id="id"><strong>ID</strong></th>
                        <th scope="col" id="name"><strong>Name</strong></th>
                        <th scope="col" style="width: 150px" id="created_at"><strong>Created at</strong></th>
                    </tr>
                    </thead>

                    <tbody id="the-list" data-wp-lists="list:tag">
                    <?php
                    if (!empty($list_import)) {
                        foreach ($list_import as $item) {
                            ?>
                            <tr id="tag-1" class="level-0">
                                <td><?php echo esc_attr($item->ID); ?></td>
                                <td class="name column-name has-row-actions column-primary" data-colname="Name">
                                    <?php echo esc_attr($item->post_title) ?>
                                </td>
                                <td><?php echo esc_attr($item->post_date); ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /col-right -->
    </div>
</div>