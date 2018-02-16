<?php
/**
 * FAQ & Support page
 */
?>
<style>
    .faq-ts-accordion {
        background-color: #ccc;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
        margin-bottom: 5px;
    }

    .active, .faq-ts-accordion:hover {
        background-color: #ccc; 
    }


    .faq-ts-accordion:after {
        content: '\002B';
        color: #777;
        font-weight: bold;
        float: right;
        margin-left: 5px;
    }

    .active:after {
        content: "\2212";
    }

    .panel {
        padding: 0 18px;
        display: none;
        background-color: light-grey;
        overflow: hidden;
    }

    .main-panel {
        width: 650px !important;
    }

    .support-panel {
        padding: 5px;
    }

    .dashicons-external {
        content: "\f504";
    }

    .dashicons-editor-help {
        content: "\f223";
    }

    div.panel.show {
        display: block !important;
    }

</style>

<div class="main-panel">
    <h3>Frequently Asked Questions for Order Delivery Date Pro for WooCommerce Plugin</h3>
    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>I need some lead preparation time before I can make a delivery. Can I set a minimum delivery period on my WooCommerce store?</strong></button>
    <div class="panel">
        <p>Yes, you can set a minimum delivery period in hours, which will be taken into consideration before showing the earliest available delivery date or time slot to your customers. This can be done under the <strong>“Minimum Delivery time (in hours)”</strong> field under the General Settings -> Date Settings tab in the Order Delivery Date on the admin side. Minutes will be accepted in the decimal format like for 30 Minutes you can use 0.50. <a href="https://www.tychesoftwares.com/setup-shipping-days/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>The working days of my company are different than the working days of my shipping company. Can I add them differently?</strong></button>
    <div class="panel">
        <p>Yes, you can set your company’s working days and shipping company’s working days differently. You can set up this under Shipping Days section under General Settings -> Date Settings tab in the Order Delivery Date on the admin side.<a href="https://www.tychesoftwares.com/how-to-setup-minimum-required-time-for-delivery-in-woocommerce/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>Can I provide same day deliveries to my customers?</strong></button>
    <div class="panel">
        <p>Yes. The Same day delivery feature enables you to get the deliveries for the same date. This is available in the Time Settings tab in the Order Delivery Date on the admin side. The current date would be available until the cut-off time set for the same date delivery is passed. Once the time is passed the date will be disabled and will have the label “Cut-Off time over”. Similarly, the Next day delivery feature works where the next day would be available for delivery.<a href="https://www.tychesoftwares.com/setup-day-delivery-next-day-delivery/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>Can I charge my customers if they want deliveries on certain days?</strong></button>
    <div class="panel">
        <p>Yes, you can charge your customers by adding additional charges to the delivery days/dates as well as time slots from the plugin. Charges for the days can be added under Weekday Settings tab. For time slot, you can add charges while creating time slots.<a href="https://www.tychesoftwares.com/additional-delivery-charges-delivery-dates-time/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>Can the delivery date be changed for already placed orders?</strong></button>
    <div class="panel">
        <p>Yes, the delivery date and time can be changed by the administrator as well as by the customers. The administrator can change it on WooCommerce -> Edit order page in the admin. And the customer can change it on the My Account page. To allow customers to edit the date, you need to enable <strong>“Allow Customers to edit Delivery Date & Time”</strong> checkbox under General Settings -> Additional Settings tab on the admin side.<a href="https://www.tychesoftwares.com/quick-guide-for-admin-and-customers-on-editing-delivery-dates-for-already-placed-woocommerce-orders/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>Can I limit the number of deliveries per day?</strong></button>
    <div class="panel">
        <p>Yes, you can limit the number of deliveries per day. You need to set the number of deliveries in the “Maximum Order Deliveries per day (based on per order)” field under General Settings -> Date Settings tab in the Order Delivery Date on the admin side.<a href="https://www.tychesoftwares.com/understanding-maximum-order-deliveries-setting/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>Can I add different delivery schedules for different delivery zones?</strong></button>
    <div class="panel">
        <p>Yes, you can add different delivery schedules for different shipping methods added for the default WooCommerce shipping zones.<br><br>Apart from shipping methods, you can also add different schedules for different product categories and default WooCommerce shipping classes.<a href="https://www.tychesoftwares.com/custom-delivery-settings/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>Can I export the deliveries to another calendar for easy access?</strong></button>
    <div class="panel">
        <p>Yes, you can export your deliveries to the google calendar directly or manually by downloading ICS files. This can be done under Google Calendar Sync tab.<a href="https://www.tychesoftwares.com/how-to-synchornize-delivery-dates-with-your-google-calendar/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>

    <button class="faq-ts-accordion"><span class="dashicons dashicons-editor-help"></span><strong>I don't want some time slots for particular of the dates or weekdays. Can I disable them?</strong></button>
    <div class="panel">
        <p>Yes, disable time slots for certain days or dates. You can add the time slots which you want to disable under General Settings -> Time Slot -> Block a Time slots link. The time slot will not be shown on the checkout page for that particular day or date.<a href="https://www.tychesoftwares.com/block-a-time-slot-in-order-delivery-date-pro-for-woocommerce-plugin/" target="_blank" class="dashicons dashicons-external"></a></p>
    </div>
</div>

<div class="support-panel">
    <p style="font-size: 19px">
        If your queries are not answered here, you can contact our support team by posting an issue on our <a href="https://www.tychesoftwares.com/forums/forum/order-delivery-date-pro-for-woocommerce/" target="_blank">support forum</a>.
    </p>
    <p style="font-size: 19px">
        Or you can also send an email directly to <strong>support@tychesoftwares.com</strong> for some additional requirements. 
    </p>
</div>
<script>
var acc = document.getElementsByClassName("faq-ts-accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].onclick = function() {
        hideAll();

        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle("show");
    }
}

function hideAll() {
    for (i = 0; i < acc.length; i++) {
        acc[i].classList.toggle( "active", false);
        acc[i].nextElementSibling.classList.toggle( "show", false );
    }
}

</script>