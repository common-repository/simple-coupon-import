<?php
/**
 * @var $form_data
 */

?>
<div class="wrap">
    <h1 class="wp-heading-inline">Import coupon CSV</h1>
    <hr class="wp-header-end">

    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <form id="addtag" enctype="multipart/form-data" method="post"
                          action="<?php
					      echo admin_url() ?>admin.php?page=coupon-import-api" class="validate">

                        <div class="form-field form-required term-name-wrap">
                            <label for="link_api">
                                Link API

                                <a target="_blank"
                                   href="https://developers.accesstrade.vn/api-publisher-vietnamese/lay-thong-tin-cac-khuyen-mai-dang-hoat-dong#http-request">
                                    (Get link)
                                </a>
                            </label>
                            <input type="text" id="link_api" required name="link_api"
                                   value="<?php
							       echo esc_attr( $form_data['link_api'] ) ?>"/>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="access_token">
                                Access token

                                <a target="_blank" href="https://pub2.accesstrade.vn/profile/api_key">
                                    (Get token)
                                </a>
                            </label>
                            <input type="text" id="access_token" required name="access_token"
                                   value="<?php
							       echo esc_attr( $form_data['access_token'] ) ?>"/>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="page_number">Page number</label>
                            <input type="number" id="page_number" required name="page_number"
                                   value="<?php
							       echo ! empty( $form_data['page_number'] ) ? esc_attr( $form_data['page_number'] ) : 1 ?>"/>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="limit">Limit</label>
                            <input type="number" id="limit" required name="limit"
                                   value="<?php
							       echo ! empty( $form_data['limit'] ) ? esc_attr( $form_data['limit'] ) : 10 ?>"/>
                        </div>

                        <div class="form-field form-required term-name-wrap">
                            <label for="domain">Domain</label>
                            <select id="domain" name="domain">
								<?php
								if ( ! empty( $listDomain ) ) {
									foreach ( $listDomain as $domain ) {
										$selectDomain = esc_attr( $form_data['domain'] ) == $domain ? 'selected' : '';
										echo '<option ' . $selectDomain . ' value="' . $domain . '">' . $domain . '</option>';
									}
								}
								?>
                            </select>
                        </div>

						<?php
						if ( ! empty( $error ) ) { ?>
                            <div class="error"><?php
								echo esc_attr( $error ); ?></div>
							<?php
						} ?>

						<?php
						if ( ! empty( $success ) ) { ?>
                            <div class="success"><?php
								echo esc_attr( $success ); ?></div>
							<?php
						} ?>

                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                   value="Save Connect & Fetch data">
                            <span class="spinner"></span>
                        </p>
                    </form>
                </div>
            </div>
        </div><!-- /col-left -->

        <div id="col-right">
            <div class="col-wrap">
                <h2>View Result</h2>
                <table class="wp-list-table widefat fixed striped bordered table-view-list tags">
                    <thead>
                    <tr>
                        <th style="width: 50px" scope="col" id="id"><strong>Image</strong></th>
                        <th scope="col" id="name"><strong>Name</strong></th>
                        <th scope="col" id="Category"><strong>Category</strong></th>
                        <th scope="col" id="Store"><strong>Store</strong></th>
                        <th scope="col" id="Store"><strong>Coupon</strong></th>
                        <th scope="col"><strong>Started at</strong></th>
                        <th scope="col"><strong>Expired at</strong></th>
                    </tr>
                    </thead>

                    <tbody id="the-list" data-wp-lists="list:tag">
					<?php
					if ( ! empty( $list_data ) ) {
						foreach ( $list_data as $item ) {
							?>
                            <tr id="tag-1" class="level-0">
                                <td><img alt="img" src="<?php
									echo esc_attr( $item['image'] ); ?>" style="height: 50px; max-width: 100px;"/></td>
                                <td><?php
									echo esc_attr( $item['name'] ); ?></td>
                                <td><?php
									echo ! empty( $item['categories'][0]['category_name_show'] ) ? esc_attr( $item['categories'][0]['category_name_show'] ) : ''; ?></td>
                                <td><?php
									echo esc_attr( $item['merchant'] ); ?></td>
                                <td>
                                    <code>
										<?php
										echo ! empty( $item['coupons'][0]['coupon_code'] ) ? esc_attr( $item['coupons'][0]['coupon_code'] ) : ''; ?>
                                    </code>
                                </td>
                                <td><?php
									echo esc_attr( $item['start_time'] ); ?></td>
                                <td><?php
									echo esc_attr( $item['end_time'] ); ?></td>
                            </tr>
							<?php
						}
					} else {
						?>
                        <tr>
                            <td colspan="7">Please submit form preview data</td>
                        </tr>
						<?php
					}
					?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /col-right -->
    </div>
</div>