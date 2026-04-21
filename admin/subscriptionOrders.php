<?php

include("header.php");

?>

<div class="">

    <?php

    include("navigation.php");
    ?>
    <div class="Polaris-DataTable Polaris-DataTable--hoverable Polaris-DataTable--condensed Polaris-DataTable--bordered">
        <div class="Polaris-Page__Content">

            <div class="Polaris-DataTable sd_common_datatable">
                <table class="Polaris-DataTable__Table" id="subscription_order_table">
                    <thead>

                        <tr role="row">
                            <th data-polaris-header-cell="true"
                                class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--firstColumn Polaris-DataTable__Cell--header"
                                scope="col" aria-sort="none">Customer</th>

                            <th data-polaris-header-cell="true"
                                class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric"
                                scope="col" aria-sort="none">Order No.</th>

                            <th data-polaris-header-cell="true"
                                class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric"
                                scope="col" aria-sort="none">Order Type</th>

                            <th data-polaris-header-cell="true"
                                class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric"
                                scope="col" aria-sort="none">Order Date</th>

                            <th data-polaris-header-cell="true"
                                class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric"
                                scope="col" aria-sort="none">Action</th>
                        </tr>


                    </thead>
                    <tbody class="Polaris-DataTable__TableBody">
                        <!-- Dynamic rows go here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php

    include("footer.php");

    ?>